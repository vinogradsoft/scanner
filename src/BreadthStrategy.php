<?php

namespace Vinograd\Scanner;

use SplQueue;

class BreadthStrategy extends AbstractTraversalStrategy
{
    /**
     * @param Visitor $visitor
     * @param mixed $detect
     * @param Driver $driver
     * @param Verifier $leafVerifier
     * @param Verifier $nodeVerifier
     */
    public function detect($detect, Driver $driver, Verifier $leafVerifier, Verifier $nodeVerifier, Visitor $visitor): void
    {
        if ($this->stop) {
            return;
        }

        $queue = new SplQueue();
        $queue->enqueue($detect);

        $nodeFactory = $driver->getNodeFactory();

        $completeFound = null;

        $visitor->scanStarted($this, $detect);

        while ($queue->count() > 0) {
            $completeFound = $node = $queue->dequeue();
            $founds = $driver->parse($node);

            $driver->setDetect($node);

            foreach ($founds as $key => $found) {
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