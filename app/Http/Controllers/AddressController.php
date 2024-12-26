<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        return view('address');
    }

    public function tspVue()
    {
        return view('tsp');
    }
}
