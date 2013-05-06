<?php


namespace Components;


  /**
   * Binding_Provider
   *
   * <p>
   *   API for custom instance provider implementations.
   * </p>
   *
   * <code>
   *   My_Binding_Module extends Binding_Module
   *   {
   *     protected function configure()
   *     {
   *       $this->bind('Connection')
   *         ->toProvider('Connection_Provider')
   *         ->asSingleton();
   *     }
   *   }
   *
   *
   *   class Connection_Provider implements Binding_Provider
   *   {
   *     // ACCESSORS
   *     public function getConnections()
   *     {
   *       return $this->m_connections;
   *     }
   *
   *
   *     // OVERRIDES
   *     public static function getType()
   *     {
   *       return 'Connection_Sql';
   *     }
   *
   *     public function get()
   *     {
   *       $connection=new Connection_Sql();
   *       $connection->open();
   *
   *       array_push($this->m_connections, $connection);
   *
   *       return $connection;
   *     }
   *
   *
   *     // IMPLEMENTATION
   *     private $m_connections=array();
   *   }
   *
   *
   *   $injector=Injector::create(new My_Binding_Module());
   *
   *   // Inject multiple connections into your application and loose track of it...
   *   $a=$injector->createInstance('Connection');
   *   $b=$injector->createInstance('Connection');
   *   $c=$injector->createInstance('Connection');
   *
   *   // ... yet make sure to close them all at the end.
   *   foreach($injector->getProvider('Connection')->getConnections() as $connection)
   *     $connection->close();
   * </code>
   *
   * @package net.evalcode.components
   * @subpackage inject.binding
   *
   * @author evalcode.net
   */
  interface Binding_Provider
  {
    // STATIC ACCESSORS
    /**
     * Returns type of implementation for bound type.
     *
     * @return string
     */
    static function getType();
    //--------------------------------------------------------------------------


    // ACCESSORS
    /**
     * Returns instance of implementation for bound type.
     *
     * @return mixed
     */
    function get();
    //--------------------------------------------------------------------------
  }
?>
