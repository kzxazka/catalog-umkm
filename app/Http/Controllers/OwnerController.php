<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function products()
    {
        return view('owner.products');
    }

    public function links()
    {
        return view('owner.links');
    }

    public function settings()
    {
        return view('owner.settings');
    }
}
