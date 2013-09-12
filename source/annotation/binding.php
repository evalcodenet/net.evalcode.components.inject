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
   * @api
   * @package net.evalcode.components.inject
   * @subpackage annotation
   *
   * @author evalcode.net
   *
   * @see \Components\Annotation_Binding_Provider \Components\Annotation_Binding_Provider
   * @see \Components\Annotation_Inject \Components\Annotation_Inject
   * @see \Components\Annotation_Named \Components\Annotation_Named
   */
  abstract class Annotation_Binding extends Annotation
  {

  }


  /**
   * Annotation_Binding_Provider
   *
   * @api
   * @package net.evalcode.components.inject
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
