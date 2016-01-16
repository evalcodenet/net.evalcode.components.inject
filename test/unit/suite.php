<?php


namespace Components;


  /**
   * Inject_Test_Unit_Suite
   *
   * @package net.evalcode.components.inject
   * @subpackage test.unit
   *
   * @author evalcode.net
   */
  class Inject_Test_Unit_Suite extends Binding_Module
    implements Test_Unit_Suite, Binding_Provider
  {
    // OVERRIDES
    public function name()
    {
      return 'inject/test/unit/suite';
    }

    public function cases()
    {
      return array(
        'Components\\Inject_Test_Unit_Case_Injector'
      );
    }

    /**
     * @beforeSuite
     */
    public function beforeSuite()
    {
      $this->m_testRunner->setInjector(
        $this->m_testRunner->getInjector()->createChild($this)
      );
    }

    /**
     * @afterSuite
     */
    public function afterSuite()
    {
      $this->m_testRunner->setInjector(
        $this->m_testRunner->getInjector()->getParent()
      );
    }

    public function get()
    {
      return new Inject_Test_Unit_Case_Injector_Foo();
    }

    public static function getType()
    {
      return 'Components\\Inject_Test_Unit_Case_Injector_Foo';
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * @inject(Test_Runner)
     *
     * @var \Components\Test_Runner
     */
    private $m_testRunner;
    //-----


    protected function configure()
    {
      $this->bind(HashMap::TYPE)
        ->toInstance(array('key0'=>'value0'))
        ->named('testArray');

      $this->bind(Boolean::TYPE)
        ->toInstance(true)
        ->named('testBoolean');

      $this->bind(Integer::TYPE)
        ->toInstance(10)
        ->named('testInteger');

      $this->bind(String::TYPE)
        ->toInstance('Dependency Injection Test')
        ->named('testString');

      $this->bind('Inject_Test_Unit_Case_Injector_Foo')
        ->toProvider($this)
        ->asSingleton();

      $this->bind('Inject_Test_Unit_Case_Injector_Bar')
        ->toInstance(new Inject_Test_Unit_Case_Injector_Bar())
        ->asSingleton();
    }
    //--------------------------------------------------------------------------
  }
?>
