<div>
    <x-form-section submit="calculateCosts">
        <x-slot name="title">
            {{ __('Estimate Your Healthcare Costs') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Please provide your usage information to estimate your healthcare costs.') }}
        </x-slot>

        <x-slot name="form">
            <div class="col-span-3">
                <h3 class="text-xl font-bold mb-2 mt-6 dark:text-white">Usage Scenario: Routine Care</h3>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400"><i>Specify if the services are in-network or out-of-network, and enter the number of times for each in a year. The cost figures are based on national averages; please edit as needed for your location:</i></p>
            </div>

            @foreach ($services as $serviceName => $service)
            <div class="col-span-6 sm:col-span-6">
                <x-label for="{{ $serviceName }}" value="{{ __(ucfirst(str_replace('_', ' ', $serviceName))) }}" />
                <div class="flex items-center space-x-4 mb-2">
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model.live="services.{{ $serviceName }}.network_tier" value="In-Network" class="form-radio dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 dark:text-gray-300">{{ __('In-Network') }}</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model.live="services.{{ $serviceName }}.network_tier" value="Out-of-Network" class="form-radio dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 dark:text-gray-300">{{ __('Out-of-Network') }}</span>
                    </label>
                </div>
                <x-input id="{{ $serviceName }}_count" type="number" class="mt-1 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" wire:model.live="services.{{ $serviceName }}.count" />
                @if($services[$serviceName]['network_tier'] === 'Out-of-Network')
                <div class="mt-2">
                    <div class="flex items-center border rounded-md dark:border-gray-600">
                        <span class="px-3 text-gray-700 dark:text-gray-300 border-r border-gray-300 dark:border-gray-600">$</span>
                        <x-input type="number" class="flex-1 block w-full rounded-none rounded-r-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" wire:model.live="services.{{ $serviceName }}.price" />
                    </div>
                </div>
                @endif
                <x-input-error for="services.{{ $serviceName }}.count" class="mt-2" />
                <x-input-error for="services.{{ $serviceName }}.price" class="mt-2" />
            </div>
            @endforeach

            <div class="col-span-3">
                <h3 class="text-xl font-bold mb-2 mt-6 dark:text-white">Additional Services</h3>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400"><i>Specify the number of times for each service in a year. The cost figures are based on national averages; please edit as needed for your location:</i></p>
            </div>

            @foreach ($additionalServices as $serviceName => $service)
            <div class="col-span-6 sm:col-span-4 mb-6">
                <x-label for="{{ $serviceName }}" value="{{ __(ucfirst(str_replace('_', ' ', $serviceName))) }}" />
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 w-1/3">
                        <x-input id="{{ $serviceName }}_count" type="number" class="mt-1 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" wire:model="additionalServices.{{ $serviceName }}.count" />
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center border rounded-md dark:border-gray-600">
                            <span class="px-3 text-gray-700 dark:text-gray-300 border-r border-gray-300 dark:border-gray-600">$</span>
                            <x-input type="number" class="fex-1 block w-full rounded-none rounded-r-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" wire:model="additionalServices.{{ $serviceName }}.price" />
                        </div>
                    </div>
                </div>
                <x-input-error for="additionalServices.{{ $serviceName }}.count" class="mt-2" />
            </div>
            @endforeach
        </x-slot>

        <x-slot name="actions">
            <x-button>
                {{ __('Calculate Costs') }}
            </x-button>
        </x-slot>
    </x-form-section>

</div>