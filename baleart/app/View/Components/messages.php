<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class messages extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $type)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.messages');
    }

    public function majuscules($v) {

        // Pasa a mayúsculas
        return strtoupper($v); 
    }
}
