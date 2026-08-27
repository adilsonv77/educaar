<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\AlunoTurma;
use App\DAO\SalaDAO;
use App\Exceptions\ManyTempUsersException;

class UserService {
    private const SUBSTANTIVOS = [
        'Explorador',
        'Cientista',
        'Aprendiz',
        'Pesquisador',
        'Pensador',
        'Inventor',
        'Navegante',
        'Estrategista',
        'Desbravador',
        'Visionário',
    ];

    private const ADJETIVOS = [
        'Curioso',
        'Estudioso',
        'Criativo',
        'Ágil',
        'Sábio',
        'Atento',
        'Dedicado',
        'Brilhante',
        'Persistente',
        'Inventivo',
    ];

    /**
     * Constantes que definem limites de cadastro GLOBAIS do sistema,
     * não somente da sala ou jogo.
     * 
     * Uso em checkThrottling(): Define o limite de cadastros (TEMP_USER_LIMIT)
     * pelo tempo definido (TIME_LIMIT). Logo, se tiver mais de TEMP_USER_LIMIT
     * cadastros em TIME_LIMIT o sistema trava o cadastro.
    */
    private const TEMP_USER_LIMIT = 100;
    private const TIME_LIMIT = 5;

    public function createTempUser(int $salaId, int $turmaId): User {
        return DB::transaction(function() use ($salaId, $turmaId) {
            $name = $this->createUsername();
            $schoolId = salaDAO::getSchoolIdBySala($salaId);
            $expiresAt = salaDAO::getDeadline($salaId);

            $data = [
                'name' => $name,
                'username' => $name,
                'type' => 'student',
                'password' => Hash::make(Str::random(20)),
                'school_id' => $schoolId,
                'expires_at' => $expiresAt
            ];

            $user = User::create($data);
            AlunoTurma::create([
                'turma_id' => $turmaId,
                'aluno_id' => $user->id
            ]);

            return $user;
        });

    }

    private function createUsername(): string {
        do {
            $substantivo = self::SUBSTANTIVOS[array_rand(self::SUBSTANTIVOS)];
            $adjetivo = self::ADJETIVOS[array_rand(self::ADJETIVOS)];
            $sufixo = random_int(10, 999);

            $username = "{$substantivo} {$adjetivo} {$sufixo}";
        } while (User::where('username', $username)->exists());

        return $username;
    }

    public function checkThrottling(): void {
        $recentCount = User::where('created_at', '>=', now()->subMinutes(self::TIME_LIMIT))
                                ->whereNotNull('expires_at')
                                ->count();

        if ($recentCount > self::TEMP_USER_LIMIT) { 
            throw new ManyTempUsersException($recentCount, self::TEMP_USER_LIMIT, self::TIME_LIMIT);
        }
    }
}