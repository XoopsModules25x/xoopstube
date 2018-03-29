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
 * @Module          XoopsTube
 * @Release         Date: 21 June 2005
 * @Developer       John N
 * @Team            XOOPS Development Team
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */

use Xmf\Request;
use XoopsModules\Xoopstube;

require_once __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

$op = '';

if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}

if (isset($_GET)) {
    foreach ($_GET as $k => $v) {
        ${$k} = $v;
    }
}

/**
 * @param int $cid
 */
function createCategory($cid = 0)
{
    // require_once __DIR__ . '/../class/xoopstube_lists.php';
    //    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    global $xtubemyts, $totalcats, $xoopsModule;

    $lid          = 0;
    $title        = '';
    $imgurl       = '';
    $description  = '';
    $pid          = '';
    $weight       = 0;
    $nohtml       = 0;
    $nosmiley     = 0;
    $noxcodes     = 0;
    $noimages     = 0;
    $nobreak      = 1;
    $spotlighttop = 0;
    $spotlighthis = 0;
    $client_id    = 0;
    $banner_id    = 0;
    $heading      = _AM_XOOPSTUBE_CCATEGORY_CREATENEW;
    $totalcats    = Xoopstube\Utility::getTotalCategoryCount();

    if ($cid > 0) {
        $sql          = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' WHERE cid=' . (int)$cid;
        $cat_arr      = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));
        $title        = $xtubemyts->htmlSpecialChars($cat_arr['title']);
        $imgurl       = $xtubemyts->htmlSpecialChars($cat_arr['imgurl']);
        $description  = $xtubemyts->htmlSpecialChars($cat_arr['description']);
        $nohtml       = (int)$cat_arr['nohtml'];
        $nosmiley     = (int)$cat_arr['nosmiley'];
        $noxcodes     = (int)$cat_arr['noxcodes'];
        $noimages     = (int)$cat_arr['noimages'];
        $nobreak      = (int)$cat_arr['nobreak'];
        $spotlighthis = (int)$cat_arr['spotlighthis'];
        $spotlighttop = (int)$cat_arr['spotlighttop'];
        $weight       = $cat_arr['weight'];
        $client_id    = $cat_arr['client_id'];
        $banner_id    = $cat_arr['banner_id'];
        $heading      = _AM_XOOPSTUBE_CCATEGORY_MODIFY;

        $memberHandler = xoops_getHandler('member');
        $group_list    = $memberHandler->getGroupList();

        $gpermHandler = xoops_getHandler('groupperm');
        $groups       = $gpermHandler->getGroupIds('XTubeCatPerm', $cid, $xoopsModule->getVar('mid'));
    //        $groups        = $groups;
    } else {
        $groups = true;
    }
    echo '<br><br>';
    $sform = new \XoopsThemeForm($heading, 'op', xoops_getenv('PHP_SELF'), 'post', true);
    $sform->setExtra('enctype="multipart/form-data"');

    $sform->addElement(new \XoopsFormText(_AM_XOOPSTUBE_FCATEGORY_TITLE, 'title', 50, 80, $title), true);
    $sform->addElement(new \XoopsFormText(_AM_XOOPSTUBE_FCATEGORY_WEIGHT, 'weight', 10, 80, $weight), false);

    if ($totalcats > 0 && $cid) {
        $mytreechose = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');
        ob_start();
        $mytreechose->makeMySelBox('title', 'title', $cat_arr['pid'], 1, 'pid');
        $sform->addElement(new \XoopsFormLabel(_AM_XOOPSTUBE_FCATEGORY_SUBCATEGORY, ob_get_contents()));
        ob_end_clean();
    } else {
        $mytreechose = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');
        ob_start();
        $mytreechose->makeMySelBox('title', 'title', $cid, 1, 'pid');
        $sform->addElement(new \XoopsFormLabel(_AM_XOOPSTUBE_FCATEGORY_SUBCATEGORY, ob_get_contents()));
        ob_end_clean();
    }

    $graph_array      = Xoopstube\Lists::getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['catimage'], $type = 'images');
    $indexImageSelect = new \XoopsFormSelect('', 'imgurl', $imgurl);
    $indexImageSelect->addOptionArray($graph_array);
    $indexImageSelect->setExtra("onchange='showImgSelected(\"image\", \"imgurl\", \"" . $GLOBALS['xoopsModuleConfig']['catimage'] . '", "", "' . XOOPS_URL . "\")'");
    $indeximage_tray = new \XoopsFormElementTray(_AM_XOOPSTUBE_FCATEGORY_CIMAGE, '&nbsp;');
    $indeximage_tray->addElement($indexImageSelect);
    if ('' !== $imgurl && 1 != $imgurl) {
        $indeximage_tray->addElement(new \XoopsFormLabel('', "<br><br><img src='" . XOOPS_URL . '/' . $GLOBALS['xoopsModuleConfig']['catimage'] . '/' . $imgurl . "' name='image' id='image' alt=''>"));
    } else {
        $indeximage_tray->addElement(new \XoopsFormLabel('', "<br><br><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt=''>"));
    }
    $sform->addElement($indeximage_tray);

    //    $editor = xtube_getWysiwygForm(_AM_XOOPSTUBE_FCATEGORY_DESCRIPTION, 'description', $description, 15, 60, '');
    //    $sform->addElement($editor, false);

    $optionsTrayNote = new \XoopsFormElementTray(_AM_XOOPSTUBE_FCATEGORY_DESCRIPTION, '<br>');
    if (class_exists('XoopsFormEditor')) {
        $options['name']   = 'description';
        $options['value']  = $description;
        $options['rows']   = 5;
        $options['cols']   = '100%';
        $options['width']  = '100%';
        $options['height'] = '200px';
        $editor            = new \XoopsFormEditor('', $GLOBALS['xoopsModuleConfig']['form_optionsuser'], $options, $nohtml = false, $onfailure = 'textarea');
        $optionsTrayNote->addElement($editor);
    } else {
        $editor = new \XoopsFormDhtmlTextArea('', 'description', $item->getVar('description', 'e'), '100%', '100%');
        $optionsTrayNote->addElement($editor);
    }

    $sform->addElement($optionsTrayNote, false);

    // Select Client/Sponsor
    $client_select   = new \XoopsFormSelect(_AM_XOOPSTUBE_CATSPONSOR, 'client_id', $client_id, false);
    $sql             = 'SELECT cid, name FROM ' . $GLOBALS['xoopsDB']->prefix('bannerclient') . ' ORDER BY name ASC';
    $result          = $GLOBALS['xoopsDB']->query($sql);
    $client_array    = [];
    $client_array[0] = '&nbsp;';
    while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $client_array[$myrow['cid']] = $myrow['name'];
    }
    $client_select->addOptionArray($client_array);

    $client_select->setDescription(_AM_XOOPSTUBE_CATSPONSORDSC);
    $sform->addElement($client_select);

    // Select Banner
    $banner_select   = new \XoopsFormSelect(_AM_XOOPSTUBE_BANNERID, 'banner_id', $banner_id, false);
    $sql             = 'SELECT bid, cid FROM ' . $GLOBALS['xoopsDB']->prefix('banner') . ' ORDER BY bid ASC';
    $result          = $GLOBALS['xoopsDB']->query($sql);
    $banner_array    = [];
    $banner_array[0] = '&nbsp;';
    while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $banner_array[$myrow['bid']] = $myrow['bid'];
    }
    $banner_select->addOptionArray($banner_array);
    $banner_select->setDescription(_AM_XOOPSTUBE_BANNERIDDSC);
    $sform->addElement($banner_select);

    $options_tray = new \XoopsFormElementTray(_AM_XOOPSTUBE_TEXTOPTIONS, '<br>');

    $html_checkbox = new \XoopsFormCheckBox('', 'nohtml', $nohtml);
    $html_checkbox->addOption(1, _AM_XOOPSTUBE_DISABLEHTML);
    $options_tray->addElement($html_checkbox);

    $smiley_checkbox = new \XoopsFormCheckBox('', 'nosmiley', $nosmiley);
    $smiley_checkbox->addOption(1, _AM_XOOPSTUBE_DISABLESMILEY);
    $options_tray->addElement($smiley_checkbox);

    $xcodes_checkbox = new \XoopsFormCheckBox('', 'noxcodes', $noxcodes);
    $xcodes_checkbox->addOption(1, _AM_XOOPSTUBE_DISABLEXCODE);
    $options_tray->addElement($xcodes_checkbox);

    $noimages_checkbox = new \XoopsFormCheckBox('', 'noimages', $noimages);
    $noimages_checkbox->addOption(1, _AM_XOOPSTUBE_DISABLEIMAGES);
    $options_tray->addElement($noimages_checkbox);

    $breaks_checkbox = new \XoopsFormCheckBox('', 'nobreak', $nobreak);
    $breaks_checkbox->addOption(1, _AM_XOOPSTUBE_DISABLEBREAK);
    $options_tray->addElement($breaks_checkbox);
    $sform->addElement($options_tray);

    //    $sform -> addElement(new \XoopsFormSelectGroup(_AM_XOOPSTUBE_FCATEGORY_GROUPPROMPT, "groups", true, $groups, 5, true));

    $sform->addElement(new \XoopsFormHidden('cid', (int)$cid));

    $sform->addElement(new \XoopsFormHidden('spotlighttop', (int)$cid));

    $button_tray = new \XoopsFormElementTray('', '');
    $hidden      = new \XoopsFormHidden('op', 'save');
    $button_tray->addElement($hidden);

    if (!$cid) {
        $butt_create = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BSAVE, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addCat\'"');
        $button_tray->addElement($butt_create);

        $butt_clear = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BRESET, 'reset');
        $button_tray->addElement($butt_clear);

        $butt_cancel = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BCANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);
    } else {
        $butt_create = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BMODIFY, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addCat\'"');
        $button_tray->addElement($butt_create);

        $butt_delete = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BDELETE, 'submit');
        $butt_delete->setExtra('onclick="this.form.elements.op.value=\'del\'"');
        $button_tray->addElement($butt_delete);

        $butt_cancel = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BCANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);
    }
    $sform->addElement($button_tray);
    $sform->display();

    $result2 = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat'));
    list($numrows) = $GLOBALS['xoopsDB']->fetchRow($result2);
}

/*
if (!isset($_POST['op'])) {
    $op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
    $op = isset($_POST['op']) ? $_POST['op'] : 'main';
}
*/

$op = Request::getString('op', Request::getString('op', 'main', 'POST'), 'GET');

switch ($op) {
    case 'move':
        if (!Request::hasVar('ok', 'POST')) {
            $cid = Request::getInt('cid', Request::getInt('cid', 0, 'GET'), 'POST'); //(isset($_POST['cid'])) ? $_POST['cid'] : $_GET['cid'];

            xoops_cp_header();
            //renderAdminMenu(_AM_XOOPSTUBE_MCATEGORY);

            require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $xoopstubetree = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');
            $sform         = new \XoopsThemeForm(_AM_XOOPSTUBE_CCATEGORY_MOVE, 'move', xoops_getenv('PHP_SELF'), 'post', true);
            ob_start();
            $xoopstubetree->makeMySelBox('title', 'title', 0, 0, 'target');
            $sform->addElement(new \XoopsFormLabel(_AM_XOOPSTUBE_BMODIFY, ob_get_contents()));
            ob_end_clean();
            $create_tray = new \XoopsFormElementTray('', '');
            $create_tray->addElement(new \XoopsFormHidden('source', $cid));
            $create_tray->addElement(new \XoopsFormHidden('ok', 1));
            $create_tray->addElement(new \XoopsFormHidden('op', 'move'));
            $butt_save = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BMOVE, 'submit');
            $butt_save->setExtra('onclick="this.form.elements.op.value=\'move\'"');
            $create_tray->addElement($butt_save);
            $butt_cancel = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BCANCEL, 'submit');
            $butt_cancel->setExtra('onclick="this.form.elements.op.value=\'cancel\'"');
            $create_tray->addElement($butt_cancel);
            $sform->addElement($create_tray);
            $sform->display();
            xoops_cp_footer();
        } else {
            $source = Request::getString('source', '', 'POST'); //$_POST['source'];
            $target = Request::getString('target', '', 'POST'); //$_POST['target'];
            if ($target === $source) {
                redirect_header("category.php?op=move&ok=0&cid=$source", 5, _AM_XOOPSTUBE_CCATEGORY_MODIFY_FAILED);
            }
            if (!$target) {
                redirect_header("category.php?op=move&ok=0&cid=$source", 5, _AM_XOOPSTUBE_CCATEGORY_MODIFY_FAILEDT);
            }
            $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' set cid = ' . $target . ' WHERE cid =' . $source;
            $result = $GLOBALS['xoopsDB']->queryF($sql);
            $error  = _AM_XOOPSTUBE_DBERROR . ': <br><br>' . $sql;
            if (!$result) {
                trigger_error($error, E_USER_ERROR);
            }
            redirect_header('category.php?op=default', 1, _AM_XOOPSTUBE_CCATEGORY_MODIFY_MOVED);
        }
        break;

    case 'addCat':

        $groups       = Request::getArray('groups', [], 'POST'); //isset($_REQUEST['groups']) ? $_REQUEST['groups'] : array();
        $cid          = Request::getInt('cid', 0, 'POST'); //(isset($_REQUEST['cid'])) ? $_REQUEST['cid'] : 0;
        $pid          = Request::getInt('pid', 0, 'POST'); //(isset($_REQUEST['pid'])) ? $_REQUEST['pid'] : 0;
        $weight       = (Request::getInt('weight', 0, 'POST') > 0) ? Request::getInt('weight', 0, 'POST') : 0; //(isset($_REQUEST['weight']) && $_REQUEST['weight'] > 0) ? $_REQUEST['weight'] : 0;
        $spotlighthis = Request::getInt('lid', 0, 'POST'); //(isset($_REQUEST['lid'])) ? $_REQUEST['lid'] : 0;
        $spotlighttop = (1 == Request::getInt('spotlighttop', 0, 'POST')) ? 1 : 0; //($_REQUEST['spotlighttop'] == 1) ? 1 : 0;
        $title        = Request::getString('title', '', 'POST'); //$xtubemyts->addslashes($_REQUEST['title']);
        $descriptionb = Request::getString('description', '', 'POST'); //$xtubemyts->addslashes($_REQUEST['description']);
        $imgurl       = Request::getString('imgurl', '', 'POST'); // $_REQUEST['imgurl'] && $_REQUEST['imgurl'] != 'blank.gif') ? $xtubemyts->addslashes($_REQUEST['imgurl']) : '';
        $client_id    = Request::getInt('client_id', 0, 'POST'); //(isset($_REQUEST['client_id'])) ? $_REQUEST['client_id'] : 0;
        if ($client_id > 0) {
            $banner_id = 0;
        } else {
            $banner_id = Request::getInt('banner_id', 0, 'POST'); //(isset($_REQUEST['banner_id'])) ? $_REQUEST['banner_id'] : 0;
        }
        $nohtml   = Request::getInt('nohtml', 0, 'POST'); //isset($_REQUEST['nohtml']) ? $_REQUEST['nohtml'] : 0;
        $nosmiley = Request::getInt('nosmiley', 0, 'POST'); //isset($_REQUEST['nosmiley']) ? $_REQUEST['nosmiley'] : 0;
        $noxcodes = Request::getInt('noxcodes', 0, 'POST'); //isset($_REQUEST['noxcodes']) ? $_REQUEST['noxcodes'] : 0;
        $noimages = Request::getInt('noimages', 0, 'POST'); //isset($_REQUEST['noimages']) ? $_REQUEST['noimages'] : 0;
        $nobreak  = Request::getInt('nobreak', 0, 'POST'); //isset($_REQUEST['nobreak']) ? $_REQUEST['nobreak'] : 0;

        if (!$cid) {
            $cid = 0;
            $sql = 'INSERT INTO '
                   . $GLOBALS['xoopsDB']->prefix('xoopstube_cat')
                   . " (cid, pid, title, imgurl, description, nohtml, nosmiley, noxcodes, noimages, nobreak, weight, spotlighttop, spotlighthis, client_id, banner_id ) VALUES ($cid, $pid, '$title', '$imgurl', '$descriptionb', '$nohtml', '$nosmiley', '$noxcodes', '$noimages', '$nobreak', '$weight',  $spotlighttop, $spotlighthis, $client_id, $banner_id )";
            if (0 == $cid) {
                $newid = $GLOBALS['xoopsDB']->getInsertId();
            }

            // Notify of new category

            global $xoopsModule;
            $tags                  = [];
            $tags['CATEGORY_NAME'] = $title;
            $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $newid;
            $notificationHandler   = xoops_getHandler('notification');
            $notificationHandler->triggerEvent('global', 0, 'new_category', $tags);
            $database_mess = _AM_XOOPSTUBE_CCATEGORY_CREATED;
        } else {
            if ($cid == $pid) {
                redirect_header('category.php', 1, _AM_XOOPSTUBE_ERROR_CATISCAT);
            }
            $sql           = 'UPDATE '
                             . $GLOBALS['xoopsDB']->prefix('xoopstube_cat')
                             . " SET title ='$title', imgurl='$imgurl', pid =$pid, description='$descriptionb', spotlighthis='$spotlighthis' , spotlighttop='$spotlighttop', nohtml='$nohtml', nosmiley='$nosmiley', noxcodes='$noxcodes', noimages='$noimages', nobreak='$nobreak', weight='$weight', client_id='$client_id', banner_id='$banner_id' WHERE cid="
                             . $cid;
            $database_mess = _AM_XOOPSTUBE_CCATEGORY_MODIFIED;
        }
        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            /** @var \XoopsLogger $logger */
            $logger = \XoopsLogger::getInstance();
            $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

            return false;
        }
        redirect_header('category.php', 1, $database_mess);
        break;

    case 'del':

        global $xoopsModule;

        //        $cid = (isset($_POST['cid']) && is_numeric($_POST['cid'])) ? (int) $_POST['cid'] : (int) $_GET['cid'];
        $cid = Request::getInt('cid', Request::getInt('cid', 0, 'GET'), 'POST');
        //        $ok            = (isset($_POST['ok']) && $_POST['ok'] == 1) ? (int) $_POST['ok'] : 0;
        $ok            = (1 == Request::getInt('ok', 0, 'POST')) ? 1 : 0;
        $xoopstubetree = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');

        if (1 == $ok) {
            // get all subcategories under the specified category
            $arr    = $xoopstubetree->getAllChildId($cid);
            $lcount = count($arr);

            for ($i = 0; $i < $lcount; ++$i) {
                // get all links in each subcategory
                $result = $GLOBALS['xoopsDB']->query('SELECT lid FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE cid=' . (int)$arr[$i]);
                // now for each linkload, delete the text data and vote ata associated with the linkload
                while (false !== (list($lid) = $GLOBALS['xoopsDB']->fetchRow($result))) {
                    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $GLOBALS['xoopsDB']->prefix('xoopstube_votedata'), (int)$lid);
                    $GLOBALS['xoopsDB']->query($sql);
                    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $GLOBALS['xoopsDB']->prefix('xoopstube_videos'), (int)$lid);
                    $GLOBALS['xoopsDB']->query($sql);

                    // delete comments
                    xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
                }
                // all links for each subcategory is deleted, now delete the subcategory data
                $sql = sprintf('DELETE FROM %s WHERE cid = %u', $GLOBALS['xoopsDB']->prefix('xoopstube_cat'), (int)$arr[$i]);
                $GLOBALS['xoopsDB']->query($sql);
                // delete altcat entries
                $sql = sprintf('DELETE FROM %s WHERE cid = %u', $GLOBALS['xoopsDB']->prefix('xoopstube_altcat'), $arr[$i]);
                $GLOBALS['xoopsDB']->query($sql);
            }
            // all subcategory and associated data are deleted, now delete category data and its associated data
            $result = $GLOBALS['xoopsDB']->query('SELECT lid FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE cid=' . $cid);
            while (false !== (list($lid) = $GLOBALS['xoopsDB']->fetchRow($result))) {
                $sql = sprintf('DELETE FROM %s WHERE lid = %u', $GLOBALS['xoopsDB']->prefix('xoopstube_videos'), (int)$lid);
                $GLOBALS['xoopsDB']->query($sql);
                // delete comments
                xoops_comment_delete($xoopsModule->getVar('mid'), (int)$lid);
                $sql = sprintf('DELETE FROM %s WHERE lid = %u', $GLOBALS['xoopsDB']->prefix('xoopstube_votedata'), (int)$lid);
                $GLOBALS['xoopsDB']->query($sql);
            }

            // delete altcat entries
            $sql = sprintf('DELETE FROM %s WHERE cid = %u', $GLOBALS['xoopsDB']->prefix('xoopstube_altcat'), $cid);
            $GLOBALS['xoopsDB']->query($sql);

            // delete category
            $sql   = sprintf('DELETE FROM %s WHERE cid = %u', $GLOBALS['xoopsDB']->prefix('xoopstube_cat'), $cid);
            $error = _AM_XOOPSTUBE_DBERROR . ': <br><br>' . $sql;
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'XTubeCatPerm', $cid);
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                trigger_error($error, E_USER_ERROR);
            }

            // delete group permissions
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'XTubeCatPerm', $cid);
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                trigger_error($error, E_USER_ERROR);
            }
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'XTubeSubPerm', $cid);
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                trigger_error($error, E_USER_ERROR);
            }
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'XTubeAppPerm', $cid);
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                trigger_error($error, E_USER_ERROR);
            }
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'XTubeAutoApp', $cid);
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                trigger_error($error, E_USER_ERROR);
            }
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'XTubeRatePerms', $cid);
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                trigger_error($error, E_USER_ERROR);
            }

            redirect_header('category.php', 1, _AM_XOOPSTUBE_CCATEGORY_DELETED);
        } else {
            xoops_cp_header();
            xoops_confirm([
                              'op'  => 'del',
                              'cid' => $cid,
                              'ok'  => 1
                          ], 'category.php', _AM_XOOPSTUBE_CCATEGORY_AREUSURE);
            xoops_cp_footer();
        }
        break;

    case 'modCat':
        $cid = Request::getInt('cid', 0, 'POST'); //(isset($_POST['cid'])) ? $_POST['cid'] : 0;
        xoops_cp_header();
        //renderAdminMenu(_AM_XOOPSTUBE_MCATEGORY);
        createCategory($cid);
        require_once __DIR__ . '/admin_footer.php';
        break;

    case 'main':
    default:
        xoops_cp_header();
        //renderAdminMenu(_AM_XOOPSTUBE_MCATEGORY);

        //        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $xoopstubetree = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');
        $sform         = new \XoopsThemeForm(_AM_XOOPSTUBE_CCATEGORY_MODIFY, 'category', xoops_getenv('PHP_SELF'), 'post', true);
        $totalcats     = Xoopstube\Utility::getTotalCategoryCount();

        if ($totalcats > 0) {
            ob_start();
            $xoopstubetree->makeMySelBox('title', 'title');
            $sform->addElement(new \XoopsFormLabel(_AM_XOOPSTUBE_CCATEGORY_MODIFY_TITLE, ob_get_contents()));
            ob_end_clean();
            $dup_tray = new \XoopsFormElementTray('', '');
            $dup_tray->addElement(new \XoopsFormHidden('op', 'modCat'));
            $butt_dup = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BMODIFY, 'submit');
            $butt_dup->setExtra('onclick="this.form.elements.op.value=\'modCat\'"');
            $dup_tray->addElement($butt_dup);
            $butt_move = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BMOVE, 'submit');
            $butt_move->setExtra('onclick="this.form.elements.op.value=\'move\'"');
            $dup_tray->addElement($butt_move);
            $butt_dupct = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BDELETE, 'submit');
            $butt_dupct->setExtra('onclick="this.form.elements.op.value=\'del\'"');
            $dup_tray->addElement($butt_dupct);
            $sform->addElement($dup_tray);
            $sform->display();
        }
        createCategory(0);
        require_once __DIR__ . '/admin_footer.php';
        break;
}
