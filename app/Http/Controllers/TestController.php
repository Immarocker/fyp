<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends FrontendController 
{
    public function testMailchimp()
    {
        $apiKey = config('newsletter.apiKey');
        $listId = config('newsletter.lists.kitabbhandaar.id');

        return response()->json([
            'apiKey' => $apiKey,
            'listId' => $listId,
        ]);
    }
}