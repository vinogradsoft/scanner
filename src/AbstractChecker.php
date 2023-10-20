<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

abstract class AbstractChecker implements Checker
{
    /** @var Checker|null  */
    protected $next = null;

    /**
     * @param Checker $checker
     * @return Checker
     */
    public function append(Checker $checker): Checker
    {
        $this->next = $checker;
        return $checker;
    }

    /**
     * @param $node
     * @return bool
     */
    public function can($node): bool
    {
        if ($this->next !== null) {
            return $this->next->can($node);
        }
        return true;
    }
}