<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\PageResource;
use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index($id)
    {

        $page = PageResource::collection(Page::where('id',$id)->get());

        if (count($page) > 0) {
            return [
                'success' => true,
                'data' => $page
            ];
        } else {
            return [
                'success' => false,
                'message' => Config('languageString.page_not_found')
            ];
        }
    }
}
