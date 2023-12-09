<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

interface Node
{

    /**
     * @return string
     */
    public function getSource(): string;

}