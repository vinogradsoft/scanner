<?php

namespace Vinograd\Scanner;

abstract class AbstractTraversalStrategy
{

    /** @var bool */
    protected $stop = false;

    /**
     * @param mixed $detect
     * @param Driver $driver
     * @param Verifier $leafVerifier
     * @param Verifier $nodeVerifier
     * @param Visitor $visitor
     */
    abstract public function detect(
                 $detect,
        Driver   $driver,
        Verifier $leafVerifier,
        Verifier $nodeVerifier,
        Visitor $visitor
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