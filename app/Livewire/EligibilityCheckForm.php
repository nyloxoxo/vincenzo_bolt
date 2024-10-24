<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\UserSessionData;

class EligibilityCheckForm extends Component
{
    public $state = [
        'name' => '',
        'dob' => '',
        'street_address' => '',
        'city' => '',
        'state' => '',
        'zipcode' => '',
        'income' => '',
        'gender' => '',
        'uses_tobacco' => false,
        'coverage_year' => '',
    ];

    protected $rules = [
        'state.name' => 'required|string|max:255',
        'state.dob' => 'required|date',
        'state.street_address' => 'required|string|max:255',
        'state.city' => 'required|string|max:255',
        'state.state' => 'required|string|max:255',
        'state.zipcode' => 'required|string|regex:/^\d{5}(-\d{4})?$/',
        'state.income' => 'required|numeric|min:0',
        'state.gender' => 'required|in:Male,Female',
        'state.uses_tobacco' => 'required|boolean',
        'state.coverage_year' => 'required',
    ];

    public function mount()
    {
        $this->state['coverage_year'] = date('Y');

        /** @var UserSessionData $sessionData */
        $sessionData = session('user_session_data');
        if ($sessionData) {
            $this->state = array_merge($this->state, $sessionData->getEligibilityData());
        }
    }

    public function checkEligibility()
    {
        $this->validate();

        /** @var UserSessionData $sessionData */
        $sessionData = session('user_session_data', new UserSessionData());
        $sessionData->setEligibilityData($this->state);

        // Preserve existing usage data if it exists
        if ($existingSessionData = session('user_session_data')) {
            $sessionData->services = $existingSessionData->services;
            $sessionData->additionalServices = $existingSessionData->additionalServices;
        }

        session(['user_session_data' => $sessionData]);

        return redirect()->route('usage-data');
    }

    public function render()
    {
        return view('livewire.eligibility-check-form');
    }
}
