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
 * @author          XOOPS Development Team, Irmtfan
 * @copyright       2001-2016 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org/
 * @since           1.0.6
 */

use Xoopsmodules\xoopstube;

$moduleDirName = basename(dirname(__DIR__));

require_once __DIR__ . '/../class/Helper.php';
require_once __DIR__ . '/../class/Utility.php';


if (!defined('XOOPSTUBE_MODULE_PATH')) {
    define('XOOPSTUBE_DIRNAME', basename(dirname(__DIR__)));
    define('XOOPSTUBE_URL', XOOPS_URL . '/modules/' . XOOPSTUBE_DIRNAME);
    define('XOOPSTUBE_IMAGE_URL', XOOPSTUBE_URL . '/assets/images/');
    define('XOOPSTUBE_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . XOOPSTUBE_DIRNAME);
    define('XOOPSTUBE_IMAGE_PATH', XOOPSTUBE_ROOT_PATH . '/assets/images');
    define('XOOPSTUBE_ADMIN_URL', XOOPSTUBE_URL . '/admin/');
    define('XOOPSTUBE_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . XOOPSTUBE_DIRNAME);
    define('XOOPSTUBE_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . XOOPSTUBE_DIRNAME);
    define('XOOPSTUBE_AUTHOR_LOGOIMG', XOOPSTUBE_URL . '/assets/images/logoModule.png');
}

require_once XOOPSTUBE_ROOT_PATH . '/class/Session.php';
require_once XOOPSTUBE_ROOT_PATH . '/class/XoopstubeVideos.php';

/** @var \XoopsDatabase $db */
/** @var xoopstube\Helper $helper */
/** @var xoopstube\Utility $utility */
$db           = \XoopsDatabaseFactory::getDatabase();
$helper       = xoopstube\Helper::getInstance();
$utility      = new xoopstube\Utility();
$configurator = new xoopstube\common\Configurator();

$helper->loadLanguage('common');

$helper = xoopstube\Helper::getInstance();

//This is needed or it will not work in blocks.
//global $xtubeIsAdmin;

// Load only if module is installed
if (is_object($helper->getModule())) {
    // Find if the user is admin of the module
    $xtubeIsAdmin = $helper->isUserAdmin();
}


//handlers
//$entriesHandler     = new xoopstube\EntriesHandler($db);
//$categoriesHandler     = new xoopstube\CategoriesHandler($db);

$pathIcon16    = Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon16 = $helper->getModule()->getInfo('modicons16');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$icons = [
    'edit'    => "<img src='" . $pathIcon16 . "/edit.png'  alt=" . _EDIT . "' align='middle'>",
    'delete'  => "<img src='" . $pathIcon16 . "/delete.png' alt='" . _DELETE . "' align='middle'>",
    'clone'   => "<img src='" . $pathIcon16 . "/editcopy.png' alt='" . _CLONE . "' align='middle'>",
    'preview' => "<img src='" . $pathIcon16 . "/view.png' alt='" . _PREVIEW . "' align='middle'>",
    'print'   => "<img src='" . $pathIcon16 . "/printer.png' alt='" . _CLONE . "' align='middle'>",
    'pdf'     => "<img src='" . $pathIcon16 . "/pdf.png' alt='" . _CLONE . "' align='middle'>",
    'add'     => "<img src='" . $pathIcon16 . "/add.png' alt='" . _ADD . "' align='middle'>",
    '0'       => "<img src='" . $pathIcon16 . "/0.png' alt='" . _ADD . "' align='middle'>",
    '1'       => "<img src='" . $pathIcon16 . "/1.png' alt='" . _ADD . "' align='middle'>",
];

// MyTextSanitizer object
$myts = \MyTextSanitizer::getInstance();

$debug = false;
