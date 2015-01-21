<?php

namespace Butterfly\Component\Form\Tests;

use Butterfly\Component\Form\ArrayConstraint;
use Butterfly\Component\Form\IConstraint;
use Butterfly\Component\Form\ScalarConstraint;
use Butterfly\Component\Form\VariableIterator;
use Butterfly\Component\Transform\String\StringMaxLength;
use Butterfly\Component\Transform\String\StringTrim;
use Butterfly\Component\Validation\Compare;
use Butterfly\Component\Validation\IsNull;
use Butterfly\Component\Validation\String\StringLengthGreat;
use Butterfly\Component\Validation\String\StringLengthGreatOrEqual;
use Butterfly\Component\Validation\String\StringLengthLessOrEqual;

class VariableIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testIterable()
    {
        $items = array('a', 'b', 'c');

        $iterator = new VariableIterator($items);

        $count  = 0;
        $keys   = array();
        $values = array();

        foreach ($iterator as $key => $value) {
            $count++;
            $keys[]   = $key;
            $values[] = $value;
        }

        $this->assertEquals(3, $count);
        $this->assertEquals($items, $values);
        $this->assertEquals(array_keys($items), $keys);
    }

    public function testAddValue()
    {
        $items = array('a', 'b', 'c');

        $iterator = new VariableIterator($items);

        $count  = 0;
        $values = array();

        foreach ($iterator as $value) {
            if ($count == 0) {
                $iterator->addValue('d');
            }

            $count++;
            $values[] = $value;
        }

        $this->assertEquals(4, $count);
        $this->assertEquals(array('a', 'b', 'c', 'd'), $values);
    }

    public function testRemoveNextValue()
    {
        $items = array('a', 'b', 'c');

        $iterator = new VariableIterator($items);

        $count  = 0;
        $values = array();

        foreach ($iterator as $value) {
            if ($count == 0) {
                $iterator->removeValue('b');
            }

            $count++;
            $values[] = $value;
        }

        $this->assertEquals(2, $count);
        $this->assertEquals(array('a', 'c'), $values);
    }

    public function testRemovePreviousValue()
    {
        $items = array('a', 'b', 'c');

        $iterator = new VariableIterator($items);

        $count  = 0;
        $values = array();

        foreach ($iterator as $value) {
            if ($count == 1) {
                $iterator->removeValue('a');
            }

            $count++;
            $values[] = $value;
        }

        $this->assertEquals(3, $count);
        $this->assertEquals(array('a', 'b', 'c'), $values);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRemoveCurrentValue()
    {
        $items = array('a', 'b', 'c');

        $iterator = new VariableIterator($items);

        $count  = 0;
        foreach ($iterator as $value) {
            if ($count == 0) {
                $iterator->removeValue('a');
            }

            $count++;
        }
    }
}
