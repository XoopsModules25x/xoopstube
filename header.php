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
use XoopsModules\Xoopstube;

include __DIR__ . '/../../mainfile.php';

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
$xtubemyts = new Xoopstube\TextSanitizer(); // MyTextSanitizer object

global $xoopModuleConfig;
