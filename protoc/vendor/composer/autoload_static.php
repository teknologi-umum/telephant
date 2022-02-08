<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit57968c0323ef3e2f739f7bc506920db4
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'Valian\\Protoc\\' => 14,
        ),
        'P' => 
        array (
            'Protobuf\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Valian\\Protoc\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Protobuf\\' => 
        array (
            0 => __DIR__ . '/..' . '/protobuf-php/protobuf/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit57968c0323ef3e2f739f7bc506920db4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit57968c0323ef3e2f739f7bc506920db4::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit57968c0323ef3e2f739f7bc506920db4::$classMap;

        }, null, ClassLoader::class);
    }
}
