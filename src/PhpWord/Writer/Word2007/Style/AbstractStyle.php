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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Writer\Common\BaseStyle;
use PhpOffice\PhpWord\Settings;

/**
 * Style writer
 *
 * @since 0.10.0
 */
abstract class AbstractStyle extends BaseStyle
{
    /**
     * Write style
     */
    abstract public function write();
    
    /**
     * Convert twip value
     *
     * @param int|float $value
     * @param int $default (int|float)
     * @return int|float
     */
    protected function convertTwip($value, $default = 0)
    {
        $factors = array(
            Settings::UNIT_CM    => 567,
            Settings::UNIT_MM    => 56.7,
            Settings::UNIT_INCH  => 1440,
            Settings::UNIT_POINT => 20,
            Settings::UNIT_PICA  => 240,
        );
        $unit = $this->getPhpWordSettings()->getMeasurementUnit();
        $factor = 1;
        if (in_array($unit, $factors) && $value != $default) {
            $factor = $factors[$unit];
        }

        return $value * $factor;
    }
}
