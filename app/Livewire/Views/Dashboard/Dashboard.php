<?php

namespace App\Livewire\Views\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
  #[Layout('components.layouts.main')]
  #[Title('SAFI NFE - Dashboard')]
  public function render()
  {
    return view('livewire.views.dashboard.dashboard');
  }
}
