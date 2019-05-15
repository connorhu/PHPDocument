<?php

namespace PhpOffice\PhpWord\Reader\ODText\Styles;

use DOMElement;
use PhpOffice\PhpWord\Style\AbstractStyle;

class Style
{
    public static function read(DOMElement $node) : AbstractStyle
    {
        $family = $node->attributes->getNamedItem('family')->nodeValue;
        switch ($family) {
            case 'paragraph':
                return Paragraph::read($node);
                break;
        }
    }
}