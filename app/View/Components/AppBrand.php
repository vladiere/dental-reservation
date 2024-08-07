<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppBrand extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'HTML'
    <a href="{{ route('dashboard') }}" wire:navigate>
        <!-- Hidden when collapsed -->
        <div {{ $attributes->class(["hidden-when-collapsed"]) }}>
            <div class="flex items-center gap-2">
                <x-mary-icon name="o-square-3-stack-3d" class="w-6 -mb-1 text-blue-500" />
                <span class="font-bold text-8md me-3 bg-gradient-to-r from-blue-500 to-gray-300 bg-clip-text text-transparent ">
                    SOY Dental Clinic
                </span>
            </div>
        </div>

        <!-- Display when collapsed -->
        <div class="display-when-collapsed hidden text-center mt-4 lg:mb-6 h-[28px]">
            <span class="font-extrabold text-xl">SOY</span>
        </div>
    </a>
HTML;
    }
}
