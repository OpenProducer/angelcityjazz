<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd2aee4cb19ae5baca1e6ddff2ac4d14f
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitd2aee4cb19ae5baca1e6ddff2ac4d14f::$classMap;

        }, null, ClassLoader::class);
    }
}
