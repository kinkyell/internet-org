<?php

class DOMRecursiveDecoratorStringAsCurrent extends RecursiveIteratorDecoratorStub
{
    public function current()
    {
        $node = parent::current();
        $nodeType = $node->nodeType;

        switch($nodeType)
        {
            case XML_ELEMENT_NODE:
                return "<$node->tagName>";

            case XML_TEXT_NODE:
                return $node->nodeValue;

            default:
                return sprintf('(%d) %s', $nodeType, $node->nodeValue);
        }
    }
}

