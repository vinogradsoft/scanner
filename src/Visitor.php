<?php

namespace Vinograd\Scanner;

interface Visitor
{
    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $detect
     */
    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param $detect
     */
    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param $detect
     * @param $found
     * @param null $data
     */
    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param $detect
     * @param $found
     * @param null $data
     */
    public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void;

    /**
     * @param Visitor $visitor
     * @return bool
     */
    public function equals(Visitor $visitor): bool;
}