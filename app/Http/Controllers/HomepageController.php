<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\Test;
use App\Models\Testimonial;
use App\Models\WebsiteSetting;

class HomepageController extends Controller
{
    public function index()
    {
        $settings = WebsiteSetting::getForCurrentLab();
        $tests = Test::where('lab_id', currentLabId())
            ->latest()
            ->take(24)
            ->get();

        $testimonials = Testimonial::forCurrentLab()
            ->orderByRaw('is_featured DESC, sort_order ASC, id ASC')
            ->take(6)
            ->get();

        $faqs = Faq::forCurrentLab()->active()->orderBy('sort_order')->orderBy('id')->get();

        $promos = Promo::forCurrentLab()->active()->valid()->orderBy('sort_order')->orderBy('id')->take(12)->get();

        // Get main menu
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();

        $menuItems = $menu ? $menu->items : collect();

        return view('website.home', compact('settings', 'tests', 'testimonials', 'faqs', 'promos', 'menuItems'));
    }
}
