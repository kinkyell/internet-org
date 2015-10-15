<?php

abstract class IteratorDecoratorStub implements OuterIterator
{
    private $iterator;
    public function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }
    public function getInnerIterator()
    {
        return $this->iterator;
    }
    public function rewind()
    {
        $this->iterator->rewind();
    }
    public function valid()
    {
        return $this->iterator->valid();
    }
    public function current()
    {
        return $this->iterator->current();
    }
    public function key()
    {
        return $this->iterator->key();
    }
    public function next()
    {
        $this->iterator->next();
    }
}

