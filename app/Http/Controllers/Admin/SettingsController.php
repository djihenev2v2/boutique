<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'shop_name'         => Setting::get('shop_name', ''),
            'shop_phone'        => Setting::get('shop_phone', ''),
            'shop_email'        => Setting::get('shop_email', ''),
            'shop_address'      => Setting::get('shop_address', ''),
            'cod_enabled'       => Setting::get('cod_enabled', '1'),
            'baridimob_enabled' => Setting::get('baridimob_enabled', '0'),
            'cib_enabled'       => Setting::get('cib_enabled', '0'),
            'terms'             => Setting::get('terms', ''),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shop_name'    => 'required|string|max:255',
            'shop_phone'   => 'nullable|string|max:30',
            'shop_email'   => 'nullable|email|max:255',
            'shop_address' => 'nullable|string|max:500',
            'terms'        => 'nullable|string',
        ]);

        Setting::set('shop_name',         $request->input('shop_name', ''));
        Setting::set('shop_phone',        $request->input('shop_phone', ''));
        Setting::set('shop_email',        $request->input('shop_email', ''));
        Setting::set('shop_address',      $request->input('shop_address', ''));
        Setting::set('cod_enabled',       $request->has('cod_enabled')       ? '1' : '0');
        Setting::set('baridimob_enabled', $request->has('baridimob_enabled') ? '1' : '0');
        Setting::set('cib_enabled',       $request->has('cib_enabled')       ? '1' : '0');
        Setting::set('terms',             $request->input('terms', ''));

        return redirect()->route('admin.settings')->with('success', 'Paramètres enregistrés avec succès.');
    }
}
