<?php


  /**
   * Binding_Type_Instance
   *
   * <p>
   *   Binds types to concrete instances.
   * </p>
   *
   * <code>
   *   // Corresponding binding module configuration.
   *   class Binding_Module_Impl extends Binding_Module
   *   {
   *     protected function configure()
   *     {
   *       // Instance bindings are singletons per-se.
   *       $this->bind('Foo')->toInstance(new FooImpl());
   *
   *       // ... but of course can be named.
   *       $this->bind('Environment')
   *         ->toInstance(Environment::forProduction())
   *         ->named('current');
   *
   *       // ... but of course can be named.
   *       $this->bind('Environment')
   *         ->toInstance(Environment::forDevelopment())
   *         ->named('development');
   *     }
   *   }
   * </code>
   *
   * @package net.evalcode.components
   * @subpackage inject.binding.type
   *
   * @since 1.0
   * @access public
   *
   * @author Carsten Schipke <carsten.schipke@evalcode.net>
   * @copyright Copyright (C) 2012 evalcode.net
   * @license GNU General Public License 3
   */
  final class Binding_Type_Instance extends Binding_Type_Abstract
  {
    // CONSTRUCTION
    public function __construct($type_, $instance_, $isPrimitive_)
    {
      $this->m_instance=$instance_;

      parent::__construct($type_, $isPrimitive_?$type_:get_class($instance_), $isPrimitive_);
    }
    //--------------------------------------------------------------------------
  }
?>