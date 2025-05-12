<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Alert;

class GeneralSettingController extends Controller
{
    public function index()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $data = Settings::orderBy('id')->get();
            return view('settings.general.index', compact('data'));
        } else {
            Abort('403');
        }
    }
    public function update(Request $request)
    {
        try {
            $request->validate([
                'key' => 'required|array',
                'value' => 'required|array',
                'value.*' => 'string', // Adjust validation rules based on your needs
            ]);

            // Loop through each key-value pair and update the settings
            foreach ($request->key as $index => $key) {
                Settings::where('key', $key)->update(['value' => $request->value[$index]]);
            }

            // Redirect back with success message
            Alert::success('Berhasil', 'Perubahan Berhasil dilakukan!');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Perubahan Gagal dilakukan!');
            return redirect()->back();
        }
    }
}
