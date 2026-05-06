<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SettingController extends Controller
{
    // GET /api/settings — public
    public function index()
    {
        try {
            $settings = Setting::all()->groupBy('group');
            return response()->json($settings);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    /*public function store(Request $request)
{
    $request->validate(['image' => 'required|image|max:5120']);
    $path = $request->file('image')->store('settings', 'public');
    return response()->json(['url' => $path]);
}*/


    public function store(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120']);

        $result = Cloudinary::upload($request->file('image')->getRealPath(), [
            'folder' => 'abk-auto'
        ]);

        return response()->json([
            'url' => $result->getSecurePath()
        ]);
    }


    // PUT /api/settings — admin seulement
    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'settings'         => 'required|array',
                'settings.*.key'   => 'required|string',
                'settings.*.value' => 'nullable|string',
            ]);

            foreach ($data['settings'] as $item) {
                Setting::where('key', $item['key'])
                       ->update(['value' => $item['value']]);
            }

            return response()->json(['message' => 'Paramètres sauvegardés.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}