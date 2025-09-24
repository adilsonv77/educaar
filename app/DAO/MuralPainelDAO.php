<?php

namespace App\DAO;

use App\Models\Mural;
use App\Models\MuralPainel;

class MuralPainelDAO
{
    public static function getByMuralId($id)
    {
        return MuralPainel::where('mural_id', $id)->get();
    }
}
?>