<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteHero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteHeroController extends Controller
{
    public const HEROES = [
        'dashboard_background' => 'Dashboard Background',
        'dashboard_foreground' => 'Dashboard Foreground',
        'gallery' => 'Gallery',
        'purchases' => 'Purchases',
        'cart' => 'Cart',
    ];

    public function index()
    {
        $heroes = SiteHero::whereIn('key', array_keys(self::HEROES))->get()->keyBy('key');

        return view('admin.site-heroes.index', [
            'heroLabels' => self::HEROES,
            'heroes' => $heroes,
        ]);
    }

    public function update(Request $request)
    {
        $rules = [];

        foreach (array_keys(self::HEROES) as $key) {
            $rules[$key] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240';
        }

        $request->validate($rules);

        foreach (array_keys(self::HEROES) as $key) {
            if (! $request->hasFile($key)) {
                continue;
            }

            $hero = SiteHero::where('key', $key)->first();
            $path = $request->file($key)->store('heroes', 'public');

            if ($hero) {
                Storage::disk('public')->delete($hero->image_path);
                $hero->update(['image_path' => $path]);
            } else {
                SiteHero::create([
                    'key' => $key,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.site-heroes.index')->with('success', 'Hero images updated successfully.');
    }
}
