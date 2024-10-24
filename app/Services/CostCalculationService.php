// CostCalculationService.php
<?php


namespace App\Services;

use App\Services\CostCalculationTracker;
use App\Services\ApiResponseDecoderService;

class CostCalculationService
{
    private $apiResponseDecoderService;

    public function __construct(ApiResponseDecoderService $apiResponseDecoderService)
    {
        $this->apiResponseDecoderService = $apiResponseDecoderService;
    }

    public function calculateCosts($apiResponse, $usageData)
    {
        $decodedPlans = $this->apiResponseDecoderService->decodeApiResponse($apiResponse);
        $trackers = [];

        foreach ($decodedPlans as $plan) {
            $tracker = new CostCalculationTracker(
                $plan['id'],
                $plan['name'],
                $plan['deductibles'][0]['amount'],
                $plan['moops'][0]['amount'],
                $plan['premium']
            );

            foreach ($usageData as $serviceUsageName => $usage) {
                $apiType = $this->getTypeFromServiceUsageName($serviceUsageName);
                $benefit = $plan['benefits'][$apiType] ?? null;

                if ($benefit) {
                    $networkTier = $usage['network_tier'];
                    $usageCount = $usage['count'];
                    $costSharing = $benefit['cost_sharings'][$networkTier] ?? null;

                    if ($costSharing) {
                        $copayAmount = $costSharing['copay_amount'] ?? 0;
                        $coinsuranceRate = $costSharing['coinsurance_rate'] ?? 0;
                        $isCopay = $costSharing['coinsurance_options'] === 'Not Applicable' && isset($costSharing['copay_amount']);
                        $isCoinsurance = $costSharing['coinsurance_options'] !== 'Not Applicable' && isset($costSharing['coinsurance_rate']);

                        for ($i = 0; $i < $usageCount; $i++) {
                            $usageCost = $this->calculateUsageCost($isCopay, $isCoinsurance, $copayAmount, $coinsuranceRate, $networkTier, $usage, $tracker);
                            $tracker->addServiceUsage($serviceUsageName, $i + 1, $copayAmount, $coinsuranceRate, $usageCost, $networkTier);

                            if ($tracker->totalOutOfPocket >= $tracker->planInfo['outOfPocketMax']) {
                                break;
                            }
                        }
                    }
                }
            }

            $trackers[] = $tracker->toArray();
        }

        return $trackers;
    }

    private function calculateUsageCost($isCopay, $isCoinsurance, $copayAmount, $coinsuranceRate, $networkTier, $usage, $tracker)
    {
        if ($isCopay) {
            return $copayAmount;
        } elseif ($isCoinsurance) {
            $baseCost = $networkTier === 'Out-of-Network' ? ($usage['out_of_network_price'] ?? 100) : 100;
            $remainingDeductible = $tracker->planInfo['initialDeductible'] - $tracker->totalOutOfPocket;
            if ($remainingDeductible <= 0) {
                return $baseCost * ($coinsuranceRate / 100);
            } else {
                $deductibleApplied = min($remainingDeductible, $baseCost);
                $usageCost = $deductibleApplied;
                if ($deductibleApplied < $baseCost) {
                    $usageCost += ($baseCost - $deductibleApplied) * ($coinsuranceRate / 100);
                }
                return $usageCost;
            }
        }
        return 0;
    }

    private function getTypeFromServiceUsageName($serviceUsageName)
    {
        $serviceNameMapping = [
            'primary_care' => 'PRIMARY_CARE_VISIT_TO_TREAT_AN_INJURY_OR_ILLNESS',
            'specialist' => 'SPECIALIST_VISIT',
            'urgent_care' => 'URGENT_CARE_CENTERS_OR_FACILITIES',
            'er_emergency' => 'EMERGENCY_ROOM_SERVICES',
            'therapy' => 'MENTAL_BEHAVIORAL_HEALTH_OUTPATIENT_SERVICES',
            'chiropractic' => 'CHIROPRACTIC_CARE',
            'xray' => 'X_RAYS_AND_DIAGNOSTIC_IMAGING',
            'bloodwork' => 'LABORATORY_OUTPATIENT_AND_PROFESSIONAL_SERVICES',
            'mri' => 'MAGNETIC_RESONANCE_IMAGING',
            'ct_scan' => 'COMPUTED_TOMOGRAPHY',
            'pet_scan' => 'POSITRON_EMISSION_TOMOGRAPHY'
        ];

        return $serviceNameMapping[$serviceUsageName] ?? $serviceUsageName;
    }
}
// CostCalculationService.php
