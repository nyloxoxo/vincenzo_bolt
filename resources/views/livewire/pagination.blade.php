// pagination.blade.php
<div class="flex justify-between items-center">
    <button wire:click="previousPage" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
        Previous
    </button>
    <span>Page {{ $currentPage }} of {{ $totalPages }}</span>
    <button wire:click="nextPage" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
        Next
    </button>
</div>
@livewire('pagination', ['totalResults' => $totalResults, 'limit' => 10])
// pagination.blade.php