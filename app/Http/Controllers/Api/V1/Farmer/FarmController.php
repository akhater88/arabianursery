<?php

namespace App\Http\Controllers\Api\V1\Farmer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FarmController extends Controller
{
    public function getFarmerProfile(Request $request){
        $user = Auth::user();
        $farm = $user->farm;
        $userArray = $user->toArray();
        $userArray['farm'] = $farm->toArray();
        return response()->json($userArray, 200);

    }

    public function getFarmerNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications; // Retrieve all notifications
        $user->unreadNotifications->markAsRead();
        return response()->json($notifications);
    }
}
