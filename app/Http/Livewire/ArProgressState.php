<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ArProgressState extends Component
{
    // Esta é a variável que será observada
    public $nextPosition = 1; 
    public $contentId;
    
    
    protected $listeners = ['progressChanged' => 'updatePosition']; 
    public function mount($nextPosition, $contentId)
    {
        $this->nextPosition = $nextPosition;
        $this->contentId = $contentId;
    }

    public function updatePosition($newPosition)
    {
        $this->nextPosition = $newPosition;

        $this->dispatchBrowserEvent('progressUpdated', [
            'position' => $this->nextPosition,
            'contentId' => $this->contentId
        ]);
    }
    
    

    public function render()
    {
        return view('livewire.ar-progress-state');
    }
}