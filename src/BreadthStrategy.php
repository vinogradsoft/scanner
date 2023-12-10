<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

use SplQueue;

class BreadthStrategy extends AbstractTraversalStrategy
{

    /**
     * @param mixed $node
     * @param Driver $driver
     * @param Verifier $leafVerifier
     * @param Verifier $nodeVerifier
     * @param Visitor $visitor
     */
    public function detect(
        mixed       $node,
        Driver      $driver,
        Verifier    $leafVerifier,
        Verifier    $nodeVerifier,
        Visitor     $visitor
    ): void
    {
        if ($this->stop) {
            return;
        }

        $queue = new SplQueue();
        $queue->enqueue($node);

        $completeFound = null;

        $visitor->scanStarted($this, $node);

        while ($queue->count() > 0) {
            $completeFound = $element = $queue->dequeue();
            $founds = $driver->parse($element);

            $driver->setDetect($element);

            foreach ($founds as $found) {
                if ($this->stop) {
                    $visitor->scanCompleted($this, $element);
                    return;
                }

                if ($driver->isLeaf($found)) {
                    if ($leafVerifier->can($driver->getDataForFilter())) {
                        $visitor->visitLeaf($this, $element, $found);
                    }
                } else {
                    if ($nodeVerifier->can($driver->getDataForFilter())) {
                        $visitor->visitNode($this, $element, $found);
                    }
                    $queue->enqueue($driver->next());
                }
            }
        }
        $visitor->scanCompleted($this, $completeFound);
    }

}