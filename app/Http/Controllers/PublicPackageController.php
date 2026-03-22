<?php

namespace App\Http\Controllers;

use App\Models\TestPackage;
use App\Models\WebsiteSetting;
use App\Models\Menu;
use Illuminate\Http\Request;

class PublicPackageController extends Controller
{
    /**
     * Display all active packages
     */
    public function index()
    {
        $settings = WebsiteSetting::getForCurrentLab();
        
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        
        $menuItems = $menu ? $menu->items : collect();

        $packages = TestPackage::forCurrentLab()
            ->active()
            ->withCount('tests')
            ->with('tests')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('website.packages.index', compact('settings', 'menuItems', 'packages'));
    }

    /**
     * Display a specific package
     */
    public function show(TestPackage $testPackage)
    {
        if ($testPackage->lab_id !== currentLabId() || !$testPackage->is_active) {
            abort(404);
        }

        $settings = WebsiteSetting::getForCurrentLab();
        
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        
        $menuItems = $menu ? $menu->items : collect();

        $testPackage->load('tests');

        return view('website.packages.show', compact('settings', 'menuItems', 'testPackage'));
    }
}
