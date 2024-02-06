<?php

namespace App\Http\Controllers\Api\V1\Farmer;


use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{

    function __construct()
    {

    }

    public function posts(Request $request)
    {
        $posts = Post::orderBy('created_at', 'desc')
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $data = [
            'total_size' => $posts->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'posts' => $posts->items()
        ];
       return response()->json($data, 200);
    }

    public function getPostById(Post $post){
        $data = [
            $post->toArray()
        ];
        return response()->json($data, 200);
    }

}
