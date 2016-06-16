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

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$moduleDirName = basename(dirname(__DIR__));

$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$pathIcon32    = '../../' . $module->getInfo('sysIcons32');
$pathModIcon32 = './' . $module->getInfo('modIcons32');
xoops_loadLanguage('modinfo', $module->dirname());

$xoopsModuleAdminPath = XOOPS_ROOT_PATH . '/' . $module->getInfo('dirmoduleadmin');
if (!file_exists($fileinc = $xoopsModuleAdminPath . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $xoopsModuleAdminPath . '/language/english/main.php';
}
include_once $fileinc;

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png'
);

$adminmenu[] = array(
    'title' => _MI_XOOPSTUBE_BINDEX,
    'link'  => 'admin/main.php',
    'icon'  => $pathIcon32 . '/manage.png'
);

//++$i;
//$adminmenu[$i]['title'] = _MI_XOOPSTUBE_MCATEGORY;
//$adminmenu[$i]['link']  = "admin/category.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/category.png';
//++$i;
//$adminmenu[$i]['title'] = _MI_XOOPSTUBE_MVIDEOS;
//$adminmenu[$i]['link']  = "admin/xoopstube.php?op=edit";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/marquee.png';

$adminmenu[] = array(
    'title' => _MI_XOOPSTUBE_SNEWFILESVAL,
    'link'  => 'admin/newvideos.php',
    'icon'  => $pathIcon32 . '/add.png'
);

$adminmenu[] = array(
    'title' => _MI_XOOPSTUBE_SMODREQUEST,
    'link'  => 'admin/modifications.php',
    'icon'  => $pathIcon32 . '/update.png'
);

$adminmenu[] = array(
    'title' => _MI_XOOPSTUBE_SBROKENSUBMIT,
    'link'  => 'admin/brokenvideo.php',
    'icon'  => $pathIcon32 . '/link_break.png'
);

$adminmenu[] = array(
    'title' => _MI_XOOPSTUBE_MUPLOADS,
    'link'  => 'admin/upload.php',
    'icon'  => $pathIcon32 . '/photo.png'
);

$adminmenu[] = array(
    'title' => _MI_XOOPSTUBE_VUPLOADS,
    'link'  => 'admin/vupload.php',
    'icon'  => $pathIcon32 . '/marquee.png'
);

$adminmenu[] = array(
    'title' => _MI_XOOPSTUBE_PERMISSIONS,
    'link'  => 'admin/permissions.php',
    'icon'  => $pathIcon32 . '/permissions.png'
);

$adminmenu[] = array(
    'title' => _MI_XOOPSTUBE_MVOTEDATA,
    'link'  => 'admin/votedata.php',
    'icon'  => $pathIcon32 . '/poll.png'
);

$adminmenu[] = array(
    'title' => _MI_XOOPSTUBE_INDEXPAGE,
    'link'  => 'admin/indexpage.php',
    'icon'  => $pathIcon32 . '/index.png'
);

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
);

//++$i;
//$adminmenu[$i]['title'] = _MI_XOOPSTUBE_BLOCKADMIN;
//$adminmenu[$i]['link']  = 'admin/blocksadmin.php';
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/block.png';
