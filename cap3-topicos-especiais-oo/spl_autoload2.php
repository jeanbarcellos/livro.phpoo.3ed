<?php

class LibraryLoader {
    public static function loadClass($class) {
        if (file_exists("Lib/{$class}.php")) {
            require_once("Lib/{$class}.php");
            return TRUE;
        }
    }
}

class ApplicationLoader {
    public static function loadClass($class) {
        if (file_exists("App/{$class}.php")) {
            require_once("App/{$class}.php");
            return TRUE;
        }
    }
}



