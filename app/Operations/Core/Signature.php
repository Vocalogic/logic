<?php


namespace App\Operations\Core;

use Exception;
use GdImage;

/**
 * Signature Pad
 */
class Signature
{
    const WIDTH  = 380;
    const HEIGHT = 100;
    const JS     = "$('.sigPad').signaturePad({drawOnly:true});
                $.fn.signaturePad.clear = '.clearButton';";

    const DISPLAY_WIDTH = 250;

    /**
     * Creates the actual lines based on the JSON Data in the db.
     *
     * @param $img
     * @param $startX
     * @param $startY
     * @param $endX
     * @param $endY
     * @param $colour
     * @param $thickness
     */
    static private function drawThickLine($img, $startX, $startY, $endX, $endY, $colour, $thickness)
    {
        $angle = (atan2(($startY - $endY), ($endX - $startX)));
        $dist_x = $thickness * (sin($angle));
        $dist_y = $thickness * (cos($angle));
        $p1x = ceil(($startX + $dist_x));
        $p1y = ceil(($startY + $dist_y));
        $p2x = ceil(($endX + $dist_x));
        $p2y = ceil(($endY + $dist_y));
        $p3x = ceil(($endX - $dist_x));
        $p3y = ceil(($endY - $dist_y));
        $p4x = ceil(($startX - $dist_x));
        $p4y = ceil(($startY - $dist_y));
        $array = [0 => $p1x, $p1y, $p2x, $p2y, $p3x, $p3y, $p4x, $p4y];
        imagefilledpolygon($img, $array, (count($array) / 2), $colour);
    }

    /**
     * Build the Binary Data for the Image
     *
     * @param       $json
     * @param array $options
     * @return GdImage
     * @throws Exception
     */
    static public function sigJsonToImage($json, $options = [])
    {
        $defaultOptions = [
            'imageSize'      => [self::WIDTH, self::HEIGHT]
            ,
            'bgColour'       => [0xff, 0xff, 0xff]
            ,
            'penWidth'       => 2
            ,
            'penColour'      => [0x14, 0x53, 0x94]
            ,
            'drawMultiplier' => 12
        ];

        $options = array_merge($defaultOptions, $options);
        $img = imagecreatetruecolor($options['imageSize'][0] * $options['drawMultiplier'],
            $options['imageSize'][1] * $options['drawMultiplier']);
        if ($options['bgColour'] == 'transparent')
        {
            imagesavealpha($img, true);
            $bg = imagecolorallocatealpha($img, 0, 0, 0, 127);
        }
        else
        {
            $bg = imagecolorallocate($img, $options['bgColour'][0], $options['bgColour'][1], $options['bgColour'][2]);
        }
        $pen = imagecolorallocate($img, $options['penColour'][0], $options['penColour'][1], $options['penColour'][2]);
        imagefill($img, 0, 0, $bg);
        if (is_string($json))
        {
            $json = json_decode(stripslashes($json));
        }
        if (empty($json)) throw new Exception("Unable to render signature.");
        foreach ($json as $v)
        {
            self::drawThickLine($img, $v->lx * $options['drawMultiplier'], $v->ly * $options['drawMultiplier'],
                $v->mx * $options['drawMultiplier'], $v->my * $options['drawMultiplier'], $pen,
                $options['penWidth'] * ($options['drawMultiplier'] / 2));
        }
        $imgDest = imagecreatetruecolor($options['imageSize'][0], $options['imageSize'][1]);
        if ($options['bgColour'] == 'transparent')
        {
            imagealphablending($imgDest, false);
            imagesavealpha($imgDest, true);
        }
        imagecopyresampled($imgDest, $img, 0, 0, 0, 0, $options['imageSize'][0], $options['imageSize'][0],
            $options['imageSize'][0] * $options['drawMultiplier'],
            $options['imageSize'][0] * $options['drawMultiplier']);
        imagedestroy($img);
        return $imgDest;
    }

    /**
     * Return the html to render the signature.
     *
     * @param string $signature JSON Data
     * @param bool   $encoded
     * @return string
     * @throws Exception
     */
    static public function renderImage(string $signature, bool $encoded = false): string
    {
        if (!$signature) return '';
        $img = self::sigJsonToImage($signature,
            [
                'imageSize' => [self::WIDTH, self::HEIGHT],
                'bgColour'  => 'transparent'
            ]
        );
        $tmp = md5(time() . rand(0, 4921)) . ".png";
        if ($encoded)
        {
            imagepng($img, "/tmp/$tmp");
            $b64 = base64_encode(file_get_contents("/tmp/$tmp"));
            @unlink("/tmp/$tmp");
            return $b64;
        }
        ob_start();
        imagepng($img);
        imagedestroy($img);
        $img = base64_encode(ob_get_clean());
        return "<img style='width: ".self::DISPLAY_WIDTH."px' src='data:image/png;base64," . $img . "' />";
    }



}
