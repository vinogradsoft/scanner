<?php

namespace Vinograd\Scanner;

interface Filter
{
    /**
     * @param $found
     * @return bool
     */
    public function filter($found): bool;

    /**
     * @param $config
     */
    public function setConfiguration($config): void;
}