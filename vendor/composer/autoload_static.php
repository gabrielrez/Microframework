<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitff7d7d9ee5c75a68a6aa5979034949bd
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Push\\Push\\' => 10,
        ),
        'C' => 
        array (
            'Core\\' => 5,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Push\\Push\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Core',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitff7d7d9ee5c75a68a6aa5979034949bd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitff7d7d9ee5c75a68a6aa5979034949bd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitff7d7d9ee5c75a68a6aa5979034949bd::$classMap;

        }, null, ClassLoader::class);
    }
}
