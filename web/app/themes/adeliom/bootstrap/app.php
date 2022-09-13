<?php

declare(strict_types=1);

use App\Exceptions\Handler;
use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Exceptions\HandlerInterface;

$autoloader = static function ($prefix, $baseDir): void {
    /**
     * An example of a project-specific implementation.
     *
     * After registering this autoload function with SPL, the following line
     * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
     * from /path/to/project/src/Baz/Qux.php:
     *
     *      new \Foo\Bar\Baz\Qux;
     *
     * @param string $class The fully-qualified class name.
     *
     * @return void
     */
    spl_autoload_register(static function ($class) use ($prefix, $baseDir): void {
        // does the class use the namespace prefix?
        $len = strlen($prefix);

        if (strncmp($prefix, $class, $len) !== 0) {
            // no, move to the next registered autoloader
            return;
        }

        // get the relative class name
        $relativeClass = substr($class, $len);

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        // if the file exists, require it
        if (file_exists($file)) {
            require $file;
        }
    });
};

$autoloader('App\\', dirname(__DIR__) . '/app/');

$app = new Application(dirname(__DIR__));
$app->singleton(HandlerInterface::class, Handler::class);

return $app;
