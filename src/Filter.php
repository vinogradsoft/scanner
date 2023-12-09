<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

interface Filter
{

    /**
     * @param mixed $found
     * @return bool
     */
    public function filter(mixed $found): bool;

    /**
     * @param mixed $config
     * @return void
     */
    public function setConfiguration(mixed $config): void;

}