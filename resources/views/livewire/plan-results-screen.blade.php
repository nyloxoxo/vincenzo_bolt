// plan-results-screen.blade.php

<div class="p-6 dark:bg-gray-800 dark:text-gray-200">

    @if ($errorCode)
    <h2 class="text-2xl font-semibold mb-4 dark:text-gray-100">
        Something went wrong
    </h2>
    <p>{{ $errorMessage }}</p>
    <p>Error: {{ $errorCode }}</p>
    @else
    <h2 class="text-2xl font-semibold mb-4 dark:text-gray-100">Your Healthcare Cost Estimate</h2>

    <div class="mb-6">
        <h3 class="text-xl font-semibold mb-2 dark:text-gray-100">Available Plans</h3>
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
                @foreach($data['plans'] ?? [] as $plan)
                <tr class="border-b dark:border-gray-600">
                    <td class="p-2">{{ $plan['name'] }}</td>
                    <td class="p-2">{{ $plan['type'] }}</td>
                    <td class="text-right p-2">${{ number_format($plan['premium'], 2) }}</td>
                    <td class="text-right p-2">${{ number_format($plan['deductibles'][0]['amount'], 2) }}</td>
                    <td class="text-right p-2">${{ number_format($plan['moops'][0]['amount'], 2) }}</td>
                    <td class="text-center p-2">
                        <span class="px-2 py-1 rounded {{ getMetalLevelClass($plan['metal_level']) }}">
                            {{ $plan['metal_level'] }}
                        </span>
                    </td>
                    <td class="p-2">
                        <button wire:click="togglePlanDetails('{{ $plan['id'] }}')" class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                            {{ $expandedPlanId === $plan['id'] ? 'Collapse Details' : 'Expand Details' }}
                        </button>
                    </td>
                </tr>
                @if($expandedPlanId === $plan['id'])
                <tr>
                    <td colspan="7" class="p-4 bg-gray-100 dark:bg-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-3 dark:text-gray-200">Plan Details:</h4>
                                <div class="space-y-2 text-sm dark:text-gray-300">
                                    <p><span class="font-medium">ID:</span> {{ $plan['id'] }}</p>
                                    <p><span class="font-medium">Issuer:</span> {{ $plan['issuer']['name'] }}</p>
                                    <p><span class="font-medium">Eligible Dependents:</span> {{ implode(', ', $plan['issuer']['eligible_dependents']) }}</p>
                                    <p class="font-medium">Plan URLs:</p>
                                    <ul class="list-disc list-inside ml-4 space-y-1">
                                        <li><span class="font-medium">Benefits:</span> <a href="{{ $plan['benefits_url'] }}" class="text-blue-600 dark:text-blue-400 hover:underline" target="_blank">Click Here</a></li>
                                        <li><span class="font-medium">Brochure:</span> <a href="{{ $plan['brochure_url'] }}" class="text-blue-600 dark:text-blue-400 hover:underline" target="_blank">Click Here</a></li>
                                        <li><span class="font-medium">Formulary:</span> <a href="{{ $plan['formulary_url'] }}" class="text-blue-600 dark:text-blue-400 hover:underline" target="_blank">Click Here</a></li>
                                        <li><span class="font-medium">Network:</span> <a href="{{ $plan['network_url'] }}" class="text-blue-600 dark:text-blue-400 hover:underline" target="_blank">Click Here</a></li>
                                    </ul>
                                    <p><span class="font-medium">Phone #:</span> {{ $plan['issuer']['toll_free'] }}</p>
                                    <p><span class="font-medium">Network:</span> {{ $plan['has_national_network'] ? 'National' : 'Local' }}</p>
                                    <p><span class="font-medium">State:</span> {{ $plan['state'] }}</p>
                                    <p><span class="font-medium">Max Out of Pocket (Total):</span> ${{ number_format($plan['moops'][0]['amount'], 2) }}</p>
                                    <p><span class="font-medium">Medical EHB Deductible:</span> ${{ number_format($plan['deductibles'][0]['amount'], 2) }}</p>
                                    <p><span class="font-medium">Drug EHB Deductible:</span> ${{ number_format($plan['deductibles'][1]['amount'] ?? 0, 2) }}</p>
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-3 dark:text-gray-200">Plan Benefits:</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="bg-gray-100 dark:bg-gray-600">
                                                <th class="text-left p-2 dark:text-gray-200">Benefit Type</th>
                                                <th class="text-left p-2 dark:text-gray-200">Coverage</th>
                                                <th class="text-left p-2 dark:text-gray-200">In-Network Cost</th>
                                                <th class="text-left p-2 dark:text-gray-200">Out-of-Network Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody class="dark:text-gray-300">
                                            @foreach($plan['benefits'] as $benefit)
                                            <tr class="border-b dark:border-gray-600">
                                                <td class="p-2">{{ $benefit['name'] }}</td>
                                                <td class="p-2">{{ $benefit['covered'] ? '✓' : '✗' }}</td>
                                                <td class="p-2">{{ $benefit['cost_sharings'][0]['display_string'] }}</td>
                                                <td class="p-2">{{ $benefit['cost_sharings'][1]['display_string'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        <h3 class="text-xl font-semibold mb-2 dark:text-gray-100">Next Steps</h3>
        <p>Based on your eligibility and usage, here are some recommended next steps:</p>
        <ul class="list-disc list-inside dark:text-gray-300">
            <li>Consider a health plan with lower copayments for specialist visits</li>
            <li>Look into prescription drug coverage options</li>
            <li>Explore preventive care benefits to potentially reduce emergency visits</li>
        </ul>
    </div>
    //@include('livewire.pagination', ['totalResults' => count($data['plans']), 'limit' => 10])
    @livewire('pagination', ['totalResults' => $totalResults, 'limit' => 10])
    @endif
</div>

@php
function getMetalLevelClass($metalLevel) {
switch (strtolower($metalLevel)) {
case 'bronze':
return 'bg-yellow-700 text-white';
case 'silver':
return 'bg-gray-400 text-gray-800';
case 'gold':
return 'bg-yellow-500 text-gray-800';
case 'platinum':
return 'bg-gray-300 text-gray-800';
default:
return 'bg-gray-200 text-gray-800';
}
}
@endphp

<span>Page {{ $currentPage }} of {{ $totalPages }}</span>
// plan-results-screen.blade.php