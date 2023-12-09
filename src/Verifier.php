<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

class Verifier
{

    private Checker|null $initialChecker;

    private Checker|null $checker;

    /**
     * @param Filter $filter
     * @return $this
     */
    public function append(Filter $filter): Verifier
    {
        if (empty($this->initialChecker)) {
            $this->initialChecker = new BaseChecker($filter);
            $this->checker = $this->initialChecker;
            return $this;
        }
        $this->checker = $this->checker->append(new BaseChecker($filter));
        return $this;
    }

    /**
     * @param mixed $verifiable
     * @return bool
     */
    public function can(mixed $verifiable): bool
    {
        if (empty($this->initialChecker)) {
            return true;
        }
        return $this->initialChecker->can($verifiable);
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->initialChecker = null;
        $this->checker = null;
    }

}