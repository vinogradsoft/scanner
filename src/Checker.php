<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

interface Checker
{
    /**
     * @param Checker $checker
     * @return Checker
     */
    public function append(Checker $checker): Checker;

    /**
     * @param $found
     * @return bool
     */
    public function can($found): bool;
}