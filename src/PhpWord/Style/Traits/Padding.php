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

namespace PhpOffice\PhpWord\Style\Traits;

use PhpOffice\PhpWord\Shared\Converter;

/**
 * Padding trait
 */
trait Padding
{
    private $padding;

    private $paddingTop;

    private $paddingBottom;

    private $paddingLeft;

    private $paddingRight;

    /**
     * getter for padding
     *
     * @return mixed return value for
     */
    public function getPadding()
    {
        return $this->padding;
    }

    /**
     * setter for padding
     *
     * @param mixed $value
     * @return self
     */
    public function setPadding($value)
    {
        if ($value !== null) {
            $value = Converter::autoConvertTo($value, 'twip');
        }
        $this->padding = $value;

        return $this;
    }

    /**
     * getter for paddingTop
     *
     * @return mixed return value for
     */
    public function getPaddingTop()
    {
        return $this->paddingTop;
    }

    /**
     * setter for paddingTop
     *
     * @param mixed $value
     * @return self
     */
    public function setPaddingTop($value)
    {
        if ($value !== null) {
            $value = Converter::autoConvertTo($value, 'twip');
        }
        $this->paddingTop = $value;

        return $this;
    }

    /**
     * getter for paddingBottom
     *
     * @return mixed return value for
     */
    public function getPaddingBottom()
    {
        return $this->paddingBottom;
    }

    /**
     * setter for paddingBottom
     *
     * @param mixed $value
     * @return self
     */
    public function setPaddingBottom($value)
    {
        if ($value !== null) {
            $value = Converter::autoConvertTo($value, 'twip');
        }
        $this->paddingBottom = $value;

        return $this;
    }

    /**
     * getter for paddingLeft
     *
     * @return mixed return value for
     */
    public function getPaddingLeft()
    {
        return $this->paddingLeft;
    }

    /**
     * setter for paddingLeft
     *
     * @param mixed $value
     * @return self
     */
    public function setPaddingLeft($value)
    {
        if ($value !== null) {
            $value = Converter::autoConvertTo($value, 'twip');
        }
        $this->paddingLeft = $value;

        return $this;
    }

    /**
     * getter for paddingRight
     *
     * @return mixed return value for
     */
    public function getPaddingRight()
    {
        return $this->paddingRight;
    }

    /**
     * setter for paddingRight
     *
     * @param mixed $value
     * @return self
     */
    public function setPaddingRight($value)
    {
        if ($value !== null) {
            $value = Converter::autoConvertTo($value, 'twip');
        }
        $this->paddingRight = $value;

        return $this;
    }
}
