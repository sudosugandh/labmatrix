<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\Test;
use App\Models\Testimonial;
use App\Models\TestPackage;
use App\Models\WebsiteSetting;

class HomepageController extends Controller
{
    public function index()
    {
        $settings = WebsiteSetting::getForCurrentLab();
        $labId = currentLabId();

        $featuredExists = Test::where('lab_id', $labId)->where('featured_on_homepage', true)->exists();
        $testsQuery = Test::where('lab_id', $labId);
        if ($featuredExists) {
            $testsQuery
                ->where('featured_on_homepage', true)
                ->orderByRaw('COALESCE(homepage_sort_order, 999999) ASC')
                ->orderBy('id');
        } else {
            $testsQuery->latest();
        }
        $tests = $testsQuery->take(24)->get();

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

        $homePackages = collect();
        if (($settings->home_show_packages_teaser ?? false) && $labId) {
            $homePackages = TestPackage::where('lab_id', $labId)
                ->where('is_active', true)
                ->with(['tests' => fn ($q) => $q->orderBy('name')->limit(8)])
                ->withCount('tests')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->take(6)
                ->get();
        }

        return view('website.home', compact('settings', 'tests', 'testimonials', 'faqs', 'promos', 'menuItems', 'homePackages'));
    }
}
