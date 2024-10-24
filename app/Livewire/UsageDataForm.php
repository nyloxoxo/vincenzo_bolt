<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\UserSessionData;

class UsageDataForm extends Component
{
    public $services = [
        'therapy' => ['count' => 0, 'network_tier' => 'In-Network', 'price' => 150],
        'chiropractic' => ['count' => 0, 'network_tier' => 'In-Network', 'price' => 100],
        'urgent_care' => ['count' => 0, 'network_tier' => 'In-Network', 'price' => 200],
        'er_emergency' => ['count' => 0, 'network_tier' => 'In-Network', 'price' => 1000],
        'specialist' => ['count' => 0, 'network_tier' => 'In-Network', 'price' => 250],
        'primary_care' => ['count' => 0, 'network_tier' => 'In-Network', 'price' => 150],
    ];

    public $additionalServices = [
        'xray' => ['count' => 0, 'price' => 400],
        'bloodwork' => ['count' => 0, 'price' => 300],
        'mri' => ['count' => 0, 'price' => 400],
        'ct_scan' => ['count' => 0, 'price' => 475],
        'pet_scan' => ['count' => 0, 'price' => 2200],
    ];

    protected $rules = [
        'services.*.count' => 'integer|min:0',
        'services.*.network_tier' => 'in:In-Network,Out-of-Network',
        'services.*.price' => 'numeric|min:0',
        'additionalServices.*.count' => 'integer|min:0',
        'additionalServices.*.price' => 'numeric|min:0',
    ];

    public function mount()
    {
        /** @var UserSessionData $sessionData */
        $sessionData = session('user_session_data');

        if ($sessionData && $sessionData->services !== null) {
            $this->services = $sessionData->services;
            $this->additionalServices = $sessionData->additionalServices;
        }
    }

    public function calculateCosts()
    {
        $this->validate();

        /** @var UserSessionData $sessionData */
        $sessionData = session('user_session_data', new UserSessionData());
        $sessionData->services = $this->services;
        $sessionData->additionalServices = $this->additionalServices;
        session(['user_session_data' => $sessionData]);

        session()->flash('message', 'Costs calculated successfully.');

        return redirect()->route('results.plans');
    }

    public function render()
    {
        return view('livewire.usage-data-form');
    }
}
