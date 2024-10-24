<?php
// Pagination.php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Pagination extends Component
{
    public $currentPage = 1;
    public $totalPages;

    public function mount($totalResults, $limit)
    {
        $this->totalPages = ceil($totalResults / $limit);
        Log::info('Mount: Total Pages', ['totalPages' => $this->totalPages]);
    }

    public function nextPage()
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
            $this->emit('pageChanged', $this->currentPage);
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->emit('pageChanged', $this->currentPage);
        }
    }

    public function render()
    {
        Log::info('Render: Current Page', ['currentPage' => $this->currentPage]);
        return view('livewire.pagination', [
            'currentPage' => $this->currentPage,
            'totalPages' => $this->totalPages,
        ]);
    }
}
// Pagination.php
