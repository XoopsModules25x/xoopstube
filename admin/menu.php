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

use Xmf\Module\Admin;
use XoopsModules\Xoopstube\{
    Helper
};
/** @var Admin $adminObject */
/** @var Helper $helper */


include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
$helper = Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_BINDEX,
    'link'  => 'admin/main.php',
    'icon'  => $pathIcon32 . '/manage.png',
];

//$adminmenu[] = [
//'title' =>  _MI_XOOPSTUBE_MCATEGORY,
//'link' =>  "admin/category.php",
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/category.png';
//];

//$adminmenu[] = [
//'title' =>  _MI_XOOPSTUBE_MVIDEOS,
//'link' => edit",
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/marquee.png';
//];

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_SNEWFILESVAL,
    'link'  => 'admin/newvideos.php',
    'icon'  => $pathIcon32 . '/add.png',
];

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_SMODREQUEST,
    'link'  => 'admin/modifications.php',
    'icon'  => $pathIcon32 . '/update.png',
];

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_SBROKENSUBMIT,
    'link'  => 'admin/brokenvideo.php',
    'icon'  => $pathIcon32 . '/link_break.png',
];

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_MUPLOADS,
    'link'  => 'admin/upload.php',
    'icon'  => $pathIcon32 . '/photo.png',
];

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_VUPLOADS,
    'link'  => 'admin/vupload.php',
    'icon'  => $pathIcon32 . '/marquee.png',
];

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_PERMISSIONS,
    'link'  => 'admin/permissions.php',
    'icon'  => $pathIcon32 . '/permissions.png',
];

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_BLOCKADMIN,
    'link'  => 'admin/blocksadmin.php',
    'icon'  => $pathIcon32 . '/block.png',
];

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_MVOTEDATA,
    'link'  => 'admin/votedata.php',
    'icon'  => $pathIcon32 . '/poll.png',
];

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_INDEXPAGE,
    'link'  => 'admin/indexpage.php',
    'icon'  => $pathIcon32 . '/index.png',
];

if (is_object($helper->getModule()) && $helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link' => 'admin/migrate.php',
        'icon' => $pathIcon32 . '/database_go.png',
    ];
}

$adminmenu[] = [
    'title' => _MI_XOOPSTUBE_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];
