
@extends('page.layouts.page')
@section('title', $hotel->h_name)
@section('style')

@stop
@section('seo')
@stop
@section('content')
<section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url({{ asset('/page/images/hotel.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate pb-5 text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></span> <span>Khách sạn <i class="fa fa-chevron-right"></i></span></p>
                <h1 class="mb-0 bread">Đặt Phòng Khách Sạn</h1>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section pt-5">
    <div class="container">
<div class="grid-2">
<div>
<h4 class="section-title">📅 Thông Tin Lịch Trình</h4>


<div class="field mb-3">
<label>Ngày Nhận Phòng</label>
<input type="date" name="check_in">
</div>


<div class="field mb-3">
<label>Ngày Trả Phòng</label>
<input type="date" name="check_out">
</div>


<div class="field mb-3">
<label>Số Lượng Phòng</label>
<select name="quantity">
<option>1 Phòng</option>
<option>2 Phòng</option>
<option>3 Phòng</option>
</select>
</div>


<div class="field mb-3">
<div class="form-group">
                            <label for="inputEmail3" class="control-label">Số người lớn <sup class="text-danger">(*)</sup></label>
                            <input type="number" name="b_number_adults" class="form-control" placeholder="Số người lớn">
                            @if ($errors->first('b_number_adults'))
                                <span class="text-danger">{{ $errors->first('b_number_adults') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="control-label">Số trẻ em ( dưới 12 tuổi) <sup class="text-danger">(*)</sup></label>
                            <input type="number" name="b_number_children" class="form-control" placeholder="Số trẻ em">
                            @if ($errors->first('b_number_children'))
                                <span class="text-danger">{{ $errors->first('b_number_children') }}</span>
                            @endif
                        </div>
</div>
<div class="field mb-3">
<label>Yêu Cầu Đặc Biệt</label>
<textarea rows="3" placeholder="Ví dụ: Phòng gần biển, thêm giường phụ..."></textarea>
</div>
</div>
<div>
<h4 class="section-title">👤 Thông Tin Khách Hàng</h4>
<div class="field mb-3">
<label>Họ và Tên</label>
<input type="text" name="name" placeholder="Nguyễn Văn A">
</div>
<div class="field mb-3">
<label>Số điện thoại</label>
<input type="text" name="phone" placeholder="098xxxxxxx">
</div>
<div class="field mb-3">
<label>Email</label>
<input type="email" name="email" placeholder="email@gmail.com">
</div>
                        <div class="form-group">
                            <label class="control-label"><strong>Phương thức thanh toán</strong></label>
                            <div class="payment-method" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_later" value="later" checked>
                                    <label class="form-check-label" for="payment_later">
                                        <strong>Thanh toán trực tiếp</strong> - Thanh toán khi nhận phòng tại khách sạn
                                    </label>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_now" value="vnpay">
                                    <label class="form-check-label" for="payment_now">
                                        <strong>Thanh toán ngay qua VNPay</strong> - Thanh toán trực tuyến an toàn, nhanh chóng
                                        <br><small class="text-muted"><i class="fa fa-credit-card"></i> Hỗ trợ thẻ ATM, Visa, Mastercard</small>
                                    </label>
                                </div>
                            </div>
                        </div>
</div>
</div>
<div class="summary-box mt-4">
<h5><strong>Tổng Thanh Toán</strong></h5>
<p>Giá phòng: {{ number_format($hotel->h_price) }} VND</p>
<p>Thuế & Phí: {{ number_format($hotel->h_price * 0.1) }} VND</p>
<h4><strong>Tổng: {{ number_format($hotel->h_price + $hotel->h_price * 0.1) }} VND</strong></h4>
</div>


<button class="btn-main mt-4">Xác Nhận Đặt Phòng</button>


</div>
</div>
</div>
</section>
@stop
@section('script')
@stop
