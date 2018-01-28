<?php
/**
 * XoopsTube - a multicategory video management module
 *
 * Based upon WF-Links
 *
 * File: admin/vupload.php
 *
 * @copyright        https://xoops.org/ XOOPS Project
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
 */

use Xmf\Request;
use XoopsModules\Xoopstube;

require_once __DIR__ . '/admin_header.php';

//$op       = (isset($_REQUEST['op']) && !empty($_REQUEST['op'])) ? $_REQUEST['op'] : '';
//$rootpath = (isset($_GET['rootpath'])) ? (int) $_GET['rootpath'] : 0;

$op       = Request::getCmd('op', Request::getCmd('op', '', 'POST'), 'GET');
$rootpath = Request::getInt('rootpath', 0, 'GET');

switch (strtolower($op)) {
    case 'vupload':
        if ('' !== $_FILES['uploadfile']['name']) {
            if (file_exists(XOOPS_ROOT_PATH . '/' . Request::getString('uploadpath', '', 'POST') . '/' . $_FILES['uploadfile']['name'])) {
                redirect_header('vupload.php', 2, _AM_XOOPSTUBE_VUPLOAD_VIDEOEXIST);
            }
            $allowed_mimetypes = 'media/aac, media/flv, media/mp3, media/mp4';
            Xoopstube\Utility::uploadFiles($_FILES, Request::getString('uploadpath', '', 'POST'), $allowed_mimetypes, 'vupload.php', 1, 0);
            redirect_header('vupload.php', 2, _AM_XOOPSTUBE_VUPLOAD_VIDEOUPLOAD);
        } else {
            redirect_header('vupload.php', 2, _AM_XOOPSTUBE_VUPLOAD_NOVIDEOEXIST);
        }
        break;

    case 'delfile':
        if (1 == Request::getInt('confirm', '', 'POST')) { //isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $filetodelete = XOOPS_ROOT_PATH . '/' . Request::getString('uploadpath', '', 'POST') . '/' . Request::getString('videofile', '', 'POST');
            if (file_exists($filetodelete)) {
                chmod($filetodelete, 0666);
                if (@unlink($filetodelete)) {
                    redirect_header('vupload.php', 1, _AM_XOOPSTUBE_VUPLOAD_FILEDELETED);
                } else {
                    redirect_header('vupload.php', 1, _AM_XOOPSTUBE_VUPLOAD_FILEERRORDELETE);
                }
            }
        } else {
            //            if (empty($_POST['videofile'])) {
            if (!Request::hasVar('videofile', 'POST')) {
                redirect_header('vupload.php', 1, _AM_XOOPSTUBE_VUPLOAD_NOFILEERROR);
            }
            xoops_cp_header();
            xoops_confirm([
                              'op'         => 'delfile',
                              'uploadpath' => Request::getString('uploadpath', '', 'POST'),
                              'videofile'  => Request::getString('videofile', '', 'POST'),
                              'confirm'    => 1
                          ], 'vupload.php', _AM_XOOPSTUBE_VUPLOAD_DELETEFILE . '<br><br>' . Request::getString('videofile', '', 'POST'), _AM_XOOPSTUBE_BDELETE);
        }
        break;

    case 'default':
    default:
        $displayimage = '';
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        $dirarray  = [1 => $GLOBALS['xoopsModuleConfig']['videodir']];
        $namearray = [1 => _AM_XOOPSTUBE_VUPLOAD_CATVIDEO];
        $listarray = [1 => _AM_XOOPSTUBE_VUPLOAD_FCATVIDEO];

        //renderAdminMenu( _AM_XOOPSTUBE_VUPLOADS );

        if ($rootpath > 0) {
            echo '<div><b>&nbsp;' . _AM_XOOPSTUBE_VUPLOAD_FUPLOADPATH . '</b> ' . XOOPS_ROOT_PATH . '/' . $dirarray[$rootpath] . '</div>';
            echo '<div><b>&nbsp;' . _AM_XOOPSTUBE_VUPLOAD_FUPLOADURL . '</b> ' . XOOPS_URL . '/' . $dirarray[$rootpath] . '</div><br>';
        }
        $pathlist = isset($listarray[$rootpath]) ? $namearray[$rootpath] : '';
        $namelist = isset($listarray[$rootpath]) ? $namearray[$rootpath] : '';

        $iform = new \XoopsThemeForm(_AM_XOOPSTUBE_VUPLOAD_FUPLOADVIDEOTO . $pathlist, 'op', xoops_getenv('PHP_SELF'), 'post', true);
        $iform->setExtra('enctype="multipart/form-data"');
        ob_start();
        $iform->addElement(new \XoopsFormHidden('dir', $rootpath));
        Xoopstube\Utility::getDirSelectOption($namelist, $dirarray, $namearray);
        $iform->addElement(new \XoopsFormLabel(_AM_XOOPSTUBE_VUPLOAD_FOLDERSELECTION, ob_get_contents()));
        ob_end_clean();

        if ($rootpath > 0) {
            $file_array       = Xoopstube\Lists:: getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $dirarray[$rootpath], $type = 'media');
            $indexfile_select = new \XoopsFormSelect('', 'videofile', '');
            $indexfile_select->addOptionArray($file_array);
            $indexfile_select->setExtra("onchange='showImgSelected(\"media\", \"videofile\", \"" . $dirarray[$rootpath] . '", "", "' . XOOPS_URL . "\")'");
            $indexfile_tray = new \XoopsFormElementTray(_AM_XOOPSTUBE_VUPLOAD_FSHOWSELECTEDFILE, '&nbsp;');
            $indexfile_tray->addElement($indexfile_select);
            if (!empty($imgurl)) {
                $indexfile_tray->addElement(new \XoopsFormLabel('', '<br><br><img src="' . XOOPS_URL . '/' . $dirarray[$rootpath] . '/' . $videofile . '" name="image" id="image" alt="">'));
            } else {
                $indexfile_tray->addElement(new \XoopsFormLabel('', '<br><br><img src="' . XOOPS_URL . '/uploads/blank.gif" name="image" id="image" alt="">'));
            }
            $iform->addElement($indexfile_tray);

            $file_tray = new \XoopsFormFile(_AM_XOOPSTUBE_VUPLOAD_FUPLOADVIDEO, 'uploadfile', 0);
            $file_tray->setDescription('<span style="font-size: small;">' . _AM_XOOPSTUBE_VUPLOAD_FSHOWSELECTEDFILEDSC . '</span>');
            $iform->addElement($file_tray);
            $iform->addElement(new \XoopsFormHidden('uploadpath', $dirarray[$rootpath]));
            $iform->addElement(new \XoopsFormHidden('rootnumber', $rootpath));

            $dup_tray = new \XoopsFormElementTray('', '');
            $dup_tray->addElement(new \XoopsFormHidden('op', 'vupload'));
            $butt_dup = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BUPLOAD, 'submit');
            $butt_dup->setExtra('onclick="this.form.elements.op.value=\'vupload\'"');
            $dup_tray->addElement($butt_dup);

            $butt_dupct = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BDELETEVIDEO, 'submit');
            $butt_dupct->setExtra('onclick="this.form.elements.op.value=\'delfile\'"');
            $dup_tray->addElement($butt_dupct);
            $iform->addElement($dup_tray);
        }
        $iform->display();
}
require_once __DIR__ . '/admin_footer.php';
