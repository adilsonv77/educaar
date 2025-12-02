<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Activity;

class ContentActivitiesOrder extends Component
{
    protected $listeners = ['updateOrder'];
    public $contentId;
    public $activities = [];

    public function mount($contentId){
        $this->contentId = $contentId;
        $this->activities = Activity::where('content_id', $contentId)->orderBy('position', 'asc')->get();
    }

    public function updateOrder($orderedIds){
        foreach($orderedIds as $index => $id){
            Activity::where('id', $id)->update(['position' =>$index + 1]);
        }

        $this->activities = Activity::where('content_id', $this->contentId)->orderBy('position', 'asc')->get();
        
    }

    public function render()
    {
        return view('livewire.content-activities-order');
    }
}
