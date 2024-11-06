<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf4cead1933f3093bde001c79e4a1ae58
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Core\\Repositories\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Core\\Repositories\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf4cead1933f3093bde001c79e4a1ae58::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf4cead1933f3093bde001c79e4a1ae58::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf4cead1933f3093bde001c79e4a1ae58::$classMap;

        }, null, ClassLoader::class);
    }
}