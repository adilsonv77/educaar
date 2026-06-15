<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\DAO\ActivityDAO;

class HintButton extends Component {
    public string $hint = '';

    protected $listeners = [
        'showHint' => 'showHint',
        'getHint' => 'getHint',
        'updateHint' => 'updateHint',
    ];

    public function mount($activities, int $nextPosition) {
        $this->hint = $activities[$nextPosition-1]->hint ?? '';
    }

    public function render()
    {
        return view('livewire.hint-button');
    }

    public function getHint(int $contentId, int $activityId) {
        $this->hint = ActivityDAO::getHint($contentId, $activityId);
        $this->showHint();
    }

    public function updateHint(string $hint) {
        if($hint === '') { return; }
        $this->hint = $hint;
    }

    public function showHint() {
        $this->dispatchBrowserEvent('show-hint-modal');
    }
}
