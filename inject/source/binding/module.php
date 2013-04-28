<?php


namespace Components;


  /**
   * Binding_Module
   *
   * <p>
   *   Holds type & provider bindings to configure an Injector.
   * </p>
   *
   * <code>
   *   // Examplary binding module implementation.
   *   My_Binding_Module extends Binding_Module
   *   {
   *     // OVERRIDES
   *     protected function configure()
   *     {
   *       $this->bind('Foo')->to('FooImpl');
   *     }
   *   }
   *
   *   // Create instance of a bound type.
   *   $injector=Injector::create(new My_Binding_Module());
   *   $foo=$injector->createInstance('Foo');
   * </code>
   *
   * @package net.evalcode.components
   * @subpackage inject.binding
   *
   * @author evalcode.net
   */
  abstract class Binding_Module implements Object
  {
    // ACCESSORS
    /**
     * Initializes binding configuration.
     *
     * @param Injector $injector_
     */
    public function initialize(Injector $injector_)
    {
      if($this->m_initialized)
        return;

      $this->configureImpl($injector_);
      $this->index();

      $this->m_initialized=true;
    }

    /**
     * Resolves (named) binding for given type.
     *
     * @param string $type_
     * @param string $name_
     *
     * @return Binding_Type_Abstract
     *
     * @throws Binding_Exception If failed to resolve binding.
     */
    public function getBinding($type_, $name_=null)
    {
      $hashCode=Binding_Builder::createHashCode($type_, $name_);

      if(isset($this->m_bindings[$hashCode]))
        return $this->m_bindings[$hashCode];

      if($native=Primitive::asNative($type_))
      {
        $hashCode=Binding_Builder::createHashCode($native, $name_);

        if(isset($this->m_bindings[$hashCode]))
          return $this->m_bindings[$hashCode];
      }

      if(false===$this->m_initialized)
        throw new Binding_Exception('binding/module', 'Not initialized.');

      return null;
    }

    /**
     * Resolves binding for given type.
     *
     * @param string $type_
     *
     * @return Binding_Type_Abstract
     */
    public function getBindingForType($type_)
    {
      if(false===isset($this->m_boundTypes[$type_]))
        return null;

      return $this->m_bindings[$this->m_boundTypes[$type_]];
    }
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    /**
     * @see Components.Object::hashCode()
     */
    public function hashCode()
    {
      return spl_object_hash($this);
    }

    /**
     * @see Components.Object::equals()
     */
    public function equals($object_)
    {
      if($object_ instanceof static)
        return $this->hashCode()===$object_->hashCode();

      return false;
    }

    /**
     * @see Components.Object::__toString()
     */
    public function __toString()
    {
      return sprintf('%s@%s{initialized: %s, bindings: %s}',
        get_class($this),
        $this->hashCode(),
        Boolean::valueOf($this->m_initialized),
        Boolean::valueOf($this->m_bindings)
      );
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * Indicates whether this module has been configured & initialized.
     *
     * @var boolean
     */
    private $m_initialized=false;
    /**
     * Binding builders created internally during configuration.
     *
     * @var array|Binding_Builder
     */
    private $m_builders=array();
    /**
     * Bindings created during configuration.
     *
     * @var array|Binding_Type_Abstract
     */
    private $m_bindings=array();
    /**
     * Index of bound types to enhance performance of binding lookups.
     *
     * @var array|string
     */
    private $m_boundTypes=array();
    //-----


    /**
     * Implement to configure bindings.
     */
    protected abstract function configure();


    /**
     * Bind type for given name to ...
     *
     * @param string $type_
     *
     * @return Binding_Builder
     */
    protected function bind($type_)
    {
      $builder=new Binding_Builder($type_);
      array_push($this->m_builders, $builder);

      return $builder;
    }


    /**
     * Initiates binding configuration and adds built-in default bindings.
     *
     * @param Injector $injector_
     */
    private function configureImpl(Injector $injector_)
    {
      $this->bind('Injector')->toInstance($injector_);

      $this->configure();
    }

    /**
     * Creates index of bound types/names for validation and performance.
     *
     * @throws Binding_Exception
     */
    private function index()
    {
      foreach($this->m_builders as $builder)
      {
        $binding=$builder->getBinding();

        if(isset($this->m_bindings[$binding->hashCode()]))
        {
          throw new Binding_Exception('binding/module',
            sprintf('Already bound [%s].', $binding)
          );
        }

        $this->m_bindings[$binding->hashCode()]=$binding;

        if(false===$binding->isPrimitive())
          $this->m_boundTypes[$binding->getType()]=$binding->hashCode();
      }
    }
    //--------------------------------------------------------------------------
  }
?>
