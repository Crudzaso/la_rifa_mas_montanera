<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class App extends Component
{
    public function __construct()
    {
        //
    }

    
    public function render(): View|Closure|string
    {
        return view('components.layout.app');
    }
}
