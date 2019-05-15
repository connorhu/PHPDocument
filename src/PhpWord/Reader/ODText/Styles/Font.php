<?php

namespace PhpOffice\PhpWord\Reader\ODText\Styles;

use DOMElement;
use PhpOffice\PhpWord\Style;

class Font
{
    public static function read(DOMElement $node) : Style\Font
    {
        if ($node->nodeName !== 'style:text-properties') {
            throw new \Exception('invalid node');
        }
        
        $font = new Style\Font();

        $attributes = $node->attributes;

        $attribute = $attributes->getNamedItem('font-name');
        $name = $attribute !== null ? $attribute->nodeValue : null;
        $font->setName($name);

        $attribute = $attributes->getNamedItem('font-weight');
        $weight = $attribute !== null ? $attribute->nodeValue : null;
        if ($weight === 'bold') {
            $font->setBold(true);
        }

        $attribute = $attributes->getNamedItem('font-style');
        $fontStyle = $attribute !== null ? $attribute->nodeValue : null;
        if ($fontStyle === 'italic') {
            $font->setItalic(true);
        }
        
        // TODO: read all attributes

        return $font;
    }
}