<?php
/**
 * Module: XoopsTube
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * PHP version 5
 *
 * @category        Module
 * @package         Xoopstube
 * @author          XOOPS Development Team
 * @copyright       2001-2016 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link            http://xoops.org/
 * @since           1.0.6
 */

// WARNING: ONCE SET DO NOT CHANGE! Improper use will render this module useless and unworkable.
// Only Change if you know what you are doing.

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

$moduleDirName = basename(dirname(__DIR__));

//These are names of the current tables

if (!defined('xoopstube_broken')) {
    define('xoopstube_broken', 'xoopstube_broken');
}
if (!defined('xoopstube_cat')) {
    define('xoopstube_cat', 'xoopstube_cat');
}
if (!defined('xoopstube_videos')) {
    define('xoopstube_videos', 'xoopstube_videos');
}
if (!defined('xoopstube_mod')) {
    define('xoopstube_mod', 'xoopstube_mod');
}
if (!defined('xoopstube_votedata')) {
    define('xoopstube_votedata', 'xoopstube_votedata');
}
if (!defined('xoopstube_indexpage')) {
    define('xoopstube_indexpage', 'xoopstube_indexpage');
}
if (!defined('xoopstube_altcat')) {
    define('xoopstube_altcat', 'xoopstube_altcat');
}
if (!defined('xoopstube_url')) {
    define('xoopstube_url', XOOPS_URL . '/modules/' . $moduleDirName . '/');
}

if (!defined('XOOPSTUBE_MODULE_PATH')) {
    //    define("XOOPSTUBE_DIRNAME", $GLOBALS['xoopsModule']->dirname());
    define('XOOPSTUBE_DIRNAME', $moduleDirName);
    define('XOOPSTUBE_PATH', XOOPS_ROOT_PATH . '/modules/' . XOOPSTUBE_DIRNAME);
    define('XOOPSTUBE_URL', XOOPS_URL . '/modules/' . XOOPSTUBE_DIRNAME);
    define('XOOPSTUBE_IMAGES_URL', XOOPSTUBE_URL . '/assets/images');
    define('XOOPSTUBE_ADMIN_URL', XOOPSTUBE_URL . '/admin');
    define('XOOPSTUBE_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . XOOPSTUBE_DIRNAME);
    define('XOOPSTUBE_AUTHOR_LOGOIMG', XOOPSTUBE_URL . '/assets/images/logo_module.png');
}

// Define here the place where main upload path

//$moduleUploadsPath = $GLOBALS['xoopsModuleConfig']['uploaddir'];

define('XOOPSTUBE_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . XOOPSTUBE_DIRNAME); // WITHOUT Trailing slash
//define("XOOPSTUBE_UPLOAD_PATH", $img_dir); // WITHOUT Trailing slash
define('XOOPSTUBE_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . XOOPSTUBE_DIRNAME); // WITHOUT Trailing slash

$uploadFolders = array(
    XOOPSTUBE_UPLOAD_PATH,
    XOOPSTUBE_UPLOAD_PATH . '/category',
    XOOPSTUBE_UPLOAD_PATH . '/videos',
    XOOPSTUBE_UPLOAD_PATH . '/screenshots'
);
