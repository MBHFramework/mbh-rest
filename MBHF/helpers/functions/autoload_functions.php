<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

spl_autoload_register("__kernel_autoload");
spl_autoload_register("__models_autoload");
spl_autoload_register("__functions_autoload");

function __kernel_autoload(string $exec)
{
  $exec = "MBHF/kernel/$exec.php";
  if(is_readable($exec)) {
    require_once($exec);
  }
}

function __helpers_autoload(string $helper)
{
  $helper = "MBHF/helpers/$helper.php";
  if(is_readable($helper)) {
    require_once($helper);
  }
}

function __model_autoload(string $model)
{
  $model = "MBHF/models/$model.php";
  if(is_readable($model)) {
    require_once($model);
  }
}

function __functions_autoload(array $helpers)
{
  foreach ($helpers as $helper) {
      __helpers_autoload($helper);
  }
}

function __models_autoload(array $models)
{
  foreach ($models as $model) {
    __model_autoload($model);
  }
}
