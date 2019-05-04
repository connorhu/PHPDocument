<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Shared;

/**
 * Common converter functions
 */
class Converter
{
    const UNIT_TWIP = 'twip';
    const UNIT_PICA = 'pc';
    const UNIT_CM = 'cm';
    const UNIT_MM = 'mm';
    const UNIT_INCH = 'in';
    const UNIT_PIXEL = 'px';
    const UNIT_POINT = 'pt';
    const UNIT_EMU = 'emu'; // English Metric Unit

    const INCH_TO_CM = 2.54;
    const INCH_TO_TWIP = 1440;
    const INCH_TO_PIXEL = 96;
    const INCH_TO_POINT = 72;
    const INCH_TO_PICA = 6;
    const PIXEL_TO_EMU = 9525;
    const DEGREE_TO_ANGLE = 60000;

    /**
     * Convert centimeter to twip
     *
     * @param float $centimeter
     * @return float
     */
    public static function cmToTwip($centimeter = 1)
    {
        return $centimeter / self::INCH_TO_CM * self::INCH_TO_TWIP;
    }

    /**
     * Convert centimeter to inch
     *
     * @param float $centimeter
     * @return float
     */
    public static function cmToInch($centimeter = 1)
    {
        return $centimeter / self::INCH_TO_CM;
    }

    public static function cmToIn($centimeter = 1)
    {
        return self::cmToInch($centimeter);
    }

    /**
     * Convert centimeter to pixel
     *
     * @param float $centimeter
     * @return float
     */
    public static function cmToPixel($centimeter = 1)
    {
        return $centimeter / self::INCH_TO_CM * self::INCH_TO_PIXEL;
    }

    public static function cmToPx($centimeter = 1)
    {
        return self::cmToPx($centimeter);
    }

    /**
     * Convert centimeter to point
     *
     * @param float $centimeter
     * @return float
     */
    public static function cmToPoint($centimeter = 1)
    {
        return $centimeter / self::INCH_TO_CM * self::INCH_TO_POINT;
    }

    public static function cmToPt($centimeter = 1)
    {
        return self::cmToPoint($centimeter);
    }

    /**
     * Convert centimeter to EMU
     *
     * @param float $centimeter
     * @return float
     */
    public static function cmToEmu($centimeter = 1)
    {
        return round($centimeter / self::INCH_TO_CM * self::INCH_TO_PIXEL * self::PIXEL_TO_EMU);
    }

    /**
     * Convert twip to inch
     *
     * @param float $twip
     * @return float
     */
    public static function twipToInch($twip = 1)
    {
        return $twip / self::INCH_TO_TWIP;
    }

    public static function twipToIn($twip = 1)
    {
        return self::twipToInch($twip);
    }

    /**
     * Convert twip to point unit
     *
     * @param float $twip
     * @return float
     */
    public static function twipToPoint($twip = 1)
    {
        return ($twip / self::INCH_TO_TWIP) * self::INCH_TO_POINT;
    }

    public static function twipToPt($twip = 1)
    {
        return self::twipToPoint($twip);
    }

    /**
     * Convert inch to twip
     *
     * @param float $inch
     * @return float
     */
    public static function inchToTwip($inch = 1)
    {
        return $inch * self::INCH_TO_TWIP;
    }

    public static function inToTwip($inch = 1)
    {
        return self::inchToTwip($inch);
    }

    /**
     * Convert inch to centimeter
     *
     * @param float $inch
     * @return float
     */
    public static function inchToCm($inch = 1)
    {
        return $inch * self::INCH_TO_CM;
    }

    public static function inToCm($inch = 1)
    {
        return self::inchToCm($inch);
    }

    /**
     * Convert inch to pixel
     *
     * @param float $inch
     * @return float
     */
    public static function inchToPixel($inch = 1)
    {
        return $inch * self::INCH_TO_PIXEL;
    }

    public static function inToPx($inch = 1)
    {
        return self::inchToPixel($inch);
    }

    /**
     * Convert inch to point
     *
     * @param float $inch
     * @return float
     */
    public static function inchToPoint($inch = 1)
    {
        return $inch * self::INCH_TO_POINT;
    }

    public static function inToPt($inch = 1)
    {
        return self::inchToPoint($inch);
    }

    /**
     * Convert inch to EMU
     *
     * @param float $inch
     * @return float
     */
    public static function inchToEmu($inch = 1)
    {
        return round($inch * self::INCH_TO_PIXEL * self::PIXEL_TO_EMU);
    }

    public static function inToEmu($inch = 1)
    {
        return self::inToEmu($inch);
    }

    /**
     * Convert pixel to twip
     *
     * @param int $pixel
     * @return float
     */
    public static function pixelToTwip($pixel = 1)
    {
        return $pixel / self::INCH_TO_PIXEL * self::INCH_TO_TWIP;
    }

    public static function pxToTwip($pixel = 1)
    {
        return self::pixelToTwip($pixel);
    }

    /**
     * Convert pixel to centimeter
     *
     * @param int $pixel
     * @return float
     */
    public static function pixelToCm($pixel = 1)
    {
        return $pixel / self::INCH_TO_PIXEL * self::INCH_TO_CM;
    }

    public static function pxToCm($pixel = 1)
    {
        return self::pxToCm($pixel);
    }

    /**
     * Convert pixel to point
     *
     * @param int $pixel
     * @return float
     */
    public static function pixelToPoint($pixel = 1)
    {
        return $pixel / self::INCH_TO_PIXEL * self::INCH_TO_POINT;
    }

    /**
     * Convert pixel to EMU
     *
     * @param int $pixel
     * @return int
     */
    public static function pixelToEmu($pixel = 1)
    {
        return round($pixel * self::PIXEL_TO_EMU);
    }

    public static function pxToEmu($pixel = 1)
    {
        return self::pixelToEmu($pixel);
    }

    /**
     * Convert point to twip unit
     *
     * @param int $point
     * @return float
     */
    public static function pointToTwip($point = 1)
    {
        return $point / self::INCH_TO_POINT * self::INCH_TO_TWIP;
    }

    public static function ptToTwip($point = 1)
    {
        return self::pointToTwip($point);
    }

    /**
     * Convert point to pixel
     *
     * @param float $point
     * @return float
     */
    public static function pointToPixel($point = 1)
    {
        return $point / self::INCH_TO_POINT * self::INCH_TO_PIXEL;
    }

    public static function ptToPx($point = 1)
    {
        return self::pointToPixel($point);
    }

    /**
     * Convert point to EMU
     *
     * @param int $point
     * @return float
     */
    public static function pointToEmu($point = 1)
    {
        return round($point / self::INCH_TO_POINT * self::INCH_TO_PIXEL * self::PIXEL_TO_EMU);
    }

    public static function ptToEmu($point = 1)
    {
        return self::pointToEmu($point);
    }

    /**
     * Convert point to cm
     *
     * @param float $point
     * @return float
     */
    public static function pointToCm($point = 1)
    {
        return $point / self::INCH_TO_POINT * self::INCH_TO_CM;
    }

    public static function ptToCm($point = 1)
    {
        return self::pointToCm($point);
    }

    /**
     * Convert EMU to pixel
     *
     * @param int $emu
     * @return float
     */
    public static function emuToPixel($emu = 1)
    {
        return round($emu / self::PIXEL_TO_EMU);
    }

    public static function emuToPx($emu = 1)
    {
        return self::emuToPx($emu);
    }

    /**
     * Convert pica to point
     *
     * @param int $pica
     * @return float
     */
    public static function picaToPoint($pica = 1)
    {
        return $pica / self::INCH_TO_PICA * self::INCH_TO_POINT;
    }

    public static function pcToPt($pica = 1)
    {
        return self::picaToPoint($pica);
    }

    /**
     * Convert degree to angle
     *
     * @param int $degree
     * @return int
     */
    public static function degreeToAngle($degree = 1)
    {
        return (int) round($degree * self::DEGREE_TO_ANGLE);
    }

    /**
     * Convert angle to degrees
     *
     * @param int $angle
     * @return int
     */
    public static function angleToDegree($angle = 1)
    {
        return round($angle / self::DEGREE_TO_ANGLE);
    }

    /**
     * Convert HTML hexadecimal to RGB
     *
     * @param string $value HTML Color in hexadecimal
     * @return array Value in RGB
     */
    public static function htmlToRgb($value): array
    {
        if ($value[0] == '#') {
            $value = substr($value, 1);
        }

        if (strlen($value) == 6) {
            list($red, $green, $blue) = array($value[0] . $value[1], $value[2] . $value[3], $value[4] . $value[5]);
        } elseif (strlen($value) == 3) {
            list($red, $green, $blue) = array($value[0] . $value[0], $value[1] . $value[1], $value[2] . $value[2]);
        } else {
            return false;
        }

        $red = hexdec($red);
        $green = hexdec($green);
        $blue = hexdec($blue);

        return array($red, $green, $blue);
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10px, ...) to points
     *
     * @param string $value
     * @return float
     */
    public static function cssToPoint($value)
    {
        return self::autoConvertTo($value, 'points');
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10px, ...) to twips
     *
     * @param string $value
     * @return float
     */
    public static function cssToTwip($value)
    {
        return self::pointToTwip(self::cssToPoint($value));
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10px, ...) to pixel
     *
     * @param string $value
     * @return float
     */
    public static function cssToPixel($value)
    {
        return self::pointToPixel(self::cssToPoint($value));
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10px, ...) to cm
     *
     * @param string $value
     * @return float
     */
    public static function cssToCm($value)
    {
        return self::pointToCm(self::cssToPoint($value));
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10px, ...) to emu
     *
     * @param string $value
     * @return float
     */
    public static function cssToEmu($value)
    {
        return self::pointToEmu(self::cssToPoint($value));
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10px, ...) to ...
     *
     * @param string $value
     * @param string $targetMeasurement
     * @return float
     */
    public static function autoConvertTo($value, $targetMeasurement = 'twip')
    {
        if ($value == '0') {
            return 0;
        }
        $matches = array();
        if (preg_match('/^[+-]?([0-9]+\.?[0-9]*)?(px|em|ex|%|in|cm|mm|pt|pc)$/i', $value, $matches)) {
            $size = $matches[1];
            $unit = $matches[2];

            switch ($unit) {
                case self::UNIT_POINT:
                    return $size;
                case self::UNIT_PIXEL:
                    return self::{'pixelTo' . ucfirst($targetMeasurement)}($size);
                case self::UNIT_POINT:
                    return self::{'cmTo' . ucfirst($targetMeasurement)}($size);
                case self::UNIT_MM:
                    return self::{'cmTo' . ucfirst($targetMeasurement)}($size / 10);
                case self::UNIT_INCH:
                    return self::{'inchTo' . ucfirst($targetMeasurement)}($size);
                case self::UNIT_PICA:
                    return self::{'picaTo' . ucfirst($targetMeasurement)}($size);
            }
        }

        return null;
    }
}
