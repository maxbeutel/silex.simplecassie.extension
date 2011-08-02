Cassandra Extension for Silex
================================
This extension sets up the Simplecassie PHP client library for interfacing with Cassandra in Silex


Installation
------------

cd /path/to/silex_project/vendor
git clone https://github.com/mbeutel/silex.simplecassie.extension

Download Simplecassie include file from http://code.google.com/p/simpletools-php/wiki/SimpleCassie to /path/to/silex_project/vendor/SimpleCassie.php

Edit your index.php:

```php
$app['autoloader']->registerNamespace('Simplecassie', APPLICATION_PATH . '/vendor/Silex-extensions/simplecassie-cassandra-extension/lib');
$app->register(new Simplecassie\Extension\CassandraExtension(), array(
    'simplecassie.class_path'       => APPLICATION_PATH . '/vendor/SimpleCassie.php',   // set path to Simplecassie library classes here
    'simplecassie.host'             => 'localhost',                                     // cassandra host
    'simplecassie.port'             => 9160,                                            // cassandra port
    'simplecassie.timeout'          => 100,                                             // connection timeout
    'simplecassie.testConnection'   => true,                                            // check if connection is alive on request (true|false)
));
```

The Simplecassie client is then available via $app['simplecassie']

Tested with cassandra 0.8.2 and 0.7.1.6

Many thanks to Marcin Rosinski for his work on Simplecassie