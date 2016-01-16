<?php


namespace Components;


  /**
   * Inject_Test_Unit_Case_Injector
   *
   * @package net.evalcode.components.inject
   * @subpackage test.unit.case
   *
   * @author evalcode.net
   */
  class Inject_Test_Unit_Case_Injector implements Test_Unit_Case
  {
    // PROPERTIES
    /**
     * @inject(Test_Runner)
     *
     * @var \Components\Test_Runner
     */
    public $testRunner;
    /**
     * @inject(Inject_Test_Unit_Case_Injector_Foo)
     * @provider
     */
    public $fooProvider;
    /**
     * @inject(Inject_Test_Unit_Case_Injector_Foo)
     */
    public $foo;
    /**
     * @inject(Inject_Test_Unit_Case_Injector_Bar)
     */
    protected $bar;
    /**
     * @inject(array)
     * @named(testArray)
     */
    protected $testArray;
    /**
     * @inject(boolean)
     * @named(testBoolean)
     */
    private $testBoolean;
    /**
     * @inject(integer)
     * @named(testInteger)
     */
    private $testInteger;
    /**
     * @inject(string)
     * @named(testString)
     */
    private $testString;
    //--------------------------------------------------------------------------


    // TESTS
    /**
     * @test
     */
    public function testArray()
    {
      assertArray($this->testArray);
    }

    /**
     * @test
     */
    public function testBoolean()
    {
      assertBoolean($this->testBoolean);
    }

    /**
     * @test
     */
    public function testInteger()
    {
      assertInteger($this->testInteger);
    }

    /**
     * @test
     */
    public function testString()
    {
      assertString($this->testString);
    }

    /**
     * @test
     */
    public function testBar()
    {
      assertObject($this->bar);
      assertEquals('Components\\Inject_Test_Unit_Case_Injector_Bar', get_class($this->bar));
      assertEquals('foo', $this->bar->getName());
    }

    /**
     * @test
     */
    public function testFoo()
    {
      assertObject($this->foo);
      assertEquals('Components\\Inject_Test_Unit_Case_Injector_Foo', get_class($this->foo));
      assertEquals('bar', $this->foo->getName());
    }

    /**
     * @test
     */
    public function testFooProvider()
    {
      assertEquals(Inject_Test_Unit_Suite::getType(), $this->fooProvider->getType());
      assertEquals('bar', $this->fooProvider->get()->getName());
    }

    /**
     * @test
     */
    public function testTestRunner()
    {
      assertNotNull($this->testRunner);
    }
    //--------------------------------------------------------------------------
  }


  /**
   * Inject_Test_Unit_Case_Injector_Foo
   *
   * @package net.evalcode.components.inject
   * @subpackage test.unit.case
   *
   * @author evalcode.net
   */
  class Inject_Test_Unit_Case_Injector_Foo
  {
    // ACCESSORS
    public function getName()
    {
      return 'bar';
    }
    //--------------------------------------------------------------------------
  }


  /**
   * Inject_Test_Unit_Case_Injector_Bar
   *
   * @package net.evalcode.components.inject
   * @subpackage test.unit.case
   *
   * @author evalcode.net
   */
  class Inject_Test_Unit_Case_Injector_Bar
  {
    // ACCESSORS
    public function getName()
    {
      return 'foo';
    }
    //--------------------------------------------------------------------------
  }
?>
