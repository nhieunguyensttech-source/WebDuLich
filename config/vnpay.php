<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VNPay Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình thông tin VNPay để tích hợp thanh toán
    |
    */

    // Mã website của bạn trên VNPay (TMN Code)
    'tmn_code' => env('VNPAY_TMN_CODE', ''),

    // Mã bảo mật (Hash Secret) từ VNPay
    'hash_secret' => env('VNPAY_HASH_SECRET', ''),

    // URL trả về sau khi thanh toán (browser redirect)
    'return_url' => env('VNPAY_RETURN_URL', 'http://127.0.0.1:8000/payment/vnpay/return'),

    // IPN URL - VNPay gọi từ server để cập nhật trạng thái thanh toán
    'ipn_url' => env('VNPAY_IPN_URL', 'http://127.0.0.1:8000/payment/vnpay/ipn'),

    // URL VNPay (sandbox hoặc production)
    'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),

    // Môi trường: sandbox hoặc production
    'environment' => env('VNPAY_ENVIRONMENT', 'sandbox'),
];

