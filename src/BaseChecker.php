<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

class BaseChecker extends AbstractChecker
{

    private Filter $filter;

    /**
     * @param Filter $filter
     */
    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param mixed $element
     * @return bool
     */
    public function can(mixed $element): bool
    {
        return $this->filter->filter($element) ? parent::can($element) : false;
    }

}