<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Content;
use App\DAO\ActivityDAO;
use Illuminate\Support\Facades\DB;
use App\Models\RandomSort;

class HintButton extends Component {
    public string $hint = '';

    protected $listeners = [
        'showHint' => 'showHint',
        'getHint' => 'getHint',
        'updateHint' => 'updateHint',
    ];

    public function mount($activities, int $nextPosition) {
        $contentId = $activities[1]->content_id;
        $contentType = DB::table('contents')
            ->where('id', $contentId)
            ->value('sort_activities');

        if($contentType === 2) {
            $activities = $this->syncOrdem($activities, $contentId);
        }

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

    private function syncOrdem($activities, int $contentId) {
        $randomSort = RandomSort::where('content_id', $contentId)->first();
        $sortOrder = array_map('trim', explode(',', $randomSort->sort));

        $activitiesByIndex = [];
        $i = 1;
        foreach ($activities as $activity) {
            $activitiesByIndex[$i] = $activity;
            $i++;
        }

        $sorted = [];
        foreach ($sortOrder as $index) {
            if (isset($activitiesByIndex[$index])) {
                $sorted[] = $activitiesByIndex[$index];
            }
        }

        return $sorted;
    }
}
