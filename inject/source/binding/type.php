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
   * <code>
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
   * </code>
   *
   * @package net.evalcode.components
   * @subpackage inject.binding
   *
   * @author evalcode.net
   */
  interface Binding_Type
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
