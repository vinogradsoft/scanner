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
     * @param mixed $detect
     * @return void
     */
    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, mixed $detect): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param mixed $parentNode
     * @param mixed $currentElement
     * @param mixed|null $data
     * @return void
     */
    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, mixed $parentNode, mixed $currentElement, mixed $data = null): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param mixed $parentNode
     * @param mixed $currentNode
     * @param mixed|null $data
     * @return void
     */
    public function visitNode(AbstractTraversalStrategy $scanStrategy, mixed $parentNode, mixed $currentNode, mixed $data = null): void;

}