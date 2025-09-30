<?php

spl_autoload_register(function ($class) {
    //$class = str_replace("classes\\", "", $class); // namespace entfernen
    require_once 'classes/' . $class . '.class.php';


});
