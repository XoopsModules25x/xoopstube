<?php
/**
 * XoopsTube - a multicategory video management module
 *
 * Based upon WF-Links
 *
 * File: admin/vupload.php
 *
 * @copyright        http://www.xoops.org/ The XOOPS Project
 * @copyright        XOOPS_copyrights.txt
 * @copyright        http://www.impresscms.org/ The ImpressCMS Project
 * @license          GNU General Public License (GPL)
 *                   a copy of the GNU license is enclosed.
 * ----------------------------------------------------------------------------------------------------------
 * @package          WF-Links
 * @since            1.03
 * @author           John N
 * ----------------------------------------------------------------------------------------------------------
 *                   XoopsTube
 * @since            1.00
 * @author           McDonald
 * @version          $Id$
 */

include_once __DIR__ . '/admin_header.php';

global $xoopsModuleConfig;

$op       = (isset($_REQUEST['op']) && !empty($_REQUEST['op'])) ? $_REQUEST['op'] : '';
$rootpath = (isset($_GET['rootpath'])) ? intval($_GET['rootpath']) : 0;

switch (strtolower($op)) {
    case 'vupload':
        if ($_FILES['uploadfile']['name'] != '') {
            if (file_exists(XOOPS_ROOT_PATH . '/' . $_POST['uploadpath'] . '/' . $_FILES['uploadfile']['name'])) {
                redirect_header('vupload.php', 2, _AM_XOOPSTUBE_VUPLOAD_VIDEOEXIST);
            }
            $allowed_mimetypes = 'media/aac, media/flv, media/mp3, media/mp4';
            xtubeUploadFiles($_FILES, $_POST['uploadpath'], $allowed_mimetypes, 'vupload.php', 1, 0);
            redirect_header('vupload.php', 2, _AM_XOOPSTUBE_VUPLOAD_VIDEOUPLOAD);
            exit();
        } else {
            redirect_header('vupload.php', 2, _AM_XOOPSTUBE_VUPLOAD_NOVIDEOEXIST);
            exit();
        }
        break;

    case "delfile":
        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $filetodelete = XOOPS_ROOT_PATH . '/' . $_POST['uploadpath'] . '/' . $_POST['videofile'];
            if (file_exists($filetodelete)) {
                chmod($filetodelete, 0666);
                if (@unlink($filetodelete)) {
                    redirect_header('vupload.php', 1, _AM_XOOPSTUBE_VUPLOAD_FILEDELETED);
                } else {
                    redirect_header('vupload.php', 1, _AM_XOOPSTUBE_VUPLOAD_FILEERRORDELETE);
                }
            }
            exit();
        } else {
            if (empty($_POST['videofile'])) {
                redirect_header('vupload.php', 1, _AM_XOOPSTUBE_VUPLOAD_NOFILEERROR);
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
                'vupload.php',
                _AM_XOOPSTUBE_VUPLOAD_DELETEFILE . '<br /><br />' . $_POST['videofile'],
                _AM_XOOPSTUBE_BDELETE
            );
        }
        break;

    case 'default':
    default:
        $displayimage = '';
        xoops_cp_header();
        $aboutAdmin = new ModuleAdmin();
        echo $aboutAdmin->addNavigation('vupload.php');

        $dirarray  = array(1 => $xoopsModuleConfig['videodir']);
        $namearray = array(1 => _AM_XOOPSTUBE_VUPLOAD_CATVIDEO);
        $listarray = array(1 => _AM_XOOPSTUBE_VUPLOAD_FCATVIDEO);

        //xtubeRenderAdminMenu( _AM_XOOPSTUBE_VUPLOADS );

        if ($rootpath > 0) {
            echo '<div><b>&nbsp;' . _AM_XOOPSTUBE_VUPLOAD_FUPLOADPATH . '</b> ' . XOOPS_ROOT_PATH . '/' . $dirarray[$rootpath] . '</div>';
            echo '<div><b>&nbsp;' . _AM_XOOPSTUBE_VUPLOAD_FUPLOADURL . '</b> ' . XOOPS_URL . '/' . $dirarray[$rootpath] . '</div><br />';
        }
        $pathlist = (isset($listarray[$rootpath])) ? $namearray[$rootpath] : '';
        $namelist = (isset($listarray[$rootpath])) ? $namearray[$rootpath] : '';

        $iform = new XoopsThemeForm(_AM_XOOPSTUBE_VUPLOAD_FUPLOADVIDEOTO . $pathlist, 'op', xoops_getenv('PHP_SELF'));
        $iform->setExtra('enctype="multipart/form-data"');
        ob_start();
        $iform->addElement(new XoopsFormHidden('dir', $rootpath));
        xtubeVGetDirSelectOption($namelist, $dirarray, $namearray);
        $iform->addElement(new XoopsFormLabel(_AM_XOOPSTUBE_VUPLOAD_FOLDERSELECTION, ob_get_contents()));
        ob_end_clean();

        if ($rootpath > 0) {

            $file_array       = & XoopstubeLists :: getListTypeAsArray(
                XOOPS_ROOT_PATH . '/' . $dirarray[$rootpath],
                $type = 'media'
            );
            $indexfile_select = new XoopsFormSelect('', 'videofile', '');
            $indexfile_select->addOptionArray($file_array);
            $indexfile_select->setExtra(
                "onchange='showImgSelected(\"media\", \"videofile\", \"" . $dirarray[$rootpath] . "\", \"\", \"" . XOOPS_URL . "\")'"
            );
            $indexfile_tray = new XoopsFormElementTray(_AM_XOOPSTUBE_VUPLOAD_FSHOWSELECTEDFILE, '&nbsp;');
            $indexfile_tray->addElement($indexfile_select);
            if (!empty($imgurl)) {
                $indexfile_tray->addElement(
                    new XoopsFormLabel(
                        '', '<br /><br /><img src="' . XOOPS_URL . '/' . $dirarray[$rootpath] . '/' . $videofile . '" name="image" id="image" alt="" />'
                    )
                );
            } else {
                $indexfile_tray->addElement(
                    new XoopsFormLabel(
                        '', '<br /><br /><img src="' . XOOPS_URL . '/uploads/blank.gif" name="image" id="image" alt="" />'
                    )
                );
            }
            $iform->addElement($indexfile_tray);

            $file_tray = new XoopsFormFile(_AM_XOOPSTUBE_VUPLOAD_FUPLOADVIDEO, 'uploadfile', 0);
            $file_tray->setDescription(
                '<span style="font-size: small;">' . _AM_XOOPSTUBE_VUPLOAD_FSHOWSELECTEDFILEDSC . '</span>'
            );
            $iform->addElement($file_tray);
            $iform->addElement(new XoopsFormHidden('uploadpath', $dirarray[$rootpath]));
            $iform->addElement(new XoopsFormHidden('rootnumber', $rootpath));

            $dup_tray = new XoopsFormElementTray('', '');
            $dup_tray->addElement(new XoopsFormHidden('op', 'vupload'));
            $butt_dup = new XoopsFormButton('', '', _AM_XOOPSTUBE_BUPLOAD, 'submit');
            $butt_dup->setExtra('onclick="this.form.elements.op.value=\'vupload\'"');
            $dup_tray->addElement($butt_dup);

            $butt_dupct = new XoopsFormButton('', '', _AM_XOOPSTUBE_BDELETEVIDEO, 'submit');
            $butt_dupct->setExtra('onclick="this.form.elements.op.value=\'delfile\'"');
            $dup_tray->addElement($butt_dupct);
            $iform->addElement($dup_tray);
        }
        $iform->display();
}
include_once __DIR__ . '/admin_footer.php';
