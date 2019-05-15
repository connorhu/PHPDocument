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

/**
 * Tab style
 */
class Tab extends AbstractStyle
{
    /**
     * Tab stop types
     *
     * @const string
     */
    const TAB_STOP_CLEAR = 'clear';
    const TAB_STOP_LEFT = 'left';
    const TAB_STOP_CENTER = 'center';
    const TAB_STOP_RIGHT = 'right';
    const TAB_STOP_DECIMAL = 'decimal';
    const TAB_STOP_BAR = 'bar';
    const TAB_STOP_NUM = 'num';
    const TAB_STOP_CHAR = 'char';

    static $tabStopTypes = [
        self::TAB_STOP_CLEAR,
        self::TAB_STOP_LEFT,
        self::TAB_STOP_CENTER,
        self::TAB_STOP_RIGHT,
        self::TAB_STOP_DECIMAL,
        self::TAB_STOP_BAR,
        self::TAB_STOP_NUM,
        self::TAB_STOP_CHAR,
    ];

    /**
     * Tab leader types
     *
     * @const string
     */
    const TAB_LEADER_NONE = 'none';
    const TAB_LEADER_DOT = 'dot';
    const TAB_LEADER_HYPHEN = 'hyphen';
    const TAB_LEADER_UNDERSCORE = 'underscore';
    const TAB_LEADER_HEAVY = 'heavy';
    const TAB_LEADER_MIDDLEDOT = 'middleDot';

    static $tabLeaderTypes = [
        self::TAB_LEADER_NONE,
        self::TAB_LEADER_DOT,
        self::TAB_LEADER_HYPHEN,
        self::TAB_LEADER_UNDERSCORE,
        self::TAB_LEADER_HEAVY,
        self::TAB_LEADER_MIDDLEDOT,
    ];

    /**
     * Tab stop type
     *
     * @var string
     */
    private $type = self::TAB_STOP_CLEAR;

    /**
     * Tab leader character
     *
     * @var string
     */
    private $leader = self::TAB_LEADER_NONE;

    /**
     * Tab stop position (twip)
     *
     * @var int|float
     */
    private $position = 0;

    /**
     * The leaderText field specifies a single Unicode character for use as leader text for tab stops.
     *
     * @var string
     */
    protected $leaderText;

    /**
     * The leaderText field pecifies the delimiter character for tab stops of type char.
     *
     * @var string
     */
    protected $char;

    /**
     * Create a new instance of Tab. Both $type and $leader
     * must conform to the values put forth in the schema. If they do not
     * they will be changed to default values.
     *
     * @param string $type Defaults to 'clear' if value is not possible
     * @param int $position Must be numeric; otherwise defaults to 0
     * @param string $leader Defaults to null if value is not possible
     */
    public function __construct($type = null, $position = 0, $leader = null)
    {
        $this->setType($type);
        $this->setPosition($position);
        $this->setLeader($leader);
    }

    /**
     * Get stop type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set stop type
     *
     * @param string $value
     * @return self
     */
    public function setType($value)
    {
        $this->type = $this->setEnumVal($value, self::$tabStopTypes, $this->type);

        return $this;
    }

    /**
     * Get leader
     *
     * @return string
     */
    public function getLeader()
    {
        return $this->leader;
    }

    /**
     * Set leader
     *
     * @param string $value
     * @return self
     */
    public function setLeader($value)
    {
        $this->leader = $this->setEnumVal($value, self::$tabLeaderTypes, $this->leader);

        return $this;
    }

    /**
     * Get position
     *
     * @return int|float
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position
     *
     * @param int|float $value
     * @return self
     */
    public function setPosition($value)
    {
        if (is_string($value)) {
            $value = Converter::autoConvertTo($value, 'twip');
        } else {
            $value *= 1;
        }
        
        $this->position = $this->setNumericVal($value, $this->position);

        return $this;
    }
    
    
    /**
     * getter for leaderText
     * 
     * @return string|null return value for 
     */
    public function getLeaderText() : ?string
    {
        return $this->leaderText;
    }
    
    /**
     * setter for leaderText
     *
     * @param string
     * @return self
     */
    public function setLeaderText(?string $value) : self
    {
        if ($value !== null && mb_strlen($value) > 1) {
            throw new \Exception('');
        }
        
        $this->leaderText = $value;
        
        $this->updateLeaderWithLeaderText();
        
        return $this;
    }
    
    private function updateLeaderWithLeaderText() {
        switch ($this->leaderText) {
            case ' ':
                $this->leader = self::TAB_LEADER_NONE;
                break;
            case '.':
                $this->leader = self::TAB_LEADER_DOT;
                break;
            case '-':
                $this->leader = self::TAB_LEADER_HYPHEN;
                break;
            case '_':
                $this->leader = self::TAB_LEADER_UNDERSCORE;
                break;
            case ' ':
                $this->leader = self::TAB_LEADER_HEAVY;
                break;
            case '·':
                $this->leader = self::TAB_LEADER_MIDDLEDOT;
                break;
        }
    }
    
    /**
     * getter for char
     * 
     * @return mixed return value for 
     */
    public function getChar() : ?string
    {
        return $this->char;
    }
    
    /**
     * setter for char
     *
     * @param mixed $value
     * @return self
     */
    public function setChar(?string $value) : self
    {
        if ($value !== null && mb_strlen($value) > 1) {
            throw new \Exception('');
        }

        $this->char = $value;
        return $this;
    }
}
