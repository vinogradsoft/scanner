<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

use SplQueue;

class BreadthStrategy extends AbstractTraversalStrategy
{

    /**
     * @param mixed $detect
     * @param Driver $driver
     * @param NodeFactory $nodeFactory
     * @param Verifier $leafVerifier
     * @param Verifier $nodeVerifier
     * @param Visitor $visitor
     */
    public function detect(
        mixed       $detect,
        Driver      $driver,
        NodeFactory $nodeFactory,
        Verifier    $leafVerifier,
        Verifier    $nodeVerifier,
        Visitor     $visitor
    ): void
    {
        if ($this->stop) {
            return;
        }

        $queue = new SplQueue();
        $queue->enqueue($detect);

        $completeFound = null;

        $visitor->scanStarted($this, $detect);

        while ($queue->count() > 0) {
            $completeFound = $node = $queue->dequeue();
            $founds = $driver->parse($node);

            $driver->setDetect($node);

            foreach ($founds as $found) {
                if ($this->stop) {
                    $visitor->scanCompleted($this, $nodeFactory, $node);
                    return;
                }

                if ($driver->isLeaf($found)) {
                    if ($leafVerifier->can($driver->getDataForFilter())) {
                        $visitor->visitLeaf($this, $nodeFactory, $node, $found);
                    }
                } else {
                    if ($nodeVerifier->can($driver->getDataForFilter())) {
                        $visitor->visitNode($this, $nodeFactory, $node, $found);
                    }
                    $queue->enqueue($driver->next());
                }
            }
        }
        $visitor->scanCompleted($this, $nodeFactory, $completeFound);
    }

}