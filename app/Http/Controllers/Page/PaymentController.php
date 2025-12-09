<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\BookTour;
use App\Models\Tour;
use App\Models\User;
use App\Services\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * Hiển thị form thanh toán VNPay
     */
    public function showPaymentForm($bookTourId)
    {
        $bookTour = BookTour::with(['tour', 'user'])->findOrFail($bookTourId);
        
        // Kiểm tra quyền truy cập
        $user = Auth::guard('users')->user();
        if ($user && $bookTour->b_user_id && $bookTour->b_user_id != $user->id) {
            return redirect()->route('page.home')->with('error', 'Bạn không có quyền truy cập đơn đặt tour này');
        }
        
        // Kiểm tra trạng thái đơn - chỉ cho thanh toán khi đã được xác nhận
        if ($bookTour->b_status != 2) {
            if ($bookTour->b_status == 3) {
                return redirect()->back()->with('error', 'Đơn này đã được thanh toán');
            }
            return redirect()->back()->with('error', 'Đơn này chưa được xác nhận. Vui lòng chờ xác nhận từ admin.');
        }

        // Tính tổng tiền
        $totalPrice = ($bookTour->b_number_adults * $bookTour->b_price_adults) 
                    + ($bookTour->b_number_children * $bookTour->b_price_children)
                    + ($bookTour->b_number_child6 * $bookTour->b_price_child6)
                    + ($bookTour->b_number_child2 * $bookTour->b_price_child2);

        $viewData = [
            'bookTour' => $bookTour,
            'tour' => $bookTour->tour,
            'totalPrice' => $totalPrice
        ];

        return view('page.payment.vnpay', $viewData);
    }

    /**
     * Tạo URL thanh toán VNPay và redirect
     */
    public function createPayment(Request $request, $bookTourId)
    {
        $bookTour = BookTour::with('tour')->findOrFail($bookTourId);

        // Kiểm tra quyền
        $user = Auth::guard('users')->user();
        if ($user && $bookTour->b_user_id && $bookTour->b_user_id != $user->id) {
            return redirect()->back()->with('error', 'Bạn không có quyền thanh toán đơn này');
        }

        // Kiểm tra trạng thái đơn - chỉ cho thanh toán khi đã được xác nhận
        if ($bookTour->b_status != 2) {
            if ($bookTour->b_status == 3) {
                return redirect()->back()->with('error', 'Đơn này đã được thanh toán');
            }
            return redirect()->back()->with('error', 'Đơn này chưa được xác nhận. Vui lòng chờ xác nhận từ admin.');
        }
        
        // Kiểm tra cấu hình VNPay
        if (empty(config('vnpay.tmn_code')) || empty(config('vnpay.hash_secret'))) {
            return redirect()->back()->with('error', 'Hệ thống thanh toán chưa được cấu hình. Vui lòng liên hệ admin.');
        }

        // Tính tổng tiền
        $totalPrice = ($bookTour->b_number_adults * $bookTour->b_price_adults) 
                    + ($bookTour->b_number_children * $bookTour->b_price_children)
                    + ($bookTour->b_number_child6 * $bookTour->b_price_child6)
                    + ($bookTour->b_number_child2 * $bookTour->b_price_child2);

        // Tạo order info
        $orderInfo = "Thanh toan don dat tour #{$bookTourId} - {$bookTour->tour->t_title}";

        try {
            // Tạo URL thanh toán
            $paymentUrl = $this->vnpayService->createPaymentUrl(
                $bookTourId,
                $totalPrice,
                $orderInfo,
                'other'
            );

            if (empty($paymentUrl)) {
                return redirect()->back()->with('error', 'Không thể tạo URL thanh toán. Vui lòng thử lại sau.');
            }

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            Log::error('VNPay create payment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ VNPay server
     */
    public function vnpayIpn(Request $request)
    {
        $inputData = $request->all();
        
        // Xác thực chữ ký
        $result = $this->vnpayService->verifyPayment($inputData);

        if (!$result['success']) {
            Log::error('VNPay IPN: Invalid signature', $inputData);
            return response()->json(['RspCode' => '97', 'Message' => 'Checksum failed']);
        }

        // Kiểm tra response code
        if ($result['response_code'] != '00') {
            Log::info('VNPay IPN: Payment failed', ['response_code' => $result['response_code'], 'order_id' => $result['order_id']]);
            return response()->json(['RspCode' => '00', 'Message' => 'Success']);
        }

        // Lấy thông tin đơn đặt tour
        $bookTourId = $result['order_id'];
        $bookTour = BookTour::with(['tour', 'user'])->find($bookTourId);

        if (!$bookTour) {
            Log::error('VNPay IPN: BookTour not found', ['order_id' => $bookTourId]);
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        // Kiểm tra xem đã xử lý chưa
        if ($bookTour->b_status == 3 && $bookTour->b_payment_transaction_id) {
            Log::info('VNPay IPN: Already processed', ['order_id' => $bookTourId]);
            return response()->json(['RspCode' => '00', 'Message' => 'Success']);
        }

        // Cập nhật trạng thái thanh toán
        DB::beginTransaction();
        try {
            $bookTour->b_status = 3; // Đã thanh toán
            $bookTour->b_payment_method = 'VNPay';
            $bookTour->b_payment_transaction_id = $result['transaction_id'];
            $bookTour->b_payment_date = now();
            $bookTour->b_payment_note = json_encode($inputData);
            $bookTour->save();

            // Cập nhật số người đã đăng ký
            $numberUser = $bookTour->b_number_adults + $bookTour->b_number_children 
                        + $bookTour->b_number_child6 + $bookTour->b_number_child2;
            $tour = $bookTour->tour;
            $tour->t_number_registered = $tour->t_number_registered + $numberUser;
            $tour->save();

            DB::commit();

            // Gửi email thông báo thanh toán thành công
            $this->sendPaymentSuccessEmail($bookTour);

            Log::info('VNPay IPN: Payment processed successfully', ['order_id' => $bookTourId]);
            return response()->json(['RspCode' => '00', 'Message' => 'Success']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VNPay IPN error: ' . $e->getMessage(), ['order_id' => $bookTourId]);
            return response()->json(['RspCode' => '99', 'Message' => 'Unknown error']);
        }
    }

    /**
     * Xử lý callback từ VNPay (browser redirect)
     */
    public function vnpayReturn(Request $request)
    {
        $inputData = $request->all();
        
        // Xác thực chữ ký
        $result = $this->vnpayService->verifyPayment($inputData);

        if (!$result['success']) {
            return redirect()->route('page.home')
                ->with('error', 'Thanh toán thất bại: ' . ($result['message'] ?? 'Lỗi xác thực'));
        }

        // Kiểm tra response code
        if ($result['response_code'] != '00') {
            return redirect()->route('page.home')
                ->with('error', 'Thanh toán thất bại. Mã lỗi: ' . $result['response_code']);
        }

        // Lấy thông tin đơn đặt tour
        $bookTourId = $result['order_id'];
        $bookTour = BookTour::with(['tour', 'user'])->find($bookTourId);

        if (!$bookTour) {
            return redirect()->route('page.home')
                ->with('error', 'Không tìm thấy đơn đặt tour');
        }

        // Kiểm tra xem đơn đã được thanh toán chưa (tránh duplicate)
        if ($bookTour->b_status == 3 && $bookTour->b_payment_transaction_id) {
            Log::info('VNPay Return: Order already paid', ['order_id' => $bookTourId, 'transaction_id' => $bookTour->b_payment_transaction_id]);
            return redirect()->route('my.tour')
                ->with('success', 'Thanh toán đã được xử lý thành công trước đó. Mã giao dịch: ' . $bookTour->b_payment_transaction_id);
        }

        // Cập nhật trạng thái thanh toán
        DB::beginTransaction();
        try {
            $bookTour->b_status = 3; // Đã thanh toán
            $bookTour->b_payment_method = 'VNPay';
            $bookTour->b_payment_transaction_id = $result['transaction_id'];
            $bookTour->b_payment_date = now();
            $bookTour->b_payment_note = json_encode($inputData);
            $bookTour->save();

            // Cập nhật số người đã đăng ký
            $numberUser = $bookTour->b_number_adults + $bookTour->b_number_children 
                        + $bookTour->b_number_child6 + $bookTour->b_number_child2;
            $tour = $bookTour->tour;
            $tour->t_number_registered = $tour->t_number_registered + $numberUser;
            $tour->save();

            DB::commit();

            // Gửi email thông báo thanh toán thành công (tách riêng để không ảnh hưởng đến thông báo thành công)
            try {
                $this->sendPaymentSuccessEmail($bookTour);
                $emailMessage = ' Chúng tôi đã gửi email xác nhận đến bạn.';
            } catch (\Exception $emailException) {
                // Log lỗi email nhưng không ảnh hưởng đến thông báo thành công
                Log::error('Payment success email error: ' . $emailException->getMessage(), [
                    'order_id' => $bookTourId,
                    'transaction_id' => $result['transaction_id']
                ]);
                $emailMessage = ' (Lưu ý: Không thể gửi email xác nhận, nhưng thanh toán đã được xử lý thành công)';
            }

            Log::info('VNPay Return: Payment processed successfully', [
                'order_id' => $bookTourId,
                'transaction_id' => $result['transaction_id']
            ]);

            return redirect()->route('my.tour')
                ->with('success', 'Thanh toán thành công! Mã giao dịch: ' . $result['transaction_id'] . '.' . $emailMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VNPay Return: Payment update error', [
                'error' => $e->getMessage(),
                'order_id' => $bookTourId,
                'transaction_id' => $result['transaction_id'] ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('page.home')
                ->with('error', 'Có lỗi xảy ra khi cập nhật thông tin thanh toán. Vui lòng liên hệ admin với mã giao dịch: ' . ($result['transaction_id'] ?? 'N/A'));
        }
    }

    /**
     * Gửi email thông báo thanh toán thành công
     */
    protected function sendPaymentSuccessEmail($bookTour)
    {
        $tour = $bookTour->tour;
        $user = $bookTour->user ?? (object)[
            'name' => $bookTour->b_name,
            'email' => $bookTour->b_email,
            'phone' => $bookTour->b_phone,
            'address' => $bookTour->b_address
        ];

        // Gửi email cho khách hàng
        Mail::send('email_payment_success', compact('bookTour', 'tour', 'user'), function($email) use ($user) {
            $email->subject('Xác nhận thanh toán thành công - VHDTravel');
            $email->to($user->email);
        });

        // Gửi email thông báo cho admin (email từ config)
        $adminEmail = config('mail.from.address');
        if ($adminEmail && $adminEmail != $user->email) { // Không gửi duplicate nếu admin = user
            try {
                Mail::send('email_payment_notify_admin', compact('bookTour', 'tour', 'user'), function($email) use ($adminEmail) {
                    $email->subject('Thông báo: Có đơn đặt tour đã thanh toán thành công');
                    $email->to($adminEmail);
                });
            } catch (\Exception $e) {
                Log::warning('Failed to send admin notification email: ' . $e->getMessage());
            }
        }
    }
}

