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

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';
include_once $path . '/include/cp_functions.php';
require_once $path . '/include/cp_header.php';

include_once dirname(dirname(__FILE__)) . '/include/common.php';

global $xoopsModule;

$thisModuleDir  = $GLOBALS['xoopsModule']->getVar('dirname');
$thisModulePath = dirname(dirname(__FILE__));

//if functions.php file exist
//require_once dirname(dirname(__FILE__)) . '/include/functions.php';
//require_once $thisModulePath . '/include/functions.php';

// Load language files
//xoops_loadLanguage('admin', $thisModuleDir);
//xoops_loadLanguage('modinfo', $thisModuleDir);
//xoops_loadLanguage('main', $thisModuleDir);
xoops_loadLanguage('admin', XTUBE_DIRNAME);
xoops_loadLanguage('modinfo', XTUBE_DIRNAME);
xoops_loadLanguage('main', XTUBE_DIRNAME);

$pathIcon16      = '../' . $xoopsModule->getInfo('icons16');
$pathIcon32      = '../' . $xoopsModule->getInfo('icons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

require_once $GLOBALS['xoops']->path($pathModuleAdmin . '/moduleadmin.php');

include $thisModulePath . '/include/config.php';
include_once $thisModulePath . '/include/functions.php';
include_once $thisModulePath . '/include/video.php';
include_once $thisModulePath . '/class/xtube_lists.php';
include_once $thisModulePath . '/class/myts_extended.php';

include_once XOOPS_ROOT_PATH . '/modules/xoopstube/class/xoopstubetree.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$xtubemyts = new xtubeTextSanitizer(); // MyTextSanitizer object

$xtubeImageArray = array(
    'editimg'     => "<img src='$pathIcon16/edit.png' alt='" . _AM_XTUBE_ICO_EDIT . "' align='middle'>",
    'deleteimg'   => "<img src='$pathIcon16/delete.png' alt='" . _AM_XTUBE_ICO_DELETE . "' align='middle'>",
    'altcat'      => "<img src='$pathIcon16/folder_add.png' alt='" . _AM_XTUBE_ALTCAT_CREATEF . "' align='middle'>",
    'online'      => "<img src='$pathIcon16/1.png' alt='" . _AM_XTUBE_ICO_ONLINE . "' title='" . _AM_XTUBE_ICO_ONLINE    . "' align='middle'>",
    'offline'     => "<img src='$pathIcon16/0.png' alt='" . _AM_XTUBE_ICO_OFFLINE . "' title='" . _AM_XTUBE_ICO_OFFLINE    . "' align='middle'>",
    'approved'    => "<img src='$pathIcon16/1.png' alt=''" . _AM_XTUBE_ICO_APPROVED . "' align='middle'>",
    'notapproved' => "<img src='$pathIcon16/0.png' alt='" . _AM_XTUBE_ICO_NOTAPPROVED . "' align='middle'>",
    'relatedfaq'  => "<img src='../images/icon/link.gif' alt='" . _AM_XTUBE_ICO_VIDEO . "' align='absmiddle'>",
    'expired'     => "<img src='../images/icon/clock_red.png' alt='" . _AM_XTUBE_ICO_EXPIRE . "' title='" . _AM_XTUBE_ICO_EXPIRE    . "' align='absmiddle'>",
    'approve'     => "<img src='$pathIcon16/1.png' alt='" . _AM_XTUBE_ICO_APPROVE . "' align='middle'>",
    'ignore'      => "<img src='$pathIcon16/0.png' alt='" . _AM_XTUBE_ICO_IGNORE . "' align='middle'>",
    'ack_yes'     => "<img src='$pathIcon16/1.png' alt='" . _AM_XTUBE_ICO_ACK . "' align='middle'>",
    'ack_no'      => "<img src='$pathIcon16/0.png' alt='" . _AM_XTUBE_ICO_REPORT . "' align='middle'>",
    'con_yes'     => "<img src='$pathIcon16/1.png' alt='" . _AM_XTUBE_ICO_CONFIRM . "' align='middle'>",
    'con_no'      => "<img src='$pathIcon16/0.png' alt='" . _AM_XTUBE_ICO_CONBROKEN . "' align='middle'>",
    'view'        => "<img src='$pathIcon16/search.png' alt='" . _AM_XTUBE_ICO_VIEW . "' align='middle'>"
);