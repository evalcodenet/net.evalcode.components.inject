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
   * @subpackage inject.annotation
   *
   * @since 1.0
   * @access public
   *
   * @author Carsten Schipke <carsten.schipke@evalcode.net>
   * @copyright Copyright (C) 2012 evalcode.net
   * @license GNU General Public License 3
   *
   * @see Components.Annotation_Binding_Provider
   * @see Components.Annotation_Inject
   * @see Components.Annotation_Named
   */
  abstract class Annotation_Binding extends Annotation
  {

  }


  /**
   * Annotation_Binding_Provider
   *
   * @package net.evalcode.components
   * @subpackage inject.annotation
   *
   * @since 1.0
   * @access public
   *
   * @author Carsten Schipke <carsten.schipke@evalcode.net>
   * @copyright Copyright (C) 2012 evalcode.net
   * @license GNU General Public License 3
   */
  final class Annotation_Binding_Provider extends Annotation_Binding
  {
    // CONSTANTS
    /**
     * Provider
     *
     * @var string
     */
    const NAME='Provider';
    /**
     * Annotation_Binding_Provider
     *
     * @var string
     */
    const TYPE=__CLASS__;
    //--------------------------------------------------------------------------
  }
?>
