<?php

namespace Vinograd\Scanner;

use Vinograd\Scanner\Exception\ConfigurationException;

class Scanner
{

    /** @var Driver|null */
    private $driver = null;

    /** @var Verifier */
    private $leafVerifier;

    /** @var Verifier */
    private $nodeVerifier;

    /** @var Visitor|null */
    protected $visitor = null;

    /** @var AbstractTraversalStrategy|null */
    protected $traversal = null;

    /** @var NodeFactory */
    protected $nodeFactory;

    public function __construct()
    {
        $this->leafVerifier = new Verifier();
        $this->nodeVerifier = new Verifier();
    }

    /**
     * @param mixed $source
     */
    public function search($source): void
    {
        $this->validate();
        $this->driver->beforeSearch();
        $this->detect($source);
    }

    /**
     * Tool validation
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
        if (empty($this->nodeFactory)) {
            throw new ConfigurationException('NodeFactory not installed.');
        }
    }

    /**
     * @param $detect
     */
    protected function detect($detect): void
    {
        $detect = $this->driver->normalise($detect);
        $this->traversal->detect($detect, $this->driver, $this->nodeFactory, $this->leafVerifier, $this->nodeVerifier, $this->visitor);
    }

    /**
     * @return NodeFactory
     */
    public function getNodeFactory(): NodeFactory
    {
        return $this->nodeFactory;
    }

    /**
     * @param NodeFactory $nodeFactory
     */
    public function setNodeFactory(NodeFactory $nodeFactory): void
    {
        $this->nodeFactory = $nodeFactory;
    }

    /**
     * @param AbstractTraversalStrategy $strategy
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
     */
    public function setVisitor(Visitor $visitor): void
    {
        if ($this->visitor === $visitor) {
            return;
        }
        $this->visitor = $visitor;
    }

    /**
     * @return Visitor
     */
    public function getVisitor(): ?Visitor
    {
        return $this->visitor;
    }

    public function resetLeafFilters(): void
    {
        $this->leafVerifier->clear();
    }

    public function resetNodeFilters(): void
    {
        $this->nodeVerifier->clear();
    }

    /**
     * @param Filter $filter
     */
    public function addLeafFilter(Filter $filter): void
    {
        $this->leafVerifier->append($filter);
    }

    /**
     * @param Filter $filter
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
     */
    public function setDriver(Driver $driver): void
    {
        $this->driver = $driver;
    }

}
