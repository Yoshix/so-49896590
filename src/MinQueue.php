<?php
declare(strict_types=1);

class MinQueue implements \Countable
{
    /**
     * @var \SplPriorityQueue
     */
    private $queue;

    /**
     * @var \SplObjectStorage
     */
    private $register;

    /**
     * MinQueue constructor.
     */
    public function __construct()
    {
        $this->queue = new class extends \SplPriorityQueue
        {
            /** @inheritdoc */
            public function compare($p, $q)
            {
                return $q <=> $p;
            }
        };

        $this->register = new \SplObjectStorage();
    }

    /**
     * @param object $value
     * @param mixed  $priority
     */
    public function insert($value, $priority)
    {
        $this->queue->insert($value, $priority);
        $this->register->attach($value);
    }

    /**
     * @return object
     */
    public function extract()
    {
        $value = $this->queue->extract();
        $this->register->detach($value);

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function contains($value)
    {
        return $this->register->contains($value);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->queue);
    }
}
