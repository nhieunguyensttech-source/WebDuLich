$(function () {
    $('.btn-cancel-order').click(function (event) {
        event.preventDefault();
        let url = $(this).attr('href');
        var __that = $(this);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {}
        }).done(function (result) {

            if (result.status_code == 200) {
                __that.parent().find('.btn-status-order').css('display', 'none');
                __that.text('Đã hủy');
                toastr.success(result.message, {timeOut: 3000});
            } else {
                swal('Thông báo', result.message, "error");
            }
        }).fail(function (XMLHttpRequest, textStatus, thrownError) {
            toastr.error('Đã sảy ra lỗi không thể hủy tour', {timeOut: 3000});
        });
    })
})
// Thêm tour vào so sánh
$(document).on('click', '.add-compare', function () {

    let id = $(this).data('id');

    $.post("{{ route('compare.add') }}", {
        id: id,
        _token: "{{ csrf_token() }}"
    }, function (res) {

        if (res.status === 'limit') {
            alert('Bạn chỉ được so sánh tối đa 3 tour!');
            return;
        }

        if (res.status === 'exists') {
            alert('Tour này đã có trong danh sách so sánh!');
            return;
        }

        $("#compareBox").fadeIn();
        loadComparePopup();
    });
});

// Load popup
function loadComparePopup() {
    $.get("{{ route('compare.index') }}?list=1", function (html) {
        $("#compareList").html(html);
    });
}

// Xóa tour khỏi danh sách
$(document).on('click', '.remove-compare', function () {

    let id = $(this).data('id');

    $.post("{{ route('compare.remove') }}", {
        id: id,
        _token: "{{ csrf_token() }}"
    }, function () {
        loadComparePopup();
        location.reload();
    });
});
var swiper = new Swiper(".location-swiper", {
    slidesPerView: 4,
    spaceBetween: 25,
    loop: true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    breakpoints: {
        1200: { slidesPerView: 4 },
        992: { slidesPerView: 3 },
        768: { slidesPerView: 2 },
        576: { slidesPerView: 1 }
    }
});