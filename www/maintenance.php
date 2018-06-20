<?php

/**
 * Check if guest has access
 */
$hasAccess = 0;
$keyName = 'access';
$queryAccess =  !empty($_GET[$keyName]) ? $_GET[$keyName] : null;

if ($queryAccess === '0') {
    setcookie($keyName, null, -1);
} elseif ($queryAccess) {
    setcookie($keyName, 1, 400000);
    $hasAccess = true;
}

$hasAccess = !empty($_COOKIE[$keyName]) || $hasAccess;

/**
 * Show dump page if project is now released
 *
 * Check for a NOT_RELEASED env variable
 */
if (isset($_SERVER['NOT_RELEASED']) && $_SERVER['NOT_RELEASED'] && !$hasAccess) {
    /**
     * Set default params
     */
    $dumpPageName = 'maintenance.html';
    $maintenancePath = 'public' . DIRECTORY_SEPARATOR . $dumpPageName;

    /**
     * Check for a project's maintenance file
     */
    $projectName = !empty($_SERVER['PROJECT']) ? $_SERVER['PROJECT']: '';
    if ($projectName) {
        $projectPath =  'projects' . DIRECTORY_SEPARATOR . $projectName . DIRECTORY_SEPARATOR;

        /**
         * If file exist then update path to maintenance file
         */
        if (file_exists(DOCROOT . $projectPath . $maintenancePath)) {
            $maintenancePath = $projectPath . $maintenancePath;
        }
    }

    /**
     * Return file by path from DOCROOT
     */
    include DOCROOT . $maintenancePath;
    die();
}
