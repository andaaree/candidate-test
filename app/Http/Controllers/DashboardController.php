<?php

namespace App\Http\Controllers;

use App\Traits\FeedbackHandler;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use FeedbackHandler;
    protected $items = [];

    public function index(Request $request)
    {
        $this->items = $this->defaultNav(null,request()->path());
        return view('pages.dashboard',[
            'items' => $this->items
        ]);
    }
}
