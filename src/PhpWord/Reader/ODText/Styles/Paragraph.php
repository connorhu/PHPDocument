<?php

namespace PhpOffice\PhpWord\Reader\ODText\Styles;

use DOMElement;
use PhpOffice\PhpWord\Style;

class Paragraph
{
    public static function read(DOMElement $node) : Style\Paragraph
    {
        $attributes = $node->attributes;
        $style = new Style\Paragraph();

        $name = $attributes->getNamedItem('name')->nodeValue;
        $style->setStyleName($name);

        $parentStyleName = $attributes->getNamedItem('parent-style-name')->nodeValue;
        $style->setBasedOn($parentStyleName);
        
        foreach ($node->childNodes as $childNode) {
            if ($childNode->nodeName === 'style:paragraph-properties') {
                self::readParagraphProperties($childNode, $style);
            }

            if ($childNode->nodeName === 'style:text-properties') {
                self::readTextProperties($childNode, $style);
            }
        }
        
        return $style;
    }
    
    private static function readParagraphProperties(DOMElement $node, Style\Paragraph $style)
    {
        foreach ($node->childNodes as $childNode) {
            if ($childNode->nodeName === 'style:tab-stops') {
                $tabStops = Tab::read($childNode);
                
                $style->setTabs($tabStops);
            }
        }
    }

    private static function readTextProperties(DOMElement $node, Style\Paragraph $style)
    {
        $font = Font::read($node);
        
        $style->setFont($font);
    }
}