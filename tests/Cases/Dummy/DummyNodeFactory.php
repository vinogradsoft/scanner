<?php

namespace Test\Cases\Dummy;

use Vinograd\Scanner\Leaf;
use Vinograd\Scanner\Node;
use Vinograd\Scanner\NodeFactory;

class DummyNodeFactory implements NodeFactory
{

    public function needSupportsOf(array $supports): void
    {

    }

    public function createNode($detect, $found): Node
    {
        return $found;
    }

    public function createLeaf($detect, $found): Leaf
    {
        return $found;
    }
}