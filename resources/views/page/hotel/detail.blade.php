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
                    <h1 class="mb-0 bread">Khách Sạn</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="ftco-section pt-5">
    <div class="container">

        {{-- ======= TÊN + ĐÁNH GIÁ + ĐỊA CHỈ ======= --}}
        <div class="row mb-4">
            <div class="col-lg-12">
                <h1 style="font-weight: bold">{{ $hotel->h_name }}</h1>

                <div class="d-flex align-items-center mb-2">
                    {{-- Số sao --}}
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa fa-star {{ $i <= $hotel->h_star ? 'text-warning' : 'text-muted' }}"></i>
                    @endfor
                </div>

                <p class="text-muted mb-1">
                    <i class="fa fa-map-marker-alt"></i>
                    {{ $hotel->location->l_name ?? '' }} — {{ $hotel->h_address }}
                </p>

                <p class="text-danger" style="font-size: 22px; font-weight: bold;">
                    Giá từ: {{ number_format($hotel->h_price, 0, ',', '.') }} VND
                </p>
            </div>
        </div>

        {{-- ======= GALLERY ẢNH ======= --}}
        <div class="row mb-5">
            <div class="col-lg-12">
                <a data-fancybox="gallery" href="{{ asset(pare_url_file($hotel->h_image)) }}">
                    <img src="{{ asset(pare_url_file($hotel->h_image)) }}" class="img-fluid rounded shadow" style="width: 100%; object-fit: cover;">
                </a>

                @if(isset($hotel->images) && count($hotel->images) > 0)
                <div class="d-flex gap-2 mt-3">
                    @foreach($hotel->images as $img)
                        <a data-fancybox="gallery" href="{{ asset(pare_url_file($img->hi_image)) }}">
                            <img src="{{ asset(pare_url_file($img->hi_image)) }}"
                                 style="width:120px; height:80px; object-fit:cover" class="rounded shadow-sm">
                        </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <div class="row">

            {{-- ======= NỘI DUNG BÊN TRÁI ======= --}}
            <div class="col-lg-8">

                {{-- Thông tin liên hệ --}}
                <h3 class="mt-4">Thông tin liên hệ</h3>
                <table class="table table-bordered">
                    <tr>
                        <td width="30%">Địa điểm</td>
                        <td>{{ $hotel->location->l_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Địa chỉ</td>
                        <td>{{ $hotel->h_address }}</td>
                    </tr>
                    <tr>
                        <td>Điện thoại</td>
                        <td>{{ $hotel->h_phone }}</td>
                    </tr>
                    <tr>
                        <td>Giá từ</td>
                        <td>{{ number_format($hotel->h_price, 0, ',', '.') }} VND</td>
                    </tr>
                </table>

                {{-- Mô tả --}}
                <h3 class="mt-5">Mô tả về khách sạn</h3>
                <div class="content">{!! $hotel->h_description !!}</div>

                {{-- Nội dung chi tiết --}}
                <h3 class="mt-5">Chi tiết khách sạn</h3>
                <div class="content">{!! $hotel->h_content !!}</div>

                {{-- ======= DANH SÁCH BÌNH LUẬN ======= --}}
                <div class="pt-5 mt-5" style="border-top: 1px solid #ccc;">
                    <h3 class="mb-5" style="font-size: 20px; font-weight: bold;">Bình luận</h3>

                    <ul class="comment-list">
                        @if ($hotel->comments->count() > 0)
                            @foreach($hotel->comments as $comment)
                                @include('page.common.itemComment', compact('comment'))
                            @endforeach
                        @endif
                    </ul>

                    {{-- Form bình luận --}}
                    <div class="comment-form-wrap pt-4">
                        <h4 class="mb-4">
                            {{ Auth::guard('users')->check() ? 'Gửi bình luận của bạn' : 'Bạn cần đăng nhập để bình luận' }}
                        </h4>

                        @if (Auth::guard('users')->check())
                            <form action="#" class="p-4 bg-light">
                                <div class="form-group">
                                    <label>Nội dung</label>
                                    <textarea id="message" cols="30" rows="5" class="form-control"></textarea>
                                    <span class="text-errors-comment text-danger" style="display: none;">Nội dung không được bỏ trống!</span>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="button" class="btn btn-primary px-4 btn-comment"
                                            hotel_id="{{ $hotel->id }}">
                                        Gửi bình luận
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ======= BÊN PHẢI: ĐẶT PHÒNG + KHÁCH SẠN LIÊN QUAN ======= --}}
            <div class="col-lg-4">

                {{-- BOOKING BOX --}}
                <div class="p-4 mb-4 bg-light shadow-sm rounded">
                    <p class="mb-3" style="font-size: 20px;">Giá từ:</p>
                    <p class="text-danger" style="font-size: 26px; font-weight: bold;">
                        {{ number_format($hotel->h_price, 0, ',', '.') }} VND
                    </p>
                    <a href="{{ route('hotel.booking', $hotel->id) }}" class="btn btn-primary w-100 py-3">Đặt khách sạn</a>
                </div>

                {{-- RELATED HOTEL --}}
                @if ($hotels->count() > 0)
                    <div class="bg-light p-4 rounded shadow-sm">
                        <h4 class="mb-4">Khách Sạn Liên Quan</h4>
                        @foreach($hotels as $hotel)
                            @include('page.common.itemHotel', ['hotel' => $hotel, 'itemHotel' => 'item-related-tour'])
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>
@stop
@section('script')
@stop