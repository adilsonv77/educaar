<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class ManyTempUsersException extends Exception {
    public function __construct(
        private readonly int $recentCount,
        private readonly int $limit,
        private readonly int $timeLimit,
    ) {}

    public function report() {
        Log::warning('Possível abuso de criação de utilizadores temporários', [
            'recent_count' => $this->recentCount,
            'limit' => $this->limit,
            'time_limit' => $this->timeLimit
        ]);
    }
}
