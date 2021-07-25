<?php

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author          XOOPS Development Team
 */

use Xmf\Module\Admin;
use XoopsModules\Xoopstube\{
    Helper
};
/** @var Admin $adminObject */
/** @var Helper $helper */

require dirname(__DIR__) . '/preloads/autoloader.php';

require dirname(__DIR__, 3) . '/include/cp_header.php';
require dirname(__DIR__, 3) . '/class/xoopsformloader.php';
require dirname(__DIR__, 3) . '/class/xoopslists.php';
require dirname(__DIR__) . '/include/common.php';
require dirname(__DIR__) . '/include/video.php';

$moduleDirName = \basename(\dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
$helper      = Helper::getInstance();

$adminObject = Admin::getInstance();

$pathIcon16    = Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon16 = $helper->getModule()->getInfo('modicons16');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');
$helper->loadLanguage('common');

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}


$myts = \MyTextSanitizer::getInstance(); // MyTextSanitizer object

$xtubeImageArray = [
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
    'view'        => "<img src='$pathIcon16/search.png' alt='" . _AM_XOOPSTUBE_ICO_VIEW . "' align='middle'>",
];
