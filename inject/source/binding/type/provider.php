<?php


namespace Components;


  /**
   * Binding_Type_Provider
   *
   * <p>
   *   Binds types to custom instance provider implementations.
   * </p>
   *
   * <code>
   *   // Custom provider implementation.
   *   class Foo_Binding_Provider implements Binding_Provider
   *   {
   *     public static function getType()
   *     {
   *       return 'FooImpl';
   *     }
   *
   *     public function get()
   *     {
   *       return new FooImpl();
   *     }
   *   }
   *
   *
   *   // Corresponding binding module configuration.
   *   class Binding_Module_Impl extends Binding_Module
   *   {
   *     protected function configure()
   *     {
   *       // lazy
   *       $this->bind('Foo')->toProvider('Foo_Binding_Provider');
   *
   *       // ... or concrete
   *       $this->bind('Foo')->toProvider(new Foo_Binding_Provider());
   *     }
   *   }
   * </code>
   *
   * @package net.evalcode.components
   * @subpackage inject.binding.type
   *
   * @author evalcode.net
   */
  final class Binding_Type_Provider extends Binding_Type_Abstract
  {
    // CONSTRUCTION
    public function __construct($type_, $provider_, $isPrimitive_)
    {
      parent::__construct($type_, $provider_::getType(), $isPrimitive_);

      if(is_string($provider_))
      {
        $this->m_providerType=$provider_;
      }
      else
      {
        $this->m_provider=$provider_;
        $this->m_providerType=get_class($provider_);
      }
    }
    //--------------------------------------------------------------------------


    // ACCESSORS
    /**
     * Initializes instance of bound provider and caches it in case it is
     * bound as singleton. Returns instance of provider.
     *
     * @return \Components\Binding_Provider
     */
    public function getProvider()
    {
      if($this->m_isSingleton || null!==$this->m_provider)
      {
        if(null===$this->m_provider)
          $this->m_provider=new $this->m_providerType();

        return $this->m_provider;
      }

      return new $this->m_providerType();
    }
    //--------------------------------------------------------------------------


    // OVERRIDES
    /**
     * Resolves instance via bound provider.
     *
     * @see Components.Binding_Type_Abstract::getInstance()
     *
     * @return mixed
     */
    public function getInstance()
    {
      return $this->getProvider()->get();
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * Cached instance of bound provider.
     *
     * @var \Components\Binding_Provider
     */
    private $m_provider;
    /**
     * Type of bound provider.
     *
     * @var string
     */
    private $m_providerType;
    //--------------------------------------------------------------------------
  }
?>
