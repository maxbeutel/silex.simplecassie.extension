<?php

namespace Simplecassie\Extension;

use Silex\Application;
use Silex\ExtensionInterface;
use RuntimeException;
use InvalidArgumentException;
use SimpleCassie;

class CassandraExtension implements ExtensionInterface
{
    const DEFAULT_HOST = 'localhost';
    const DEFAULT_PORT = 9160;
    const DEFAULT_TIMEOUT = 200;
    
    public function __construct()
    {
    }
    
    public function register(Application $app) 
    {
        if (!isset($app['simplecassie.path'])) {
            throw new InvalidArgumentException('Path to Simplecassie include file must be set');
        }
        
        require_once $app['simplecassie.path'];
        
        $cassie = new SimpleCassie(
            isset($app['simplecassie.host'])    ? $app['simplecassie.host']    : self::DEFAULT_HOST, 
            isset($app['simplecassie.port'])    ? $app['simplecassie.port']    : self::DEFAULT_PORT, 
            isset($app['simplecassie.timeout']) ? $app['simplecassie.timeout'] : self::DEFAULT_TIMEOUT
        );
        
        if (isset($app['simplecassie.failoverNodes']) && is_array($app['simplecassie.failoverNodes'])) {
            foreach ($app['simplecassie.failoverNodes'] as $failoverNodeConfig) {
                $cassie->addNode(
                    isset($failoverNodeConfig['host'])    ? $failoverNodeConfig['host']    : self::DEFAULT_HOST,
                    isset($failoverNodeConfig['port'])    ? $failoverNodeConfig['port']    : self::DEFAULT_PORT,
                    isset($failoverNodeConfig['timeout']) ? $failoverNodeConfig['timeout'] : self::DEFAULT_TIMEOUT 
                );
            }
        }
        
        $testConnection = isset($app['simplecassie.testConnection']) ? (bool)$app['simplecassie.testConnection'] : false;
        
        $app['simplecassie'] = $app->share(function() use($cassie, $testConnection) {
            if ($testConnection && !$cassie->isConnected()) {
                throw new RuntimeException('Could not connect to cassandra instance');
            }
            
            return $cassie;
        });
    }
}