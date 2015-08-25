<?php namespace Stoempsaucisse\LaravelBoilerplate;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

/**
 * ComposerScripts
 *
 */
class ComposerScripts {
    public static function cpArtisan(Event $event)
    {
        $vendor_dir = $event->getComposer()->getConfig()->get('vendor-dir');
        copy($vendor_dir . '/stoempsaucisse/laravel-boilerplate/src/artisan', substr($vendor_dir, 0, strrpos($vendor_dir, '/vendor')) . '/artisan');
        //var_dump(substr($vendor_dir, 0, strrpos($vendor_dir, '/vendor')) . '/');
    }
}
