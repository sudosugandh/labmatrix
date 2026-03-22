<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Menu;
use App\Models\WebsiteSetting;

class FrontendPageController extends Controller
{
    public function show($slug)
    {
        // Exclude common Laravel/auth routes and admin routes
        $excludedRoutes = [
            'login', 'register', 'forgot-password', 'reset-password', 
            'verify-email', 'confirm-password', 'logout', 'dashboard', 
            'admin', 'patients', 'tests', 'orders', 'profile', 
            'api', 'up', 'home', 'book-appointment', 'my-appointments',
            'patient-reports', 'appointments', 'packages'
        ];
        
        if (in_array($slug, $excludedRoutes)) {
            abort(404, 'Page not found');
        }

        $page = Page::where('lab_id', currentLabId())
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $settings = WebsiteSetting::getForCurrentLab();
        
        // Get main menu
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        
        $menuItems = $menu ? $menu->items : collect();

        return view('website.page', compact('page', 'settings', 'menuItems'));
    }
}
