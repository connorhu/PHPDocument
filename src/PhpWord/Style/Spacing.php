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

use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\LineSpacingRule;

/**
 * Spacing between lines and above/below paragraph style
 *
 * @see  http://www.datypic.com/sc/ooxml/t-w_CT_Spacing.html
 * @since 0.10.0
 */
class Spacing extends AbstractStyle
{
    /**
     * Spacing above paragraph (twip)
     *
     * @var int|float
     */
    private $before;

    /**
     * Spacing below paragraph (twip)
     *
     * @var int|float
     */
    private $after;

    /**
     * Spacing below paragraph (twip)
     *
     * @var int|float
     */
    private $below;

    /**
     * Spacing above paragraph (twip)
     *
     * @var int|float
     */
    private $above;

    /**
     * Spacing between lines in paragraph (twip)
     *
     * @var int|float
     */
    private $line;

    /**
     * Type of spacing between lines
     *
     * @var string
     */
    private $lineRule = LineSpacingRule::AUTO;

    /**
     * Create a new instance
     *
     * @param array $style
     */
    public function __construct($style = array())
    {
        $this->setStyleByArray($style);
    }

    /**
     * Get before
     *
     * @return int|float
     */
    public function getBefore()
    {
        return $this->before;
    }

    /**
     * Set before
     *
     * @param mixed $value
     * @return self
     */
    public function setBefore($value = null)
    {
        $this->before = $this->setNumericVal(Converter::autoConvertTo($value, 'twip'), $this->before);

        return $this;
    }

    /**
     * Get after
     *
     * @return int|float
     */
    public function getAfter()
    {
        return $this->after;
    }

    /**
     * Set after
     *
     * @param mixed $value
     * @return self
     */
    public function setAfter($value = null)
    {
        $this->after = $this->setNumericVal(Converter::autoConvertTo($value, 'twip'), $this->after);

        return $this;
    }

    /**
     * Get below
     *
     * @return int|float
     */
    public function getBelow()
    {
        return $this->below;
    }

    /**
     * Set below
     *
     * @param mixed $value
     * @return self
     */
    public function setBelow($value = null)
    {
        $this->below = $this->setNumericVal(Converter::autoConvertTo($value, 'twip'), $this->below);

        return $this;
    }

    /**
     * Get above
     *
     * @return int|float
     */
    public function getAbove()
    {
        return $this->above;
    }

    /**
     * Set above
     *
     * @param mixed $value
     * @return self
     */
    public function setAbove($value = null)
    {
        $this->above = $this->setNumericVal(Converter::autoConvertTo($value, 'twip'), $this->above);

        return $this;
    }

    /**
     * Get line
     *
     * @return int|float
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Set distance
     *
     * @param int|float $value
     * @return self
     */
    public function setLine($value = null)
    {
        $this->line = $this->setNumericVal($value, $this->line);

        return $this;
    }

    /**
     * Get line rule
     *
     * @return string
     */
    public function getLineRule()
    {
        return $this->lineRule;
    }

    /**
     * Set line rule
     *
     * @param string $value
     * @return self
     */
    public function setLineRule($value = null)
    {
        LineSpacingRule::validate($value);
        $this->lineRule = $value;

        return $this;
    }

    /**
     * Get line rule
     *
     * @return string
     * @deprecated Use getLineRule() instead
     * @codeCoverageIgnore
     */
    public function getRule()
    {
        return $this->lineRule;
    }

    /**
     * Set line rule
     *
     * @param string $value
     * @return self
     * @deprecated Use setLineRule() instead
     * @codeCoverageIgnore
     */
    public function setRule($value = null)
    {
        $this->lineRule = $value;

        return $this;
    }
}
