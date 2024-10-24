@props(['submit'])

<div {{ $attributes }}>
    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit="{{ $submit }}">
            <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow {{ isset($actions) ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md' }}">
                <div class="grid gap-6">

                    <div class="col-span-6">
                        <x-section-title>

                            <x-slot name="title">
                                {{ $title }}

                            </x-slot>
                            <x-slot name="description">
                                {{ $description }}
                            </x-slot>

                        </x-section-title>

                    </div>

                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                {{ $actions }}
            </div>
            @endif
        </form>
    </div>
</div>