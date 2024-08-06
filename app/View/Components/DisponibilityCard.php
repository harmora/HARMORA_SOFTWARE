<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Disponibility;


class DisponibilityCard extends Component
{
    protected $user;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {

     $this->user = getAuthenticatedUser();

    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $visibleColumns = getUserPreferences('disponibilities'); // Adjust this based on how you get user preferences


        $disponibilities = $this->user->entreprise->disponibility;
        return view('components.disponibility-card',['disponibilities'=>$disponibilities],compact('visibleColumns'));

    }
}
