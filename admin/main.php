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
 * @link            https://xoops.org/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           1.0.6
 */

use Xmf\Request;
use XoopsModules\Xoopstube;

require_once __DIR__ . '/admin_header.php';
global $xoopsModule;

$mytree = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');

$op  = Request::getCmd('op', Request::getCmd('op', '', 'POST'), 'GET');
$lid = Request::getInt('lid', Request::getInt('lid', 0, 'POST'), 'GET');

/**
 * @param int $lid
 *
 * @return null
 */
function edit($lid = 0)
{
    global $xtubemyts, $mytree, $xtubeImageArray;

    $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid;
    if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
        /** @var \XoopsLogger $logger */
        $logger = \XoopsLogger::getInstance();
        $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

        return false;
    }
    $video_array  = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));
    $directory    = $GLOBALS['xoopsModuleConfig']['videoimgdir'];
    $lid          = $video_array['lid'] ?: 0;
    $cid          = $video_array['cid'] ?: 0;
    $title        = $video_array['title'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['title']) : '';
    $vidid        = $video_array['vidid'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['vidid']) : '';
    $picurl       = $video_array['picurl'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['picurl']) : 'http://';
    $publisher    = $video_array['publisher'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['publisher']) : '';
    $screenshot   = $video_array['screenshot'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['screenshot']) : '';
    $descriptionb = $video_array['description'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['description']) : '';
    $published    = $video_array['published'] ?: time();
    $expired      = $video_array['expired'] ?: 0;
    $updated      = $video_array['updated'] ?: 0;
    $offline      = $video_array['offline'] ?: 0;
    $vidsource    = $video_array['vidsource'] ?: 0;
    $ipaddress    = $video_array['ipaddress'] ?: 0;
    $notifypub    = $video_array['notifypub'] ?: 0;
    $time         = $video_array['time'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['time']) : '0:00:00';
    $keywords     = $video_array['keywords'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['keywords']) : '';
    $item_tag     = $video_array['item_tag'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['item_tag']) : '';

    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //renderAdminMenu( _AM_XOOPSTUBE_MVIDEOS );

    if ($lid) {
        $_vote_data = Xoopstube\Utility::getVoteDetails($lid);
        $text_info  = '
            <table width="100%" style="font-size: 90%;">
             <tr>
              <td style="width: 25%; border-right: #E8E8E8 1px solid; vertical-align: top; padding-left: 10px;">
               <div><b>' . _AM_XOOPSTUBE_VIDEO_ID . ' </b>' . $lid . '</div>
               <div><b>' . _AM_XOOPSTUBE_MINDEX_SUBMITTED . ': </b>' . Xoopstube\Utility::getTimestamp(formatTimestamp($video_array['date'], $GLOBALS['xoopsModuleConfig']['dateformat'])) . '</div>
               <div><b>' . _AM_XOOPSTUBE_MOD_MODIFYSUBMITTER . ' </b>' . Xoopstube\Utility::getLinkedUserNameFromId($video_array['submitter']) . '</div><div><b>' . _AM_XOOPSTUBE_VIDEO_IP . ' </b>' . $ipaddress . '</div>
               <div><b>' . _AM_XOOPSTUBE_VIDEO_VIEWS . ' </b>' . $video_array['hits'] . '</div>
              </td>
              <td style="width: 25%; border-right: #E8E8E8 1px solid; vertical-align: top; padding-left: 10px;">
               <div><b>' . _AM_XOOPSTUBE_VOTE_TOTALRATE . ': </b>' . (int)$_vote_data['rate'] . '</div>
               <div><b>' . _AM_XOOPSTUBE_VOTE_USERAVG . ': </b>' . (int)round($_vote_data['avg_rate'], 2) . '</div>
               <div><b>' . _AM_XOOPSTUBE_VOTE_MAXRATE . ': </b>' . (int)$_vote_data['min_rate'] . '</div>
               <div><b>' . _AM_XOOPSTUBE_VOTE_MINRATE . ': </b>' . (int)$_vote_data['max_rate'] . '</div>
              </td>
              <td style="width: 25%; border-right: #E8E8E8 1px solid; vertical-align: top; padding-left: 10px;">
               <div><b>' . _AM_XOOPSTUBE_VOTE_MOSTVOTEDTITLE . ': </b>' . (int)$_vote_data['max_title'] . '</div>
               <div><b>' . _AM_XOOPSTUBE_VOTE_LEASTVOTEDTITLE . ': </b>' . (int)$_vote_data['min_title'] . '</div>
               <div><b>' . _AM_XOOPSTUBE_VOTE_REGISTERED . ': </b>' . ((int)$_vote_data['rate'] - $_vote_data['null_ratinguser']) . '</div>
               <div><b>' . _AM_XOOPSTUBE_VOTE_NONREGISTERED . ': </b>' . (int)$_vote_data['null_ratinguser'] . '</div>
              </td>
              <td style="width: 25%; vertical-align: top; padding-left: 10px;">
                <div>' . xtubeGetVideoThumb($video_array['vidid'], $video_array['title'], $video_array['vidsource'], $video_array['picurl'], $video_array['screenshot']) . '</div>
              </td>
             </tr>
            </table>';
        echo '
            <fieldset style="border: #E8E8E8 1px solid;"><legend style="display: inline; font-weight: bold; color: #0A3760;">' . _AM_XOOPSTUBE_INFORMATION . '</legend>
            <div style="padding: 8px;">' . $text_info . '</div>
        <!--    <div style="padding: 8px;"><li>' . $xtubeImageArray['deleteimg'] . ' ' . _AM_XOOPSTUBE_VOTE_DELETEDSC . '</li></div>\n    -->
            </fieldset>
            <br>';
    }
    unset($_vote_data);

    $caption = $lid ? _AM_XOOPSTUBE_VIDEO_MODIFYFILE : _AM_XOOPSTUBE_VIDEO_CREATENEWFILE;

    $sform = new \XoopsThemeForm($caption, 'storyform', xoops_getenv('PHP_SELF'), 'post', true);
    $sform->setExtra('enctype="multipart / form - data"');

    // Video title
    $sform->addElement(new \XoopsFormText(_AM_XOOPSTUBE_VIDEO_TITLE, 'title', 70, 255, $title), true);

    // Video source
    $vidsource_array  = [
        0   => _AM_XOOPSTUBE_YOUTUBE,
        1   => _AM_XOOPSTUBE_METACAFE,
        2   => _AM_XOOPSTUBE_IFILM,
        3   => _AM_XOOPSTUBE_PHOTOBUCKET,
        4   => _AM_XOOPSTUBE_VIDDLER,
        100 => _AM_XOOPSTUBE_GOOGLEVIDEO,
        101 => _AM_XOOPSTUBE_MYSPAVETV,
        102 => _AM_XOOPSTUBE_DAILYMOTION,
        103 => _AM_XOOPSTUBE_BLIPTV,
        104 => _AM_XOOPSTUBE_CLIPFISH,
        105 => _AM_XOOPSTUBE_LIVELEAK,
        106 => _AM_XOOPSTUBE_MAKTOOB,
        107 => _AM_XOOPSTUBE_VEOH,
        108 => _AM_XOOPSTUBE_VIMEO,
        109 => _MD_XOOPSTUBE_MEGAVIDEO,
        200 => _MD_XOOPSTUBE_XOOPSTUBE
    ]; // #200 is reserved for XoopsTube's internal FLV player
    $vidsource_select = new \XoopsFormSelect(_AM_XOOPSTUBE_VIDSOURCE, 'vidsource', $vidsource);
    $vidsource_select->addOptionArray($vidsource_array);
    $sform->addElement($vidsource_select);

    // Video code
    $videocode = new \XoopsFormText(_AM_XOOPSTUBE_VIDEO_DLVIDID, 'vidid', 70, 512, $vidid);
    $videocode->setDescription('<br><span style="font-size: small;">' . _AM_XOOPSTUBE_VIDEO_DLVIDIDDSC . '</span>');
    $sform->addElement($videocode, true);
    $note = _AM_XOOPSTUBE_VIDEO_DLVIDID_NOTE;
    $sform->addElement(new \XoopsFormLabel('', $note));

    // Picture url
    $picurl = new \XoopsFormText(_AM_XOOPSTUBE_VIDEO_PICURL, 'picurl', 70, 255, $picurl);
    $picurl->setDescription('<br><span style="font-weight: normal;font-size: smaller;">' . _AM_XOOPSTUBE_VIDEO_PICURLNOTE . '</span>');
    $sform->addElement($picurl, false);

    // Video publisher
    $sform->addElement(new \XoopsFormText(_AM_XOOPSTUBE_VIDEO_PUBLISHER, 'publisher', 70, 255, $publisher), true);

    // Time form
    $timeform = new \XoopsFormText(_AM_XOOPSTUBE_TIME, 'time', 7, 7, $time);
    $timeform->setDescription('<span style="font-size: small;">(h:mm:ss)</span>');
    $sform->addElement($timeform, false);

    // Category menu
    ob_start();
    $mytree->makeMySelBox('title', 'title', $cid, 0);
    $sform->addElement(new \XoopsFormLabel(_AM_XOOPSTUBE_VIDEO_CATEGORY, ob_get_contents()));
    ob_end_clean();

    // Description form
    //    $editor = xtube_getWysiwygForm( _AM_XOOPSTUBE_VIDEO_DESCRIPTION, 'descriptionb', $descriptionb );
    //    $sform -> addElement( $editor, false );

    $optionsTrayNote = new \XoopsFormElementTray(_AM_XOOPSTUBE_VIDEO_DESCRIPTION, '<br>');
    if (class_exists('XoopsFormEditor')) {
        $options['name']   = 'descriptionb';
        $options['value']  = $descriptionb;
        $options['rows']   = 5;
        $options['cols']   = '100%';
        $options['width']  = '100%';
        $options['height'] = '200px';
        $descriptionb      = new \XoopsFormEditor('', $GLOBALS['xoopsModuleConfig']['form_options'], $options, $nohtml = false, $onfailure = 'textarea');
        $optionsTrayNote->addElement($descriptionb);
    } else {
        $descriptionb = new \XoopsFormDhtmlTextArea('', 'descriptionb', $item->getVar('descriptionb', 'e'), '100%', '100%');
        $optionsTrayNote->addElement($descriptionb);
    }

    $sform->addElement($optionsTrayNote, false);

    // Meta keywords form
    $keywords = new \XoopsFormTextArea(_AM_XOOPSTUBE_KEYWORDS, 'keywords', $keywords, 7, 60, false);
    $keywords->setDescription("<br><br><br><br><span style='font-size: smaller;'>" . _AM_XOOPSTUBE_KEYWORDS_NOTE . '</span>');
    $sform->addElement($keywords);

    // Insert tags if Tag-module is installed
    if (Xoopstube\Utility::isModuleTagInstalled()) {
        require_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
        $text_tags = new TagFormTag('item_tag', 70, 255, $video_array['item_tag'], 0);
        $sform->addElement($text_tags);
    } else {
        $sform->addElement(new \XoopsFormHidden('item_tag', $video_array['item_tag']));
    }

    // Video Publish Date
    $sform->addElement(new \XoopsFormDateTime(_AM_XOOPSTUBE_VIDEO_SETPUBLISHDATE, 'published', $size = 15, $published));

    if ($lid) {
        $sform->addElement(new \XoopsFormHidden('was_published', $published));
        $sform->addElement(new \XoopsFormHidden('was_expired', $expired));
    }

    // Video Expire Date
    $isexpired           = ($expired > time()) ? 1 : 0;
    $expiredates         = ($expired > time()) ? _AM_XOOPSTUBE_VIDEO_EXPIREDATESET . Xoopstube\Utility::getTimestamp(formatTimestamp($expired, $GLOBALS['xoopsModuleConfig']['dateformat'])) : _AM_XOOPSTUBE_VIDEO_SETDATETIMEEXPIRE;
    $warning             = ($published > $expired && $expired > time()) ? _AM_XOOPSTUBE_VIDEO_EXPIREWARNING : '';
    $expiredate_checkbox = new \XoopsFormCheckBox('', 'expiredateactivate', $isexpired);
    $expiredate_checkbox->addOption(1, $expiredates . ' <br> <br> ');

    $expiredate_tray = new \XoopsFormElementTray(_AM_XOOPSTUBE_VIDEO_EXPIREDATE . $warning, '');
    $expiredate_tray->addElement($expiredate_checkbox);
    $expiredate_tray->addElement(new \XoopsFormDateTime(_AM_XOOPSTUBE_VIDEO_SETEXPIREDATE . ' <br> ', 'expired', 15, $expired));
    $expiredate_tray->addElement(new \XoopsFormRadioYN(_AM_XOOPSTUBE_VIDEO_CLEAREXPIREDATE, 'clearexpire', 0, ' ' . _YES . '', ' ' . _NO . ''));
    $sform->addElement($expiredate_tray);

    // Set video offline yes/no
    $videostatus_radio = new \XoopsFormRadioYN(_AM_XOOPSTUBE_VIDEO_FILESSTATUS, 'offline', $offline, ' ' . _YES . '', ' ' . _NO . '');
    $sform->addElement($videostatus_radio);

    // Set video status as updated yes/no
    $up_dated            = (0 == $updated) ? 0 : 1;
    $video_updated_radio = new \XoopsFormRadioYN(_AM_XOOPSTUBE_VIDEO_SETASUPDATED, 'up_dated', $up_dated, ' ' . _YES . '', ' ' . _NO . '');
    $sform->addElement($video_updated_radio);

    $result = $GLOBALS['xoopsDB']->query('SELECT COUNT( * ) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . ' WHERE lid = ' . $lid);
    list($broken_count) = $GLOBALS['xoopsDB']->fetchRow($result);
    if ($broken_count > 0) {
        $video_updated_radio = new \XoopsFormRadioYN(_AM_XOOPSTUBE_VIDEO_DELEDITMESS, 'delbroken', 1, ' ' . _YES . '', ' ' . _NO . '');
        $sform->addElement($editmess_radio);
    }

    if ($lid && 0 == $published) {
        $approved         = (0 == $published) ? 0 : 1;
        $approve_checkbox = new \XoopsFormCheckBox(_AM_XOOPSTUBE_VIDEO_EDITAPPROVE, 'approved', 1);
        $approve_checkbox->addOption(1, ' ');
        $sform->addElement($approve_checkbox);
    }

    if (!$lid) {
        $button_tray = new \XoopsFormElementTray('', '');
        $button_tray->addElement(new \XoopsFormHidden('status', 1));
        $button_tray->addElement(new \XoopsFormHidden('notifypub', $notifypub));
        $button_tray->addElement(new \XoopsFormHidden('op', 'save'));
        $button_tray->addElement(new \XoopsFormButton('', '', _AM_XOOPSTUBE_BSAVE, 'submit'));
        $sform->addElement($button_tray);
    } else {
        $button_tray = new \XoopsFormElementTray('', '');
        $button_tray->addElement(new \XoopsFormHidden('lid', $lid));
        $button_tray->addElement(new \XoopsFormHidden('status', 2));
        $hidden = new \XoopsFormHidden('op', 'save');
        $button_tray->addElement($hidden);

        $butt_dup = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BMODIFY, 'submit');
        $butt_dup->setExtra('onclick="this . form . elements . op . value = \'save\'"');
        $button_tray->addElement($butt_dup);
        $butt_dupct = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BDELETE, 'submit');
        $butt_dupct->setExtra('onclick="this.form.elements.op.value=\'delete\'"');
        $button_tray->addElement($butt_dupct);
        $butt_dupct2 = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BCANCEL, 'submit');
        $butt_dupct2->setExtra('onclick="this.form.elements.op.value=\'videosConfigMenu\'"');
        $button_tray->addElement($butt_dupct2);
        $sform->addElement($button_tray);
    }
    $sform->display();
    unset($hidden);
    require_once __DIR__ . '/admin_footer.php';

    return null;
}

switch (strtolower($op)) {
    case 'edit':
        edit($lid);
        break;

    case 'save':

        $groups    = Request::getArray('groups', [], 'POST'); //isset($_POST['groups']) ? $_POST['groups'] : array();
        $lid       = Request::getInt('lid', 0, 'POST');// (!empty($_POST['lid'])) ? $_POST['lid'] : 0;
        $cid       = Request::getInt('cid', 0, 'POST');// (!empty($_POST['cid'])) ? $_POST['cid'] : 0;
        $vidrating = Request::getInt('vidrating', 6, 'POST');// (!empty($_POST['vidrating'])) ? $_POST['vidrating'] : 6;
        $status    = Request::getInt('status', 2, 'POST');// (!empty($_POST['status'])) ? $_POST['status'] : 2;

        // Get data from form
        $vidid        = $xtubemyts->addSlashes(Request::getString('vidid', '', 'POST'));
        $picurl       = ('http://' !== Request::getString('picurl', '', 'POST')) ? $xtubemyts->addSlashes(Request::getString('picurl', '', 'POST')) : '';
        $title        = $xtubemyts->addSlashes(Request::getString('title', '', 'POST'));
        $descriptionb = $xtubemyts->addSlashes(Request::getString('descriptionb', '', 'POST'));
        $time         = $xtubemyts->addSlashes(Request::getString('time', '', 'POST'));
        $keywords     = $xtubemyts->addSlashes(Request::getString('keywords', '', 'POST'));
        $item_tag     = $xtubemyts->addSlashes(Request::getString('item_tag', '', 'POST'));
        $submitter    = $GLOBALS['xoopsUser']->uid();
        $publisher    = $xtubemyts->addSlashes(Request::getString('publisher', '', 'POST'));
        $vidsource    = Request::getInt('vidsource', 0, 'POST'); //(!empty($_POST['vidsource'])) ? $_POST['vidsource'] : 0;
        $updated      = (0 == Request::getInt('was_published', '', 'POST')) ? 0 : time();

        //PHP 5.3
        $published0 = Request::getArray('published', '', 'POST');
        $published  = strtotime($published0['date']) + $published0['time'];

        //          PHP 5.4
        //        $published    = strtotime(Request::getArray('published', '', 'POST')['date']) + Request::getArray('published', '', 'POST')['time'];

        if (0 == Request::getInt('up_dated', '', 'POST')) {
            $updated = 0;
            $status  = 1;
        }

        $offline   = (1 == Request::getInt('offline', '', 'POST')) ? 1 : 0; // $_POST['offline'] == 1) ? 1 : 0;
        $approved  = (1 == Request::getInt('approved', '', 'POST')) ? 1 : 0; //isset($_POST['approved']) && $_POST['approved'] == 1) ? 1 : 0;
        $notifypub = (1 == Request::getInt('notifypub', '', 'POST')); //(isset($_POST['notifypub']) && $_POST['notifypub'] == 1);

        if (!$lid) {
            $date        = time();
            $publishdate = time();
            $expiredate  = '0';
        } else {
            $publishdate = Request::getBool('was_published', false, 'POST');//$_POST['was_published'];
            $expiredate  = Request::getBool('was_expired', false, 'POST');//$_POST['was_expired'];
        }
        if (1 == $approved && empty($publishdate)) {
            $publishdate = time();
        }
        //        if (Request::hasVar('expiredateactivate', 'POST')) {
        //PHP 5.3
        $expiredate0 = Request::getArray('expired', [], 'POST');
        $expiredate  = strtotime($expiredate0['date']) + $expiredate0['time'];
        //PHP 5.4
        //        $expiredate = strtotime(Request::getArray('expired', array(), 'POST')['date']) + Request::getArray('expired', array(), 'POST')['time'];
        //        }

        if (Request::hasVar('clearexpire', 'POST')) {
            $expiredate = '0';
        }

        // Update or insert linkload data into database
        if (!$lid) {
            $date        = time();
            $publishdate = time();
            $ipaddress   = $_SERVER['REMOTE_ADDR'];
            $sql         = 'INSERT INTO '
                           . $GLOBALS['xoopsDB']->prefix('xoopstube_videos')
                           . ' (lid, cid, title, vidid, screenshot, submitter, publisher, status, date, hits, rating, votes, comments, vidsource, published, expired, updated, offline, description, ipaddress, notifypub, vidrating, time, keywords, item_tag, picurl )';
            $sql         .= " VALUES    (NULL, $cid, '$title', '$vidid', '', '$submitter', '$publisher', '$status', '$date', 0, 0, 0, 0, '$vidsource', '$published', '$expiredate', '$updated', '$offline', '$descriptionb', '$ipaddress', '0', '$vidrating', '$time', '$keywords', '$item_tag', '$picurl')";
        //    $newid = $GLOBALS['xoopsDB'] -> getInsertId();
        } else {
            $sql = 'UPDATE '
                   . $GLOBALS['xoopsDB']->prefix('xoopstube_videos')
                   . " SET cid = $cid, title='$title', vidid='$vidid', screenshot='', publisher='$publisher', status='$status', vidsource='$vidsource', published='$published', expired='$expiredate', updated='$updated', offline='$offline', description='$descriptionb', vidrating='$vidrating', time='$time', keywords='$keywords', item_tag='$item_tag', picurl='$picurl' WHERE lid="
                   . $lid;
        }

        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            /** @var \XoopsLogger $logger */
            $logger = \XoopsLogger::getInstance();
            $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

            return false;
        }

        $newid = $GLOBALS['xoopsDB']->getInsertId();

        // Add item_tag to Tag-module
        if (!$lid) {
            $tagupdate = Xoopstube\Utility::updateTag($newid, $item_tag);
        } else {
            $tagupdate = Xoopstube\Utility::updateTag($lid, $item_tag);
        }

        // Send notifications
        if (!$lid) {
            $tags                  = [];
            $tags['VIDEO_NAME']    = $title;
            $tags['VIDEO_URL']     = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $cid . '&amp;lid=' . $newid;
            $sql                   = 'SELECT title FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' WHERE cid=' . $cid;
            $result                = $GLOBALS['xoopsDB']->query($sql);
            $row                   = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));
            $tags['CATEGORY_NAME'] = $row['title'];
            $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;
            $notificationHandler   = xoops_getHandler('notification');
            $notificationHandler->triggerEvent('global', 0, 'new_video', $tags);
            $notificationHandler->triggerEvent('category', $cid, 'new_video', $tags);
        }
        if ($lid && $approved && $notifypub) {
            $tags                  = [];
            $tags['VIDEO_NAME']    = $title;
            $tags['VIDEO_URL']     = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $cid . '&amp;lid=' . $lid;
            $sql                   = 'SELECT title FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' WHERE cid=' . $cid;
            $result                = $GLOBALS['xoopsDB']->query($sql);
            $row                   = $GLOBALS['xoopsDB']->fetchArray($result);
            $tags['CATEGORY_NAME'] = $row['title'];
            $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;
            $notificationHandler   = xoops_getHandler('notification');
            $notificationHandler->triggerEvent('global', 0, 'new_video', $tags);
            $notificationHandler->triggerEvent('category', $cid, 'new_video', $tags);
            $notificationHandler->triggerEvent('video', $lid, 'approve', $tags);
        }
        $message = (!$lid) ? _AM_XOOPSTUBE_VIDEO_NEWFILEUPLOAD : _AM_XOOPSTUBE_VIDEO_FILEMODIFIEDUPDATE;
        $message = ($lid && !Request::getBool('was_published', false, 'POST') && $approved) ? _AM_XOOPSTUBE_VIDEO_FILEAPPROVED : $message;

        if (Request::hasVar('delbroken')) { //cleanRequestVars($_REQUEST, 'delbroken', 0)) {
            $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . ' WHERE lid=' . $lid;
            if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
                /** @var \XoopsLogger $logger */
                $logger = \XoopsLogger::getInstance();
                $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }
        }

        redirect_header('main.php', 1, $message);

        break;

    case 'delete':
        if (Request::hasVar('confirm')) { // (cleanRequestVars($_REQUEST, 'confirm', 0)) {
            $title = Request::getString('title', 0); //cleanRequestVars($_REQUEST, 'title', 0);

            // delete video
            $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid;
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                /** @var \XoopsLogger $logger */
                $logger = \XoopsLogger::getInstance();
                $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }

            // delete altcat
            $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_altcat') . ' WHERE lid=' . $lid;
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                /** @var \XoopsLogger $logger */
                $logger = \XoopsLogger::getInstance();
                $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }

            // delete vote data
            $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_votedata') . ' WHERE lid=' . $lid;
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                /** @var \XoopsLogger $logger */
                $logger = \XoopsLogger::getInstance();
                $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }

            // delete comments
            xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
            redirect_header('main.php', 1, sprintf(_AM_XOOPSTUBE_VIDEO_FILEWASDELETED, $title));
        } else {
            $sql = 'SELECT lid, title, item_tag, vidid FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid;
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                /** @var \XoopsLogger $logger */
                $logger = \XoopsLogger::getInstance();
                $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }
            list($lid, $title) = $GLOBALS['xoopsDB']->fetchrow($result);
            $item_tag = $result['item_tag'];

            xoops_cp_header();
            //renderAdminMenu( _AM_XOOPSTUBE_BINDEX );

            xoops_confirm([
                              'op'      => 'delete',
                              'lid'     => $lid,
                              'confirm' => 1,
                              'title'   => $title
                          ], 'main.php', _AM_XOOPSTUBE_VIDEO_REALLYDELETEDTHIS . '<br><br>' . $title, _DELETE);

            // Remove item_tag from Tag-module
            $tagupdate = Xoopstube\Utility::updateTag($lid, $item_tag);

            require_once __DIR__ . '/admin_footer.php';
        }
        break;

    case 'toggle':
        if (Request::hasVar('lid', 'GET') > 0) {
            $a = (null === Request::getInt('offline', null, 'GET'));
            $b = null === Request::getInt('offzzline', null, 'GET');
            $c = null === Request::getInt('offline', '', 'GET');
            $d = null === Request::getInt('offzzline', '', 'GET');
            //            $e = empty(Request::getInt('offline', 0, 'GET'));
            $yy  = Request::getInt('offzzline', null, 'GET');
            $f0  = isset($yy);
            $g0  = empty($yy);
            $h0  = null === $yy;
            $yy1 = Request::getString('offzzline');
            $f1  = isset($yy1);
            $g1  = empty($yy1);
            $h1  = null === $yy1;

            $yy2 = Request::getVar('offzzline', null, 'GET');
            $f2  = isset($yy2);
            $g2  = empty($yy2);
            $h2  = null === $yy2;

            $xx = Request::getInt('offline', '', 'GET');
            $f  = isset($xx);
            $g  = empty($xx);
            $h  = null === $xx;
            //            $e = empty(Request::getInt('offline', '', 'GET'));
            $offline = Request::getInt('offline', null, 'GET');
            if (null !== $offline) {
                xtubeToggleOffline($lid, $offline);
            }
        }
        break;

    case 'delvote':
        $rid = Request::getInt('rid', 0); //cleanRequestVars($_REQUEST, 'rid', 0);
        $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_votedata') . ' WHERE ratingid=' . $rid;
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            /** @var \XoopsLogger $logger */
            $logger = \XoopsLogger::getInstance();
            $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

            return false;
        }
        Xoopstube\Utility::updateRating($rid);
        redirect_header('main.php', 1, _AM_XOOPSTUBE_VOTE_VOTEDELETED);
        break;

    case 'main':
    default:
        $start     = Request::getInt('start', 0, 'POST');// cleanRequestVars($_REQUEST, 'start', 0);
        $start1    = Request::getInt('start1', 0, 'POST');// cleanRequestVars($_REQUEST, 'start1', 0);
        $start2    = Request::getInt('start2', 0, 'POST');// cleanRequestVars($_REQUEST, 'start2', 0);
        $start3    = Request::getInt('start3', 0, 'POST');// cleanRequestVars($_REQUEST, 'start3', 0);
        $start4    = Request::getInt('start4', 0, 'POST');// cleanRequestVars($_REQUEST, 'start4', 0);
        $start5    = Request::getInt('start5', 0, 'POST');// cleanRequestVars($_REQUEST, 'start5', 0);
        $totalcats = Xoopstube\Utility::getTotalCategoryCount();

        $result = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken'));
        list($totalbrokenvideos) = $GLOBALS['xoopsDB']->fetchRow($result);
        $result2 = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod'));
        list($totalmodrequests) = $GLOBALS['xoopsDB']->fetchRow($result2);
        $result3 = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published = 0');
        list($totalnewvideos) = $GLOBALS['xoopsDB']->fetchRow($result3);
        $result4 = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published > 0');
        list($totalvideos) = $GLOBALS['xoopsDB']->fetchRow($result4);

        xoops_cp_header();

        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_MI_XOOPSTUBE_ADD_VIDEO, 'main.php?op=edit', 'add', '');
        $adminObject->addItemButton(_MI_XOOPSTUBE_ADD_CATEGORY, 'category.php', 'add', '');
        $adminObject->displayButton('left', '');

        //renderAdminMenu( _AM_XOOPSTUBE_BINDEX );
        //    echo '
        //          <fieldset style="border: #E8E8E8 1px solid;">
        //          <legend style="display: inline; font-weight: bold; color: #0A3760;">' . _AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY . '</legend>
        //          <div style="padding: 8px;">
        //          <span style="font-size: small;">
        //          <a href="category.php">' . _AM_XOOPSTUBE_SCATEGORY . '</a><b>' . $totalcats . '</b> |
        //          <a href="main.php">' . _AM_XOOPSTUBE_SFILES . '</a><b>' . $totalvideos . '</b> |
        //          <a href="newvideos.php">' . _AM_XOOPSTUBE_SNEWFILESVAL . '</a><b>' . $totalnewvideos . '</b> |
        //          <a href="modifications.php">' . _AM_XOOPSTUBE_SMODREQUEST . '</a><b>' . $totalmodrequests . '</b> |
        //          <a href="brokenvideo.php">' . _AM_XOOPSTUBE_SBROKENSUBMIT . '</a><b>' . $totalbrokenvideos . '</b>
        //          </span>
        //          </div>
        //          </fieldset>';

        if ($totalcats > 0) {
            $sform = new \XoopsThemeForm(_AM_XOOPSTUBE_CCATEGORY_MODIFY, 'category', 'category.php');
            ob_start();
            $mytree->makeMySelBox('title', 'title');
            $sform->addElement(new \XoopsFormLabel(_AM_XOOPSTUBE_CCATEGORY_MODIFY_TITLE, ob_get_contents()));
            ob_end_clean();
            $dup_tray = new \XoopsFormElementTray('', '');
            $dup_tray->addElement(new \XoopsFormHidden('op', 'modCat'));
            $butt_dup = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BMODIFY, 'submit');
            $butt_dup->setExtra('onclick="this.form.elements.op.value=\'modCat\'"');
            $dup_tray->addElement($butt_dup);
            $butt_dupct = new \XoopsFormButton('', '', _AM_XOOPSTUBE_BDELETE, 'submit');
            $butt_dupct->setExtra('onclick="this.form.elements.op.value=\'del\'"');
            $dup_tray->addElement($butt_dupct);
            $sform->addElement($dup_tray);
            $sform->display();

            //TODO add table with categories

            //            $sql='SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' ORDER BY cid DESC';
            //            $publishedArray       = $GLOBALS['xoopsDB']->query($sql, $GLOBALS['xoopsModuleConfig']['admin_perpage'], $start);
            //            $publishedArrayCount = $GLOBALS['xoopsDB']->getRowsNum($GLOBALS['xoopsDB']->query($sql));
            //            renderCategoryListHeader(_AM_XOOPSTUBE_MINDEX_PUBLISHEDVIDEO);
            //            setPageNavigationCategoryList($publishedArrayCount, $start, 'art', '', 'left');
            //            if ($publishedArrayCount > 0) {
            //                while (false !== ($published = $GLOBALS['xoopsDB']->fetchArray($publishedArray))) {
            //                    renderCategoryListBody($published);
            //                }
            //                echo '</table>';
            //            } else {
            //                renderCategoryListFooter();
            //            }
            //            setPageNavigationCategoryList($publishedArrayCount, $start, 'art', '', 'right');
        }

        if ($totalvideos > 0) {
            $sql                 = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published > 0  ORDER BY lid DESC';
            $publishedArray      = $GLOBALS['xoopsDB']->query($sql, $GLOBALS['xoopsModuleConfig']['admin_perpage'], $start);
            $publishedArrayCount = $GLOBALS['xoopsDB']->getRowsNum($GLOBALS['xoopsDB']->query($sql));
            Xoopstube\Utility::renderVideoListHeader(_AM_XOOPSTUBE_MINDEX_PUBLISHEDVIDEO);
            Xoopstube\Utility::setPageNavigationVideoList($publishedArrayCount, $start, 'art', '', 'left');
            if ($publishedArrayCount > 0) {
                while (false !== ($published = $GLOBALS['xoopsDB']->fetchArray($publishedArray))) {
                    Xoopstube\Utility::renderVideoListBody($published);
                }
                echo '</table>';
            } else {
                Xoopstube\Utility::renderVideoListFooter();
            }
            Xoopstube\Utility::setPageNavigationVideoList($publishedArrayCount, $start, 'art', '', 'right');
        }
        require_once __DIR__ . '/admin_footer.php';
        break;
}
/**
 * @param $lid
 * @param $offline
 *
 * @return bool|null
 */
function xtubeToggleOffline($lid, $offline)
{
    $message = '';
    $offline = (1 == $offline) ? 0 : 1;

    $message = _AM_XOOPSTUBE_TOGGLE_ONLINE_SUCCESS;
    if (1 == $offline) {
        $message = _AM_XOOPSTUBE_TOGGLE_OFFLINE_SUCCESS;
    }

    //    $thisHandler   = xoops_getModuleHandler('xoopstube_videos', 'xoopstube');
    //    $obj            = $thisHandler->get($lid);
    //    $obj->setVar('offline', $offline);
    //    if ($thisHandler->insert($obj, true)) {
    //        redirect_header('main.php', 1, _AM_XOOPSTUBE_TOGGLE_SUCCESS);
    //    } else {
    //        redirect_header('main.php', 1, _AM_XOOPSTUBE_TOGGLE_FAILED);
    //    }

    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . " SET  offline='" . $offline . "' WHERE lid=" . $lid;

    if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
        redirect_header('main.php', 1, _AM_XOOPSTUBE_TOGGLE_FAILED);

        return false;
    } else {
        redirect_header('main.php', 1, $message);
    }

    return null;
}
