<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7173298890712c7de5c14f6bfb5d6fe5
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'George\\Php\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'George\\Php\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit7173298890712c7de5c14f6bfb5d6fe5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7173298890712c7de5c14f6bfb5d6fe5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7173298890712c7de5c14f6bfb5d6fe5::$classMap;

        }, null, ClassLoader::class);
    }
}
