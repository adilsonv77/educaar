<?php

namespace App\Services;

use App\DAO\StudentAppDAO;

class ActivityService {
    /* Dei um ctrl+c ctrl+v no código anterior, não analisei como o sistema se comporta atualmente */
    public function processToAr(array $activities, int $userId, int $anoId, bool $isJogo): void {
        $bloquearPorData = 0;
        $dataCorte = null;

        foreach ($activities as $activity) {
            if ($dataCorte == null) {
                $dataCorte = StudentAppDAO::buscarDataCorte($activity->id, $userId, $anoId)->first();

                if ($dataCorte != null) {
                    $diff = date_diff(now(), new \DateTime($dataCorte->dt_corte));

                    if ($diff->format("%R%a") <= 0) {
                        $bloquearPorData = 1;
                    }
                }
            }

            $activity->bloquearPorData = $bloquearPorData;
            if ($bloquearPorData == 0) {
                $respondida = StudentAppDAO::verificaAtividadeRespondida($activity->id, $userId, 
                    $isJogo);

                $activity->respondido = $respondida ? 1 : 0;
            }
        }
    }
}