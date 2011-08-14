<?php

/**
 * CasterTest
 *
 * @package    Congow\Orient
 * @subpackage Test
 * @author     Alessandro Nadalin <alessandro.nadalin@gmail.com>
 * @author     David Funaro <ing.davidino@gmail.com>
 * @version
 */

namespace test;

use test\PHPUnit\TestCase;
use Congow\Orient\Formatter\Caster;

class StubObject
{
    public function __toString(){
        return 'a';
    }
}


class CasterTest extends TestCase
{
    public function setup()
    {
        $this->caster = new Caster();
    }
    
    public function testStringToStringConversion()
    {
        $this->assertTrue(is_string($this->caster->setValue('john')->castString()));
    }
    
    public function testBooleanToStringConversion()
    {
        $this->assertTrue(is_string($this->caster->setValue(true)->castString()));
    }
    
    public function testToStringableObjectToStringConversion()
    {
        $this->assertTrue(is_string($this->caster->setValue(new StubObject)->castString()));
    }
    
    public function testNotToStringableObjectToStringConversion()
    {
        $emtpyString = $this->caster->setValue(new \stdClass())->castString();
        $this->assertTrue(empty($emtpyString));
    }
    
    public function testBooleanToBooleanConversion()
    {
        $this->assertTrue(is_bool($this->caster->setValue(true)->castBoolean()));
        $this->assertEquals(true, $this->caster->setValue(true)->castBoolean());
    }
    
    public function testStringToBooleanConversion()
    {
        $this->assertTrue(is_bool($this->caster->setValue('john')->castBoolean()));
        $this->assertEquals(true, $this->caster->setValue('john')->castBoolean());
        $this->assertEquals(false, $this->caster->setValue('0')->castBoolean());
    }
    
    public function testObjectToBooleanConversion()
    {
        $this->assertTrue(is_bool($this->caster->setValue(new StubObject())->castBoolean()));
        $this->assertEquals(true, $this->caster->setValue(new StubObject())->castBoolean());
    }
}