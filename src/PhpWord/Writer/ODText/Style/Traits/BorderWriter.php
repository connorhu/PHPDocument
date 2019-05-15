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
trait BorderWriter
{
    private function getBorderXMLAttributes(Paragraph $style) : array
    {
        if (!$style->hasBorder()) {
            return [];
        }
        
        $attributes = [];
        
        $borderSizes = $style->getBorderSize();
        $uniqBorderSizes = array_unique($borderSizes);
        $numberOfSizes = count($uniqBorderSizes);

        $borderStyles = $style->getBorderStyle();
        $uniqBorderStyles = array_unique($borderStyles);
        $numberOfStyles = count($uniqBorderStyles);

        $borderColors = $style->getBorderColor();
        $uniqBorderColors = array_unique($borderColors);
        $numberOfColors = count($uniqBorderColors);

        if ($numberOfColors + $numberOfStyles + $numberOfSizes <= 3) {
            $size = array_shift($uniqBorderSizes);
            $style = array_shift($uniqBorderStyles);
            $color = array_shift($uniqBorderColors);
            $attributes['fo:border'] = $this->getBorderXMLAttributeValue($size, $style, $color);
        } else {
            static $suffixes = array(
                '-top',
                '-left',
                '-right',
                '-bottom',
            );

            foreach ($style->getBorderSize() as $key => $size) {
                $style = $borderStyles[$key];
                $color = $borderColors[$key];
                
                $attributes['fo:border'.$suffixes[$key]] = $this->getBorderXMLAttributeValue($size, $style, $color);
            }
        }
        
        return $attributes;
    }
    
    private function getBorderXMLAttributeValue(?string $size, ?string $style, ?string $color)
    {
        $size = Converter::twipToPt($size) . 'pt';
        return $size . ' ' . ($style === null ? 'solid' : $style) . ' ' . ($color === null ? '#000000' : ('#' . $color));
    }
    
    /**
     * Write style.
     */
    private function doWriteAttribute(XMLWriter $writer, string $attributeNameSuffix, ?string $size, ?string $style, ?string $color)
    {
        $size = Converter::twipToPt($size) . 'pt';
        $attribute = $size . ' ' . ($style === null ? 'solid' : $style) . ' ' . ($color === null ? '#000000' : ('#' . $color));
        $writer->writeAttribute('fo:border' . $attributeNameSuffix, $attribute);
    }

    private function writeBorderAttributes(XMLWriter $writer, Paragraph $style)
    {
        if (!$style->hasBorder()) {
            return;
        }

        $borderSizes = $style->getBorderSize();
        $uniqBorderSizes = array_unique($borderSizes);
        $numberOfSizes = count($uniqBorderSizes);

        $borderStyles = $style->getBorderStyle();
        $uniqBorderStyles = array_unique($borderStyles);
        $numberOfStyles = count($uniqBorderStyles);

        $borderColors = $style->getBorderColor();
        $uniqBorderColors = array_unique($borderColors);
        $numberOfColors = count($uniqBorderColors);

        if ($numberOfColors + $numberOfStyles + $numberOfSizes <= 3) {
            $size = array_shift($uniqBorderSizes);
            $style = array_shift($uniqBorderStyles);
            $color = array_shift($uniqBorderColors);
            $this->doWriteAttribute($writer, '', $size, $style, $color);
        } else {
            static $suffixes = array(
                '-top',
                '-left',
                '-right',
                '-bottom',
            );

            foreach ($style->getBorderSize() as $key => $size) {
                $style = $borderStyles[$key];
                $color = $borderColors[$key];
                $this->doWriteAttribute($writer, $suffixes[$key], $size, $style, $color);
            }
        }
    }
}
