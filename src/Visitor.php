<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

interface Visitor
{

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param mixed $detect
     * @return void
     */
    public function scanStarted(AbstractTraversalStrategy $scanStrategy, mixed $detect): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param mixed $detect
     * @return void
     */
    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, mixed $detect): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param mixed $detect
     * @param mixed $found
     * @param mixed|null $data
     * @return void
     */
    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, mixed $detect, mixed $found, mixed $data = null): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param mixed $detect
     * @param mixed $found
     * @param mixed|null $data
     * @return void
     */
    public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, mixed $detect, mixed $found, mixed $data = null): void;

    /**
     * @param Visitor $visitor
     * @return bool
     */
    public function equals(Visitor $visitor): bool;

}