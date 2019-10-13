<?php

function readInt($file)
{
    $b4 = ord(fgetc($file));
    $b3 = ord(fgetc($file));
    $b2 = ord(fgetc($file));
    $b1 = ord(fgetc($file));

    return ($b1 << 24) | ($b2 << 16) | ($b3 << 8) | $b4;
}

function readShort($file)
{
    $b2 = ord(fgetc($file));
    $b1 = ord(fgetc($file));

    return ($b1 << 8) | $b2;
}

function emptyCrest()
{
    return '';
}

function clanAllyCrest($type, $id, $gsId, $crest)
{
    $cacheTime = 3600;
    $path = Yii::getPathOfAlias('webroot.uploads.images.crest') . DIRECTORY_SEPARATOR . $gsId . DIRECTORY_SEPARATOR . $type;
    $filePath = $id . '.png';

    if (!is_dir($path)) {
        if (!mkdir($path, 0777, true)) {
            return '';
        }
    }

    if (!is_file($path . DIRECTORY_SEPARATOR . $filePath) || (time() - filemtime($path . DIRECTORY_SEPARATOR . $filePath) >= $cacheTime)) {
        // Генерю файл
        $rnd_file = tmpfile();
        fwrite($rnd_file, $crest);
        fseek($rnd_file, 0);

        $file = &$rnd_file; //fopen($filename,'rb');
        $dds = fread($file, 4);

        if ($dds !== 'DDS ') {
            return emptyCrest();
        }

        $hdrSize = readInt($file);
        $hdrFlags = readInt($file);
        $imgHeight = readInt($file) - 4;
        $imgWidth = readInt($file);
        $imgPitch = readShort($file);

        fseek($file, 84);

        $dxt1 = fread($file, 4);

        if ($dxt1 !== 'DXT1') {
            return emptyCrest();
        }

        fseek($file, 128);

        //header ("Content-type: image/png");
        $img = imagecreatetruecolor($imgWidth, $imgHeight);

        for ($y = -1; $y < $imgHeight / 4; $y++) {
            for ($x = 0; $x < $imgWidth / 4; $x++) {
                $color0_16 = readShort($file);
                $color1_16 = readShort($file);
                $r0 = ($color0_16 >> 11) << 3;
                $g0 = (($color0_16 >> 5) & 63) << 2;
                $b0 = ($color0_16 & 31) << 3;
                $r1 = ($color1_16 >> 11) << 3;
                $g1 = (($color1_16 >> 5) & 63) << 2;
                $b1 = ($color1_16 & 31) << 3;
                $color0_32 = imagecolorallocate($img, $r0, $g0, $b0);
                $color1_32 = imagecolorallocate($img, $r1, $g1, $b1);
                $color01_32 = imagecolorallocate($img, $r0 / 2 + $r1 / 2, $g0 / 2 + $g1 / 2, $b0 / 2 + $b1 / 2);
                $black = imagecolorallocate($img, 0, 0, 0);
                $data = readInt($file);
                for ($yy = 0; $yy < 4; $yy++) {
                    for ($xx = 0; $xx < 4; $xx++) {
                        $bb = $data & 3;
                        $data = $data >> 2;
                        switch ($bb) {
                            case 0:
                                $c = $color0_32;
                                break;
                            case 1:
                                $c = $color1_32;
                                break;
                            case 2:
                                $c = $color01_32;
                                break;
                            default:
                                $c = $black;
                                break;
                        }
                        imagesetpixel($img, $x * 4 + $xx, $y * 4 + $yy, $c);
                    }
                }
            }
        }

        imagepng($img, $path . DIRECTORY_SEPARATOR . $filePath);
        imagedestroy($img);
    }

    return '<img src="' . app()->getBaseUrl(true) . '/uploads/images/crest/' . $gsId . '/' . $type . '/' . $filePath . '">';
}