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
 * @copyright       2001-2016 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org/
 * @since           1.0.6
 */

use XoopsModules\Xoopstube;

require_once \dirname(__DIR__, 2) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
$modulePath    = __DIR__;

require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';
//require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/Utility.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/video.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/sbookmarks.php';
//require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/Thumbnails.php';
//require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/Tree.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

xoops_loadLanguage('main', $moduleDirName);

//require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/TextSanitizer.php';
$myts = \MyTextSanitizer::getInstance(); // MyTextSanitizer object

if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
    require_once $GLOBALS['xoops']->path('class/theme.php');
    $GLOBALS['xoTheme'] = new \xos_opal_Theme();
}

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new XoopsTpl();
}


//$GLOBALS['xoTheme']->addStylesheet('modules/' . $moduleDirName . '/assets/css/xtubestyle.css');

$GLOBALS['xoTheme']->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
//$xoTheme->addScript(XOOPSTUBE_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
//$xoTheme->addStylesheet(XOOPSTUBE_URL . '/assets/js/magnific/magnific-popup.css');
$GLOBALS['xoTheme']->addStylesheet(XOOPSTUBE_URL . '/assets/css/module.css');

require_once XOOPS_ROOT_PATH . '/header.php';

global $xoopModuleConfig;
