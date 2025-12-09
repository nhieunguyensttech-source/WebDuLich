@foreach($tours as $tour)
<li class="d-flex justify-content-between mb-1">
    <span>{{ $tour->title }}</span>
    <span class="text-danger remove-compare" data-id="{{ $tour->id }}" style="cursor:pointer;">×</span>
</li>
@endforeach
    @foreach ($tours as $tour)
        <div class="compare-item">
            <img src="{{ asset('images/tours/' . $tour->image) }}" alt="">
            <p>{{ $tour->name }}</p>
            <button class="remove-compare" data-id="{{ $tour->id }}">X</button>
        </div>
    @endforeach

    @if (count($tours) > 0)
        <a href="{{ route('compare.index') }}" class="btn btn-primary mt-2">So sánh ngay</a>
    @endif
