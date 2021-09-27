<?php

namespace Vinograd\Scanner;

class BaseChecker extends AbstractChecker
{
    /** @var Filter  */
    private $filter;

    /**
     * BaseChecker constructor.
     * @param Filter $filter
     */
    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param $node
     * @return bool
     */
    public function can($node): bool
    {
        return $this->filter->filter($node) ? parent::can($node) : false;
    }

}