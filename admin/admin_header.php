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

use XoopsModules\Xoopstube;

require_once __DIR__ . '/../../../include/cp_header.php';
//require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');

// require_once __DIR__ . '/../class/Utility.php';
require_once __DIR__ . '/../include/common.php';

$moduleDirName = basename(dirname(__DIR__));
$helper = Xoopstube\Helper::getInstance();
$adminObject = Xmf\Module\Admin::getInstance();

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

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}

//include $moduleDirName . '/include/config.php';
// require_once __DIR__ . '/../class/Utility.php';
require_once __DIR__ . '/../include/video.php';
// require_once __DIR__ . '/../class/Lists.php';
// require_once __DIR__ . '/../class/TextSanitizer.php';
// require_once __DIR__ . '/../class/Tree.php';

require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$xtubemyts = new Xoopstube\TextSanitizer(); // MyTextSanitizer object

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
    'view'        => "<img src='$pathIcon16/search.png' alt='" . _AM_XOOPSTUBE_ICO_VIEW . "' align='middle'>"
];
