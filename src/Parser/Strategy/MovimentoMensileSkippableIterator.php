<?php

namespace Antbank\AccountBalanceParser\DbItaly\Parser\Strategy;

class MovimentoMensileSkippableIterator implements \Iterator
{
    /**
     * @var \Iterator
     */
    protected $iterator;

    /**
     * @var int
     */
    protected $skipping = 0;

    /**
     * MovimentoMensileSkippableIterator constructor.
     * @param \Iterator $iterator
     */
    public function __construct(\Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->iterator->current();
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->iterator->next();
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->iterator->rewind();
    }

    /**
     * @return int
     */
    public function getSkipping(): int
    {
        return $this->skipping;
    }

    public function setSkipping(int $skipping)
    {
        $this->skipping = $skipping;
    }

    public function increaseSkipping()
    {
        $this->skipping++;
    }

    public function decreaseSkipping()
    {
        $this->skipping--;
    }
}
