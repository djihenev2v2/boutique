<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class PageController extends Controller
{
    public function termsOfSale()
    {
        $terms = Setting::get('terms');
        return view('client.conditions-de-vente', compact('terms'));
    }
}
