<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCode\Writer\WriterInterface;

class QrCodeService {
    public function generate(string $url, int $id, int $size = 300, WriterInterface $writer = new PngWriter()) {
        return new Builder(
            writer: $writer,
            data: sprintf("%s%d", $url, $id),
            size: $size,
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            logoPunchoutBackground: true,
            logoPath: public_path('/images/Logo Mini.PNG'),
            logoResizeToHeight: 70,
            logoResizeToWidth: 70
        );
    }
}