<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

abstract class AbstractChecker implements Checker
{

    protected Checker|null $next = null;

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
     * @param mixed $node
     * @return bool
     */
    public function can(mixed $node): bool
    {
        if ($this->next !== null) {
            return $this->next->can($node);
        }
        return true;
    }
}