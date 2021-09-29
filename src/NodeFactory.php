<?php

namespace Vinograd\Scanner;

interface NodeFactory
{
    /**
     * @param $detect
     * @param $found
     * @return Node
     */
    public function createNode($detect, $found): Node;

    /**
     * @param $detect
     * @param $found
     * @return Leaf
     */
    public function createLeaf($detect, $found): Leaf;
}