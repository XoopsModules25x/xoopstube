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

include dirname(dirname(__DIR__)) . '/mainfile.php';
xoops_load('XoopsRequest');

$moduleDirName = basename(__DIR__);
$modulePath    = __DIR__;

include XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/config.php';
include XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/utilities.php';
include XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/video.php';
include XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/sbookmarks.php';
include_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/class_thumbnail.php';
include_once XOOPS_ROOT_PATH . '/modules/xoopstube/class/xoopstubetree.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

if (!file_exists('language/' . $GLOBALS['xoopsConfig']['language'] . '/main.php')) {
    include __DIR__ . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/main.php';
}

include_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/myts_extended.php';
$xtubemyts = new XtubeTextSanitizer(); // MyTextSanitizer object

global $xoopModuleConfig;
