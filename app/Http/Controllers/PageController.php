<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    // Method untuk menampilkan halaman home
    public function home()
    {
        // memanggil view resources/views/home.blade.php
        return view('home');
    }

    // Method untuk menampilkan halaman about
    public function about()
    {
        return view('about');
    }
}
