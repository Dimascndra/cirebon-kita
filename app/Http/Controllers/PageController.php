<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the About Us page.
     */
    public function about()
    {
        return view('pages.about');
    }
}
