<?php


namespace Components;


  /**
   * Binding_Type_Class
   *
   * <p>
   *   Binds types to implementations by their type names.
   * </p>
   *
   * <pre>
   *   // Corresponding binding module configuration.
   *   class Binding_Module_Impl extends Binding_Module
   *   {
   *     protected function configure()
   *     {
   *       // Default type-to-implementation binding
   *       $this->bind('Foo')->to('FooImpl');
   *
   *       // ... named binding
   *       $this->bind('Environment')
   *         ->to('Environment_Production')
   *         ->named('production');
   *
   *       // ... and/or as singleton.
   *       $this->bind('Environment')
   *         ->to('Environment_Production')
   *         ->named('production')
   *         ->asSingleton();
   *     }
   *   }
   * </pre>
   *
   * @package net.evalcode.components.inject
   * @subpackage binding.type
   *
   * @author evalcode.net
   */
  class Binding_Type_Class extends Binding_Type_Abstract
  {
    // CONSTRUCTION
    public function __construct($type_, $targetType_)
    {
      parent::__construct($type_, $targetType_, false);
    }
    //--------------------------------------------------------------------------


    // OVERRIDES
    /**
     * Initializes instance of bound imlementation and caches it in case it is
     * bound as singleton. Returns instance of bound implementation
     *
     * @see \Components\Binding_Type_Abstract::getInstance() getInstance
     *
     * @return mixed
     */
    public function getInstance()
    {
      if($this->m_isSingleton)
      {
        if(null===$this->m_instance)
          $this->m_instance=new $this->m_targetType();

        return $this->m_instance;
      }

      return new $this->m_targetType();
    }
    //--------------------------------------------------------------------------
  }
?>
