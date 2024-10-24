<?php
// CostResultsScreen.php

namespace App\Livewire;

use Livewire\Component;
use App\Services\HealthcareApiService;
use App\Services\UserSessionData;
use App\Services\CostCalculationService;
use App\Services\ApiResponseDecoderService;
use App\Services\HealthcareApiResponse;
use Illuminate\Support\Facades\Session;

class CostResultsScreen extends Component
{
    public ?array $data = null;

    public ?int $errorCode = null;
    public ?string $errorMessage = null;

    public $decodedPlans;
    public $planTrackers;
    public $expandedPlanId = null;

    public function mount(
        HealthcareApiService $apiService,
        CostCalculationService $costCalculationService,
        ApiResponseDecoderService $decoderService
    ) {
        $userData = Session::get('user_session_data');
        if (!$userData instanceof UserSessionData) {
            throw new \Exception("Missing or invalid user data. Please complete the previous forms.");
        }

        $response = $apiService->processUserData($userData);

        $this->data = $response->data;
        $this->errorCode = $response->errorCode;
        $this->errorMessage = $response->errorMessage;

        $this->decodedPlans = $decoderService->decodeApiResponse($response->data);

        $this->planTrackers = $costCalculationService->calculateCosts(
            $response->data,
            $userData->services
        );
    }

    public function togglePlanDetails($planId)
    {
        if ($this->expandedPlanId === $planId) {
            $this->expandedPlanId = null;
        } else {
            $this->expandedPlanId = $planId;
        }
    }

    public function render()
    {
        $currentPage = request()->get('page', 1); // Default to page 1
        $totalPages = ceil($this->totalResults / 10); // Assuming 10 results per page

        return view('livewire.cost-results-screen', [
            'data' => $this->data,
            'errorCode' => $this->errorCode,
            'errorMessage' => $this->errorMessage,
            'decodedPlans' => $this->decodedPlans,
            'planTrackers' => $this->planTrackers,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }
}
// CostResultsScreen.php
