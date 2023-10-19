<?php
namespace Composer;

$GLOBALS['_composer_bin_dir'] = __DIR__;
$GLOBALS['_composer_autoload_path'] = __DIR__ . '/..'.'/autoload.php';


return include __DIR__ . '/..'.'/google/cloud-functions-framework/router.php';
