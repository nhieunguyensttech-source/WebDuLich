<?php

namespace App\Services;

class VNPayService
{
    protected $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    protected $vnp_Returnurl;
    protected $vnp_TmnCode;
    protected $vnp_HashSecret;
    protected $vnp_TxnRef;
    protected $vnp_OrderInfo;
    protected $vnp_OrderType;
    protected $vnp_Amount;
    protected $vnp_Locale = 'vn';
    protected $vnp_IpAddr;
    protected $vnp_CreateDate;

    public function __construct()
    {
        $this->vnp_TmnCode = config('vnpay.tmn_code', '');
        $this->vnp_HashSecret = config('vnpay.hash_secret', '');
        $this->vnp_Returnurl = config('vnpay.return_url', url('/payment/vnpay/return'));
        
        // Sử dụng URL từ config hoặc mặc định
        if (config('vnpay.url')) {
            $this->vnp_Url = config('vnpay.url');
        }
    }
    
    /**
     * Lấy IPN URL để gửi cho VNPay
     */
    public function getIpnUrl()
    {
        return config('vnpay.ipn_url', url('/payment/vnpay/ipn'));
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPaymentUrl($orderId, $amount, $orderInfo, $orderType = 'other')
    {
        // Kiểm tra cấu hình
        if (empty($this->vnp_TmnCode) || empty($this->vnp_HashSecret)) {
            throw new \Exception('VNPay chưa được cấu hình. Vui lòng kiểm tra file .env');
        }

        $this->vnp_TxnRef = $orderId;
        $this->vnp_Amount = $amount * 100; // VNPay yêu cầu số tiền nhân 100
        $this->vnp_OrderInfo = $orderInfo;
        $this->vnp_OrderType = $orderType;
        $this->vnp_IpAddr = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $this->vnp_CreateDate = date('YmdHis');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $this->vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $this->vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $this->vnp_IpAddr,
            "vnp_Locale" => $this->vnp_Locale,
            "vnp_OrderInfo" => $this->vnp_OrderInfo,
            "vnp_OrderType" => $this->vnp_OrderType,
            "vnp_ReturnUrl" => $this->vnp_Returnurl,
            "vnp_TxnRef" => $this->vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->vnp_Url . "?" . $query;
        if (isset($this->vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    /**
     * Xác thực callback từ VNPay
     */
    public function verifyPayment($inputData)
    {
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);

        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);
        
        if ($secureHash == $vnp_SecureHash) {
            return [
                'success' => true,
                'response_code' => $inputData['vnp_ResponseCode'] ?? '',
                'transaction_id' => $inputData['vnp_TransactionNo'] ?? '',
                'amount' => $inputData['vnp_Amount'] ?? 0,
                'order_id' => $inputData['vnp_TxnRef'] ?? '',
            ];
        }

        return [
            'success' => false,
            'message' => 'Chữ ký không hợp lệ'
        ];
    }
}

