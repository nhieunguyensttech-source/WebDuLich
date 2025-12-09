<div>
    <div style="background-color:#ffebee;text-align: center;border-color: red; border-style: solid; border-width: 5px;">
        <h4 style="text-align: center; color: red;">
            THÔNG BÁO: CÓ ĐƠN ĐẶT TOUR ĐÃ THANH TOÁN THÀNH CÔNG
        </h4>
    </div>
    
    <h2>Thông tin đơn đặt tour đã thanh toán</h2>
    
    <div style="background-color:#ddd;margin-top:8px; padding: 15px;">
        <h3>Thông tin đơn đặt tour</h3>
        <p>Mã đơn đặt tour: <b style="color:red; font-size: 18px;">#{{ $bookTour->id }}</b></p>
        <p>Tên tour: <b>{{ $tour->t_title }}</b></p>
        <p>Ngày đi: <b>{{ $bookTour->b_start_date ?? $tour->t_start_date }}</b></p>
        <p>Điểm khởi hành: <b>{{ $bookTour->b_address ?? $tour->t_starting_gate }}</b></p>
        <p>Lịch trình: <b>{{ $tour->t_schedule }}</b></p>
    </div>
   
    <div style="background-color:#e8f5e9;margin-top:8px; padding: 15px;">
        <h3>Thông tin thanh toán</h3>
        <p>Phương thức thanh toán: <b>{{ $bookTour->b_payment_method ?? 'VNPay' }}</b></p>
        <p>Mã giao dịch: <b>{{ $bookTour->b_payment_transaction_id ?? 'N/A' }}</b></p>
        <p>Ngày thanh toán: <b>{{ $bookTour->b_payment_date ? date('d/m/Y H:i:s', strtotime($bookTour->b_payment_date)) : now()->format('d/m/Y H:i:s') }}</b></p>
        
        @php
        $totalPrice = ($bookTour->b_number_adults * $bookTour->b_price_adults) 
                    + ($bookTour->b_number_children * $bookTour->b_price_children)
                    + ($bookTour->b_number_child6 * $bookTour->b_price_child6)
                    + ($bookTour->b_number_child2 * $bookTour->b_price_child2);
        @endphp
        
        <p style="font-size: 18px; color: green;">
            <strong>Tổng tiền đã thanh toán: <b style="color: red;">{{ number_format($totalPrice, 0,',','.') }} VNĐ</b></strong>
        </p>
    </div>

    <div style="background-color:#fff3cd;margin-top:8px; padding: 15px;">
        <h3>Thông tin khách hàng</h3>
        <p>Họ tên: <b>{{ $user->name ?? $bookTour->b_name }}</b></p>
        <p>Email: <b>{{ $user->email ?? $bookTour->b_email }}</b></p>
        <p>Số điện thoại: <b>{{ $user->phone ?? $bookTour->b_phone }}</b></p>
        <p>Địa chỉ: <b>{{ $user->address ?? $bookTour->b_address }}</b></p>
    </div>
   
    <div style="background-color:#ddd;margin-top:8px; padding: 15px;">
        <h3>Chi tiết số lượng khách</h3>
        <p>Người lớn: <b>{{ $bookTour->b_number_adults }}</b> người</p>
        @if($bookTour->b_number_children > 0)
        <p>Trẻ em 6-12 tuổi: <b>{{ $bookTour->b_number_children }}</b> người</p>
        @endif
        @if($bookTour->b_number_child6 > 0)
        <p>Trẻ em 2-6 tuổi: <b>{{ $bookTour->b_number_child6 }}</b> người</p>
        @endif
        @if($bookTour->b_number_child2 > 0)
        <p>Trẻ em dưới 2 tuổi: <b>{{ $bookTour->b_number_child2 }}</b> người</p>
        @endif
    </div>

    <div style="background-color:#ffebee;margin-top:8px; padding: 15px; text-align: center;">
        <p><strong>Vui lòng kiểm tra và xác nhận đơn đặt tour này trong hệ thống quản trị.</strong></p>
        <p><a href="{{ url('/admin/book-tour') }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Xem chi tiết trong Admin</a></p>
    </div>
</div>

