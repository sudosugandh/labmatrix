<?php

namespace App\View\Components;

use App\Models\WebsiteSetting;
use App\Models\Menu;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WebsiteLayout extends Component
{
    public $settings;
    public $menuItems;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->settings = WebsiteSetting::getForCurrentLab();
        
        $menu = Menu::where('lab_id', currentLabId())
            ->where('name', 'Main Menu')
            ->first();
        
        $this->menuItems = $menu ? $menu->items : collect();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.website-layout', [
            'settings' => $this->settings,
            'menuItems' => $this->menuItems,
        ]);
    }
}
