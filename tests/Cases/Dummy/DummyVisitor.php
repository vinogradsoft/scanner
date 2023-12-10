<?php
declare(strict_types=1);

namespace Test\Cases\Dummy;

use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\Visitor;

class DummyVisitor implements Visitor
{

    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {

    }

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {

    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, $parentNode, $currentElement, $data = null): void
    {

    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, $parentNode, $currentNode, $data = null): void
    {

    }

    public function equals(Visitor $visitor): bool
    {
        return $this === $visitor;
    }
}