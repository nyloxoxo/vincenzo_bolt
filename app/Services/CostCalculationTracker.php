<?php

namespace App\Services;

class CostCalculationTracker
{
    public $planInfo;
    public $services = [];
    public $totalOutOfPocket = 0;

    public function __construct($id, $planName, $initialDeductible, $outOfPocketMax, $planPremium)
    {
        $this->planInfo = [
            'id' => $id,
            'planName' => $planName,
            'initialDeductible' => $initialDeductible,
            'outOfPocketMax' => $outOfPocketMax,
            'planPremium' => $planPremium
        ];
    }

    public function addServiceUsage($serviceName, $usageCount, $copayAmount, $coinsuranceRate, $cost, $networkTier)
    {
        $currentOutOfPocket = min($this->totalOutOfPocket + $cost, $this->planInfo['outOfPocketMax']);
        $remainingDeductible = max($this->planInfo['initialDeductible'] - $currentOutOfPocket, 0);

        $this->services[$serviceName][] = [
            'usageCount' => $usageCount,
            'copayAmount' => $copayAmount,
            'coinsuranceRate' => $coinsuranceRate,
            'cost' => $cost,
            'networkTier' => $networkTier,
            'currentOutOfPocket' => $currentOutOfPocket,
            'remainingDeductible' => $remainingDeductible
        ];

        $this->totalOutOfPocket = $currentOutOfPocket;
    }

    public function toArray()
    {
        return [
            'planInfo' => $this->planInfo,
            'services' => $this->services,
            'totalOutOfPocket' => $this->totalOutOfPocket
        ];
    }
}
