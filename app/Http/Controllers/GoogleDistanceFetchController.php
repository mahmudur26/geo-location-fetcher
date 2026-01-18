<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoogleDistanceFetchController extends Controller
{
    public function distance_fetch()
    {
        return view('distance_fetch.google_distance_fetch');
    }
}
