<?php
declare(strict_types=1);

namespace Test\Cases\Dummy;

use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\NodeFactory;
use Vinograd\Scanner\Visitor;

class DummyVisitor implements Visitor
{

    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {

    }

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect): void
    {

    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {

    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {

    }

    public function equals(Visitor $visitor): bool
    {
        return $this === $visitor;
    }
}