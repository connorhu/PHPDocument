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

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style;

/**
 * Font style writer
 *
 * @since 0.10.0
 */
class Paragraph extends AbstractStyle
{
    /**
     * Write style.
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Paragraph) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();

        $marginTop = (is_null($style->getSpaceAbove()) || $style->getSpaceAbove() == 0) ? '0' : Converter::twipToInch($style->getSpaceAbove());
        $marginBottom = (is_null($style->getSpaceBelow()) || $style->getSpaceBelow() == 0) ? '0' : Converter::twipToInch($style->getSpaceBelow());
        $marginLeft = (is_null($style->getSpaceBefore()) || $style->getSpaceBefore() == 0) ? '0' : Converter::twipToInch($style->getSpaceBefore());
        $marginRight = (is_null($style->getSpaceAfter()) || $style->getSpaceAfter() == 0) ? '0' : Converter::twipToInch($style->getSpaceAfter());

        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', $style->getStyleName());
        $xmlWriter->writeAttribute('style:family', 'paragraph');
        if ($style->isAuto()) {
            $xmlWriter->writeAttribute('style:parent-style-name', 'Standard');
            $xmlWriter->writeAttribute('style:master-page-name', 'Standard');
        } else {
            if ($style->getBreakPosition() !== Style\Paragraph::BREAK_POSITION_UNSET && $style->getBreakKind() === Style\Paragraph::BREAK_KIND_PAGE && $style->getPageStyle() !== null) {
                $xmlWriter->writeAttribute('style:master-page-name', $style->getPageStyle());
            }
        }

        $xmlWriter->startElement('style:paragraph-properties');
        if ($style->isAuto()) {
            $xmlWriter->writeAttribute('style:page-number', 'auto');
        } else {
            $xmlWriter->writeAttribute('fo:margin-top', $marginTop . 'in');
            $xmlWriter->writeAttribute('fo:margin-bottom', $marginBottom . 'in');
            $xmlWriter->writeAttribute('fo:margin-left', $marginLeft . 'in');
            $xmlWriter->writeAttribute('fo:margin-right', $marginRight . 'in');

            $xmlWriter->writeAttribute('fo:text-align', $style->getAlignment());

            if ($style->getLineHeight() !== null) {
                $xmlWriter->writeAttribute('fo:line-height', $style->getLineHeight() . '%');
            }

            if ($style->isJustifySingleWord() !== null) {
                $xmlWriter->writeAttribute('style:justify-single-word', $style->isJustifySingleWord() ? 'true' : 'false');
            }

            $xmlWriter->writeAttribute('fo:keep-with-next', $style->isKeepNext() ? 'always' : 'auto');

            $xmlWriter->writeAttribute('fo:keep-together', $style->isKeepLines() ? 'always' : 'auto');

            $xmlWriter->writeAttribute('fo:hyphenation-ladder-count', $style->getHyphenationLadderCount() === null ? 'no-limit' : $style->getHyphenationLadderCount());

            if ($style->getIndent() !== null) {
                $xmlWriter->writeAttribute('fo:text-indent', Converter::twipToInch($style->getIndent()) . 'in');
            }

            if ($style->getBreakPosition() !== Style\Paragraph::BREAK_POSITION_UNSET) {
                $attributeName = 'fo:break-' . $style->getBreakPosition();

                $xmlWriter->writeAttribute($attributeName, $style->getBreakKind());

                if ($style->getBreakKind() === Style\Paragraph::BREAK_KIND_PAGE && $style->getPageNumber() !== null) {
                    $xmlWriter->writeAttribute('style:page-number', $style->getPageNumber());
                }
            }
            
            if ($style->getBackgroundColor() !== null) {
                $xmlWriter->writeAttribute('fo:background-color', '#' . $style->getBackgroundColor());
            }
        }

        //Right to left
        $xmlWriter->writeAttributeIf($style->isBidi(), 'style:writing-mode', 'rl-tb');

        $xmlWriter->endElement(); //style:paragraph-properties

        if ($style->getFont()) {
            $writer = new \PhpOffice\PhpWord\Writer\ODText\Style\Font($xmlWriter, $style->getFont());
            $writer->write();
        }

        $xmlWriter->endElement(); //style:style
    }
}
