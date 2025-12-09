@if($tour->t_status < 2)
<div class="{{ !isset($itemTour) ? 'col-md-4' : '' }} ftco-animate fadeInUp ftco-animated {{ isset($itemTour) ? $itemTour : '' }}">
    <div class="project-wrap">
        @php
            $tourImage = $tour->t_image ? asset(pare_url_file($tour->t_image)) : asset('admin/dist/img/no-image.png');
        @endphp
        <a href="{{ route('tour.detail', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]) }}" class="img"
           style="background-image: url('{{ $tourImage }}')">
           @if( $tour->t_sale > 0)
           <span  class="price">Sale {{ $tour->t_sale }}%</span>
           @endif
           @if( $tour->t_sale > 0)
           <span class="price" style="margin-left:100px">
           {{ number_format($tour->t_price_adults-($tour->t_price_adults*$tour->t_sale/100),0,',','.') }} vnd/người <br>
           <span style="text-decoration: line-through;margin-left:35px;color:#ddd">{{ number_format($tour->t_price_adults,0,',','.') }}</span>
        </span>
           @else
           <span class="price" >
           {{ number_format($tour->t_price_adults-($tour->t_price_adults*$tour->t_sale/100),0,',','.') }} vnd/người</span>
           @endif
        </a>
        
        <div class="text p-4">   
            @if($tour->t_number_registered==$tour->t_number_guests)
            <h5 class="days" style="color:red">Đã hết chỗ</h5>
           
            @endif 
            <span class="days">{{ $tour->t_schedule }}  </span>
            <h3>
                <a href="{{ route('tour.detail', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]) }}" title="{{ $tour->t_title }}">
                    {{ the_excerpt($tour->t_title, 100) }}
                </a>

            </h3>
            <p class="location"><span class="fa fa-map-marker"></span> Từ : {{ isset($tour->t_starting_gate) ? $tour->t_starting_gate : '' }}</p>
            <p class="location"><span class="fa fa-calendar-check-o"></span> Khởi hành : {{ $tour->t_start_date  }}</p>
            <?php $number = $tour->t_number_guests - $tour->t_number_registered ?>
            <p class="location"><span class="fa fa-user"></span> Số chỗ : {{ $tour->t_number_guests  }} - Còn trống: {{  $number  }} </p>
          
            <p class="location"><span class="fa fa-user"></span> Đã xác nhận : {{  $tour->t_number_registered  }}</p>
            @if($tour->t_number_registered<$tour->t_number_guests)
            
            <a class="location"><span class="fa fa-user"></span> số người đang đăng ký: {{ $tour->t_follow  }} </a>
            @endif
            @if($number-$tour->t_follow<2 && $tour->t_number_registered!=$tour->t_number_guests)
            <a style="color:red"> sắp hết </a>
            @endif
            <div>
  <a href="{{ route('compare.index') }}" class="add-compare" data-id="{{ $tour->id }}">
    So sánh tour
</a>
<form action="{{ route('compare.add') }}" method="POST" style="display:inline;">
    @csrf
    <input type="hidden" name="id" value="{{ $tour->id }}">
    <button type="submit" class="btn btn-sm btn-primary ml-3">Thêm tour vào so sánh</button>
</form>
</div>
            {{--<ul>--}}
            {{--<li><i class="fa fa-user" aria-hidden="true"></i> 2</li>--}}
            {{--<li><i class="fa fa-user" aria-hidden="true"></i> 3</li>--}}
            {{--</ul>--}}
        </div>
    </div>
</div>
@endif