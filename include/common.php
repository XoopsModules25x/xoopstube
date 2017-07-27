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
 * @author          XOOPS Development Team, Irmtfan
 * @copyright       2001-2016 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org/
 * @since           1.0.6
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

require_once __DIR__ . '/config.php';

//define("XOOPSTUBE_DIRNAME", basename(dirname(__DIR__)));
//define("XOOPSTUBE_URL", XOOPS_URL . '/modules/' . XOOPSTUBE_DIRNAME);
//define("XOOPSTUBE_IMAGES_URL", XOOPSTUBE_URL . '/assets/images');
//define("XOOPSTUBE_ADMIN_URL", XOOPSTUBE_URL . '/admin');
//define("XOOPSTUBE_ROOT_PATH", XOOPS_ROOT_PATH . '/modules/' . XOOPSTUBE_DIRNAME);

//xoops_load("xoopsuserutility");
//xoops_load("XoopsCache");
//xoops_load("XoopsFile");

xoops_loadLanguage('common', XOOPSTUBE_DIRNAME);

require_once XOOPSTUBE_ROOT_PATH . '/class/utility.php';
//require_once XOOPSTUBE_ROOT_PATH . '/include/config.php';
require_once XOOPSTUBE_ROOT_PATH . '/class/session.php';
require_once XOOPSTUBE_ROOT_PATH . '/class/xoopstube.php';
require_once XOOPSTUBE_ROOT_PATH . '/class/utility.php';

//require_once XOOPSTUBE_ROOT_PATH . '/class/request.php';
//require_once XOOPSTUBE_ROOT_PATH . '/class/breadcrumb.php';

$debug     = false;
$xoopstube = XoopstubeXoopstube::getInstance($debug);

//This is needed or it will not work in blocks.
global $xtubeIsAdmin;

// Load only if module is installed
if (is_object($xoopstube->getModule())) {
    // Find if the user is admin of the module
    $xtubeIsAdmin = XoopstubeUtility::xtubeUserIsAdmin();
}
