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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org/
 * @since           1.0.6
 */
use Xmf\Request;

include __DIR__ . '/../../mainfile.php';

$moduleDirName = basename(__DIR__);
$modulePath    = __DIR__;

require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/config.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/utility.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/video.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/sbookmarks.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/class_thumbnail.php';
require_once XOOPS_ROOT_PATH . '/modules/xoopstube/class/xoopstubetree.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

xoops_loadLanguage('main', $moduleDirName);

require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/myts_extended.php';
$xtubemyts = new XtubeTextSanitizer(); // MyTextSanitizer object

global $xoopModuleConfig;
