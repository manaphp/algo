<?php

namespace Link;

class Node
{
    /**
     * @var mixed
     */
    public $value;

    /**
     * @var static
     */
    public $next;

    /**
     * Node constructor.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}

class Link
{
    /**
     * @var Node
     */
    protected $_head;

    /**
     * @param array $values
     */
    public function __construct($values = [])
    {
        $prev = null;
        foreach ($values as $value) {
            $node = new Node($value);

            if ($prev === null) {
                $this->_head = $node;
            } else {
                $prev->next = $node;
            }

            $prev = $node;
        }
    }

    /**
     * @return array
     */
    public function values()
    {
        $values = [];

        $current = $this->_head;
        while ($current !== null) {
            $values[] = $current->value;
            $current = $current->next;
        }

        return $values;
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function add($value)
    {
        $node = new Node($value);

        $node->next = $this->_head;
        $this->_head = $node;

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function append($value)
    {
        $node = new Node($value);

        if ($this->_head === null) {
            $this->_head = $node;
        } else {
            $current = $this->_head;
            while ($current->next !== null) {
                $current = $current->next;
            }
            $current->next = $node;
        }

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function remove($value)
    {
        $prev = null;
        $current = $this->_head;

        while ($current !== null) {
            if ($current->value === $value) {
                if ($prev === null) {
                    $this->_head = $current->next;
                } else {
                    $prev->next = $current->next;
                }
                break;
            }

            $prev = $current;
            $current = $current->next;
        }

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function removeAll($value)
    {
        $prev = null;
        $current = $this->_head;

        while ($current !== null) {
            if ($current->value === $value) {
                if ($prev === null) {
                    $this->_head = $current->next;
                    $current = $current->next;
                    $prev = null;
                } else {
                    $prev->next = $current->next;
                    $current = $current->next;
                }
            } else {
                $prev = $current;
                $current = $current->next;
            }
        }

        return $this;
    }

    /**
     * @return static
     */
    public function reverse()
    {
        $prev = null;
        $current = $this->_head;
        while ($current) {
            $next = $current->next;
            $current->next = $prev;
            $prev = $current;
            $current = $next;
        }

        $this->_head = $prev;

        return $this;
    }

    /**
     * @param static $link
     *
     * @return static
     */
    public function concat($link)
    {
        if ($this->_head === null) {
            $this->_head = $link->_head;
        } elseif ($link->_head === null) {
            null;
        } else {
            $current = $this->_head;
            while ($current && $current->next !== null) {
                $current = $current->next;
            }
            $current->next = $link->_head;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function length()
    {
        $current = $this->_head;

        $length = 0;
        while ($current) {
            $length++;
            $current = $current->next;
        }

        return $length;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function exists($value)
    {
        $current = $this->_head;
        while ($current) {
            if ($current->value === $value) {
                return true;
            }
            $current = $current->next;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->_head === null;
    }

    /**
     * @return mixed|null
     */
    public function middle()
    {
        if ($this->_head === null) {
            return null;
        } else {
            $fast = $slow = $this->_head;
            while ($fast !== null && $fast->next !== null) {
                $slow = $slow->next;
                $fast = $fast->next->next;
            }

            return $slow->value;
        }
    }
}

//construct
assert((new Link())->values() === []);
assert((new Link([]))->values() === []);
assert((new Link([1]))->values() === [1]);
assert((new Link([0, 1]))->values() === [0, 1]);

//add
assert((new Link())->add(0)->values() === [0]);
assert((new Link([0]))->add(1)->values() === [1, 0]);

//append
assert((new Link([]))->append(1)->values() === [1]);
assert((new Link([0]))->append(1)->values() === [0, 1]);

//remove
assert((new Link([]))->remove(0)->values() === []);
assert((new Link([0]))->remove(0)->values() === []);
assert((new Link([0, 0]))->remove(0)->values() === [0]);
assert((new Link([1]))->remove(0)->values() === [1]);
assert((new Link([0, 1]))->remove(0)->values() === [1]);
assert((new Link([1, 0]))->remove(0)->values() === [1]);

//removeAll
assert((new Link([]))->removeAll(0)->values() === []);
assert((new Link([0]))->removeAll(0)->values() === []);
assert((new Link([0, 0]))->removeAll(0)->values() === []);
assert((new Link([1]))->removeAll(0)->values() === [1]);
assert((new Link([0, 1, 0]))->removeAll(0)->values() === [1]);
assert((new Link([1, 0, 0]))->removeAll(0)->values() === [1]);
assert((new Link([1, 0, 0, 1]))->removeAll(0)->values() === [1, 1]);

//reverse
assert((new Link())->reverse()->values() === []);
assert((new Link([1]))->reverse()->values() === [1]);
assert((new Link([1, 2]))->reverse()->values() === [2, 1]);

//concat
assert((new Link([]))->concat(new Link([]))->values() === []);
assert((new Link([1]))->concat(new Link([]))->values() === [1]);
assert((new Link([]))->concat(new Link([1]))->values() === [1]);
assert((new Link([0]))->concat(new Link([1]))->values() === [0, 1]);

//length
assert((new Link())->length() === 0);
assert((new Link([1]))->length() === 1);

//exists
assert((new Link())->exists(1) === false);
assert((new Link([0]))->exists(1) === false);
assert((new Link([1]))->exists(1) === true);

//isEmpty
assert((new Link())->isEmpty() === true);
assert((new Link([1]))->isEmpty() === false);

//middle
assert((new  Link())->middle() === null);
assert((new  Link([0]))->middle() === 0);
assert((new  Link([0, 1]))->middle() === 1);
assert((new  Link([0, 1, 2]))->middle() === 1);
assert((new  Link([0, 1, 2, 3]))->middle() === 2);