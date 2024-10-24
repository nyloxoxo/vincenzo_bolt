<?php
// ApiResponseDecoderService.php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class ApiResponseDecoderService
{
    public function decodeApiResponse($apiResponse)
    {
        $decodedPlans = [];
        if (!isset($apiResponse['plans'])) {
            //dd($apiResponse);
            throw new Exception("Error: 'plans' key not found in API response");
            return $decodedPlans;
        }

        if (!is_array($apiResponse['plans'])) {
            throw new Exception("Error: 'plans' is not an array in API response");
            return $decodedPlans;
        }

        foreach ($apiResponse['plans'] as $planIndex => $plan) {
            $planId = $this->safeGet($plan, 'id', null, "Plan at index $planIndex");
            if (!$planId) {
                throw new Exception("Error: Plan ID not found for plan at index $planIndex");
                continue;
            }

            $planName = $this->safeGet($plan, 'name', '', "Plan $planId");

            $decodedPlans[$planId] = $this->decodePlan($plan, $planId, $planName);
        }
        return $decodedPlans;
    }

    private function decodePlan($plan, $planId, $planName)
    {
        return [
            'id' => $planId,
            'name' => $planName,
            'premium' => $this->safeGet($plan, 'premium', null, $planName),
            'metal_level' => $this->safeGet($plan, 'metal_level', '', $planName),
            'type' => $this->safeGet($plan, 'type', '', $planName),
            'benefits' => $this->decodeBenefits($this->safeGet($plan, 'benefits', [], $planName), $planId, $planName),
            'deductibles' => $this->decodeCostStructures($this->safeGet($plan, 'deductibles', [], $planName), $planId, $planName, 'deductible'),
            'tiered_deductibles' => $this->decodeCostStructures($this->safeGet($plan, 'tiered_deductibles', [], $planName), $planId, $planName, 'tiered_deductible'),
            'moops' => $this->decodeCostStructures($this->safeGet($plan, 'moops', [], $planName), $planId, $planName, 'moop'),
            'tiered_moops' => $this->decodeCostStructures($this->safeGet($plan, 'tiered_moops', [], $planName), $planId, $planName, 'tiered_moop'),
            'issuer' => [
                'id' => $this->safeGet($this->safeGet($plan, 'issuer', [], $planName), 'id', '', $planName),
                'name' => $this->safeGet($this->safeGet($plan, 'issuer', [], $planName), 'name', '', $planName),
            ],
            'hsa_eligible' => $this->safeGet($plan, 'hsa_eligible', false, $planName),
            'insurance_market' => $this->safeGet($plan, 'insurance_market', '', $planName),
            'specialist_referral_required' => $this->safeGet($plan, 'specialist_referral_required', false, $planName),
            'disease_mgmt_programs' => $this->safeGet($plan, 'disease_mgmt_programs', [], $planName),
            'has_national_network' => $this->safeGet($plan, 'has_national_network', false, $planName),
            'market' => $this->safeGet($plan, 'market', '', $planName),
            'max_age_child' => $this->safeGet($plan, 'max_age_child', null, $planName),
        ];
    }

    private function decodeBenefits($benefits, $planId, $planName)
    {
        $decodedBenefits = [];
        foreach ($benefits as $benefit) {
            $benefitType = $this->safeGet($benefit, 'type', 'unknown', $planName);
            $decodedBenefits[$benefitType] = [
                'plan_id' => $planId,
                'plan_name' => $planName,
                'benefit_type' => $benefitType,
                'benefit_name' => $this->safeGet($benefit, 'name', '', $planName),
                'is_covered' => $this->safeGet($benefit, 'covered', false, $planName),
                'cost_sharings' => $this->decodeCostSharings($this->safeGet($benefit, 'cost_sharings', [], $planName), $planId, $planName, $benefitType),
                'explanation' => $this->safeGet($benefit, 'explanation', '', $planName),
                'exclusions' => $this->safeGet($benefit, 'exclusions', '', $planName),
                'has_limits' => $this->safeGet($benefit, 'has_limits', false, $planName),
                'limit_unit' => $this->safeGet($benefit, 'limit_unit', '', $planName),
                'limit_quantity' => $this->safeGet($benefit, 'limit_quantity', null, $planName),
            ];
        }
        return $decodedBenefits;
    }

    private function decodeCostSharings($costSharings, $planId, $planName, $benefitType)
    {
        $decodedCostSharings = [];
        foreach ($costSharings as $costSharing) {
            $networkTier = $this->safeGet($costSharing, 'network_tier', 'unknown', "$planName - $benefitType");
            $decodedCostSharings[$networkTier] = [
                'plan_id' => $planId,
                'plan_name' => $planName,
                'benefit_type' => $benefitType,
                'coinsurance_rate' => $this->safeGet($costSharing, 'coinsurance_rate', null, "$planName - $benefitType"),
                'coinsurance_options' => $this->safeGet($costSharing, 'coinsurance_options', '', "$planName - $benefitType"),
                'copay_amount' => $this->safeGet($costSharing, 'copay_amount', null, "$planName - $benefitType"),
                'copay_options' => $this->safeGet($costSharing, 'copay_options', '', "$planName - $benefitType"),
                'network_tier' => $networkTier,
                'csr' => $this->safeGet($costSharing, 'csr', null, "$planName - $benefitType"),
                'display_string' => $this->safeGet($costSharing, 'display_string', '', "$planName - $benefitType"),
                'benefit_before_deductible' => $this->safeGet($costSharing, 'benefit_before_deductible', '', "$planName - $benefitType"),
            ];
        }
        return $decodedCostSharings;
    }

    private function decodeCostStructures($structures, $planId, $planName, $type)
    {
        $decodedStructures = [];
        foreach ($structures as $structure) {
            $decodedStructures[] = [
                'plan_id' => $planId,
                'plan_name' => $planName,
                'type' => $this->safeGet($structure, 'type', 'unknown', "$planName - $type"),
                'amount' => $this->safeGet($structure, 'amount', 0, "$planName - $type"),
                'csr' => $this->safeGet($structure, 'csr', null, "$planName - $type"),
                'network_tier' => $this->safeGet($structure, 'network_tier', 'unknown', "$planName - $type"),
                'family_cost_type' => $this->safeGet($structure, 'family_cost', null, "$planName - $type"),
                'is_individual' => $this->safeGet($structure, 'individual', false, "$planName - $type"),
                'is_family' => $this->safeGet($structure, 'family', false, "$planName - $type"),
                'display_string' => $this->safeGet($structure, 'display_string', '', "$planName - $type"),
            ];
        }
        return $decodedStructures;
    }

    private function safeGet($array, $key, $default = null, $context = '')
    {
        if (isset($array[$key])) {
            return $array[$key];
        }
        throw new Exception("Key '$key' not found in context: $context");
        return $default;
    }
}
// ApiResponseDecoderService.php
