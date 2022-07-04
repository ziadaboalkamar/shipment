<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $page_title='لوحة المواقبة';
        return view('home' ,compact('page_title'));
    }
    public function index2()
    {
        $page_title='لوحة المواقبة';
        return view('home' ,compact('page_title'));
    }
}
