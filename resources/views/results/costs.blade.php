<x-app-layout>
    <x-wrapper maxWidth='6xl'>
        <x-header>Costs</x-header>

        <x-tabs>
            <x-tab id="plans" label="Plans" :href="route('results.plans')" />
            <x-tab id="costs" label="Costs" href="#" :active="true" />
        </x-tabs>

        @livewire('cost-results-screen') <!-- Reset Button -->

        <div class="flex items-center justify-center py-4">
            <x-start-again-button />
        </div>

    </x-wrapper>
</x-app-layout>