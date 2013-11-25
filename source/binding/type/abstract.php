<?php


namespace Components;


  /**
   * Binding_Type_Abstract
   *
   * <p>
   *   Common binding implementation.
   * </p>
   *
   * @package net.evalcode.components.inject
   * @subpackage binding.type
   *
   * @author evalcode.net
   *
   * @see \Components\Binding_Type_Class \Components\Binding_Type_Class
   * @see \Components\Binding_Type_Instance \Components\Binding_Type_Instance
   * @see \Components\Binding_Type_Provider \Components\Binding_Type_Provider
   */
  abstract class Binding_Type_Abstract implements Binding_Type
  {
    // CONSTRUCTION
    public function __construct($type_, $targetType_, $isPrimitive_)
    {
      $this->m_type=$type_;
      $this->m_targetType=$targetType_;
      $this->m_isPrimitive=$isPrimitive_;
    }
    //--------------------------------------------------------------------------


    // ACCESSORS
    /**
     * Returns instance of bound implementation.
     *
     * @return mixed
     */
    public function getInstance()
    {
      return $this->m_instance;
    }

    /**
     * Returns bound type.
     *
     * @return string
     */
    public function getType()
    {
      return $this->m_type;
    }

    /**
     * Returns type of bound implementation.
     *
     * @return string
     */
    public function getTargetType()
    {
      return $this->m_targetType;
    }

    /**
     * Determines whether bound type is a primitive.
     *
     * @return boolean
     */
    public function isPrimitive()
    {
      return $this->m_isPrimitive;
    }

    /**
     * Returns bound name, in case of a named binding.
     *
     * @return string
     */
    public function getName()
    {
      return $this->m_name;
    }

    /**
     * Determines whether implementation is bound as singleton.
     *
     * @return boolean
     */
    public function isSingleton()
    {
      return true;
    }
    //--------------------------------------------------------------------------


    // OVERRIDES
    /**
     * @see \Components\Binding_Type::named() named
     */
    public function named($name_)
    {
      $this->m_name=$name_;

      return $this;
    }

    /**
     * @see \Components\Binding_Type::asSingleton() asSingleton
     */
    public function asSingleton()
    {
      $this->m_isSingleton=true;

      return $this;
    }

    /**
     * The combination of binding type+name is unique, therefore we use
     * this key as the bindings hashcode for identification in binding
     * configuration.
     *
     * @see \Components\Object::hashCode() hashCode
     */
    public function hashCode()
    {
      if(null===$this->m_hashCode)
        $this->m_hashCode=Binding_Builder::createHashCode($this->m_type, $this->m_name);

      return $this->m_hashCode;
    }

    /**
     * @see \Components\Object::equals() equals
     */
    public function equals($object_)
    {
      if($object_ instanceof self)
        return $this->hashCode()===$object_->hashCode();

      return false;
    }

    /**
     * @see \Components\Object::__toString() __toString
     */
    public function __toString()
    {
      return sprintf('%s@%s{name: %s, source-type: %s, target-type: %s, primitive: %s, singleton: %s}',
        get_class($this),
        $this->hashCode(),
        $this->m_name,
        $this->m_type,
        $this->m_targetType,
        $this->m_isPrimitive?'true':'false',
        $this->m_isSingleton?'true':'false'
      );
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * Indicates whether bound type is a primitive.
     *
     * @var boolean
     */
    protected $m_isPrimitive=false;
    /**
     * Indicates whether implementation is bound as singleton.
     *
     * @var boolean
     */
    protected $m_isSingleton=false;
    /**
     * Local cache for this binding's hash code.
     *
     * @var string
     *
     * @see \Components\Binding_Type_Abstract::hashCode() \Components\Binding_Type_Abstract::hashCode()
     */
    protected $m_hashCode;
    /**
     * Local cache for instance of bound implementation.
     *
     * @var mixed
     */
    protected $m_instance;
    /**
     * Bound name, in case of named binding.
     *
     * @var string
     */
    protected $m_name;
    /**
     * Type of bound implementation.
     *
     * @var string
     */
    protected $m_targetType;
    /**
     * Bound type.
     *
     * @var string
     */
    protected $m_type;
    //--------------------------------------------------------------------------
  }
?>
