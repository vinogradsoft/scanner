<?php

namespace Vinograd\Scanner;

class Verifier
{
    /** @var Checker|null  */
    private $initialChecker;

    /** @var Checker|null  */
    private $checker;

    /**
     * @param Filter $filter
     * @return $this
     */
    public function append(Filter $filter): Verifier
    {
        if (empty($this->initialChecker)) {
            $this->initialChecker = new BaseChecker($filter);
            $this->checker = $this->initialChecker;
        }
        $this->checker = $this->checker->append(new BaseChecker($filter));
        return $this;
    }

    /**
     * @param mixed $verifiable
     * @return bool
     */
    public function can($verifiable): bool
    {
        if (empty($this->initialChecker)) {
            return true;
        }
        return $this->initialChecker->can($verifiable);
    }

    public function clear()
    {
        $this->initialChecker = null;
        $this->checker = null;
    }
}