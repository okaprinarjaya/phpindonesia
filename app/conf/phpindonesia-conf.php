<?php
// This file generated by Propel 1.6.7 convert-conf target
// from XML runtime conf file /home/nurcahyo/public_html/phpid/runtime-conf.xml
$conf = array (
  'datasources' => 
  array (
    'dev_phpindonesia' => 
    array (
      'adapter' => 'mysql',
      'connection' => 
      array (
        'classname' => 'DebugPDO',
        'dsn' => 'mysql:host=localhost;dbname=dev_phpindonesia',
        'user' => 'dev',
        'password' => 'dev',
        'attributes' => 
        array (
          'ATTR_EMULATE_PREPARES' => 
          array (
            'value' => true,
          ),
        ),
        'settings' => 
        array (
          'charset' => 
          array (
            'value' => 'utf8',
          ),
        ),
      ),
    ),
    'default' => 'dev_phpindonesia',
  ),
  'debugpdo' => 
  array (
    'logging' => 
    array (
      'details' => 
      array (
        'method' => 
        array (
          'enabled' => true,
        ),
        'time' => 
        array (
          'enabled' => true,
          'precision' => '3',
        ),
        'mem' => 
        array (
          'enabled' => true,
          'precision' => '1',
        ),
      ),
    ),
  ),
  'generator_version' => '1.6.7',
);
$conf['classmap'] = include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classmap-phpindonesia-conf.php');
return $conf;