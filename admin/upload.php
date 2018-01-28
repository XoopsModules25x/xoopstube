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

require_once __DIR__ . '/admin_header.php';

//$op       = (isset($_REQUEST['op']) && !empty($_REQUEST['op'])) ? $_REQUEST['op'] : '';
//$rootpath = (isset($_GET['rootpath'])) ? (int) $_GET['rootpath'] : 0;

$op       = Request::getCmd('op', Request::getCmd('op', '', 'POST'), 'GET');
$rootpath = Request::getInt('rootpath', 0, 'GET');

switch (strtolower($op)) {
    case 'upload':
        if ('' !== $_FILES['uploadfile']['name']) {
            if (file_exists(XOOPS_ROOT_PATH . '/' . Request::getString('uploadpath', '', 'POST') . '/' . $_FILES['uploadfile']['name'])) {
                redirect_header('upload.php', 2, _AM_XOOPSTUBE_VIDEO_IMAGEEXIST);
            }
            $allowed_mimetypes = [
                'image/gif',
                'image/jpeg',
                'image/pjpeg',
                'image/x-png',
                'image/png',
                'media/flv'
            ];
            Xoopstube\Utility::uploadFiles($_FILES, Request::getString('uploadpath', '', 'POST'), $allowed_mimetypes, 'upload.php', 1, 0);
            redirect_header('upload.php', 2, _AM_XOOPSTUBE_VIDEO_IMAGEUPLOAD);
        } else {
            redirect_header('upload.php', 2, _AM_XOOPSTUBE_VIDEO_NOIMAGEEXIST);
        }
        break;

    case 'delfile':

        if (1 === Request::getInt('confirm', '', 'POST')) { // isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $filetodelete = XOOPS_ROOT_PATH . '/' . Request::getString('uploadpath', '', 'POST') . '/' . Request::getString('videofile', '', 'POST');
            if (file_exists($filetodelete)) {
                chmod($filetodelete, 0666);
                if (@unlink($filetodelete)) {
                    redirect_header('upload.php', 1, _AM_XOOPSTUBE_VIDEO_FILEDELETED);
                } else {
                    redirect_header('upload.php', 1, _AM_XOOPSTUBE_VIDEO_FILEERRORDELETE);
                }
            }
        } else {
            //            if (empty($_POST['videofile'])) {
            if (!Request::hasVar('videofile', 'POST')) {
                redirect_header('upload.php', 1, _AM_XOOPSTUBE_VIDEO_NOFILEERROR);
            }
            xoops_cp_header();
            xoops_confirm([
                              'op'         => 'delfile',
                              'uploadpath' => Request::getString('uploadpath', '', 'POST'),
                              'videofile'  => Request::getString('videofile', '', 'POST'),
                              'confirm'    => 1
                          ], 'upload.php', _AM_XOOPSTUBE_VIDEO_DELETEFILE . '<br><br>' . Request::getString('videofile', '', 'POST'), _AM_XOOPSTUBE_BDELETE);
        }
        break;

    case 'default':
    default:
        $displayimage = '';
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        $dirarray  = [
            1 => $GLOBALS['xoopsModuleConfig']['catimage'],
            2 => $GLOBALS['xoopsModuleConfig']['mainimagedir'],
            3 => $GLOBALS['xoopsModuleConfig']['videoimgdir']
        ];
        $namearray = [
            1 => _AM_XOOPSTUBE_VIDEO_CATIMAGE,
            2 => _AM_XOOPSTUBE_VIDEO_MAINIMAGEDIR,
            3 => _AM_XOOPSTUBE_VIDEO_CATVIDEOIMG
        ];
        $listarray = [
            1 => _AM_XOOPSTUBE_VIDEO_FCATIMAGE,
            2 => _AM_XOOPSTUBE_VIDEO_FMAINIMAGEDIR,
            3 => _AM_XOOPSTUBE_VIDEO_FCATVIDEOIMG
        ];

        //    $dirarray  = array(
        //        1 => $GLOBALS['xoopsModuleConfig']['catimage'],
        //        2 => $GLOBALS['xoopsModuleConfig']['mainimagedir']
        //    );
        //    $namearray = array(
        //        1 => _AM_XOOPSTUBE_VIDEO_CATIMAGE,
        //        2 => _AM_XOOPSTUBE_VIDEO_MAINIMAGEDIR
        //    );
        //    $listarray = array(
        //        1 => _AM_XOOPSTUBE_VIDEO_FCATIMAGE,
        //        2 => _AM_XOOPSTUBE_VIDEO_FMAINIMAGEDIR
        //    );

        //renderAdminMenu( _AM_XOOPSTUBE_MUPLOADS );
        Xoopstube\Utility::getServerStatistics();
        if ($rootpath > 0) {
            echo '<div><b>' . _AM_XOOPSTUBE_VIDEO_FUPLOADPATH . '</b> ' . XOOPS_ROOT_PATH . '/' . $dirarray[$rootpath] . '</div>';
            echo '<div><b>' . _AM_XOOPSTUBE_VIDEO_FUPLOADURL . '</b> ' . XOOPS_URL . '/' . $dirarray[$rootpath] . '</div><br>';
        }
        $pathlist = isset($listarray[$rootpath]) ? $namearray[$rootpath] : '';
        $namelist = isset($listarray[$rootpath]) ? $namearray[$rootpath] : '';

        $iform = new \XoopsThemeForm(_AM_XOOPSTUBE_VIDEO_FUPLOADIMAGETO . $pathlist, 'op', xoops_getenv('PHP_SELF'), 'post', true);
        $iform->setExtra('enctype="multipart/form-data"');
        ob_start();
        $iform->addElement(new \XoopsFormHidden('dir', $rootpath));
        Xoopstube\Utility::getDirSelectOption($namelist, $dirarray, $namearray);
        $iform->addElement(new \XoopsFormLabel(_AM_XOOPSTUBE_VIDEO_FOLDERSELECTION, ob_get_contents()));
        ob_end_clean();

        if ($rootpath > 0) {
            $graph_array      = Xoopstube\Lists:: getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $dirarray[$rootpath], $type = 'images');
            $indexImageSelect = new \XoopsFormSelect('', 'videofile', '');
            $indexImageSelect->addOptionArray($graph_array);
            $indexImageSelect->setExtra("onchange='showImgSelected(\"image\", \"videofile\", \"" . $dirarray[$rootpath] . '", "", "' . XOOPS_URL . "\")'");
            $indeximage_tray = new \XoopsFormElementTray(_AM_XOOPSTUBE_VIDEO_FSHOWSELECTEDIMAGE, '&nbsp;');
            $indeximage_tray->addElement($indexImageSelect);
            if (!empty($imgurl)) {
                $indeximage_tray->addElement(new \XoopsFormLabel('', '<br><br><img src="' . XOOPS_URL . '/' . $dirarray[$rootpath] . '/' . $videofile . '" name="image" id="image" alt"">'));
            } else {
                $indeximage_tray->addElement(new \XoopsFormLabel('', '<br><br><img src="' . XOOPS_URL . '/uploads/blank.gif" name="image" id="image" alt="">'));
            }
            $iform->addElement($indeximage_tray);

            $iform->addElement(new \XoopsFormFile(_AM_XOOPSTUBE_VIDEO_FUPLOADIMAGE, 'uploadfile', 0));
            $iform->addElement(new \XoopsFormHidden('uploadpath', $dirarray[$rootpath]));
            $iform->addElement(new \XoopsFormHidden('rootnumber', $rootpath));

            $dup_tray = new \XoopsFormElementTray('', '');
            $dup_tray->addElement(new \XoopsFormHidden('op', 'upload'));
            $butt_dup = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BUPLOAD, 'submit');
            $butt_dup->setExtra('onclick="this.form.elements.op.value=\'upload\'"');
            $dup_tray->addElement($butt_dup);

            $butt_dupct = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BDELETEIMAGE, 'submit');
            $butt_dupct->setExtra('onclick="this.form.elements.op.value=\'delfile\'"');
            $dup_tray->addElement($butt_dupct);
            $iform->addElement($dup_tray);
        }
        $iform->display();
}
require_once __DIR__ . '/admin_footer.php';
