// cost-results-screen.blade.php

<div class="p-6 dark:bg-gray-800 dark:text-gray-200">
    <h2 class="text-2xl font-semibold mb-4 dark:text-gray-100">Available Plans</h2>

    <table class="w-full">
        <thead>
            <tr class="bg-gray-100 dark:bg-gray-700">
                <th class="text-left p-2 dark:text-gray-300">Plan Name</th>
                <th class="text-left p-2 dark:text-gray-300">Plan Type</th>
                <th class="text-right p-2 dark:text-gray-300">Monthly Premium</th>
                <th class="text-right p-2 dark:text-gray-300">Deductible</th>
                <th class="text-right p-2 dark:text-gray-300">Max Out of Pocket</th>
                <th class="text-center p-2 dark:text-gray-300">Metal Level</th>
                <th class="text-center p-2 dark:text-gray-300"></th>
            </tr>
        </thead>
        <tbody class="dark:text-gray-300">
            @foreach($decodedPlans as $plan)
            <tr class="border-b dark:border-gray-600">
                <td class="p-2">{{ $plan['name'] }}</td>
                <td class="p-2">{{ $plan['type'] }}</td>
                <td class="p-2 text-right">${{ number_format($plan['premium'], 2) }}</td>
                <td class="p-2 text-right">${{ number_format($plan['deductibles'][0]['amount'], 2) }}</td>
                <td class="p-2 text-right">${{ number_format($plan['moops'][0]['amount'], 2) }}</td>
                <td class="p-2 text-center">{{ $plan['metal_level'] }}</td>
                <td class="p-2 text-center">
                    <button wire:click="togglePlanDetails('{{ $plan['id'] }}')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ $expandedPlanId === $plan['id'] ? 'Collapse Details' : 'Expand Details' }}
                    </button>
                </td>
            </tr>
            @if($expandedPlanId === $plan['id'])
            <tr>
                <td colspan="7" class="p-4 bg-gray-50 dark:bg-gray-600">

                    @foreach($planTrackers as $tracker)
                    @if($expandedPlanId === $tracker['planInfo']['id'])
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-gray-700 dark:text-gray-200">Plan Information</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-2">Plan Name</th>
                                        <th class="px-4 py-2">Initial Deductible</th>
                                        <th class="px-4 py-2">Out-of-Pocket Max</th>
                                        <th class="px-4 py-2">Plan Premium</th>
                                        <th class="px-4 py-2">Plan Total Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white dark:bg-gray-700">
                                        <td class="px-4 py-3">{{ $tracker['planInfo']['planName'] }}</td>
                                        <td class="px-4 py-3">${{ $tracker['planInfo']['initialDeductible'] }}</td>
                                        <td class="px-4 py-3">${{ $tracker['planInfo']['outOfPocketMax'] }}</td>
                                        <td class="px-4 py-3">${{ $tracker['planInfo']['planPremium'] }}</td>
                                        <td class="px-4 py-3">${{ $tracker['planInfo']['planPremium'] * 12 + $tracker['totalOutOfPocket'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h4 class="text-xl font-semibold mt-6 mb-3 text-gray-700 dark:text-gray-200">Service Breakdown</h4>
                        @foreach($tracker['services'] as $serviceName => $usages)
                        <div class="mb-6">
                            <h5 class="text-lg font-medium mb-2 text-gray-600 dark:text-gray-300">Service Breakdown: {{ $serviceName }}</h5>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-800">
                                        <tr>
                                            <th class="px-4 py-2">Usage Count</th>
                                            <th class="px-4 py-2">Copay Amount</th>
                                            <th class="px-4 py-2">Coinsurance Rate</th>
                                            <th class="px-4 py-2">Cost</th>
                                            <th class="px-4 py-2">Network Tier</th>
                                            <th class="px-4 py-2">Running Total (Out-of-Pocket)</th>
                                            <th class="px-4 py-2">Remaining Deductible</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tracker['services'][$serviceName] as $usage)
                                        <tr class="bg-white dark:bg-gray-700">
                                            <td class="px-4 py-3">{{ $usage['usageCount'] }}</td>
                                            <td class="px-4 py-3">${{ $usage['copayAmount'] }}</td>
                                            <td class="px-4 py-3">{{ $usage['coinsuranceRate'] }}%</td>
                                            <td class="px-4 py-3">${{ $usage['cost'] }}</td>
                                            <td class="px-4 py-3">{{ $usage['networkTier'] }}</td>
                                            <td class="px-4 py-3">${{ $usage['currentOutOfPocket'] }}</td>
                                            <td class="px-4 py-3">${{ $usage['remainingDeductible'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach

                        <h4 class="text-xl font-semibold mt-6 mb-3 text-gray-700 dark:text-gray-200">Cost Summary</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-2">Service Type</th>
                                        <th class="px-4 py-2">Total Service Cost</th>
                                        <th class="px-4 py-2">Running Total (Out-of-Pocket)</th>
                                        <th class="px-4 py-2">Remaining Deductible</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tracker['services'] as $serviceName => $usages)
                                    @php
                                    $lastUsage = end($usages);
                                    $totalServiceCost = array_sum(array_column($usages, 'cost'));
                                    @endphp
                                    <tr class="bg-white dark:bg-gray-700">
                                        <td class="px-4 py-3">{{ $serviceName }}</td>
                                        <td class="px-4 py-3">${{ $totalServiceCost }}</td>
                                        <td class="px-4 py-3">${{ $lastUsage['currentOutOfPocket'] }}</td>
                                        <td class="px-4 py-3">${{ $lastUsage['remainingDeductible'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    @endforeach

                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    @livewire('pagination', ['totalResults' => $totalResults, 'limit' => 10, 'currentPage' => $currentPage])
    <span>Page {{ $currentPage }} of {{ $totalPages }}</span>



</div>
// cost-results-screen.blade.php