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

namespace PhpOffice\PhpWord\Style;

use PhpOffice\Common\Text;
use PhpOffice\PhpWord\Exception\InvalidStyleException;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TextAlignment;

/**
 * Paragraph style
 *
 * OOXML:
 * - General: alignment, outline level
 * - Indentation: left, right, firstline, hanging
 * - Spacing: before, after, line spacing
 * - Pagination: widow control, keep next, keep line, page break before
 * - Formatting exception: suppress line numbers, don't hyphenate
 * - Textbox options
 * - Tabs
 * - Shading
 * - Borders
 *
 * OpenOffice:
 * - Indents & spacing
 * - Alignment
 * - Text flow
 * - Outline & numbering
 * - Tabs
 * - Dropcaps
 * - Borders
 * - Background
 *
 * @see  http://www.schemacentral.com/sc/ooxml/t-w_CT_PPr.html
 */
class Paragraph extends AbstractStyle
{
    use Traits\Border;
    use Traits\Padding;

    /**
     * @const int One line height equals 240 twip
     */
    const LINE_HEIGHT = 240;

    /**
     * Aliases
     *
     * @var array
     */
    protected $aliases = array('line-height' => 'lineHeight', 'line-spacing' => 'spacing');

    /**
     * Parent style
     *
     * @var string
     */
    private $basedOn = 'Normal';

    /**
     * Style for next paragraph
     *
     * @var string
     */
    private $next;

    /**
     * @var string
     */
    private $alignment = '';

    /**
     * Indentation
     *
     * @var \PhpOffice\PhpWord\Style\Indentation
     */
    private $indentation;

    /**
     * Spacing
     *
     * @var \PhpOffice\PhpWord\Style\Spacing
     */
    private $spacing;

    /**
     * Text line height
     *
     * @var int
     */
    private $lineHeight;

    /**
     * Allow first/last line to display on a separate page
     *
     * @var bool
     */
    private $widowControl = true;

    /**
     * Keep paragraph with next paragraph
     *
     * @var bool
     */
    private $keepNext;

    /**
     * Keep all lines on one page
     *
     * @var bool
     */
    private $keepLines;

    /**
     * Start paragraph on next page
     *
     * @var bool
     * @deprecated
     */
    private $pageBreakBefore = false;

    const BREAK_POSITION_UNSET = null;
    const BREAK_POSITION_BEFORE = 'before';
    const BREAK_POSITION_AFTER = 'after';

    /**
     * @var string
     */
    private $breakPosition = self::BREAK_POSITION_UNSET;

    const BREAK_KIND_PAGE = 'page';
    const BREAK_KIND_COLUMN = 'column';

    /**
     * @var string
     */
    private $breakKind = self::BREAK_KIND_PAGE;

    /**
     * Page style on page break insertion
     *
     * @var string
     */
    private $pageStyle;

    /**
     * Page style on page break insertion.
     * ODF tag: style:page-number
     *
     * @see http://docs.oasis-open.org/office/v1.2/os/OpenDocument-v1.2-os-part1.html#__RefHeading__1420088_253892949 style:page-number in ODF scheme
     * @var string
     */
    private $pageNumber;

    /**
     * Numbering style name
     *
     * @var string
     */
    private $numStyle;

    /**
     * Numbering level
     *
     * @var int
     */
    private $numLevel = 0;

    /**
     * Set of Custom Tab Stops
     *
     * @var \PhpOffice\PhpWord\Style\Tab[]
     */
    private $tabs = array();

    /**
     * Shading
     *
     * @var \PhpOffice\PhpWord\Style\Shading
     */
    private $shading;

    /**
     * Font style
     *
     * @var \PhpOffice\PhpWord\Style\Font
     */
    private $font;

    /**
     * Ignore Spacing Above and Below When Using Identical Styles
     *
     * @var bool
     */
    private $contextualSpacing = false;

    /**
     * Right to Left Paragraph Layout
     *
     * @var bool
     */
    private $bidi = false;

    /**
     * Vertical Character Alignment on Line
     *
     * @var string
     */
    private $textAlignment;

    /**
     * Suppress hyphenation for paragraph
     *
     * @var bool
     */
    private $suppressAutoHyphens = false;

    /**
     * Maximum number of consecutive hyphens
     *
     * @var int|null
     */
    private $hyphenationLadderCount;

    /**
     * Justify Single Word
     *
     * @var bool
     */
    private $justifySingleWord;

    /**
     * Background color of the paragraph
     *
     * @see http://docs.oasis-open.org/office/v1.2/os/OpenDocument-v1.2-os-part1.html#property-fo_background-color fo
     * @var bool
     */
    private $backgroundColor;

    /**
     * Set Style value
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setStyleValue(string $key, string $value) : self
    {
        $key = Text::removeUnderscorePrefix($key);
        if ('indent' == $key || 'hanging' == $key) {
            if (is_string($value)) {
                $value = Converter::autoConvertTo($value, 'twip');
            } else {
                $value = $value * 720;
            }
        }

        return parent::setStyleValue($key, $value);
    }

    /**
     * Get style values
     *
     * An experiment to retrieve all style values in one function. This will
     * reduce function call and increase cohesion between functions. Should be
     * implemented in all styles.
     *
     * @ignoreScrutinizerPatch
     * @return array
     */
    public function getStyleValues()
    {
        $styles = array(
            'name'                => $this->getStyleName(),
            'basedOn'             => $this->getBasedOn(),
            'next'                => $this->getNext(),
            'alignment'           => $this->getAlignment(),
            'indentation'         => $this->getIndentation(),
            'spacing'             => $this->getSpace(),
            'pagination'          => array(
                'widowControl'    => $this->hasWidowControl(),
                'keepNext'        => $this->isKeepNext(),
                'keepLines'       => $this->isKeepLines(),
                'pageBreak'       => $this->hasPageBreakBefore(),
            ),
            'numbering'           => array(
                'style'           => $this->getNumStyle(),
                'level'           => $this->getNumLevel(),
            ),
            'tabs'                => $this->getTabs(),
            'shading'             => $this->getShading(),
            'contextualSpacing'   => $this->hasContextualSpacing(),
            'bidi'                => $this->isBidi(),
            'textAlignment'       => $this->getTextAlignment(),
            'suppressAutoHyphens' => $this->hasSuppressAutoHyphens(),
            'font'                => $this->getFont(),
        );

        return $styles;
    }

    /**
     * @since 0.13.0
     *
     * @return string
     */
    public function getAlignment()
    {
        return $this->alignment;
    }

    /**
     * @since 0.13.0
     *
     * @param string $value
     *
     * @return self
     */
    public function setAlignment($value)
    {
        if (Jc::isValid($value)) {
            $this->alignment = $value;
        }

        return $this;
    }

    /**
     * @deprecated 0.13.0 Use the `getAlignment` method instead.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getAlign()
    {
        return $this->getAlignment();
    }

    /**
     * @deprecated 0.13.0 Use the `setAlignment` method instead.
     *
     * @param string $value
     *
     * @return self
     *
     * @codeCoverageIgnore
     */
    public function setAlign($value = null)
    {
        return $this->setAlignment($value);
    }

    /**
     * Get parent style ID
     *
     * @return string
     */
    public function getBasedOn()
    {
        return $this->basedOn;
    }

    /**
     * Set parent style ID
     *
     * @param string $value
     * @return self
     */
    public function setBasedOn($value = 'Normal')
    {
        $this->basedOn = $value;

        return $this;
    }

    /**
     * Get style for next paragraph
     *
     * @return string
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Set style for next paragraph
     *
     * @param string $value
     * @return self
     */
    public function setNext($value = null)
    {
        $this->next = $value;

        return $this;
    }

    /**
     * Get shading
     *
     * @return \PhpOffice\PhpWord\Style\Indentation
     */
    public function getIndentation()
    {
        return $this->indentation;
    }

    /**
     * Set shading
     *
     * @param mixed $value
     * @return self
     */
    public function setIndentation($value = null)
    {
        $this->setObjectVal($value, Indentation::class, $this->indentation);

        return $this;
    }

    /**
     * Get indentation
     *
     * @return int
     */
    public function getIndent()
    {
        return $this->getChildStyleValue($this->indentation, 'left');
    }

    /**
     * Set indentation
     *
     * @param int $value
     * @return self
     */
    public function setIndent($value = null)
    {
        return $this->setIndentation(array('left' => $value));
    }

    /**
     * Get hanging
     *
     * @return int
     */
    public function getHanging()
    {
        return $this->getChildStyleValue($this->indentation, 'hanging');
    }

    /**
     * Set hanging
     *
     * @param int $value
     * @return self
     */
    public function setHanging($value = null)
    {
        return $this->setIndentation(array('hanging' => $value));
    }

    /**
     * Get spacing
     *
     * @return \PhpOffice\PhpWord\Style\Spacing
     * @todo Rename to getSpacing in 1.0
     */
    public function getSpace()
    {
        return $this->spacing;
    }

    /**
     * Set spacing
     *
     * @param mixed $value
     * @return self
     * @todo Rename to setSpacing in 1.0
     */
    public function setSpace($value = null)
    {
        $this->setObjectVal($value, Spacing::class, $this->spacing);

        return $this;
    }

    /**
     * Get space before paragraph
     *
     * @return int
     */
    public function getSpaceBefore()
    {
        return $this->getChildStyleValue($this->spacing, 'before');
    }

    /**
     * Set space before paragraph
     *
     * @param int $value
     * @return self
     */
    public function setSpaceBefore($value = null)
    {
        return $this->setSpace(array('before' => $value));
    }

    /**
     * Get space after paragraph
     *
     * @return int
     */
    public function getSpaceAfter()
    {
        return $this->getChildStyleValue($this->spacing, 'after');
    }

    /**
     * Set space above text of paragraph
     *
     * @param int $value
     * @return self
     */
    public function setSpaceAfter($value = null)
    {
        return $this->setSpace(array('after' => $value));
    }

    /**
     * Get space below text of paragraph
     *
     * @return int
     */
    public function getSpaceBelow()
    {
        return $this->getChildStyleValue($this->spacing, 'below');
    }

    /**
     * Set space below text of paragraph
     *
     * @param int $value
     * @return self
     */
    public function setSpaceBelow($value = null)
    {
        return $this->setSpace(array('below' => $value));
    }

    /**
     * Get space above text of paragraph
     *
     * @return int
     */
    public function getSpaceAbove()
    {
        return $this->getChildStyleValue($this->spacing, 'above');
    }

    /**
     * Set space above text of paragraph
     *
     * @param int $value
     * @return self
     */
    public function setSpaceAbove($value = null)
    {
        return $this->setSpace(array('above' => $value));
    }

    /**
     * Get spacing between lines
     *
     * @return int|float
     */
    public function getSpacing()
    {
        return $this->getChildStyleValue($this->spacing, 'line');
    }

    /**
     * Set spacing between lines
     *
     * @param int|float $value
     * @return self
     */
    public function setSpacing($value = null)
    {
        return $this->setSpace(array('line' => $value));
    }

    /**
     * Get spacing line rule
     *
     * @return string
     */
    public function getSpacingLineRule()
    {
        return $this->getChildStyleValue($this->spacing, 'lineRule');
    }

    /**
     * Set the spacing line rule
     *
     * @param string $value Possible values are defined in LineSpacingRule
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function setSpacingLineRule($value)
    {
        return $this->setSpace(array('lineRule' => $value));
    }

    /**
     * Get line height
     *
     * @return int|float
     */
    public function getLineHeight()
    {
        return $this->lineHeight;
    }

    /**
     * Set the line height
     *
     * @param int|float|string $lineHeight
     *
     * @throws \PhpOffice\PhpWord\Exception\InvalidStyleException
     * @return self
     */
    public function setLineHeight($lineHeight)
    {
        if (is_string($lineHeight)) {
            $lineHeight = (float) (preg_replace('/[^0-9\.\,]/', '', $lineHeight));
        }

        if ((!is_int($lineHeight) && !is_float($lineHeight)) || !$lineHeight) {
            throw new InvalidStyleException('Line height must be a valid number');
        }

        $this->lineHeight = $lineHeight;
        $this->setSpacing(($lineHeight - 1) * self::LINE_HEIGHT);
        $this->setSpacingLineRule(\PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO);

        return $this;
    }

    /**
     * Get allow first/last line to display on a separate page setting
     *
     * @return bool
     */
    public function hasWidowControl()
    {
        return $this->widowControl;
    }

    /**
     * Set keep paragraph with next paragraph setting
     *
     * @param bool $value
     * @return self
     */
    public function setWidowControl(?bool $value = true) : self
    {
        $this->widowControl = $this->setBoolVal($value, $this->widowControl);

        return $this;
    }

    /**
     * Get keep paragraph with next paragraph setting
     *
     * @return bool
     */
    public function isKeepNext() : ?bool
    {
        return $this->keepNext;
    }

    /**
     * Set keep paragraph with next paragraph setting
     *
     * @param bool $value
     * @return self
     */
    public function setKeepNext(?bool $value = true) : self
    {
        $this->keepNext = $this->setBoolVal($value, $this->keepNext);

        return $this;
    }

    /**
     * Get keep all lines on one page setting
     *
     * @return bool
     */
    public function isKeepLines() : ?bool
    {
        return $this->keepLines;
    }

    /**
     * Set keep all lines on one page setting
     *
     * @param bool $value
     * @return self
     */
    public function setKeepLines($value = true)
    {
        $this->keepLines = $this->setBoolVal($value, $this->keepLines);

        return $this;
    }

    /**
     * Get start paragraph on next page setting
     *
     * @return bool
     */
    public function hasPageBreakBefore()
    {
        @trigger_error('hasPageBreakBefore method deprecated. use other break methods instead', E_USER_DEPRECATED);

        return $this->pageBreakBefore;
    }

    /**
     * Set start paragraph on next page setting
     *
     * @param bool $value
     * @return self
     */
    public function setPageBreakBefore($value = true)
    {
        @trigger_error('setPageBreakBefore method deprecated. use other break methods instead', E_USER_DEPRECATED);

        $this->pageBreakBefore = $this->setBoolVal($value, $this->pageBreakBefore);

        return $this;
    }

    /**
     * Get numbering style name
     *
     * @return string
     */
    public function getNumStyle()
    {
        return $this->numStyle;
    }

    /**
     * Set numbering style name
     *
     * @param string $value
     * @return self
     */
    public function setNumStyle($value)
    {
        $this->numStyle = $value;

        return $this;
    }

    /**
     * Get numbering level
     *
     * @return int
     */
    public function getNumLevel()
    {
        return $this->numLevel;
    }

    /**
     * Set numbering level
     *
     * @param int $value
     * @return self
     */
    public function setNumLevel($value = 0)
    {
        $this->numLevel = $this->setIntVal($value, $this->numLevel);

        return $this;
    }

    /**
     * Get tabs
     *
     * @return \PhpOffice\PhpWord\Style\Tab[]
     */
    public function getTabs() : array
    {
        return $this->tabs;
    }

    /**
     * Set tabs
     *
     * @param array $value
     * @return self
     */
    public function setTabs(array $value = null) : self
    {
        if (is_array($value)) {
            $this->tabs = $value;
        }

        return $this;
    }

    /**
     * Get allow first/last line to display on a separate page setting
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public function getWidowControl()
    {
        return $this->hasWidowControl();
    }

    /**
     * Get keep paragraph with next paragraph setting
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public function getKeepNext()
    {
        return $this->isKeepNext();
    }

    /**
     * Get keep all lines on one page setting
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public function getKeepLines()
    {
        return $this->isKeepLines();
    }

    /**
     * Get start paragraph on next page setting
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public function getPageBreakBefore()
    {
        return $this->hasPageBreakBefore();
    }

    /**
     * Get shading
     *
     * @return \PhpOffice\PhpWord\Style\Shading
     */
    public function getShading()
    {
        return $this->shading;
    }

    /**
     * Set shading
     *
     * @param mixed $value
     * @return self
     */
    public function setShading($value = null)
    {
        $this->setObjectVal($value, Shading::class, $this->shading);

        return $this;
    }

    /**
     * Get font
     *
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function getFont() : ?Font
    {
        return $this->font;
    }

    /**
     * Set shading
     *
     * @param Font|array $value
     * @return self
     */
    public function setFont($value = null) : self
    {
        $this->setObjectVal($value, Font::class, $this->font);
        $this->font->setParagraph($this);

        return $this;
    }

    /**
     * Get contextualSpacing
     *
     * @return bool
     */
    public function hasContextualSpacing()
    {
        return $this->contextualSpacing;
    }

    /**
     * Set contextualSpacing
     *
     * @param bool $contextualSpacing
     * @return self
     */
    public function setContextualSpacing($contextualSpacing)
    {
        $this->contextualSpacing = $contextualSpacing;

        return $this;
    }

    /**
     * Get bidirectional
     *
     * @return bool
     */
    public function isBidi()
    {
        return $this->bidi;
    }

    /**
     * Set bidi
     *
     * @param bool $bidi
     *            Set to true to write from right to left
     * @return self
     */
    public function setBidi($bidi)
    {
        $this->bidi = $bidi;

        return $this;
    }

    /**
     * Get textAlignment
     *
     * @return string
     */
    public function getTextAlignment()
    {
        return $this->textAlignment;
    }

    /**
     * Set textAlignment
     *
     * @param string $textAlignment
     * @return self
     */
    public function setTextAlignment($textAlignment)
    {
        TextAlignment::validate($textAlignment);
        $this->textAlignment = $textAlignment;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSuppressAutoHyphens()
    {
        return $this->suppressAutoHyphens;
    }

    /**
     * @param bool $suppressAutoHyphens
     * @return self
     */
    public function setSuppressAutoHyphens($suppressAutoHyphens)
    {
        $this->suppressAutoHyphens = (bool) $suppressAutoHyphens;

        return $this;
    }

    /**
     * @return bool
     */
    public function isJustifySingleWord()
    {
        return $this->justifySingleWord;
    }

    /**
     * @param bool $value
     *
     * @return self
     */
    public function setJustifySingleWord($value)
    {
        $this->justifySingleWord = $value;

        return $this;
    }

    /**
     * getter for Maximum number of consecutive hyphens
     *
     * @return int|null
     */
    public function getHyphenationLadderCount()
    {
        return $this->hyphenationLadderCount;
    }

    /**
     * setter for Maximum number of consecutive hyphens
     *
     * @param int|null $value
     * @return self
     */
    public function setHyphenationLadderCount($value)
    {
        $this->hyphenationLadderCount = $value !== null ? $this->setPositiveIntVal($value, $this->hyphenationLadderCount) : null;

        return $this;
    }

    /**
     * getter for breakPosition
     *
     * @return string|null
     */
    public function getBreakPosition()
    {
        return $this->breakPosition;
    }

    /**
     * setter for breakPosition
     *
     * @param string|null $value
     * @return self
     */
    public function setBreakPosition($value)
    {
        $this->breakPosition = self::setEnumVal($value, array(self::BREAK_POSITION_UNSET, self::BREAK_POSITION_BEFORE, self::BREAK_POSITION_AFTER), $this->breakPosition);

        return $this;
    }

    /**
     * getter for breakKind
     *
     * @return mixed return value for
     */
    public function getBreakKind()
    {
        return $this->breakKind;
    }

    /**
     * setter for breakKind
     *
     * @param mixed $value
     * @return self
     */
    public function setBreakKind($value)
    {
        $this->breakKind = self::setEnumVal($value, array(self::BREAK_KIND_PAGE, self::BREAK_KIND_COLUMN), $this->breakKind);

        return $this;
    }

    /**
     * getter for pageStyle
     *
     * @return mixed return value for
     */
    public function getPageStyle()
    {
        return $this->pageStyle;
    }

    /**
     * setter for pageStyle
     *
     * @param mixed $value
     * @return self
     */
    public function setPageStyle($value)
    {
        $this->pageStyle = $value;

        return $this;
    }

    /**
     * getter for pageNumber
     *
     * @return mixed return value for
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * setter for pageNumber
     *
     * @param mixed $value
     * @return self
     */
    public function setPageNumber($value)
    {
        $this->pageNumber = self::setPositiveIntVal($value, $this->pageNumber);

        return $this;
    }

    /**
     * getter for backgroundColor
     *
     * @return mixed return value for
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * setter for backgroundColor
     *
     * @param mixed $value
     * @return self
     */
    public function setBackgroundColor($value)
    {
        $this->backgroundColor = $value;

        return $this;
    }
}
