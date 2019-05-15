<?php

namespace PhpOffice\PhpWord\Style;

/*
 * This file is part of PHPDocument project
 *
 * (c) Karoly Gossler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

interface StyleInterface
{
    /**
     * Get style name
     *
     * @return string
     */
    public function getStyleName() : string;

    /**
     * Set style name
     *
     * @param string $value
     * @return self
     */
    public function setStyleName(string $value);

    /**
     * Get index number
     *
     * @return int|null
     */
    public function getIndex() : ?int;

    /**
     * Get is automatic style flag
     *
     * @return bool
     */
    public function isAuto() : bool;

    /**
     * Set is automatic style flag
     *
     * @param bool $value
     * @return self
     */
    public function setAuto(bool $value = true);

    /**
     * Return style value of child style object, e.g. `left` from `Indentation` child style of `Paragraph`
     *
     * @param \PhpOffice\PhpWord\Style\AbstractStyle $substyleObject
     * @param string $substyleProperty
     * @return mixed
     * @since 0.12.0
     */
    public function getChildStyleValue(StyleInterface $substyleObject, string $substyleProperty);

    /**
     * Set style value template method
     *
     * Some child classes have their own specific overrides.
     * Backward compability check for versions < 0.10.0 which use underscore
     * prefix for their private properties.
     * Check if the set method is exists. Throws an exception?
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function setStyleValue(string $key, string $value);

    /**
     * Set style by using associative array
     *
     * @param array $values
     * @return self
     */
    public function setStyleByArray(array $values = []);
}
