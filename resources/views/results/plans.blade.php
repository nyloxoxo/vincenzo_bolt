<x-app-layout>
    <x-wrapper maxWidth='6xl'>
        <x-header>Plans</x-header>

        <x-tabs>
            <x-tab id="plans" label="Plans" href="#" :active="true" />
            <x-tab id="costs" label="Costs" :href="route('results.costs')" />
        </x-tabs>

        @livewire('plan-results-screen')

        <div class="flex items-center justify-center py-4">
            <x-start-again-button />
        </div>

    </x-wrapper>
</x-app-layout>