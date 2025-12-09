@extends('page.layouts.page')
@section('title', 'Thanh toán VNPay')
@section('style')
<style>
    .payment-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .payment-summary {
        background: #fff;
        border: 2px solid #007bff;
        padding: 20px;
        border-radius: 5px;
    }
    .btn-payment {
        background: #007bff;
        color: white;
        padding: 15px 30px;
        font-size: 18px;
        border-radius: 5px;
        width: 100%;
    }
    .btn-payment:hover {
        background: #0056b3;
        color: white;
    }
</style>
@stop
@section('seo')
@stop
@section('content')
    @php
        $bgImage = asset('/page/images/200-hinh-nen.jpg');
    @endphp
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('{{ $bgImage }}');">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
                <div class="col-md-9 ftco-animate pb-5 text-center">
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></span> 
                        <span>Thanh toán <i class="fa fa-chevron-right"></i></span>
                    </p>
                    <h1 class="mb-0 bread">Thanh toán VNPay</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="payment-info">
                        <h3>Thông tin đơn đặt tour</h3>
                        <hr>
                        <p><strong>Mã đơn:</strong> #{{ $bookTour->id }}</p>
                        <p><strong>Tên tour:</strong> {{ $tour->t_title }}</p>
                        <p><strong>Ngày khởi hành:</strong> {{ $tour->t_start_date }}</p>
                        <p><strong>Điểm xuất phát:</strong> {{ $tour->t_starting_gate ?? 'N/A' }}</p>
                        <p><strong>Họ tên:</strong> {{ $bookTour->b_name }}</p>
                        <p><strong>Email:</strong> {{ $bookTour->b_email }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $bookTour->b_phone }}</p>
                    </div>

                    <div class="payment-summary">
                        <h3 class="text-center mb-4">Tổng tiền thanh toán</h3>
                        <table class="table table-bordered">
                            <tr>
                                <td>Người lớn ({{ $bookTour->b_number_adults }} người)</td>
                                <td class="text-right">{{ number_format($bookTour->b_number_adults * $bookTour->b_price_adults, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            @if($bookTour->b_number_children > 0)
                            <tr>
                                <td>Trẻ em 6-12 tuổi ({{ $bookTour->b_number_children }} người)</td>
                                <td class="text-right">{{ number_format($bookTour->b_number_children * $bookTour->b_price_children, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            @endif
                            @if($bookTour->b_number_child6 > 0)
                            <tr>
                                <td>Trẻ em 2-6 tuổi ({{ $bookTour->b_number_child6 }} người)</td>
                                <td class="text-right">{{ number_format($bookTour->b_number_child6 * $bookTour->b_price_child6, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            @endif
                            @if($bookTour->b_number_child2 > 0)
                            <tr>
                                <td>Trẻ em dưới 2 tuổi ({{ $bookTour->b_number_child2 }} người)</td>
                                <td class="text-right">{{ number_format($bookTour->b_number_child2 * $bookTour->b_price_child2, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            @endif
                            <tr class="table-info">
                                <td><strong>TỔNG CỘNG</strong></td>
                                <td class="text-right"><strong style="font-size: 20px; color: #007bff;">{{ number_format($totalPrice, 0, ',', '.') }} VNĐ</strong></td>
                            </tr>
                        </table>

                        <form action="{{ route('payment.vnpay.create', $bookTour->id) }}" method="POST" id="paymentForm">
                            @csrf
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-payment">
                                    <i class="fa fa-credit-card"></i> Thanh toán qua VNPay
                                </button>
                            </div>
                        </form>

                        <div class="alert alert-info mt-3">
                            <i class="fa fa-info-circle"></i> 
                            <strong>Lưu ý:</strong> Bạn sẽ được chuyển đến trang thanh toán của VNPay. 
                            Sau khi thanh toán thành công, bạn sẽ nhận được email xác nhận.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

