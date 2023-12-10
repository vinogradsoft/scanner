<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

class SingleStrategy extends AbstractTraversalStrategy
{

    /**
     * @param mixed $node
     * @param Driver $driver
     * @param Verifier $leafVerifier
     * @param Verifier $nodeVerifier
     * @param Visitor $visitor
     */
    public function detect(
        mixed    $node,
        Driver   $driver,
        Verifier $leafVerifier,
        Verifier $nodeVerifier,
        Visitor  $visitor
    ): void
    {
        if ($this->stop) {
            return;
        }
        $visitor->scanStarted($this, $node);

        $founds = $driver->parse($node);
        $driver->setDetect($node);

        foreach ($founds as $found) {
            if ($this->stop) {
                $visitor->scanCompleted($this, $node);
                return;
            }
            if ($driver->isLeaf($found)) {
                if ($leafVerifier->can($driver->getDataForFilter())) {
                    $visitor->visitLeaf($this, $node, $found);
                }
            } else {
                if ($nodeVerifier->can($driver->getDataForFilter())) {
                    $visitor->visitNode($this, $node, $found);
                }
            }
        }

        $visitor->scanCompleted($this, $node);
    }

}