<?php


namespace Components;


  /**
   * Injector
   *
   * <p>
   *   Dependency injection provider.
   * </p>
   *
   * <code>
   *   // Implement a Binding_Module to configure & create an Injector.
   *   My_Binding_Module extends Binding_Module
   *   {
   *     // OVERRIDES
   *     protected function configure()
   *     {
   *       $this->bind('Foo_Type')->to('Foo_Type_Impl');
   *     }
   *   }
   *
   *
   *   // Create instance of a bound type.
   *   $injector=Injector::create(new My_Binding_Module());
   *   $injector->createInstance('Foo_Type');
   * </code>
   *
   * @package net.evalcode.components
   * @subpackage inject
   *
   * @author evalcode.net
   *
   * @see Binding_Module
   * @see Binding_Provider
   * @see Binding_Annotation
   */
  final class Injector implements Object
  {
    // CONSTRUCTION
    private function __construct(Binding_Module $module_)
    {
      $this->m_module=$module_;
      $this->m_module->initialize($this);
    }
    //--------------------------------------------------------------------------


    // STATIC ACCESSORS
    /**
     * Creates a new injector for given binding module.
     *
     * <p>
     * If a root injector already exists / if at least one injector exists,
     * call will be passed to Injector::createChild([Binding_Module]).
     * </p>
     *
     * <p>
     * If this is the first invocation / no injector exists,
     * the new instance will be declared as root injector implicitly.
     * </p>
     *
     * @param Binding_Module $module_
     *
     * @return Injector
     */
    public static function create(Binding_Module $module_)
    {
      if(null===self::$m_instance)
      {
        self::initialize();

        return self::$m_instance=new self($module_);
      }

      return self::$m_instance->createChild($module_);
    }

    /**
     * Returns root injector if exists, otherwise null.
     *
     * @return Injector
     */
    public static function getRoot()
    {
      return self::$m_instance;
    }
    //--------------------------------------------------------------------------


    // ACCESSORS
    /**
     * Injects dependencies into given instance.
     *
     * <code>
     *   My_Foo
     *   {
     *      /**
     *       * &#064;Inject(My_Baa)
     *       {@*}
     *      public $baa;
     *   }
     *
     *
     *   My_Baa_Impl implements My_Baa
     *   {
     *     public function printGreeting()
     *     {
     *       print 'Hello World!';
     *     }
     *   }
     *
     *
     *   My_Binding_Module extends Binding_Module
     *   {
     *     protected function configure()
     *     {
     *       $this->bind('My_Baa')->to('My_Baa_Impl');
     *     }
     *   }
     *
     *
     *   $foo=new My_Foo();
     *
     *   $injector=Injector::create(new My_Binding_Module());
     *   $inject->injectMembers($foo);
     *
     *   // Prints 'Hello World!'
     *   $foo->baa->printGreeting();
     * </code>
     *
     * @param mixed $object_
     *
     * @throws Binding_Exception If given object depends on unbound types.
     */
    public function injectMembers($object_)
    {
      $this->injectMembersImpl($object_);
    }

    /**
     * Creates instance of given type and resolves its dependencies.
     *
     * <code>
     *   My_Foo
     *   {
     *      /**
     *       * &#064;Inject(My_Baa)
     *       {@*}
     *      public $baa;
     *   }
     *
     *
     *   My_Baa_Impl implements My_Baa
     *   {
     *     public function printGreeting()
     *     {
     *       print 'Hello World!';
     *     }
     *   }
     *
     *
     *   My_Binding_Module extends Binding_Module
     *   {
     *     protected function configure()
     *     {
     *       $this->bind('My_Foo')->to('My_Foo');
     *       $this->bind('My_Baa')->to('My_Baa_Impl');
     *     }
     *   }
     *
     *
     *   $injector=Injector::create(new My_Binding_Module());
     *
     *   // Prints 'Hello World!'
     *   $injector->createInstance('My_Foo')->baa->printGreeting();
     *   $injector->createInstance('My_Baa')->printGreeting();
     * </code>
     *
     * @param string $type_
     *
     * @return mixed
     *
     * @throws Binding_Exception If given type is not bound.
     */
    public function createInstance($type_)
    {
      if(null!==($binding=$this->m_module->getBindingForType($type_)))
        return $this->createInstanceImpl($binding);

      if(null!==$this->m_parent)
        return $this->m_parent->createInstance($type_);

      throw new Binding_Exception('inject/injector', sprintf(
        'Type not bound [type: %1$s].', $type_
      ));
    }

    /**
     * Like createInstance() but in addition attempts to resolve named bindings
     * if second parameter 'name' is passed.
     *
     * <p>
     * Named bindings are neccessary at least for bindings of primitive types,
     * e.g. configuration parameters:
     * </p>
     *
     * <code>
     *   My_Binding_Module extends Binding_Module
     *   {
     *     protected function configure()
     *     {
     *       $this->bind(String::TYPE)
     *         ->toInstance('localhost')
     *         ->named('listen.host');
     *
     *       $this->bind(Integer::TYPE)
     *         ->toInstance(80)
     *         ->named('listen.port');
     *
     *       $this->bind('My_Configuration')
     *         ->to('My_Configuration');
     *     }
     *   }
     *
     *
     *   My_Configuration
     *   {
     *      /**
     *       * &#064;Inject(integer);
     *       * &#064;Named(listen.host);
     *       {@*}
     *      private $m_host;
     *
     *      /**
     *       * &#064;Inject(integer);
     *       * &#064;Named(listen.port);
     *       {@*}
     *      private $m_port;
     *
     *
     *      public function getHost()
     *      {
     *        return $this->m_host;
     *      }
     *
     *      public function getPort()
     *      {
     *        return $this->m_port;
     *      }
     *   }
     *
     *
     *   $injector=Injector::create(new My_Binding_Module());
     *   $configuration=$injector->createInstance('My_Configuration');
     *
     *
     *   /**
     *    * &#064;return 'localhost'
     *    {@*}
     *   $configuration->getHost();
     *   /**
     *    * &#064;return 80
     *    {@*}
     *   $configuration->getPort();
     * </code>
     *
     * <code>
     *   My_Binding_Module extends Binding_Module
     *   {
     *     protected function configure()
     *     {
     *       $this->bind('My_Type')
     *         ->to('My_Type_Impl')
     *         ->named('default');
     *
     *       $this->bind('My_Type')
     *         ->to('My_Type_Impl')
     *         ->named('singleton')
     *         ->asSingleton();
     *     }
     *   }
     *
     *
     *   $injector=Injector::create(new My_Binding_Module());
     *
     *   // First instance
     *   $a=$injector->resolveInstance('My_Type', 'default');
     *   // Second instance
     *   $b=$injector->resolveInstance('My_Type', 'default');
     *
     *   // Third instance
     *   $c=$injector->resolveInstance('My_Type', 'singleton');
     *   // ... third instance again!
     *   $d=$injector->resolveInstance('My_Type', 'singleton');
     *
     *
     *   /**
     *    * &#064;return false
     *    {@*}
     *   $a->equals($b);
     *
     *   /**
     *    * &#064;return true
     *    {@*}
     *   $c->equals($d);
     *   $d->equals($c);
     * </code>
     *
     * @param string $type_
     * @param string $name_
     *
     * @return mixed
     *
     * @throws Binding_Exception If given type and/or name is not bound.
     */
    public function resolveInstance($type_, $name_=null)
    {
      if(null===$name_)
        return $this->createInstance($type_);

      if(null!==($binding=$this->m_module->getBinding($type_, $name_)))
        return $this->createInstanceImpl($binding);

      if(null!==$this->m_parent)
        return $this->m_parent->resolveInstance($type_, $name_);

      throw new Binding_Exception('inject/injector', sprintf(
        'Type and/or name not bound [type: %1$s, name: %2$s].', $type_, $name_
      ));
    }

    /**
     * Resolves provider for given type.
     *
     * <code>
     *   My_Provider implements Binding_Provider
     *   {
     *     public function get()
     *     {
     *       return new My_Baa_Impl();
     *     }
     *
     *     public static function getType()
     *     {
     *       return 'My_Baa_Impl';
     *     }
     *   }
     *
     *
     *   My_Binding_Module extends Binding_Module
     *   {
     *     protected function configure()
     *     {
     *       $this->bind('My_Foo')->to('My_Foo');
     *
     *       // Binds My_Baa to a provider that returns a (sub-)type of My_Baa.
     *       $this->bind('My_Baa')->toProvider('My_Provider');
     *
     *       // ... above statement causes creation of a new instance of given
     *       // provider everytime it is requested!
     *       // Be aware of this fact and bind your provider explicitely
     *       // as singleton ...
     *       $this->bind('My_Baa')->toProvider(new My_Provider());
     *       // ... or by passing an instance if you do not want this behavior.
     *       $this->bind('My_Baa')->toProvider('My_Provider')->asSingleton();
     *     }
     *   }
     *
     *
     *   My_Foo
     *   {
     *     /**
     *      * Internally invokes bound provider and injects its return value.
     *      *
     *      * &#064;Inject(My_Baa)
     *      {@*}
     *     public $baa;
     *
     *     /**
     *      * Injects bound provider instead of invoking it directly.
     *      *
     *      * &#064;Inject(My_Baa)
     *      * &#064;Provider
     *      {@*}
     *     public $providerBaa;
     *
     *
     *     /**
     *      * &#064;return My_Baa
     *      {@*}
     *     public function getBaa0()
     *     {
     *       return $this->baa;
     *     }
     *
     *     // same as:
     *
     *     /**
     *      * &#064;return My_Baa
     *      {@*}
     *     public function getBaa1()
     *     {
     *       return $this->providerBaa->get();
     *     }
     *   }
     *
     *
     *   $injector=Injector::create(new My_Binding_Module());
     *
     *
     *   // Resolve provider directly via Injector ...
     *   $providerBaa=$injector->getProvider('My_Baa');
     *
     *   // ... or via bound instance of My_Foo.
     *   $providerBaa=$injector->createInstance('My_Foo')->providerBaa;
     *
     *
     *   // Resolve bound My_Baa directly via Injector [invokes My_Provider::get()] ...
     *   $baa=$injector->createInstance('My_Baa');
     *
     *   // ... or via bound provider.
     *   $baa=$injector->getProvider('My_Baa')->get();
     *   $baa=$injector->createInstance('My_Foo')->providerBaa->get();
     * </code>
     *
     * @param string $type_
     *
     * @return Binding_Provider
     *
     * @throws Binding_Exception If given type is not bound to a provider.
     */
    public function getProvider($type_)
    {
      if(($binding=$this->m_module->getBindingForType($type_)) instanceof Binding_Type_Provider)
        return $binding->getProvider();

      if(null!==$this->m_parent)
        return $this->m_parent($type_);

      throw new Binding_Exception('inject/injector', sprintf(
        'No provider bound for given type [type: %1$s].', $type_
      ));
    }

    /**
     * Creates & appends an child injector for given binding module.
     *
     * <code>
     *   Foo_Binding_Module extends Binding_Module
     *   {
     *     protected function configure()
     *     {
     *       $this->bind('My_Foo')->to('My_Foo_Impl')->asSingleton();
     *     }
     *   }
     *
     *   Baa_Binding_Module extends Binding_Module
     *   {
     *     protected function configure()
     *     {
     *       $this->bind('My_Baa')->to('My_Baa_Impl')->asSingleton();
     *     }
     *   }
     *
     *   $fooInjector=Injector::create(new Foo_Binding_Module());
     *   $baaInjector=$fooInjector->createChild(new Baa_Binding_Module());
     *
     *   // Creates instance of My_Foo_Impl.
     *   $fooInjector->createInstance('My_Foo');
     *   // Throws Binding_Exception{Type not bound}.
     *   $fooInjector->createInstance('My_Baa');
     *
     *   // Creates instance of My_Foo_Impl.
     *   $baaInjector->createInstance('My_Foo');
     *   // Creates instance of My_Baa_Impl.
     *   $baaInjector->createInstance('My_Baa');
     * </code>
     *
     * @param Binding_Module
     *
     * @return Injector
     */
    public function createChild(Binding_Module $module_)
    {
      $injector=new self($module_);
      $injector->m_parent=$this;

      return $injector;
    }

    /**
     * Returns parent injector if one exists, otherwise null.
     *
     * <code>
     *   $a=Injector::create([Binding_Module]);
     *   $b=$aInjector->createChild([Binding_Module]);
     *
     *   $a===$b->getParent();
     * </code>
     *
     * @return Injector
     */
    public function getParent()
    {
      return $this->m_parent;
    }
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    public function hashCode()
    {
      return spl_object_hash($this);
    }

    public function equals($object_)
    {
      if($object_ instanceof self)
        return $this->hashCode()===$object_->hashCode;

      return false;
    }

    public function __toString()
    {
      return sprintf('%s@%s{module: %s}',
        __CLASS__,
        $this->hashCode(),
        $this->m_module
      );
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * Instance of root injector.
     *
     * @var Injector
     */
    private static $m_instance;
    /**
     * Indicates whether injector and binding module have been initialized.
     *
     * @var boolan
     */
    private static $m_initialized;
    /**
     * Local cache for known binding annotations.
     *
     * @var array|string
     */
    private $m_annotationCache=array();
    /**
     * Local cache for already initialized & injected instances.
     *
     * @var array|mixed
     */
    private $m_injectedInstances=array();
    /**
     * Binding module corresponding to this injector.
     *
     * @var Binding_Module
     */
    private $m_module;
    /**
     * Parent injector if available.
     *
     * @var Injector
     */
    private $m_parent;
    //-----


    /**
     * Inject dependencies into given instance.
     *
     * @param mixed $object_
     */
    private function injectMembersImpl($object_)
    {
      $object=new \ReflectionObject($object_);

      foreach(Annotations::get($object->name)->getPropertyAnnotations() as $propertyName=>$annotations)
      {
        if(false===isset($annotations[Annotation_Inject::NAME])
          || null===($binding=$this->getBindingForAnnotations($annotations)))
          continue;

        $property=$object->getProperty($propertyName);

        if(false===($public=$property->isPublic()))
          $property->setAccessible(true);

        if(($binding instanceof Binding_Type_Provider)
          && array_key_exists(Annotation_Binding_Provider::NAME, $annotations))
          $property->setValue($object_, $binding->getProvider());
        else
          $property->setValue($object_, $this->createInstanceImpl($binding));

        $property->setAccessible($public);
      }
    }

    /**
     * Resolves instance for given binding.
     *
     * @param Binding_Type_Abstract $binding_
     *
     * @return mixed
     */
    private function createInstanceImpl(Binding_Type_Abstract $binding_)
    {
      $instance=$binding_->getInstance();

      if($binding_->isPrimitive())
        return $instance;

      $instanceId=spl_object_hash($instance);
      if(isset($this->m_injectedInstances[$instanceId]))
        return $this->m_injectedInstances[$instanceId];

      $this->m_injectedInstances[$instanceId]=$instance;

      $this->injectMembersImpl($instance);

      return $instance;
    }

    /**
     * Resolves binding for given set of binding annotations.
     *
     * @param array|Binding_Annotation $annotations_
     *
     * @return Binding_Type_Abstract
     *
     * @throws Binding_Exception
     */
    private function getBindingForAnnotations(array $annotations_)
    {
      $type=$annotations_[Annotation_Inject::NAME]->value;

      if(isset($annotations_[Annotation_Named::NAME]))
        $name=$annotations_[Annotation_Named::NAME]->value;
      else
        $name=null;

      if(null!==($binding=$this->m_module->getBinding($type, $name)))
        return $binding;

      if(null!==$this->m_parent)
        return $this->m_parent->getBindingForAnnotations($annotations_);

      throw new Binding_Exception('inject/injector', sprintf(
        'Not bound [type: %1$s, name: %2$s].', $type, $name
      ));
    }


    // HELPERS
    /**
     * Registers binding annotations.
     */
    private static function initialize()
    {
      if(self::$m_initialized)
        return;

      Annotations::registerAnnotations(array(
        Annotation_Inject::NAME=>Annotation_Inject::TYPE,
        Annotation_Named::NAME=>Annotation_Named::TYPE,
        Annotation_Binding_Provider::NAME=>Annotation_Binding_Provider::TYPE
      ));

      self::$m_initialized=true;
    }
    //--------------------------------------------------------------------------
  }
?>
