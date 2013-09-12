<?php


namespace Components;


  /**
   * Binding_Builder
   *
   * <p>
   *   Internal implementation of Binding_Module::bind().
   * </p>
   *
   * @package net.evalcode.components.inject
   * @subpackage binding
   *
   * @author evalcode.net
   */
  final class Binding_Builder
  {
    // PREDEFINED PROPERTIES
    const BINDING_NAME_DEFAULT='binding';
    //--------------------------------------------------------------------------


    // CONSTRUCTION
    public function __construct($type_)
    {
      if(null===($native=Primitive::asNative($type_)))
      {
        $this->m_type=$type_;
        $this->m_isPrimitive=false;
      }
      else
      {
        $this->m_type=$native;
        $this->m_isPrimitive=true;
      }
    }
    //--------------------------------------------------------------------------


    // STATIC ACCESSORS
    /**
     * Returns key to identify configured binding for requested type and name.
     *
     * @see \Components\Binding_Type_Abstract::hashCode() \Components\Binding_Type_Abstract::hashCode()
     *
     * @param string $type_
     * @param string $name_
     *
     * @return string
     */
    public static function createHashCode($type_, $name_=null)
    {
      if(null===$name_)
        return self::BINDING_NAME_DEFAULT.$type_;

      return $name_.$type_;
    }
    //--------------------------------------------------------------------------


    // ACCESSORS
    /**
     * Binds to given type of an implementation.
     *
     * @param mixed $implementationType_
     *
     * @return \Components\Binding_Type
     *
     * @throws \Components\Binding_Exception If requested binding is corrupt.
     */
    public function to($implementationType_)
    {
      if(null===$implementationType_)
      {
        throw new Binding_Exception('inject/binding/builder',
          'Expected non-null argument.'
        );
      }

      if($this->m_isPrimitive)
      {
        throw new Binding_Exception('inject/binding/builder',
          'Primitives must be bound to an instance or provider.'
        );
      }

      return $this->m_binding=new Binding_Type_Class($this->m_type, $implementationType_);
    }

    /**
     * Binds to given instance of an implementation.
     *
     * @param mixed $instance_
     *
     * @return \Components\Binding_Type
     */
    public function toInstance($instance_)
    {
      if(null===$instance_)
      {
        throw new Binding_Exception('inject/binding/builder',
          'Expected non-null argument.'
        );
      }

      return $this->m_binding=new Binding_Type_Instance(
        $this->m_type, $instance_, $this->m_isPrimitive
      );
    }

    /**
     * Binds to given provider of an implementation instance.
     *
     * @param string|Components\Binding_Provider $provider_
     *
     * @return \Components\Binding_Type
     */
    public function toProvider($provider_)
    {
      if(null===$provider_)
      {
        throw new Binding_Exception('inject/binding/builder',
          'Expected type or instance of Binding_Provider.'
        );
      }

      return $this->m_binding=new Binding_Type_Provider(
        $this->m_type, $provider_, $this->m_isPrimitive
      );
    }

    /**
     * Returns configured binding.
     *
     * @return \Components\Binding_Type_Abstract
     */
    public function getBinding()
    {
      if(null===$this->m_binding)
        throw new Binding_Exception('inject/binding/builder', 'Corrupt binding.');

      return $this->m_binding;
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * Indicates if bound type is a primitive type.
     *
     * @var boolean
     */
    private $m_isPrimitive=false;
    /**
     * Configured binding.
     *
     * @var \Components\Binding_Type_Abstract
     */
    private $m_binding;
    /**
     * Bound type.
     *
     * @var string
     */
    private $m_type;
    //--------------------------------------------------------------------------
  }
?>
