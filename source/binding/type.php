<?php


namespace Components;


  /**
   * Binding_Type
   *
   * <p>
   *   Complementary interface to provider fluent API during
   *   binding configuration.
   * </p>
   *
   * <pre>
   *   My_Binding_Module extends Binding_Module
   *   {
   *     protected function configure()
   *     {
   *       $this->bind('Foo')
   *         ->to('FooImpl')
   *         ->named('singleton')
   *         ->asSingleton();
   *     }
   *   }
   * </pre>
   *
   * @package net.evalcode.components.inject
   * @subpackage binding
   *
   * @author evalcode.net
   */
  interface Binding_Type extends Object
  {
    // ACCESSORS
    /**
     * Configure as named binding.
     *
     * @param string $name_
     *
     * @return \Components\Binding_Type
     */
    function named($name_);
    /**
     * Configure as singleton.
     *
     * @return \Components\Binding_Type
     */
    function asSingleton();
    //--------------------------------------------------------------------------
  }
?>
