<?php

namespace App\Utils;

class GerarImagem
{
    public $chave;

    public function __construct($chave) {
        if (strlen($chave) == 2) {
            $chave = substr($chave, 0, 1) . "0" . substr($chave, 1, 1);
        }
        $this->chave = $chave;
    }

    public function gerar() {

        $font =  $_SERVER['DOCUMENT_ROOT'] .'/fonts/arial-black.ttf';
    
        // imagem para escrever a chave
        $img205 = imagecreate(205, 205);
        $black205 = imagecolorallocate($img205, 0, 0, 0);
        $white205 = imagecolorallocate($img205, 255, 255, 255);
        imagefill($img205, 0, 0, $white205);
        imagettftext($img205, 70, 0, 0, 150, $black205, $font, $this->chave);

        // imagem final
        $img = imagecreate(512, 512);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagefill($img, 0, 0, $white);
        imagesetthickness($img, 100);
        imagerectangle($img, 101, 101, 410, 410, $black);
        imagecopyresampled($img, $img205, 155, 152, 0, 0, 205, 205, 205, 205);
        
        imagepng($img, $_SERVER['DOCUMENT_ROOT'] . '/markers/'.$this->chave.'.png');
        imagepng($img205, $_SERVER['DOCUMENT_ROOT'] . '/markers/'.$this->chave.'_205.png');

        imagedestroy($img205);
        imagedestroy($img);
        
        return 'markers/'.$this->chave.'.png';
    }
} 