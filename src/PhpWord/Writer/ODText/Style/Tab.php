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

use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Shared\Converter;

/**
 * Tab style writer
 *
 * @since 0.10.0
 */
class Tab extends AbstractStyle
{
    // left is the default so we won't write anyting
    static $supportedTypes = [
        Style\Tab::TAB_STOP_CENTER,
        Style\Tab::TAB_STOP_RIGHT,
        Style\Tab::TAB_STOP_DECIMAL,
        Style\Tab::TAB_STOP_CHAR,
    ];
    
    /**
     * Write style.
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof Style\Tab) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();
        
        // http://docs.oasis-open.org/office/v1.2/os/OpenDocument-v1.2-os-part1.html#element-style_tab-stop
        $xmlWriter->startElement('style:tab-stop');
        $tabStopType = $style->getType();
        $leaderType = $style->getLeader();
        $leaderText = $style->getLeaderText();
        $tabStopChar = $style->getChar();
        
        // http://docs.oasis-open.org/office/v1.2/os/OpenDocument-v1.2-os-part1.html#attribute-style_type_element-style_tab-stop
        if ($tabStopType === Style\Tab::TAB_STOP_DECIMAL) {
            $xmlWriter->writeAttribute('style:type', 'char');
            $tabStopChar = '.';
        } elseif (in_array($tabStopType, self::$supportedTypes)) {
            $xmlWriter->writeAttribute('style:type', $tabStopType);
        }
        
        if ($tabStopType === Style\Tab::TAB_STOP_CHAR && $tabStopChar === null) {
            $tabStopChar = '.';
        }
        
        switch ($leaderType) {
            case Style\Tab::TAB_LEADER_NONE:
                $leaderType = ' ';
                break;
            case Style\Tab::TAB_LEADER_DOT:
                $leaderType = '.';
                break;
            case Style\Tab::TAB_LEADER_HYPHEN:
                $leaderType = '-';
                break;
            case Style\Tab::TAB_LEADER_UNDERSCORE:
                $leaderType = '_';
                break;
            case Style\Tab::TAB_LEADER_HEAVY:
                $leaderType = '';
                break;
            case Style\Tab::TAB_LEADER_MIDDLEDOT:
                $leaderType = '·';
                break;
        }
        
        if (!($leaderText === null || $leaderText === ' ')) {
            // http://docs.oasis-open.org/office/v1.2/os/OpenDocument-v1.2-os-part1.html#attribute-style_leader-text
            $xmlWriter->writeAttribute('style:leader-text', $leaderText);
            
        }

        if ($style->getPosition() !== null) {
            // http://docs.oasis-open.org/office/v1.2/os/OpenDocument-v1.2-os-part1.html#attribute-style_position_element-style_tab-stop
            $xmlWriter->writeAttribute('style:position', Converter::twipToInch($style->getPosition()) . 'in');
        }
        
        if (($tabStopType === Style\Tab::TAB_STOP_DECIMAL || $tabStopType === Style\Tab::TAB_STOP_CHAR) && $tabStopChar !== null) {
            $xmlWriter->writeAttribute('style:char', $tabStopChar);
        }
        
        // TODO: style:leader-color 19.484
        // TODO: style:leader-style 19.485
        // TODO: style:leader-text-style 19.487
        // TODO: style:leader-type 19.488
        // TODO: style:leader-width 19.489
        
        $xmlWriter->endElement(); // style:tab-stop
    }
}
