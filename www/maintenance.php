<?php

/**
 * Show dummy page if project is not released yet.
 * By default file public/maintenance.html will be shown.
 * If you set a project name in .env then script will try to return file
 * projects/<project_name>/public/maintenance.html.
 *
 * Use /?access=1 to open project and /?access=0 to remove cookie
 */

/**
 * Check if guest has access
 */
$hasAccess = false;
$keyName = 'access';

/**
 * Get 'access' param from query
 */
$queryAccess =  !empty($_GET[$keyName]) ? $_GET[$keyName] : null;

ob_start();

/** Remove access cookie */
if ($queryAccess === null) {
    setcookie($keyName, null, -1);

/** If query param is not set */
} elseif ($queryAccess) {
    setcookie($keyName, 1, time() + 60 * 60 * 24 * 5);
    $hasAccess = true;

/** If access cookie exists */
} elseif (!empty($_COOKIE[$keyName])) {
    $hasAccess = true;
}

ob_end_flush();

/**
 * Show dummy page if project is not released
 *
 * Check for a NOT_RELEASED env variable
 */
if (isset($_SERVER['NOT_RELEASED']) && $_SERVER['NOT_RELEASED'] && !$hasAccess) {
    /**
     * Set default params
     */
    $dummyPageName = 'maintenance.html';
    $maintenancePath = 'public' . DIRECTORY_SEPARATOR . $dummyPageName;

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
    echo file_get_contents(DOCROOT . $maintenancePath);
    die();
}
