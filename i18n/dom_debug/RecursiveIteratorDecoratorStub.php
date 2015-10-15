<?php
abstract class RecursiveIteratorDecoratorStub extends IteratorDecoratorStub implements RecursiveIterator
{
    public function __construct(RecursiveIterator $iterator)
    {
        parent::__construct($iterator);
    }

    public function hasChildren()
    {
        return $this->getInnerIterator()->hasChildren();
    }

    public function getChildren()
    {
        return new static($this->getInnerIterator()->getChildren());
    }
}


