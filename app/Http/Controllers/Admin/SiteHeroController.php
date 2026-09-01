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
        'dashboard_process_1' => 'Dashboard How It Works 1',
        'dashboard_process_2' => 'Dashboard How It Works 2',
        'dashboard_process_3' => 'Dashboard How It Works 3',
        'dashboard_quick_1' => 'Dashboard Quick Action 1',
        'dashboard_quick_2' => 'Dashboard Quick Action 2',
        'dashboard_quick_3' => 'Dashboard Quick Action 3',
        'homepage_service_1' => 'Homepage Quick Action 1',
        'homepage_service_2' => 'Homepage Quick Action 2',
        'homepage_service_3' => 'Homepage Quick Action 3',
        'homepage_service_4' => 'Homepage Quick Action 4',
        'homepage_service_5' => 'Homepage Quick Action 5',
        'homepage_service_6' => 'Homepage Quick Action 6',
        'gallery' => 'Gallery',
        'purchases' => 'Purchases',
        'cart' => 'Cart',
    ];

    public function index()
    {
        $heroes = SiteHero::whereIn('key', array_keys(self::HEROES))->get()->keyBy('key');
        $heroGroups = [
            'Dashboard' => [
                'dashboard_background',
                'dashboard_foreground',
                'dashboard_process_1',
                'dashboard_process_2',
                'dashboard_process_3',
                'dashboard_quick_1',
                'dashboard_quick_2',
                'dashboard_quick_3',
            ],
            'Homepage' => [
                'homepage_service_1',
                'homepage_service_2',
                'homepage_service_3',
                'homepage_service_4',
                'homepage_service_5',
                'homepage_service_6',
            ],
            'Portal' => [
                'gallery',
                'purchases',
                'cart',
            ],
        ];

        return view('admin.site-heroes.index', [
            'heroLabels' => self::HEROES,
            'heroGroups' => $heroGroups,
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
