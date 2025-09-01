<?php
namespace App\Http\Controllers;

class WebController extends Controller
{
    public function Dashboard()
    {
        return view('dashboard');
    }
}
