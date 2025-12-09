@extends('page.layouts.page')

@section('title', 'So sánh Tour')

@section('content')
<style>
.compare-title { font-size:28px; color:#0b557d; margin:20px 0; }
.compare-table { border-radius:10px; box-shadow:0 4px 18px rgba(0,0,0,0.06); overflow:hidden; }
.compare-table th { background:#e9f6ff; color:#0b557d; padding:16px; font-weight:700; }
.compare-table td { padding:16px; background:#fff; vertical-align:middle; }
.tour-img{ width:160px; border-radius:8px; }
@media(max-width:768px){ .tour-img{ width:120px; } }
</style>
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url({{ asset('/page/images/nhien-3.jpg') }});">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
                <div class="col-md-9 ftco-animate pb-5 text-center">
                    <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></span><span class="mr-2"><a href="{{ route('tour') }}">Tour<i class="fa fa-chevron-right"></i></a></span></p>
                    <h1 class="mb-0 bread">So sánh Tour</h1>
                </div>
            </div>
        </div>
    </section>
<section class="ftco-section">
  <div class="container">
    <h2 class="text-center compare-title" style="font-size: 50px; color: #000;">So sánh Tour du lịch</h2>
        <div class="text-center mt-3">
        <a href="{{ url('/tour.html') }}" class="btn btn-primary">Thêm tour so sánh</a>
    </div>
    @if($tours->count() == 0)
      <p class="text-center">Bạn chưa chọn tour để so sánh.</p>
    @else
      <div class="table-responsive">
        <table class="table table-bordered text-center compare-table">
          <thead>
            <tr>
              <th>Tiêu chí</th>
              @foreach($tours as $tour)
                <th>{{ $tour->t_title ?? $tour->title ?? 'Tour' }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            <tr><td><strong>Ảnh</strong></td>
              @foreach($tours as $tour)
                <td><img src="{{ asset(isset($tour->t_image)? pare_url_file($tour->t_image) : ('upload/tours/'.$tour->image)) }}" class="tour-img"></td>
              @endforeach
            </tr>

            <tr><td><strong>Giá</strong></td>
              @foreach($tours as $tour)
                <td class="text-danger"><span class="price" >
           {{ number_format($tour->t_price_adults-($tour->t_price_adults*$tour->t_sale/100),0,',','.') }} vnd/người</span></td>
              @endforeach
            </tr>

            <tr><td><strong>Khởi hành</strong></td>
              @foreach($tours as $tour)
                <td>{{ $tour->t_start_date ?? $tour->departure_date ?? '-' }}</td>
              @endforeach
            </tr>

            <tr><td><strong>Thời gian</strong></td>
              @foreach($tours as $tour)
                <td>{{ $tour->t_schedule ?? $tour->duration ?? '-' }}</td>
              @endforeach
            </tr>

            <tr><td><strong>Phương tiện</strong></td>
              @foreach($tours as $tour)
                <td>{{ $tour->t_vehicle ?? $tour->vehicle ?? '-' }}</td>
              @endforeach
            </tr>

            <tr><td><strong>Hành động</strong></td>
              @foreach($tours as $tour)
                <td>
                  <a href="{{ route('tour.detail', ['id' => $tour->id, 'slug' => \Illuminate\Support\Str::slug($tour->t_title ?? $tour->title ?? '')]) }}" class="btn btn-outline-primary">Xem chi tiết</a>
                  <form action="{{ route('compare.remove') }}" method="POST" style="display:inline;">
    @csrf
    <input type="hidden" name="id" value="{{ $tour->id }}">
    <button type="submit" class="btn btn-sm btn-danger">
        Xóa
    </button>
</form>
                </td>
              @endforeach
            </tr>
          </tbody>
        </table>
      </div>
    @endif
  </div>
</section>
    <section class="ftco-section ftco-about img"style="background-image: url({{ asset('page/images/bg_4.jpg') }});">
        <div class="overlay"></div>
        <div class="container py-md-5">
            <div class="row py-md-5">
                <div class="col-md d-flex align-items-center justify-content-center">
                    <a href="https://vimeo.com/45830194" class="icon-video popup-vimeo d-flex align-items-center justify-content-center mb-4">
                        <span class="fa fa-play"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
 <section class="ftco-section ftco-about ftco-no-pt img">
        <div class="container">
            <div class="col-md-12 heading-section ftco-animate">
                <div class="row d-flex">
                    <div class="col-md-12 about-intro">
                        <div class="row">
                            <div class="col-md-6 d-flex align-items-stretch">
                                <div class="img d-flex w-100 align-items-center justify-content-center" style="background-image:url({{ asset('page/images/about-1.jpg') }});">
                                </div>
                            </div>
                            <div class="col-md-6 pl-md-5 py-5">
                                <div class="row justify-content-start pb-3">
                                    <span class="subheading">Giới thiệu</span>
                                    <h2 class="mb-4">Hãy làm cho chuyến tham quan của bạn trở nên đáng nhớ và an toàn với chúng tôi</h2>
                                    <p>Những chuyến đi du lịch đều đọng lại trong chúng ta nhiều kỉ niệm đặc biệt, vì thế hãy trân trọng những giây phút vui vẻ, hạnh phúc trong chuyến đi của mình. Chúng tôi sẽ đồng hành cùng bạn để góp phần làm cho những trãi nghiệm đó càng thêm tuyệt vời.</p>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
