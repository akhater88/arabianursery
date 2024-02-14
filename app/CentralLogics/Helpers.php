<?php

namespace App\CentralLogics;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class Helpers
{
    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }

    public static function seedling_data_formatting($data, $multi_data = false)
    {
        $storage = [];
        foreach ($data as $item) {
            if (isset($item->nursery)) {
                $item['nursery_name'] = $item['nursery']['name'];
                $item['nursery_address'] = $item['nursery']['address'];
                $item['nursery_phone'] = $item['nursery']['phone'];
                $item['nursery_lat'] = $item['nursery']['latitude'];
                $item['nursery_lng'] = $item['nursery']['longitude'];
                $item['nursery_logo'] = $item['nursery']['logo'];
                $item['nursery_min_time'] = 0;
                $item['nursery_max_time'] = 0;
                unset($item['nursery']);
            } else {
                $item['store_name'] = null;
                $item['store_address'] = null;
                $item['store_phone'] = null;
                $item['store_lat'] = null;
                $item['store_lng'] = null;
                $item['store_logo'] = null;
                $item['min_delivery_time'] = null;
                $item['max_delivery_time'] = null;
            }
            array_push($storage, $item);
        }
        $data = $storage;
        return $data;
    }

    public static function sendNotification(Authenticatable $user, $title, $body, $data = []){
        $notification = Firebase::messaging();
        if($user->fcm_token) {
            $FcmToken = $user->fcm_token;
            $message = CloudMessage::fromArray([
                'token' => $FcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                //'data' => $data
            ]);
            $notification->send($message);
        }
    }


}


