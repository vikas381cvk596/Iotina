<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function demo($demopage = 'index')
    {
        return view('admin.' . $demopage)->with(['page' => $demopage]);
    }
}
