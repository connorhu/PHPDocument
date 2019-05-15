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

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Writer\ODText as ODTWriter;

/**
 * Paragraph style writer
 *
 * @since 0.10.0
 */
class Paragraph extends AbstractStyle
{
    use Traits\PaddingWriter;
    use Traits\BorderWriter;

    /**
     * Write style.
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof Style\Paragraph) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();

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
            
            if ($style->getBasedOn() !== null) {
                $xmlWriter->writeAttribute('style:parent-style-name', $style->getBasedOn());
            }
        }

        $paragraphProperties = [];

        if ($style->isAuto()) {
            $paragraphProperties['style:page-number'] = 'auto';
        } else {
            $marginTop = (is_null($style->getSpaceAbove()) || $style->getSpaceAbove() == 0) ? 0 : Converter::twipToInch($style->getSpaceAbove());
            $marginBottom = (is_null($style->getSpaceBelow()) || $style->getSpaceBelow() == 0) ? 0 : Converter::twipToInch($style->getSpaceBelow());
            $marginLeft = (is_null($style->getSpaceBefore()) || $style->getSpaceBefore() == 0) ? 0 : Converter::twipToInch($style->getSpaceBefore());
            $marginRight = (is_null($style->getSpaceAfter()) || $style->getSpaceAfter() == 0) ? 0 : Converter::twipToInch($style->getSpaceAfter());

            if ($marginTop > 0) {
                $paragraphProperties['fo:margin-top'] = $marginTop . 'in';
            }
            if ($marginBottom > 0) {
                $paragraphProperties['fo:margin-bottom'] = $marginBottom . 'in';
            }
            if ($marginLeft > 0) {
                $paragraphProperties['fo:margin-left'] = $marginLeft . 'in';
            }
            if ($marginRight > 0) {
                $paragraphProperties['fo:margin-right'] = $marginRight . 'in';
            }
            
            if (!empty($style->getAlignment())) {
                $paragraphProperties['fo:text-align'] = $style->getAlignment();
            }

            if ($style->getLineHeight() !== null) {
                $paragraphProperties['fo:line-height'] = $style->getLineHeight() . '%';
            }

            if ($style->isJustifySingleWord() !== null) {
                $paragraphProperties['style:justify-single-word'] = $style->isJustifySingleWord() ? 'true' : 'false';
            }

            if ($style->isKeepNext() !== null) {
                $paragraphProperties['fo:keep-with-next'] = $style->isKeepNext() === true ? 'always' : 'auto';
            }

            if ($style->isKeepLines() !== null) {
                $paragraphProperties['fo:keep-together'] = $style->isKeepLines() === false ? 'always' : 'auto';
            }

            if ($style->getHyphenationLadderCount() !== null) {
                $paragraphProperties['fo:hyphenation-ladder-count'] = $style->getHyphenationLadderCount();
            }

            if ($style->getIndent() !== null) {
                $paragraphProperties['fo:text-indent'] = Converter::twipToInch($style->getIndent()) . 'in';
            }

            if ($style->getBreakPosition() !== Style\Paragraph::BREAK_POSITION_UNSET) {
                $paragraphProperties['fo:break-' . $style->getBreakPosition()] = $style->getBreakKind();

                if ($style->getBreakKind() === Style\Paragraph::BREAK_KIND_PAGE && $style->getPageNumber() !== null) {
                    $paragraphProperties['style:page-number'] = $style->getPageNumber();
                }
            }

            if ($style->getBackgroundColor() !== null) {
                $paragraphProperties['fo:background-color'] = '#' . $style->getBackgroundColor();
            }

            $paddingAttributes = $this->getPaddingXMLAttributes($style);
            if (count($paddingAttributes) > 0) {
                $paragraphProperties = array_merge($paragraphProperties, $paddingAttributes);
            }
            
            $borderAttributes = $this->getBorderXMLAttributes($style);
            if (count($borderAttributes) > 0) {
                $paragraphProperties = array_merge($paragraphProperties, $borderAttributes);
            }

            // TODO: style:auto-text-indent
            // style:auto-text-indent="true"

            // TODO: style:page-number
            // style:page-number="auto"

            // fo:border="0.06pt solid #000000"
        }
        
        //Right to left
        if ($style->isBidi() !== null && $style->isBidi()) {
            $paragraphProperties['style:writing-mode'] = 'rl-tb';
        }
        
        $tabs = $style->getTabs();
        
        if (count($paragraphProperties) > 0 || count($tabs) > 0) {
            $xmlWriter->startElement('style:paragraph-properties');

            foreach ($paragraphProperties as $attributeName => $attributeValue) {
                $xmlWriter->writeAttribute($attributeName, $attributeValue);
            }
            
            $this->writeTabs($xmlWriter, $tabs);
            
            $xmlWriter->endElement(); // style:paragraph-properties
        }

        if ($style->getFont()) {
            $writer = new ODTWriter\Style\Font($this->phpWord, $xmlWriter, $style->getFont());
            $writer->write();
        }

        $xmlWriter->endElement(); //style:style
    }
    

    /**
     * Write tabs.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Tab[] $tabs
     */
    private function writeTabs(XMLWriter $xmlWriter, array $tabs)
    {
        if (empty($tabs)) {
            return;
        }

        $xmlWriter->startElement('style:tab-stops');
        foreach ($tabs as $tab) {
            $styleWriter = new Tab($this->phpWord, $xmlWriter, $tab);
            $styleWriter->write();
        }
        $xmlWriter->endElement(); // style:tab-stops
    }
}
