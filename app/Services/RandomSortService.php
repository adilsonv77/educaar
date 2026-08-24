<?php

namespace App\Services;

use App\Models\RandomSort;
use App\Models\Activity;

class RandomSortService {
    public function createRandomSort(int $contentId, int $userId): void {
         $activities = Activity::where('content_id', $contentId)->count();

        if($activities <= 1) {
            return;
        }

        $oldSort = count(explode(',', RandomSort::where('content_id', $contentId)->where('user_id', $userId)->value('sort')));

        $sort = range(1, $activities);
        shuffle($sort);
        
        if ($oldSort == $activities) {
            RandomSort::firstOrCreate([
                'user_id' => $userId,
                'content_id' => $contentId,
            ],[
                'sort' => implode(',', $sort)
            ]);
        } else {
            RandomSort::updateOrInsert([
                'user_id' => $userId,
                'content_id' => $contentId,
            ], [
                'sort' => implode(',', $sort)
            ]);
        }
    }
}