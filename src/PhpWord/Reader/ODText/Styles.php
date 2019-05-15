<?php

/*
 * This file is part of PHPDocument project
 *
 * (c) Karoly Gossler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpOffice\PhpWord\Reader\ODText;

use PhpOffice\PhpDocument\Document;

use PhpOffice\Common\XMLReader;
use PhpOffice\PhpWord\PhpWord;

/**
 * Style reader
 */
class Styles extends AbstractPart
{
    /**
     * Read meta.xml.
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function read(Document $document)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);

        $nodes = $xmlReader->getElements('office:styles/*');
        
        if ($nodes->length === 0) {
            return;
        }
        
        $styleBag = $document->getStyleBag();

        foreach ($nodes as $node) {
            switch ($node->nodeName) {
                case 'style:default-style':
                    // dump($node->attributes->getNamedItem('family')->nodeValue);
                    break;
                case 'style:style':
                    $style = Styles\Style::read($node);
                    
                    $styleBag->add($style);
                    
                    break;
            }
        }
    }
}
