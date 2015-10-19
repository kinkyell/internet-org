<?php

class DOMRecursiveIterator extends DOMIterator implements RecursiveIterator
{
    public function hasChildren()
    {
        return $this->current()->hasChildNodes();
    }
    public function getChildren()
    {
        $children = $this->current()->childNodes;
        return new self($children);
    }
}


