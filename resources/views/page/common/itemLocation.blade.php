<div class="col-lg-3 col-md-6 mb-4">
    <div class="location-card">

        {{-- Tag tên --}}
        <div class="location-tag">
            {{ $location->l_name }}
        </div>

        {{-- Ảnh --}}
        <a href="{{ route('location.show', ['id' => $location->id, 'slug' => safeTitle($location->l_name)]) }}"
           class="location-img"
           style="background-image: url({{ asset(pare_url_file($location->l_image)) }});">
        </a>

        {{-- Số tour --}}
        <div class="location-tour">
            {{ $location->tours ? $location->tours->count() : 0 }} Tours
        </div>

    </div>
</div>
