<?php
namespace Intern;

class AssetResolver {

    static $assets;

    public static function resolveJsPath($assetsFile, $entryPointName){
        if(!isset(self::$assets)){
            // Load the assets file into a string
            $assetsString = file_get_contents(PHPWS_SOURCE_DIR . 'mod/intern/' . $assetsFile);

            // Decode the JSON into objects
            self::$assets = json_decode($assetsString);
        }

        // Get the relative path of the entry point bundle we're looking for
        $relativePath = self::$assets->$entryPointName->js;

        // Change the relative path into an absolute URL
        $absolutePath = PHPWS_SOURCE_HTTP . '/mod/intern/' . $relativePath;

        return $absolutePath;
    }
}
