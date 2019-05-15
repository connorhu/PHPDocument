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

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Writer as Writers;
use PhpOffice\PhpWord\Reader as Readers;
use PhpOffice\PhpWord\PhpWord;

abstract class IOFactory
{
    private static $writers = [
        'ODText' => Writers\ODText::class,
        'RTF' => Writers\RTF::class,
        'Word2007' => Writers\Word2007::class,
        'HTML' => Writers\HTML::class,
        'PDF' => Writers\PDF::class,
    ];
    
    private static $readers = [
        'ODText' => Readers\ODText::class,
        'RTF' => Readers\RTF::class,
        'Word2007' => Readers\Word2007::class,
        'HTML' => Readers\HTML::class,
        'PDF' => Readers\PDF::class,
    ];
    
    /**
     * Create new writer
     *
     * @param PhpWord $phpWord
     * @param string $name
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     *
     * @return WriterInterface
     */
    public static function createWriter(PhpWord $phpWord, string $name = 'Word2007') : Writers\WriterInterface
    {
        if ($name !== 'WriterInterface' && !isset(self::$writers[$name])) {
            throw new Exception("\"{$name}\" is not a valid writer.");
        }

        return new self::$writers[$name]($phpWord);
    }

    /**
     * Create new reader
     *
     * @param string $name
     *
     * @throws Exception
     *
     * @return ReaderInterface
     */
    public static function createReader(string $name = 'Word2007') : Readers\ReaderInterface
    {
        return self::createObject(self::OBJECT_TYPE_READER, $name);
    }
    
    const OBJECT_TYPE_READER = 0x01;
    const OBJECT_TYPE_WRITER = 0x02;
    
    /**
     * Create new object
     *
     * @param string $type
     * @param string $name
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     *
     * @return \PhpOffice\PhpWord\Writer\WriterInterface|\PhpOffice\PhpWord\Reader\ReaderInterface
     */
    private static function createObject(string $type, string $name, PhpWord $phpWord = null)
    {
        if ($type === self::OBJECT_TYPE_WRITER) {
            return self::createWriter($name);
        }
        elseif (isset(self::$readers[$name])) {
            return new self::$readers[$name]($phpWord);
        }

        throw new Exception("\"{$name}\" is not a valid {$type}.");
    }

    /**
     * Loads PhpWord from file
     *
     * @param string $filename The name of the file
     * @param string $readerName
     * @return \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public static function load(string $filename, string $readerName = 'Word2007') : PhpWord
    {
        /** @var \PhpOffice\PhpWord\Reader\ReaderInterface $reader */
        $reader = self::createReader($readerName);

        return $reader->load($filename);
    }
}
