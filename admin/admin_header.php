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

$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';
include_once $path . '/include/cp_functions.php';
require_once $path . '/include/cp_header.php';

include_once dirname(__DIR__) . '/include/common.php';

global $xoopsModule;

$moduleDirName  = $GLOBALS['xoopsModule']->getVar('dirname');
$thisModulePath = dirname(__DIR__);

//if functions.php file exist
//require_once dirname(__DIR__) . '/class/utilities.php';
//require_once $thisModulePath . '/class/utilities.php';

// Load language files
//xoops_loadLanguage('admin', $moduleDirName);
//xoops_loadLanguage('modinfo', $moduleDirName);
//xoops_loadLanguage('main', $moduleDirName);
xoops_loadLanguage('admin', XOOPSTUBE_DIRNAME);
xoops_loadLanguage('modinfo', XOOPSTUBE_DIRNAME);
xoops_loadLanguage('main', XOOPSTUBE_DIRNAME);
xoops_load('XoopsRequest');

$pathIcon16 = $GLOBALS['xoops']->url('www/' . $GLOBALS['xoopsModule']->getInfo('systemIcons16'));
$pathIcon32 = $GLOBALS['xoops']->url('www/' . $GLOBALS['xoopsModule']->getInfo('systemIcons32'));

$pathModuleAdmin = XOOPS_ROOT_PATH . '/' . $xoopsModule->getInfo('dirmoduleadmin');

require_once $pathModuleAdmin . '/moduleadmin.php';

//include $thisModulePath . '/include/config.php';
include_once $thisModulePath . '/class/utilities.php';
include_once $thisModulePath . '/include/video.php';
include_once $thisModulePath . '/class/xoopstube_lists.php';
include_once $thisModulePath . '/class/myts_extended.php';

include_once XOOPS_ROOT_PATH . '/modules/xoopstube/class/xoopstubetree.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$xtubemyts = new XtubeTextSanitizer(); // MyTextSanitizer object

$xtubeImageArray = array(
    'editimg'     => "<img src='$pathIcon16/edit.png' alt='" . _AM_XOOPSTUBE_ICO_EDIT . "' align='middle'>",
    'deleteimg'   => "<img src='$pathIcon16/delete.png' alt='" . _AM_XOOPSTUBE_ICO_DELETE . "' align='middle'>",
    'altcat'      => "<img src='$pathIcon16/folder_add.png' alt='" . _AM_XOOPSTUBE_ALTCAT_CREATEF . "' align='middle'>",
    'online'      => "<img src='$pathIcon16/1.png' alt='" . _AM_XOOPSTUBE_ICO_ONLINE . "' title='" . _AM_XOOPSTUBE_ICO_ONLINE . "' align='middle'>",
    'offline'     => "<img src='$pathIcon16/0.png' alt='" . _AM_XOOPSTUBE_ICO_OFFLINE . "' title='" . _AM_XOOPSTUBE_ICO_OFFLINE . "' align='middle'>",
    'approved'    => "<img src='$pathIcon16/1.png' alt=''" . _AM_XOOPSTUBE_ICO_APPROVED . "' align='middle'>",
    'notapproved' => "<img src='$pathIcon16/0.png' alt='" . _AM_XOOPSTUBE_ICO_NOTAPPROVED . "' align='middle'>",
    'relatedfaq'  => "<img src='../assets/images/icon/link.gif' alt='" . _AM_XOOPSTUBE_ICO_VIDEO . "' align='absmiddle'>",
    'expired'     => "<img src='../assets/images/icon/clock_red.png' alt='" . _AM_XOOPSTUBE_ICO_EXPIRE . "' title='" . _AM_XOOPSTUBE_ICO_EXPIRE . "' align='absmiddle'>",
    'approve'     => "<img src='$pathIcon16/1.png' alt='" . _AM_XOOPSTUBE_ICO_APPROVE . "' align='middle'>",
    'ignore'      => "<img src='$pathIcon16/0.png' alt='" . _AM_XOOPSTUBE_ICO_IGNORE . "' align='middle'>",
    'ack_yes'     => "<img src='$pathIcon16/1.png' alt='" . _AM_XOOPSTUBE_ICO_ACK . "' align='middle'>",
    'ack_no'      => "<img src='$pathIcon16/0.png' alt='" . _AM_XOOPSTUBE_ICO_REPORT . "' align='middle'>",
    'con_yes'     => "<img src='$pathIcon16/1.png' alt='" . _AM_XOOPSTUBE_ICO_CONFIRM . "' align='middle'>",
    'con_no'      => "<img src='$pathIcon16/0.png' alt='" . _AM_XOOPSTUBE_ICO_CONBROKEN . "' align='middle'>",
    'view'        => "<img src='$pathIcon16/search.png' alt='" . _AM_XOOPSTUBE_ICO_VIEW . "' align='middle'>"
);
