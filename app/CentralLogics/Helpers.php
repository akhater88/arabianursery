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

            $seedlingAge = $item['created_at']->diffInDays(\Carbon\Carbon::now());
            $handedPeriod = $item['germination_period'] - $seedlingAge;
            $handedDate = \Carbon\Carbon::now()->addDays($handedPeriod)->format('d-m-Y');
            $item['expected_handed_date'] = $handedDate;
            $item['expected_handed_period'] = $handedPeriod;
            $item['available_tray'] = $item['tray_count'] - $item['seedling_purchase_requests_sum_tray_count'];
            $item['show_price'] = $item['tray_shared_price'] != null ? true : false;
            $item['price_per_tray'] = (double) $item['price_per_tray'];
            $item['discount_amount'] =(double) $item['discount_amount'];
            $item['additional_cost'] = (double) $item['additional_cost'];
            $item['tray_shared_price'] = (double) $item['tray_shared_price'];
            array_push($storage, $item);
        }
        $data = $storage;
        return $data;
    }

    public static function reserved_seedling_data_formatting($data, $multi_data = false)
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

            if(isset($item->seedlingPurchaseRequests)){
                $sumApproved = 0;
                $sumPending = 0;
                $sumRejected = 0;
                foreach ($item->seedlingPurchaseRequests as $seedling_purchase_requests){
                    if($seedling_purchase_requests->status == 1){
                        $sumApproved += $seedling_purchase_requests->tray_count;
                    } elseif ($seedling_purchase_requests->status == 2){
                        $sumPending += $seedling_purchase_requests->tray_count;
                    } else {
                        $sumRejected += $seedling_purchase_requests->tray_count;
                    }

                }
                $item['sumApproved'] = $sumApproved;
                $item['sumPending'] = $sumPending;
                $item['sumRejected'] = $sumRejected;
            }
            $seedlingAge = $item['created_at']->diffInDays(\Carbon\Carbon::now());
            $handedPeriod = $item['germination_period'] - $seedlingAge;
            $handedDate = \Carbon\Carbon::now()->addDays($handedPeriod)->format('d-m-Y');
            $item['expected_handed_date'] = $handedDate;
            $item['expected_handed_period'] = $handedPeriod;
            $item['available_tray'] = $item['tray_count'] - $item['seedling_purchase_requests_sum_tray_count'];
            $item['show_price'] = $item['tray_shared_price'] != null ? true : false;

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
//                'data' => [
//                    'title' => $title,
//                    'body' => $body,
//                    'type' => 'seedling',
//                    'seedling_id' => ''
//                ]
            ]);
            try {
                $notification->send($message);

            } catch (\Exception $e){

            }
        }
    }


}


