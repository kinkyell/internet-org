<?php

class DOMIterator extends IteratorDecoratorStub
{
    public function __construct($nodeOrNodes)
    {
        if ($nodeOrNodes instanceof DOMNode)
        {
            $nodeOrNodes = array($nodeOrNodes);
        }
        elseif ($nodeOrNodes instanceof DOMNodeList)
        {
            $nodeOrNodes = new IteratorIterator($nodeOrNodes);
        }
        if (is_array($nodeOrNodes))
        {
            $nodeOrNodes = new ArrayIterator($nodeOrNodes);
        }

        if (! $nodeOrNodes instanceof Iterator)
        {
            throw new InvalidArgumentException('Not an array, DOMNode or DOMNodeList given.');
        }

        parent::__construct($nodeOrNodes);
    }
}


