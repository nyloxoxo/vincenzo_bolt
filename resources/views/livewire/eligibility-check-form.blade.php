<div>
    <x-form-section submit="checkEligibility">
        <x-slot name="title">
            {{ __('What are you eligible for?') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Please provide your information to check your eligibility.') }}
        </x-slot>

        <x-slot name="form">

            @if (session()->has('error'))
            <div class="col-span-6 sm:col-span-4">
                <div class="mt-4 p-4 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            </div>
            @endif

            <!-- Full Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('Full Name') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" autocomplete="name" />
                <x-input-error for="state.name" class="mt-2" />
            </div>

            <!-- Date of Birth -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="dob" value="{{ __('Date of Birth') }}" />
                <x-input id="dob" type="date" class="mt-1 block w-1/2" wire:model="state.dob" />
                <x-input-error for="state.dob" class="mt-2" />
            </div>

            <!-- Street Address -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="street_address" value="{{ __('Street Address') }}" />
                <x-input id="street_address" type="text" class="mt-1 block w-full" wire:model="state.street_address" />
                <x-input-error for="state.street_address" class="mt-2" />
            </div>

            <!-- City -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="city" value="{{ __('City') }}" />
                <x-input id="city" type="text" class="mt-1 block w-full" wire:model="state.city" />
                <x-input-error for="state.city" class="mt-2" />
            </div>

            <!-- State -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="state" value="{{ __('State') }}" />
                <x-input id="state" type="text" class="mt-1 block w-1/2" wire:model="state.state" />
                <x-input-error for="state.state" class="mt-2" />
            </div>

            <!-- Zip Code -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="zipcode" value="{{ __('Zip Code') }}" />
                <x-input id="zipcode" type="text" class="mt-1 block w-1/2" wire:model="state.zipcode" />
                <x-input-error for="state.zipcode" class="mt-2" />
            </div>

            <!-- Income -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="income" value="{{ __('Income (USD)') }}" />
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                        $
                    </span>
                    <x-input id="income" type="number" class="flex-1 w-1/2 rounded-none rounded-r-md" wire:model="state.income" />
                </div>
                <x-input-error for="state.income" class="mt-2" />
            </div>

            <!-- Gender -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="gender" value="{{ __('Gender') }}" class="text-gray-700 dark:text-gray-300" />
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-indigo-600 dark:text-indigo-400" name="gender" wire:model="state.gender" value="Male">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Male') }}</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" class="form-radio text-indigo-600 dark:text-indigo-400" name="gender" wire:model="state.gender" value="Female">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Female') }}</span>
                    </label>
                </div>
                <x-input-error for="state.gender" class="mt-2" />
            </div>

            <!-- Tobacco Use -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="uses_tobacco" value="{{ __('Do you use tobacco?') }}" class="text-gray-700 dark:text-gray-300" />
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-indigo-600 dark:text-indigo-400" name="uses_tobacco" wire:model="state.uses_tobacco" value="1">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Yes') }}</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" class="form-radio text-indigo-600 dark:text-indigo-400" name="uses_tobacco" wire:model="state.uses_tobacco" value="0">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('No') }}</span>
                    </label>
                </div>
                <x-input-error for="state.uses_tobacco" class="mt-2" />
            </div>

            <!-- Coverage Year -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="coverage_year" value="{{ __('Year of Coverage') }}" />
                <select id="coverage_year" name="coverage_year" class="form-select mt-1 block w-full" wire:model="state.coverage_year">
                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                    <option value="{{ date('Y') + 1 }}">{{ date('Y') + 1 }}</option>
                </select>
                <x-input-error for="state.coverage_year" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-button>
                {{ __('Check Eligibility') }}
            </x-button>
        </x-slot>
    </x-form-section>

</div>