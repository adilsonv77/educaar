<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AlunoTurma;
use App\DAO\SalaDAO;

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

    public function __construct(private salaDAO $salaDAO) {}

    public function createTempUser(int $salaId, int $turmaId): User {
        return DB::transaction(function() use ($salaId, $turmaId) {
            $name = $this->createUserName();
            $schoolId = $this->salaDAO->getSchoolIdBySala($salaId);
            $expiresAt = $this->salaDAO::getDeadline($salaId);

            $data = [
                'name' => $name,
                'username' => $name,
                'type' => 'student',
                'password' => 'teste',
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

    private function createUsername(): String {
        $substantivo = self::SUBSTANTIVOS[array_rand(self::SUBSTANTIVOS)];
        $adjetivo = self::ADJETIVOS[array_rand(self::ADJETIVOS)];
        $sufixo = random_int(10, 999);

        return "{$substantivo} {$adjetivo} {$sufixo}";
    }
}