<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

 namespace Mbh;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class Helper
{
    /**
      * const ROUTE: Constant that indicates the route according to where the loader is called, from the REST API or from the Application
      *
      */
    const ROUTE = IS_API ? '../Mbh/Helpers/' : 'Mbh/Helpers/';

    /**
      * Static load a helper hosted in the kernel folder helpers for later use
      *
      * @param string $helper: Helper name
      *
      * @return void
      */
    final static public function load(string $helper, Twig_Environment $object = null)
    {
      $helper = ucwords($helper);
      $file = self::ROUTE . $helper . '.php';
      if(file_exists($file)) {
        include_once($file);
        # Twig integration
        if($object instanceof Twig_Environment) {
          $object->addExtension(new $helper());
        }
      } else {
        trigger_error('The helper ' . $helper . ' does not exist in the helpers library.', E_USER_ERROR);
      }
    }
}
