<?php

namespace Vinograd\Scanner;

interface Node
{
    /**
     * @return string
     */
    public function getSource(): string;
}