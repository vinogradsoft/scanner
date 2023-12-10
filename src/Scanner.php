<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

use Vinograd\Scanner\Exception\ConfigurationException;

class Scanner
{

    private Driver|null $driver = null;

    private Verifier $leafVerifier;

    private Verifier $nodeVerifier;

    protected Visitor|null $visitor = null;

    protected AbstractTraversalStrategy|null $traversal = null;

    public function __construct()
    {
        $this->leafVerifier = new Verifier();
        $this->nodeVerifier = new Verifier();
    }

    /**
     * @param mixed $rootNode
     * @return void
     */
    public function traverse(mixed $rootNode): void
    {
        $this->validate();
        $this->driver->beforeSearch();
        $this->detect($rootNode);
    }

    /**
     * @return void
     */
    protected function validate(): void
    {
        if (empty($this->traversal)) {
            throw new ConfigurationException('Traversal strategy not installed.');
        }
        if (empty($this->driver)) {
            throw new ConfigurationException('Driver not installed.');
        }
        if (empty($this->visitor)) {
            throw new ConfigurationException('Visitor not installed.');
        }
    }

    /**
     * @param mixed $detect
     * @return void
     */
    protected function detect(mixed $detect): void
    {
        $detect = $this->driver->normalize($detect);
        $this->traversal->detect($detect, $this->driver, $this->leafVerifier, $this->nodeVerifier, $this->visitor);
    }

    /**
     * @param AbstractTraversalStrategy $strategy
     * @return void
     */
    public function setStrategy(AbstractTraversalStrategy $strategy): void
    {
        if ($this->traversal === $strategy) {
            return;
        }
        $this->traversal = $strategy;
    }

    /**
     * @return AbstractTraversalStrategy|null
     */
    public function getStrategy(): ?AbstractTraversalStrategy
    {
        return $this->traversal;
    }

    /**
     * @param Visitor $visitor
     * @return void
     */
    public function setVisitor(Visitor $visitor): void
    {
        if ($this->visitor === $visitor) {
            return;
        }
        $this->visitor = $visitor;
    }

    /**
     * @return Visitor|null
     */
    public function getVisitor(): ?Visitor
    {
        return $this->visitor;
    }

    /**
     * @return void
     */
    public function resetLeafFilters(): void
    {
        $this->leafVerifier->clear();
    }

    /**
     * @return void
     */
    public function resetNodeFilters(): void
    {
        $this->nodeVerifier->clear();
    }

    /**
     * @param Filter $filter
     * @return void
     */
    public function addLeafFilter(Filter $filter): void
    {
        $this->leafVerifier->append($filter);
    }

    /**
     * @param Filter $filter
     * @return void
     */
    public function addNodeFilter(Filter $filter): void
    {
        $this->nodeVerifier->append($filter);
    }

    /**
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * @param Driver $driver
     * @return void
     */
    public function setDriver(Driver $driver): void
    {
        $this->driver = $driver;
    }

}
