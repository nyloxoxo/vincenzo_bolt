<?php
// PlanResultsScreen.php
namespace App\Livewire;

use Livewire\Component;
use App\Services\HealthcareApiService;
use App\Services\UserSessionData;
use Illuminate\Support\Facades\Session;

class PlanResultsScreen extends Component
{
    public ?array $data = null;

    public ?int $errorCode = null;
    public ?string $errorMessage = null;

    public $expandedPlanId = null;

    public function mount(HealthcareApiService $apiService)
    {
        $this->loadPlans($apiService, 1);
    }

    public function loadPlans(HealthcareApiService $apiService, $page)
    {
        $userData = Session::get('user_session_data');
        if (!$userData instanceof UserSessionData) {
            throw new \Exception("Missing or invalid user data. Please complete the previous forms.");
        }

        $response = $apiService->processUserData($userData, $page);

        $this->data = $response->data;
        $this->errorCode = $response->errorCode;
        $this->errorMessage = $response->errorMessage;

        if ($this->errorCode == 1003) {
            return redirect()->route('welcome')->with('error', 'Unable to find your address, please check it and try again.');
        }
    }

    protected $listeners = ['pageChanged' => 'loadPlans'];

    public function render()
    {
        return view('livewire.plan-results-screen', [
            'data' => $this->data,
            'errorCode' => $this->errorCode,
            'errorMessage' => $this->errorMessage,
        ]);
    }

    public function togglePlanDetails($planId)
    {
        if ($this->expandedPlanId === $planId) {
            $this->expandedPlanId = null;
        } else {
            $this->expandedPlanId = $planId;
        }
    }
}
// PlanResultsScreen.php
