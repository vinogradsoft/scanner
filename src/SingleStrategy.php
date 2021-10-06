<?php

namespace Vinograd\Scanner;

class SingleStrategy extends AbstractTraversalStrategy
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
        $detect,
        Driver $driver,
        NodeFactory $nodeFactory,
        Verifier $leafVerifier,
        Verifier $nodeVerifier,
        Visitor $visitor
    ): void
    {
        if ($this->stop) {
            return;
        }
        $visitor->scanStarted($this, $detect);

        $founds = $driver->parse($detect);
        $driver->setDetect($detect);

        foreach ($founds as $found) {
            if ($this->stop) {
                $visitor->scanCompleted($this, $nodeFactory, $detect);
                return;
            }
            if ($driver->isLeaf($found)) {
                if ($leafVerifier->can($driver->getDataForFilter())) {
                    $visitor->visitLeaf($this, $nodeFactory, $detect, $found);
                }
            } else {
                if ($nodeVerifier->can($driver->getDataForFilter())) {
                    $visitor->visitNode($this, $nodeFactory, $detect, $found);
                }
            }
        }

        $visitor->scanCompleted($this, $nodeFactory, $detect);
    }

}