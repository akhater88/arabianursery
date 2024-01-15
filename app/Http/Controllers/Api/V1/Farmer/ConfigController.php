<?php

namespace App\Http\Controllers\Api\V1\Farmer;


use App\Http\Controllers\Controller;


class ConfigController extends Controller
{

    function __construct()
    {

    }

    public function configuration()
    {
        $settings = [
            'business_name' => "المزارعون العرب",
            'address' => "عمان",
            'phone' => "+962797093010",
            'email_address' => 'support@arabiafarmers.com',
            'country' => 'JO',
            ];

        return response()->json([
            'business_name' => $settings['business_name'],
            'address' => $settings['address'],
            'phone' => $settings['phone'],
            'email' => $settings['email_address'],
            'base_urls' => [
                'item_image_url' => asset('storage/app/public/product'),
                'refund_image_url' => asset('storage/app/public/refund'),
                'customer_image_url' => asset('storage/app/public/profile'),
                'banner_image_url' => asset('storage/app/public/banner'),
                'category_image_url' => asset('storage/app/public/category'),
                'review_image_url' => asset('storage/app/public/review'),
                'notification_image_url' => asset('storage/app/public/notification'),
                'store_image_url' => asset('storage/app/public/store'),
                'vendor_image_url' => asset('storage/app/public/vendor'),
                'store_cover_photo_url' => asset('storage/app/public/store/cover'),
                'delivery_man_image_url' => asset('storage/app/public/delivery-man'),
                'chat_image_url' => asset('storage/app/public/conversation'),
                'campaign_image_url' => asset('storage/app/public/campaign'),
                'business_logo_url' => asset('storage/app/public/business'),
                'order_attachment_url' => asset('storage/app/public/order'),
                'module_image_url' => asset('storage/app/public/module'),
                'parcel_category_image_url' => asset('storage/app/public/parcel_category'),
                'landing_page_image_url' => asset('assets/landing/image'),
                'react_landing_page_images' => asset('storage/app/public/react_landing') ,
                'react_landing_page_feature_images' => asset('storage/app/public/react_landing/feature') ,
                'gateway_image_url' => asset('storage/app/public/payment_modules/gateway_image'),
            ],
            'country' => $settings['country'],

        ]);
    }

}
