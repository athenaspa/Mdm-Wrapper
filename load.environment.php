<?php
/**
 * This file is included very early. See autoload.files in composer.json and
 * https://getcomposer.org/doc/04-schema.md#files
 */
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;
use Composer\Autoload\ClassLoader;
/**
 * Load the .env file. See /.env.dist.
 */
$dotenv = new Dotenv();
try {
    $reflector = new \ReflectionClass(ClassLoader::class);
    $vendorPath = preg_replace('/^(.*)\/composer\/ClassLoader\.php$/', '$1', $reflector->getFileName() );
    if($vendorPath && is_dir($vendorPath)) {
        $dotenv->load($vendorPath . '/../.env');
    }
} catch (PathException $e) {
    // Do nothing.
} catch (ReflectionException $e) {
    // Do nothing.
}