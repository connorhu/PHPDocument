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

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Settings;

/**
 * ZipArchive wrapper
 *
 * Wraps zip archive functionality of PHP ZipArchive and PCLZip. PHP ZipArchive
 * properties and methods are bypassed and used as the model for the PCLZip
 * emulation. Only needed PHP ZipArchive features are implemented.
 *
 * @method  bool addFile(string $filename, string $localname = null)
 * @method  bool addFromString(string $localname, string $contents)
 * @method  string getNameIndex(int $index)
 * @method  int locateName(string $name)
 *
 * @since   0.10.0
 */
class ZipArchive
{
    /** @const int Flags for open method */
    const CREATE = 1; // Emulate \ZipArchive::CREATE
    const OVERWRITE = 8; // Emulate \ZipArchive::OVERWRITE

    /**
     * Number of files (emulate ZipArchive::$numFiles)
     *
     * @var int
     */
    public $numFiles = 0;

    /**
     * Archive filename (emulate ZipArchive::$filename)
     *
     * @var string
     */
    public $filename;

    /**
     * Temporary storage directory
     *
     * @var string
     */
    private $tempDir;

    /**
     * Internal zip archive object
     *
     * @var \ZipArchive|\PclZip
     */
    private $zip;

    /**
     * Catch function calls: pass to ZipArchive or PCLZip
     *
     * `call_user_func_array` can only used for public function, hence the `public` in all `pcl...` methods
     *
     * @param mixed $function
     * @param mixed $args
     * @return mixed
     */
    public function __call($function, $args)
    {
        // Set object and function
        $zipFunction = $function;
        $zipObject = $this->zip;

        // Run function
        $result = false;
        if (method_exists($zipObject, $zipFunction)) {
            $result = @call_user_func_array(array($zipObject, $zipFunction), $args);
        }

        return $result;
    }

    /**
     * Open a new zip archive
     *
     * @param string $filename The file name of the ZIP archive to open
     * @param int $flags The mode to use to open the archive
     * @return bool
     */
    public function open($filename, $flags = null) : bool
    {
        $result = true;
        $this->filename = $filename;

        $this->zip = $zip = new \ZipArchive();
        $result = $zip->open($this->filename, $flags);

        // Scrutizer will report the property numFiles does not exist
        // See https://github.com/scrutinizer-ci/php-analyzer/issues/190
        $this->numFiles = $zip->numFiles;

        return $result;
    }

    /**
     * Close the active archive
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     *
     * @return bool
     *
     * @codeCoverageIgnore Can't find any test case. Uncomment when found.
     */
    public function close() : bool
    {
        if (empty($this->zip->filename)) {
            return true;
        }
        
        if ($this->zip->close() === false) {
            throw new Exception("Could not close zip file {$this->filename}: ");
        }
    }

    /**
     * Extract the archive contents (emulate \ZipArchive)
     *
     * @param string $destination
     * @param string|array $entries
     * @return bool
     * @since 0.10.0
     */
    public function extractTo($destination, $entries = null) : bool
    {
        if (!is_dir($destination)) {
            return false;
        }

        return $this->zip->extractTo($destination, $entries);
    }

    /**
     * Extract file from archive by given file name (emulate \ZipArchive)
     *
     * @param  string $filename Filename for the file in zip archive
     * @return string $contents File string contents
     */
    public function getFromName($filename)
    {
        $contents = $this->zip->getFromName($filename);
        if ($contents === false) {
            $filename = substr($filename, 1);
            $contents = $this->zip->getFromName($filename);
        }

        return $contents;
    }
}
