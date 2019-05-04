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

namespace PhpOffice\PhpWord\Writer\ODText\Style\Traits;

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Padding style writer trait
 */
trait PaddingWriter
{
    /**
     * Write style.
     */
    public function writePaddingAttributes(XMLWriter $writer, Paragraph $style)
    {
        if ($style->getPadding() !== null) {
            $value = Converter::twipToInch($style->getPadding()) . 'in';
            $writer->writeAttribute('fo:padding', $value);
        }

        if ($style->getPaddingLeft() !== null) {
            $value = Converter::twipToInch($style->getPaddingLeft()) . 'in';
            $writer->writeAttribute('fo:padding-left', $value);
        }

        if ($style->getPaddingRight() !== null) {
            $value = Converter::twipToInch($style->getPaddingRight()) . 'in';
            $writer->writeAttribute('fo:padding-right', $value);
        }

        if ($style->getPaddingTop() !== null) {
            $value = Converter::twipToInch($style->getPaddingTop()) . 'in';
            $writer->writeAttribute('fo:padding-top', $value);
        }

        if ($style->getPaddingBottom() !== null) {
            $value = Converter::twipToInch($style->getPaddingBottom()) . 'in';
            $writer->writeAttribute('fo:padding-bottom', $value);
        }
    }
}
