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

include 'admin_header.php';

global $xoopsModuleConfig;

$op       = (isset($_REQUEST['op']) && !empty($_REQUEST['op'])) ? $_REQUEST['op'] : '';
$rootpath = (isset($_GET['rootpath'])) ? intval($_GET['rootpath']) : 0;

switch (strtolower($op)) {
    case 'upload':
        if ($_FILES['uploadfile']['name'] != '') {
            if (file_exists(XOOPS_ROOT_PATH . '/' . $_POST['uploadpath'] . '/' . $_FILES['uploadfile']['name'])) {
                redirect_header('upload.php', 2, _AM_XTUBE_VIDEO_IMAGEEXIST);
            }
            $allowed_mimetypes = array(
                'image/gif',
                'image/jpeg',
                'image/pjpeg',
                'image/x-png',
                'image/png',
                'media/flv'
            );
            xtubeUploadFiles($_FILES, $_POST['uploadpath'], $allowed_mimetypes, "upload.php", 1, 0);
            redirect_header('upload.php', 2, _AM_XTUBE_VIDEO_IMAGEUPLOAD);
            exit();
        } else {
            redirect_header('upload.php', 2, _AM_XTUBE_VIDEO_NOIMAGEEXIST);
            exit();
        }
        break;

    case 'delfile':

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $filetodelete = XOOPS_ROOT_PATH . '/' . $_POST['uploadpath'] . '/' . $_POST['videofile'];
            if (file_exists($filetodelete)) {
                chmod($filetodelete, 0666);
                if (@unlink($filetodelete)) {
                    redirect_header('upload.php', 1, _AM_XTUBE_VIDEO_FILEDELETED);
                } else {
                    redirect_header('upload.php', 1, _AM_XTUBE_VIDEO_FILEERRORDELETE);
                }
            }
            exit();
        } else {
            if (empty($_POST['videofile'])) {
                redirect_header('upload.php', 1, _AM_XTUBE_VIDEO_NOFILEERROR);
                exit();
            }
            xoops_cp_header();
            xoops_confirm(
                array(
                     'op'         => 'delfile',
                     'uploadpath' => $_POST['uploadpath'],
                     'videofile'  => $_POST['videofile'],
                     'confirm'    => 1
                ),
                'upload.php',
                _AM_XTUBE_VIDEO_DELETEFILE . "<br /><br />" . $_POST['videofile'],
                _AM_XTUBE_BDELETE
            );
        }
        break;

    case 'default':
    default:
        $displayimage = '';
        xoops_cp_header();

        $dirarray  = array(
            1 => $xoopsModuleConfig['catimage'],
            2 => $xoopsModuleConfig['mainimagedir'],
            3 => $xoopsModuleConfig['videoimgdir']
        );
        $namearray = array(
            1 => _AM_XTUBE_VIDEO_CATIMAGE,
            2 => _AM_XTUBE_VIDEO_MAINIMAGEDIR,
            3 => _AM_XTUBE_VIDEO_CATVIDEOIMG
        );
        $listarray = array(
            1 => _AM_XTUBE_VIDEO_FCATIMAGE,
            2 => _AM_XTUBE_VIDEO_FMAINIMAGEDIR,
            3 => _AM_XTUBE_VIDEO_FCATVIDEOIMG
        );

//    $dirarray  = array(
//        1 => $xoopsModuleConfig['catimage'],
//        2 => $xoopsModuleConfig['mainimagedir']
//    );
//    $namearray = array(
//        1 => _AM_XTUBE_VIDEO_CATIMAGE,
//        2 => _AM_XTUBE_VIDEO_MAINIMAGEDIR
//    );
//    $listarray = array(
//        1 => _AM_XTUBE_VIDEO_FCATIMAGE,
//        2 => _AM_XTUBE_VIDEO_FMAINIMAGEDIR
//    );

        //xtubeRenderAdminMenu( _AM_XTUBE_MUPLOADS );
        xtubeGetServerStatistics();
        if ($rootpath > 0) {
            echo '<div><b>' . _AM_XTUBE_VIDEO_FUPLOADPATH . '</b> ' . XOOPS_ROOT_PATH . '/' . $dirarray[$rootpath]
                 . '</div>';
            echo '<div><b>' . _AM_XTUBE_VIDEO_FUPLOADURL . '</b> ' . XOOPS_URL . '/' . $dirarray[$rootpath]
                 . '</div><br />';
        }
        $pathlist = (isset($listarray[$rootpath])) ? $namearray[$rootpath] : '';
        $namelist = (isset($listarray[$rootpath])) ? $namearray[$rootpath] : '';

        $iform = new XoopsThemeForm(_AM_XTUBE_VIDEO_FUPLOADIMAGETO . $pathlist, "op", xoops_getenv('PHP_SELF'));
        $iform->setExtra('enctype="multipart/form-data"');
        ob_start();
        $iform->addElement(new XoopsFormHidden('dir', $rootpath));
        xtubeGetDirSelectOption($namelist, $dirarray, $namearray);
        $iform->addElement(new XoopsFormLabel(_AM_XTUBE_VIDEO_FOLDERSELECTION, ob_get_contents()));
        ob_end_clean();

        if ($rootpath > 0) {
            $graph_array       = & xtubeLists :: getListTypeAsArray(
                XOOPS_ROOT_PATH . '/' . $dirarray[$rootpath],
                $type = 'images'
            );
            $indeximage_select = new XoopsFormSelect('', 'videofile', '');
            $indeximage_select->addOptionArray($graph_array);
            $indeximage_select->setExtra(
                "onchange='showImgSelected(\"image\", \"videofile\", \"" . $dirarray[$rootpath] . "\", \"\", \""
                . XOOPS_URL . "\")'"
            );
            $indeximage_tray = new XoopsFormElementTray(_AM_XTUBE_VIDEO_FSHOWSELECTEDIMAGE, '&nbsp;');
            $indeximage_tray->addElement($indeximage_select);
            if (!empty($imgurl)) {
                $indeximage_tray->addElement(
                    new XoopsFormLabel('',
                        '<br /><br /><img src="' . XOOPS_URL . '/' . $dirarray[$rootpath] . '/' . $videofile
                        . '" name="image" id="image" alt"" />')
                );
            } else {
                $indeximage_tray->addElement(
                    new XoopsFormLabel('',
                        '<br /><br /><img src="' . XOOPS_URL . '/uploads/blank.gif" name="image" id="image" alt="" />')
                );
            }
            $iform->addElement($indeximage_tray);

            $iform->addElement(new XoopsFormFile(_AM_XTUBE_VIDEO_FUPLOADIMAGE, 'uploadfile', 0));
            $iform->addElement(new XoopsFormHidden('uploadpath', $dirarray[$rootpath]));
            $iform->addElement(new XoopsFormHidden('rootnumber', $rootpath));

            $dup_tray = new XoopsFormElementTray('', '');
            $dup_tray->addElement(new XoopsFormHidden('op', 'upload'));
            $butt_dup = new XoopsFormButton('', '', _AM_XTUBE_BUPLOAD, 'submit');
            $butt_dup->setExtra('onclick="this.form.elements.op.value=\'upload\'"');
            $dup_tray->addElement($butt_dup);

            $butt_dupct = new XoopsFormButton('', '', _AM_XTUBE_BDELETEIMAGE, 'submit');
            $butt_dupct->setExtra('onclick="this.form.elements.op.value=\'delfile\'"');
            $dup_tray->addElement($butt_dupct);
            $iform->addElement($dup_tray);
        }
        $iform->display();
}
include 'admin_footer.php';
