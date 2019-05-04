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

namespace PhpOffice\PhpWord;

use Countable;
use Iterator;
use PhpOffice\PhpWord\Style\AbstractStyle;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Numbering;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Table;

/**
 * Style collection
 */
class StyleBag implements Countable, Iterator
{
    private $iteratorCursor = 0;
    /**
     * Style register
     *
     * @var array
     */
    private $styles = array();

    /**
     * Add paragraph style
     *
     * @param string $styleName
     * @param array|\PhpOffice\PhpWord\Style\AbstractStyle $styles
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function addParagraphStyle($styleName, $styles)
    {
        return $this->setStyleValues($styleName, new Paragraph(), $styles);
    }

    /**
     * Add font style
     *
     * @param string $styleName
     * @param array|\PhpOffice\PhpWord\Style\AbstractStyle $fontStyle
     * @param array|\PhpOffice\PhpWord\Style\AbstractStyle $paragraphStyle
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function addFontStyle($styleName, $fontStyle, $paragraphStyle = null)
    {
        return $this->setStyleValues($styleName, new Font('text', $paragraphStyle), $fontStyle);
    }

    /**
     * Add link style
     *
     * @param string $styleName
     * @param array|\PhpOffice\PhpWord\Style\AbstractStyle $styles
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function addLinkStyle($styleName, $styles)
    {
        return $this->setStyleValues($styleName, new Font('link'), $styles);
    }

    /**
     * Add numbering style
     *
     * @param string $styleName
     * @param array|\PhpOffice\PhpWord\Style\AbstractStyle $styleValues
     * @return \PhpOffice\PhpWord\Style\Numbering
     * @since 0.10.0
     */
    public function addNumberingStyle($styleName, $styleValues)
    {
        return $this->setStyleValues($styleName, new Numbering(), $styleValues);
    }

    /**
     * Add title style
     *
     * @param int|null $depth Provide null to set title font
     * @param array|\PhpOffice\PhpWord\Style\AbstractStyle $fontStyle
     * @param array|\PhpOffice\PhpWord\Style\AbstractStyle $paragraphStyle
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function addTitleStyle($depth, $fontStyle, $paragraphStyle = null)
    {
        if (empty($depth)) {
            $styleName = 'Title';
        } else {
            $styleName = "Heading_{$depth}";
        }

        return $this->setStyleValues($styleName, new Font('title', $paragraphStyle), $fontStyle);
    }

    /**
     * Add table style
     *
     * @param string $styleName
     * @param array $styleTable
     * @param array|null $styleFirstRow
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function addTableStyle($styleName, $styleTable, $styleFirstRow = null)
    {
        return $this->setStyleValues($styleName, new Table($styleTable, $styleFirstRow), null);
    }

    /**
     * Count styles
     *
     * @return int
     * @since 0.10.0
     */
    public function countStyles()
    {
        return count($this->styles);
    }

    /**
     * Reset styles.
     *
     * @since 0.10.0
     */
    public function resetStyles()
    {
        $this->styles = array();
    }

    /**
     * Set default paragraph style
     *
     * @param array|\PhpOffice\PhpWord\Style\AbstractStyle $styles Paragraph style definition
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function setDefaultParagraphStyle($styles)
    {
        return $this->addParagraphStyle('Normal', $styles);
    }

    /**
     * Get all styles
     *
     * @return \PhpOffice\PhpWord\Style\AbstractStyle[]
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * Get style by name
     *
     * @param string $styleName
     * @return \PhpOffice\PhpWord\Style\AbstractStyle Paragraph|Font|Table|Numbering
     */
    public function getStyle($styleName)
    {
        if (isset($this->styles[$styleName])) {
            return $this->styles[$styleName];
        }

        return null;
    }

    /**
     * Set style values and put it to static style collection
     *
     * The $styleValues could be an array or object
     *
     * @param string $name
     * @param \PhpOffice\PhpWord\Style\AbstractStyle $style
     * @param array|\PhpOffice\PhpWord\Style\AbstractStyle $value
     * @return \PhpOffice\PhpWord\Style\AbstractStyle
     */
    private function setStyleValues($name, $style, $value = null)
    {
        if (!isset($this->styles[$name])) {
            if ($value !== null) {
                if (is_array($value)) {
                    $style->setStyleByArray($value);
                } elseif ($value instanceof AbstractStyle) {
                    if (get_class($style) == get_class($value)) {
                        $style = $value;
                    }
                }
            }
            $style->setStyleName($name);
            $style->setIndex($this->countStyles() + 1); // One based index
            $this->styles[$name] = $style;
        }

        return $this->getStyle($name);
    }
    
    public function count() : int
    {
        return count($this->styles);
    }
    
    public function current() : mixed
    {
        return $this->styles[$this->iteratorCursor];
    }
    
    public function key() : scalar
    {
        return $this->iteratorCursor;
    }
    
    public function next() : void
    {
        ++$this->iteratorCursor;
    }
    
    public function rewind() : void
    {
        $this->iteratorCursor = 0;
    }
    
    public function valid() : bool
    {
        return isset($this->styles[$this->iteratorCursor]);
    }
    
}
