<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

interface Driver
{

    /**
     * @param mixed $source
     * @return array
     */
    public function parse(mixed $source): array;

    /**
     * @param mixed $source
     * @return mixed
     */
    public function normalize(mixed $source): mixed;

    /**
     * @param mixed $detect
     * @return void
     */
    public function setDetect(mixed $detect): void;

    /**
     * @param mixed $found
     * @return bool
     */
    public function isLeaf(mixed $found): bool;

    /**
     * @return mixed
     */
    public function getDataForFilter(): mixed;

    /**
     * @return mixed
     */
    public function next(): mixed;

    /**
     * @return void
     */
    public function beforeSearch(): void;

}