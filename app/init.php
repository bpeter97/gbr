<?php
/**
 * These files must stay here and not be autoloaded.
 * Essentially these unofficially called globally loaded files.
 * Without these loading here, the autoloader will not know what to do.
 */
require_once('core/Settings.php');
require_once('core/Config.php');
require_once('core/lib/lightopenid.php');

// Make sure to not change $class_name. From the looks of it, its predefined
function __autoload($class_name) {
    $root = Config::get('site/root');
    $ds = Config::get('site/DS');
    $core_path = $root.$ds.'core'.$ds.$class_name.'.php';
    $controller_path = $root.$ds.'controllers'.$ds.$class_name.'.php';
    $model_path = $root.$ds.'models'.$ds.$class_name.'.php';

    if(file_exists($core_path)) {
        require_once($core_path);
    } else if(file_exists($controller_path)) {
        require_once($controller_path);
    } else if(file_exists($model_path)) {
        require_once($model_path);
    } else {
        echo "Failed to load class" . $core_path;
    }
}
