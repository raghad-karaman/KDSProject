<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Region;

class HomeController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('Home.home', compact('regions'));
    }
}

