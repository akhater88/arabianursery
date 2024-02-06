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
                'post_image_url' => asset('storage/public/images/posts'),
                'seedling_image_url' => asset('storage/'),
                'assets_url' => asset(''),
                'notification_image_url' => asset('storage/app/public/notification'),
            ],
            'country' => $settings['country'],

        ]);
    }

}
