<?php

namespace Vinograd\Scanner;

class SingleStrategy extends AbstractTraversalStrategy
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
        $visitor->scanStarted($this, $detect);
        $nodeFactory = $driver->getNodeFactory();

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