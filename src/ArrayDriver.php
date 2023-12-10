<?php
declare(strict_types=1);

namespace Vinograd\Scanner;

class ArrayDriver implements Driver
{

    private array|null $dataForFilter = null;

    private array|null $next = null;

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
     * @inheritDoc
     */
    public function normalize(mixed $source): array
    {
        if (!is_array($source)) {
            return [$source];
        }
        return $source;
    }

    /**
     * @return array
     */
    public function getDataForFilter(): array
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
     * @return void
     */
    public function beforeSearch(): void
    {

    }

    /**
     * @return array
     */
    public function next(): array
    {
        return $this->next;
    }

}