<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

abstract class AbstractTraversalStrategy
{

    protected bool $stop = false;

    /**
     * @param mixed $detect
     * @param Driver $driver
     * @param NodeFactory $nodeFactory
     * @param Verifier $leafVerifier
     * @param Verifier $nodeVerifier
     * @param Visitor $visitor
     * @return void
     */
    abstract public function detect(
        mixed       $detect,
        Driver      $driver,
        NodeFactory $nodeFactory,
        Verifier    $leafVerifier,
        Verifier    $nodeVerifier,
        Visitor     $visitor
    ): void;

    /**
     * @return bool
     */
    public function isStop(): bool
    {
        return $this->stop;
    }

    /**
     * @param bool $stop
     */
    public function setStop(bool $stop): void
    {
        $this->stop = $stop;
    }

}