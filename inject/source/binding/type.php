<?php


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
   * @since 1.0
   * @access public
   *
   * @author Carsten Schipke <carsten.schipke@evalcode.net>
   * @copyright Copyright (C) 2012 evalcode.net
   * @license GNU General Public License 3
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
