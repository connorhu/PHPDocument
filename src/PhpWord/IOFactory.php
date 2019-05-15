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
        Writers\ODText::class => 'ODText',
        Writers\RTF::class => 'RTF',
        Writers\Word2007::class => 'Word2007',
        Writers\HTML::class => 'HTML',
        Writers\PDF::class => 'PDF',
    ];
    
    private static $readers = [
        Readers\ODText::class => 'ODText',
        Readers\RTF::class => 'RTF',
        Readers\Word2007::class => 'Word2007',
        Readers\HTML::class => 'HTML',
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
    public static function createWriter(PhpWord $document, string $className = Writers\Word2007::class) : Writers\WriterInterface
    {
        if (isset(self::$writers[$className])) {
            return new $className($document);
        }
        
        if (array_search($className, self::$writers) !== false) {
            @trigger_error('name based reader initialization is deprecated. use the namespaced class name: Writer\ODText::class', E_USER_DEPRECATED);
            
            return new self::$writers[$className]($document);
        }

        throw new Exception("\"{$className}\" is not a valid writer.");
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
    public static function createReader(string $className = Readers\Word2007::class) : Readers\ReaderInterface
    {
        return self::createObject(self::OBJECT_TYPE_READER, $className);
    }
    
    const OBJECT_TYPE_READER = 0x01;
    const OBJECT_TYPE_WRITER = 0x02;
    
    private function typeString(int $type) : string
    {
        switch ($type) {
            case self::OBJECT_TYPE_READER:
                return 'reader';
            case self::OBJECT_TYPE_WRITER:
                return 'writer';
        }
        
        return 'unknown type';
    }
    
    /**
     * Create new object
     *
     * @param string $type
     * @param string $name
     * @param \PhpOffice\PhpWord\PhpWord $document
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     *
     * @return \PhpOffice\PhpWord\Writer\WriterInterface|\PhpOffice\PhpWord\Reader\ReaderInterface
     */
    private static function createObject(int $type, string $className, PhpWord $document = null)
    {
        if ($type === self::OBJECT_TYPE_WRITER) {
            return self::createWriter($className);
        }

        if (isset(self::$readers[$className])) {
            return new $className($document);
        }
        
        if (array_search($className, self::$readers) !== false) {
            @trigger_error('name based reader initialization is deprecated. use the namespaced class name: Readers\ODText::class', E_USER_DEPRECATED);
            
            return new self::$readers[$className]($document);
        }

        throw new Exception($className .' is not a valid '. self::typeString($type));
    }

    /**
     * Loads PhpWord from file
     *
     * @param string $filename The name of the file
     * @param string $readerName
     * @return \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public static function load(string $filename, string $readerClass = Readers\Word2007::class) : PhpWord
    {
        /** @var \PhpOffice\PhpWord\Reader\ReaderInterface $reader */
        $reader = self::createReader($readerClass);

        return $reader->load($filename);
    }
}
