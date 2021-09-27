<?php

namespace Vinograd\Scanner;

use SplQueue;

class ArrayDriver implements Driver
{
    /**  @var NodeFactory */
    protected $nodeFactory;

    /** @var array */
    private $dataForFilter;

    /** @var array */
    private $next;

    /**
     * FileDriver constructor.
     * @param NodeFactory $factory
     */
    public function __construct(NodeFactory $factory)
    {
        $this->nodeFactory = $factory;
    }

    /**
     * @return NodeFactory
     */
    public function getNodeFactory(): NodeFactory
    {
        return $this->nodeFactory;
    }

    /**
     * @param array $detect
     */
    public function setDetect($detect): void
    {

    }

    /**
     * @param array $found
     * @return bool
     */
    public function isLeaf($found): bool
    {
        $this->dataForFilter = $found;
        $value = array_values($found)[0];
        if (is_array($value)) {
            $this->next = $value;
            return false;
        }
        $this->next = null;
        return true;
    }

    /**
     * @param array|mixed $source
     * @return array
     */
    public function normalise($source)
    {
        if (!is_array($source)) {
            return [$source];
        }
        return $source;
    }

    /**
     * @return array
     */
    public function getDataForFilter()
    {
        return $this->dataForFilter;
    }

    /**
     * @param array $source
     * @return array
     */
    public function parse($source): array
    {
        $keys = array_keys($source);
        $values = array_values($source);
        $result = [];
        foreach ($keys as $idx => $key) {
            $result[] = [$key => $values[$idx]];
        }
        return $result;
    }

    /**
     *
     */
    public function beforeSearch(): void
    {

    }

    /**
     * @return array|mixed
     */
    public function next()
    {
        return $this->next;
    }
}