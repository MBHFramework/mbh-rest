<?php

/**
 * created by Ulises J. Cornejo Fandos in 08/06/2017
 */

spl_autoload_register("__kernel_autoload");
spl_autoload_register("__models_autoload");
spl_autoload_register("__functions_autoload");

function __kernel_autoload(string $exec)
{
  $exec = "src/kernel/$exec.php";
  if(is_readable($exec)) {
    require_once($exec);
  }
}

function __helpers_autoload(string $helper)
{
  $helper = "src/helpers/$helper.php";
  if(is_readable($helper)) {
    require_once($helper);
  }
}

function __model_autoload(string $model)
{
  $model = "src/models/$model.php";
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
