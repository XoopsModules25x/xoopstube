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
 * @copyright       2001-2013 The XOOPS Project
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version         $Id$
 * @link            http://sourceforge.net/projects/xoops/
 * @since           1.0.6
 */

include dirname(dirname(__DIR__)) . '/mainfile.php';

$mydirname = basename(__DIR__);
$mydirpath = __DIR__;

include XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/include/config.php';
include XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/include/functions.php';
include XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/include/video.php';
include XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/sbookmarks.php';
include_once XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/class/class_thumbnail.php';
include_once XOOPS_ROOT_PATH . '/modules/xoopstube/class/xoopstubetree.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

if (!file_exists('language/' . $xoopsConfig['language'] . '/main.php')) {
    include __DIR__ . '/language/' . $xoopsConfig['language'] . '/main.php';
}

include_once XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/class/myts_extended.php';
$xtubemyts = new xtubeTextSanitizer(); // MyTextSanitizer object

global $xoopModuleConfig;
