<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

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
