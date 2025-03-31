<?php

if (!defined('_PS_VERSION_'))
  exit;

function upgrade_module_5_2_0($object)
{
  return $object->upgradeTo5_2_0();
}