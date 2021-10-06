<?php

namespace Vinograd\Scanner;

interface Driver
{
    /**
     * @param $source
     * @return array
     */
    public function parse($source): array;

    /**
     * @param $source
     * @return mixed
     */
    public function normalise($source);

    /**
     * @param $detect
     */
    public function setDetect($detect): void;

    /**
     * @param $found
     * @return bool
     */
    public function isLeaf($found): bool;

    /**
     * @return mixed
     */
    public function getDataForFilter();

    /**
     * @return mixed
     */
    public function next();

    /**
     *
     */
    public function beforeSearch(): void;
}