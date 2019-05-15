<?php

namespace PhpOffice\PhpWord\Writer\Common;

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\AbstractWriter;

class BasePart
{
    /**
     * Parent writer
     *
     * @var \PhpOffice\PhpWord\Writer\AbstractWriter
     */
    protected $parentWriter;

    /**
     * @var string Date format
     */
    protected $dateFormat = 'Y-m-d\TH:i:sP';

    /**
     * Set parent writer.
     *
     * @param \PhpOffice\PhpWord\Writer\AbstractWriter $writer
     */
    public function setParentWriter(AbstractWriter $writer = null)
    {
        $this->parentWriter = $writer;
    }

    /**
     * Get parent writer
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * @return \PhpOffice\PhpWord\Writer\AbstractWriter
     */
    public function getParentWriter()
    {
        if (!is_null($this->parentWriter)) {
            return $this->parentWriter;
        }
        throw new Exception('No parent WriterInterface assigned.');
    }

    /**
     * Get XML Writer
     *
     * @return \PhpOffice\Common\XMLWriter
     */
    protected function getXmlWriter()
    {
        $useDiskCaching = false;
        if (!is_null($this->parentWriter)) {
            if ($this->parentWriter->isUseDiskCaching()) {
                $useDiskCaching = true;
            }
        }
        if ($useDiskCaching) {
            return new XMLWriter(XMLWriter::STORAGE_DISK, $this->parentWriter->getDiskCachingDirectory(), $this->getPhpWordSettings()->hasCompatibility());
        }

        return new XMLWriter(XMLWriter::STORAGE_MEMORY, './', $this->getPhpWordSettings()->hasCompatibility());
    }

    /**
     * Write an XML text, this will call text() or writeRaw() depending on the value of Settings::isOutputEscapingEnabled()
     *
     * @param string $content The text string to write
     * @return bool Returns true on success or false on failure
     */
    protected function writeText($content)
    {
        if ($this->getPhpWordSettings()->isOutputEscapingEnabled()) {
            return $this->getXmlWriter()->text($content);
        }

        return $this->getXmlWriter()->writeRaw($content);
    }
    
    protected function getPhpWordSettings() : Settings
    {
        return $this->getParentWriter()->getPhpWord()->getPhpWordSettings();
    }
}