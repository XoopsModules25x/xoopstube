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

//Xoopstube\Utility::prepareFolder(XOOPSTUBE_UPLOAD_PATH);
//Xoopstube\Utility::prepareFolder(XOOPSTUBE_ATTACHED_FILES_PATH);
//Xoopstube\Utility::prepareFolder(XOOPSTUBE_PICTURES_PATH);
//Xoopstube\Utility::prepareFolder(XOOPSTUBE_CSV_PATH);
//Xoopstube\Utility::prepareFolder(XOOPSTUBE_CACHE_PATH);
//Xoopstube\Utility::prepareFolder(XOOPSTUBE_TEXT_PATH);

$op = $op = Request::getCmd('op', Request::getCmd('op', '', 'POST'), 'GET'); //cleanRequestVars($_REQUEST, 'op', '');
//$cid = cleanRequestVars( $_REQUEST, 'cid', 0 );

switch (strtolower($op)) {
    case 'save':
        $indexheading = Request::getString('indexheading', '', 'POST'); //$xtubemyts->addslashes(xoops_trim($_REQUEST['indexheading']));
        $indexheader  = Request::getText('indexheader', '', 'POST'); //$xtubemyts->addslashes(xoops_trim($_REQUEST['indexheader']));
        $indexfooter  = Request::getText('indexfooter', '', 'POST'); //$xtubemyts->addslashes(xoops_trim($_REQUEST['indexfooter']));
        $indeximage   = Request::getString('indeximage', '', 'POST'); //$xtubemyts->addslashes($_REQUEST['indeximage']);

        $nohtml   = Request::getInt('nohtml', 0, 'POST'); //isset($_REQUEST['nohtml']) ? 1 : 0;
        $nosmiley = Request::getInt('nosmiley', 0, 'POST'); //isset($_REQUEST['nosmiley']) ? 1 : 0;
        $noxcodes = Request::getInt('noxcodes', 0, 'POST'); //isset($_REQUEST['noxcodes']) ? 1 : 0;
        $noimages = Request::getInt('noimages', 0, 'POST'); //isset($_REQUEST['noimages']) ? 1 : 0;
        $nobreak  = Request::getInt('nobreak', 0, 'POST'); //isset($_REQUEST['nobreak']) ? 1 : 0;

        $indexheaderalign = Request::getString('indexheaderalign', '', 'POST'); //$xtubemyts->addslashes($_REQUEST['indexheaderalign']);
        $indexfooteralign = Request::getString('indexfooteralign', '', 'POST'); //$xtubemyts->addslashes($_REQUEST['indexfooteralign']);
        $lastvideosyn     = Request::getInt('lastvideosyn', 0, 'POST'); //$_REQUEST['lastvideosyn'];
        $lastvideostotal  = Request::getString('lastvideostotal', '', 'POST'); //$xtubemyts->addslashes($_REQUEST['lastvideostotal']);
        $sql              = 'UPDATE '
                            . $GLOBALS['xoopsDB']->prefix('xoopstube_indexpage')
                            . " SET indexheading='$indexheading', indexheader='$indexheader', indexfooter='$indexfooter', indeximage='$indeximage', indexheaderalign='$indexheaderalign', indexfooteralign='$indexfooteralign', nohtml='$nohtml', nosmiley='$nosmiley', noxcodes='$noxcodes', noimages='$noimages', nobreak='$nobreak', lastvideosyn='$lastvideosyn', lastvideostotal='$lastvideostotal'";
        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            /** @var \XoopsLogger $logger */
            $logger = \XoopsLogger::getInstance();
            $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

            return false;
        }
        redirect_header('index.php', 1, _AM_XOOPSTUBE_IPAGE_UPDATED);
        break;

    default:
        $sql = 'SELECT indeximage, indexheading, indexheader, indexfooter, nohtml, nosmiley, noxcodes, noimages, nobreak, indexheaderalign, indexfooteralign, lastvideosyn, lastvideostotal FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_indexpage');
        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            /** @var \XoopsLogger $logger */
            $logger = \XoopsLogger::getInstance();
            $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

            return false;
        }
        list($indeximage, $indexheading, $indexheader, $indexfooter, $nohtml, $nosmiley, $noxcodes, $noimages, $nobreak, $indexheaderalign, $indexfooteralign, $lastvideosyn, $lastvideostotal) = $GLOBALS['xoopsDB']->fetchrow($result);

        xoops_cp_header();
        //renderAdminMenu( _AM_XOOPSTUBE_INDEXPAGE );
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        echo '
         <fieldset style="border: #e8e8e8 1px solid;">
         <legend style="display: inline; font-weight: bold; color: #0A3760;">' . _AM_XOOPSTUBE_IPAGE_INFORMATION . '</legend>
         <div style="padding: 8px;">
         <img src="' . XOOPS_URL . '/modules/' . $moduleDirName . '/assets/images/icon/indexpage.png" alt="" style="float: left; padding-right: 10px;">
         ' . _AM_XOOPSTUBE_MINDEX_PAGEINFOTXT . '</div>
         </fieldset><br>';

        $sform = new \XoopsThemeForm(_AM_XOOPSTUBE_IPAGE_MODIFY, 'op', xoops_getenv('PHP_SELF'), 'post', true);
        $sform->addElement(new \XoopsFormText(_AM_XOOPSTUBE_IPAGE_CTITLE, 'indexheading', 60, 60, $indexheading), false);
        $graph_array      = Xoopstube\Lists:: getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['mainimagedir'], $type = 'images');
        $indexImageSelect = new \XoopsFormSelect('', 'indeximage', $indeximage);
        $indexImageSelect->addOptionArray($graph_array);
        $indexImageSelect->setExtra("onchange='showImgSelected(\"image\", \"indeximage\", \"" . $GLOBALS['xoopsModuleConfig']['mainimagedir'] . '", "", "' . XOOPS_URL . "\")'");
        $indeximage_tray = new \XoopsFormElementTray(_AM_XOOPSTUBE_IPAGE_CIMAGE, '&nbsp;');
        $indeximage_tray->addElement($indexImageSelect);
        if (!empty($indeximage)) {
            $indeximage_tray->addElement(new \XoopsFormLabel('', '<br><br><img src="' . XOOPS_URL . '/' . $GLOBALS['xoopsModuleConfig']['mainimagedir'] . '/' . $indeximage . '" name="image" id="image" alt="">'));
        } else {
            $indeximage_tray->addElement(new \XoopsFormLabel('', '<br><br><img src="' . XOOPS_URL . '/uploads/blank.gif" name="image" id="image" alt="">'));
        }
        $sform->addElement($indeximage_tray);

        //        $editor = xtube_getWysiwygForm(_AM_XOOPSTUBE_IPAGE_CHEADING, 'indexheader', $indexheader, 15, 60, '');
        //        $sform->addElement($editor, false);

        $optionsTrayNote = new \XoopsFormElementTray(_AM_XOOPSTUBE_IPAGE_CHEADING, '<br>');
        if (class_exists('XoopsFormEditor')) {
            $options['name']   = 'indexheader';
            $options['value']  = $indexheader;
            $options['rows']   = 5;
            $options['cols']   = '100%';
            $options['width']  = '100%';
            $options['height'] = '200px';
            $editor            = new \XoopsFormEditor('', $GLOBALS['xoopsModuleConfig']['form_optionsuser'], $options, $nohtml = false, $onfailure = 'textarea');
            $optionsTrayNote->addElement($editor);
        } else {
            $editor = new \XoopsFormDhtmlTextArea('', 'indexheader', $item->getVar('indexheader', 'e'), '100%', '100%');
            $optionsTrayNote->addElement($editor);
        }

        $sform->addElement($optionsTrayNote, false);

        $headeralign_select = new \XoopsFormSelect(_AM_XOOPSTUBE_IPAGE_CHEADINGA, 'indexheaderalign', $indexheaderalign);
        $headeralign_select->addOptionArray([
                                                'left'   => _AM_XOOPSTUBE_IPAGE_CLEFT,
                                                'right'  => _AM_XOOPSTUBE_IPAGE_CRIGHT,
                                                'center' => _AM_XOOPSTUBE_IPAGE_CCENTER
                                            ]);
        $sform->addElement($headeralign_select);
        $sform->addElement(new \XoopsFormTextArea(_AM_XOOPSTUBE_IPAGE_CFOOTER, 'indexfooter', $indexfooter, 10, 60));
        $footeralign_select = new \XoopsFormSelect(_AM_XOOPSTUBE_IPAGE_CFOOTERA, 'indexfooteralign', $indexfooteralign);
        $footeralign_select->addOptionArray([
                                                'left'   => _AM_XOOPSTUBE_IPAGE_CLEFT,
                                                'right'  => _AM_XOOPSTUBE_IPAGE_CRIGHT,
                                                'center' => _AM_XOOPSTUBE_IPAGE_CCENTER
                                            ]);
        $sform->addElement($footeralign_select);

        $options_tray = new \XoopsFormElementTray(_AM_XOOPSTUBE_TEXTOPTIONS, '<br>');
        //html option
        $html_checkbox = new \XoopsFormCheckBox('', 'nohtml', $nohtml);
        $html_checkbox->addOption(1, _AM_XOOPSTUBE_DISABLEHTML);
        $options_tray->addElement($html_checkbox);
        //smiley option
        $smiley_checkbox = new \XoopsFormCheckBox('', 'nosmiley', $nosmiley);
        $smiley_checkbox->addOption(1, _AM_XOOPSTUBE_DISABLESMILEY);
        $options_tray->addElement($smiley_checkbox);
        //xcodes option
        $xcodes_checkbox = new \XoopsFormCheckBox('', 'noxcodes', $noxcodes);
        $xcodes_checkbox->addOption(1, _AM_XOOPSTUBE_DISABLEXCODE);
        $options_tray->addElement($xcodes_checkbox);
        //noimages option
        $noimages_checkbox = new \XoopsFormCheckBox('', 'noimages', $noimages);
        $noimages_checkbox->addOption(1, _AM_XOOPSTUBE_DISABLEIMAGES);
        $options_tray->addElement($noimages_checkbox);
        //breaks option
        $breaks_checkbox = new \XoopsFormCheckBox('', 'nobreak', $nobreak);
        $breaks_checkbox->addOption(1, _AM_XOOPSTUBE_DISABLEBREAK);
        $options_tray->addElement($breaks_checkbox);
        $sform->addElement($options_tray);

        $sform->addElement(new \XoopsFormRadioYN(_AM_XOOPSTUBE_IPAGE_SHOWLATEST, 'lastvideosyn', $lastvideosyn, ' ' . _YES . '', ' ' . _NO . ''));

        $lastvideostotalform = new \XoopsFormText(_AM_XOOPSTUBE_IPAGE_LATESTTOTAL, 'lastvideostotal', 2, 2, $lastvideostotal);
        $lastvideostotalform->setDescription('<span style="font-size: small;">' . _AM_XOOPSTUBE_IPAGE_LATESTTOTAL_DSC . '</span>');
        $sform->addElement($lastvideostotalform, false);

        $button_tray = new \XoopsFormElementTray('', '');
        $hidden      = new \XoopsFormHidden('op', 'save');
        $button_tray->addElement($hidden);
        $button_tray->addElement(new \XoopsFormButton('', 'post', _AM_XOOPSTUBE_BSAVE, 'submit'));
        $sform->addElement($button_tray);
        $sform->display();
        break;
}
require_once __DIR__ . '/admin_footer.php';
