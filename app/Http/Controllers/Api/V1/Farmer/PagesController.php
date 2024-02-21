<?php

namespace App\Http\Controllers\Api\V1\Farmer;


use App\Http\Controllers\Controller;
use App\Models\Page;

class PagesController extends Controller
{

    public function getPageByCode(String $code){
        $page = Page::where('code', $code)->first()->toArray();
        return response()->json($page, 200);
    }

}
