<?php

namespace PhpOffice\PhpWord\Reader\ODText\Styles;

use DOMElement;
use PhpOffice\PhpWord\Style;

class Tab
{
    public static function read(DOMElement $node) : array
    {
        $tabs = [];

        if ($node->nodeName !== 'style:tab-stops') {
            return $tabs;
        }

        foreach ($node->childNodes as $childNode) {
            $attributes = $childNode->attributes;

            $attribute = $attributes->getNamedItem('type');
            $type = $attribute !== null ? $attribute->nodeValue : null;
            
            $attribute = $attributes->getNamedItem('leader-text');
            $leaderText = $attribute !== null ? $attribute->nodeValue : null;
            
            $attribute = $attributes->getNamedItem('position');
            $position = $attribute !== null ? $attribute->nodeValue : null;
            
            $attribute = $attributes->getNamedItem('char');
            $char = $attribute !== null ? $attribute->nodeValue : null;

            $tab = new Style\Tab($type, $position);
            $tab->setLeaderText($leaderText);
            
            $tabs[] = $tab;
        }
        
        return $tabs;
    }
}