<?php

namespace PhpOffice\PhpWord\Writer\Common;

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;

class BaseStyle
{
    /**
     * XML writer
     *
     * @var \PhpOffice\Common\XMLWriter
     */
    private $xmlWriter;

    /**
     * Style; set protected for a while
     *
     * @var string|\PhpOffice\PhpWord\Style\AbstractStyle
     */
    protected $style;

    /**
     * 
     *
     * @var \PhpOffice\PhpWord
     */
    protected $phpWord;

    /**
     * Create new instance.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param string|\PhpOffice\PhpWord\Style\AbstractStyle $style
     * @param \PhpOffice\PhpWord $phpWord
     */
    public function __construct(PhpWord $phpWord, XMLWriter $xmlWriter, $style = null)
    {
        $this->xmlWriter = $xmlWriter;
        $this->style = $style;
        $this->phpWord = $phpWord;
    }

    /**
     * Get XML Writer
     *
     * @return \PhpOffice\Common\XMLWriter
     */
    protected function getXmlWriter() : XMLWriter
    {
        return $this->xmlWriter;
    }

    /**
     * Get Style
     *
     * @return string|\PhpOffice\PhpWord\Style\AbstractStyle
     */
    protected function getStyle()
    {
        return $this->style;
    }

    /**
     * Assemble style array into style string
     *
     * @param array $styles
     * @return string
     */
    protected function assembleStyle($styles = array())
    {
        $style = '';
        foreach ($styles as $key => $value) {
            if (!is_null($value) && $value != '') {
                $style .= "{$key}:{$value}; ";
            }
        }

        return trim($style);
    }
    
    protected function getPhpWordSettings() : Settings
    {
        return $this->phpWord->getPhpWordSettings();
    }
}