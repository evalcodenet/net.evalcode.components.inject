<?php


namespace Components;


  // DECLARES
  // - Annotation_Binding
  // - Annotation_Binding_Provider


  /**
   * Annotation_Binding
   *
   * <p>
   *   Root binding annotation
   * </p>
   *
   * @package net.evalcode.components
   * @subpackage annotation
   *
   * @author evalcode.net
   *
   * @see Components\Annotation_Binding_Provider
   * @see Components\Annotation_Inject
   * @see Components\Annotation_Named
   */
  abstract class Annotation_Binding extends Annotation
  {

  }


  /**
   * Annotation_Binding_Provider
   *
   * @package net.evalcode.components
   * @subpackage annotation
   *
   * @author evalcode.net
   */
  final class Annotation_Binding_Provider extends Annotation_Binding
  {
    // PREDEFINED PROPERTIES
    /**
     * provider
     *
     * @var string
     */
    const NAME='provider';
    /**
     * Annotation_Binding_Provider
     *
     * @var string
     */
    const TYPE=__CLASS__;
    //--------------------------------------------------------------------------
  }
?>
