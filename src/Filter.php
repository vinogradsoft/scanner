<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

interface Filter
{

    /**
     * @param mixed $element
     * @return bool
     */
    public function filter(mixed $element): bool;

}