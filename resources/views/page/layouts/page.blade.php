<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Mạng bán TOUR DU LỊCH trực tuyến hàng đầu Việt Vam | Tuvis Travel')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('page.common.head')
</head>
<body>
    @include('page.common.navbar')
    @yield('content')
    @include('page.common.footer')
    @include('page.common.script')
    <div id="compareBox" 
     style="display:none; position:fixed; bottom:20px; right:20px; width:350px; 
            background:#fff; border:1px solid #ddd; padding:15px; border-radius:10px;
            box-shadow:0 4px 15px rgba(0,0,0,0.2); z-index:9999;">
    <div id="compareList"></div>
</div>
</body>
<!-- Messenger Plugin chat Code -->
<div id="fb-root"></div>

<!-- Your Plugin chat code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>

<script>
  var chatbox = document.getElementById('fb-customer-chat');
  chatbox.setAttribute("page_id", "102030232294539");
  chatbox.setAttribute("attribution", "biz_inbox");

  window.fbAsyncInit = function() {
    FB.init({
      xfbml            : true,
      version          : 'v12.0'
    });
  };

  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
</html>