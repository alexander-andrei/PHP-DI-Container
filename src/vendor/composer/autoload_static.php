<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8197357c44d3b5c3354ed8d69b662745
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\Yaml\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8197357c44d3b5c3354ed8d69b662745::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8197357c44d3b5c3354ed8d69b662745::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
