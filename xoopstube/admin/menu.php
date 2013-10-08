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

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();

$i = 1;

$adminmenu[$i]["title"] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]["link"]  = "admin/index.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/home.png';
$i++;

$adminmenu[$i]["title"] = _MI_XTUBE_BINDEX;
$adminmenu[$i]["link"]  = "admin/main.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';
//$i++;
//$adminmenu[$i]['title'] = _MI_XTUBE_MCATEGORY;
//$adminmenu[$i]['link']  = "admin/category.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/category.png';
//$i++;
//$adminmenu[$i]['title'] = _MI_XTUBE_MVIDEOS;
//$adminmenu[$i]['link']  = "admin/xoopstube.php?op=edit";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/marquee.png';
$i++;
$adminmenu[$i]['title'] = _MI_XTUBE_SNEWFILESVAL;
$adminmenu[$i]['link']  = "admin/newvideos.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/add.png';
$i++;
$adminmenu[$i]['title'] = _MI_XTUBE_SMODREQUEST;
$adminmenu[$i]['link']  = "admin/modifications.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/update.png';
$i++;
$adminmenu[$i]['title'] = _MI_XTUBE_SBROKENSUBMIT;
$adminmenu[$i]['link']  = "admin/brokenvideo.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/brokenlink.png';
$i++;
$adminmenu[$i]['title'] = _MI_XTUBE_MUPLOADS;
$adminmenu[$i]['link']  = "admin/upload.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/photo.png';

$i++;
$adminmenu[$i]['title'] = _MI_XTUBE_VUPLOADS;
$adminmenu[$i]['link']  = "admin/vupload.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/marquee.png';
//$i++;
//$adminmenu[$i]['title'] = _MI_XTUBE_BLOCKADMIN;
//$adminmenu[$i]['link']  = 'admin/blocksadmin.php';
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/block.png';
$i++;
$adminmenu[$i]['title'] = _MI_XTUBE_PERMISSIONS;
$adminmenu[$i]['link']  = "admin/permissions.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/permissions.png';

$i++;
$adminmenu[$i]['title'] = _MI_XTUBE_MVOTEDATA;
$adminmenu[$i]['link']  = "admin/votedata.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/poll.png';
$i++;

$adminmenu[$i]["title"] = _MI_XTUBE_INDEXPAGE;
$adminmenu[$i]["link"]  = "admin/indexpage.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/index.png';

$i++;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';

//$i++;
//$adminmenu[$i]["title"] = _AM_XTUBE_ABOUT;
//$adminmenu[$i]["link"]  = "admin/about2.php";
//$adminmenu[$i]["icon"] = $pathIcon32.'/about.png';

//	$adminmenu[1]['title'] = _MI_XTUBE_BINDEX;
//	$adminmenu[1]['link']  = 'admin/index.php';
//
//	$adminmenu[2]['title'] = _MI_XTUBE_MVIDEOS;
//	$adminmenu[2]['link']  = 'admin/index.php?op=edit';
//
//	$adminmenu[3]['title'] = _MI_XTUBE_MCATEGORY;
//	$adminmenu[3]['link']  = 'admin/category.php';
//
//	$adminmenu[4]['title'] = _MI_XTUBE_INDEXPAGE;
//	$adminmenu[4]['link']  = 'admin/indexpage.php';
//
//	$adminmenu[5]['title'] = _MI_XTUBE_BLOCKADMIN;
//	$adminmenu[5]['link']  = 'admin/myblocksadmin.php';
//	$adminmenu[5]['options']  = 'images/icon/blocks.png';
