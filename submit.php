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

include __DIR__ . '/header.php';
include XOOPS_ROOT_PATH . '/header.php';
include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$mytree = new XoopstubeTree($xoopsDB->prefix('xoopstube_cat'), 'cid', 'pid');

global $xoopsModule, $xtubemyts, $xoopsModuleConfig;

$xoopsTpl->assign(
    'xoops_module_header',
    '<link rel="stylesheet" type="text/css" href="' . xoopstube_url . '/assets/css/xtubestyle.css" />'
);

$cid = intval(xtubeCleanRequestVars($_REQUEST, 'cid', 0));
$lid = intval(xtubeCleanRequestVars($_REQUEST, 'lid', 0));

if (false == xtubeCheckGroups($cid, 'XTubeSubPerm')) {
    redirect_header('index.php', 1, _MD_XOOPSTUBE_NOPERMISSIONTOPOST);
    exit();
}

if (true == xtubeCheckGroups($cid, 'XTubeSubPerm')) {
    if (xtubeCleanRequestVars($_REQUEST, 'submit', 0)) {
        if (false == xtubeCheckGroups($cid, 'XTubeSubPerm')) {
            redirect_header('index.php', 1, _MD_XOOPSTUBE_NOPERMISSIONTOPOST);
            exit();
        }

        $submitter    = (is_object($xoopsUser) && !empty($xoopsUser)) ? $xoopsUser->getVar('uid') : 0;
        $vidsource    = xtubeCleanRequestVars($_REQUEST, 'vidsource', 0);
        $offline      = xtubeCleanRequestVars($_REQUEST, 'offline', 0);
        $notifypub    = xtubeCleanRequestVars($_REQUEST, 'notifypub', 0);
        $approve      = xtubeCleanRequestVars($_REQUEST, 'approve', 0);
        $vidrating    = xtubeCleanRequestVars($_REQUEST, 'vidrating', 0);
        $vidid        = $xtubemyts->addslashes(ltrim($_POST['vidid']));
        $title        = $xtubemyts->addslashes(ltrim($_REQUEST['title']));
        $descriptionb = $xtubemyts->addslashes(ltrim($_REQUEST['descriptionb']));
        $publisher    = $xtubemyts->addslashes(trim($_REQUEST['publisher']));
        $time         = $xtubemyts->addslashes(ltrim($_REQUEST['time']));
        $keywords     = $xtubemyts->addslashes(trim($_REQUEST['keywords']));
        $item_tag     = $xtubemyts->addslashes(ltrim($_REQUEST['item_tag']));
        $picurl       = $xtubemyts->addslashes(ltrim($_REQUEST['picurl']));
        $date         = time();
        $publishdate  = 0;
        $ipaddress    = $_SERVER['REMOTE_ADDR'];

        if ($lid == 0) {
            $status      = 0;
            $publishdate = 0;
            $message     = _MD_XOOPSTUBE_THANKSFORINFO;
            if (true == xtubeCheckGroups($cid, 'XTubeAutoApp')) {
                $publishdate = time();
                $status      = 1;
                $message     = _MD_XOOPSTUBE_ISAPPROVED;
            }
            $sql = 'INSERT INTO ' . $xoopsDB->prefix(
                    'xoopstube_videos'
                )
                . '	(lid, cid, title, vidid, submitter, publisher, status, date, hits, rating, votes, comments, vidsource, published, expired, offline, description, ipaddress, notifypub, vidrating, time, keywords, item_tag, picurl) ';
            $sql .= " VALUES 	('', $cid, '$title', '$vidid', '$submitter', '$publisher', '$status', '$date', 0, 0, 0, 0, '$vidsource', '$publishdate', 0, '$offline', '$descriptionb', '$ipaddress', '$notifypub', '$vidrating', '$time', '$keywords', '$item_tag', '$picurl')";
            if (!$result = $xoopsDB->query($sql)) {
                $_error = $xoopsDB->error() . ' : ' . $xoopsDB->errno();
                XoopsErrorHandler_HandleError(E_USER_WARNING, $_error, __FILE__, __LINE__);
            }
            $newid = mysql_insert_id();

// Add item_tag to Tag-module
            if ($lid == 0) {
                $tagupdate = xtubeUpdateTag($newid, $item_tag);
            } else {
                $tagupdate = xtubeUpdateTag($lid, $item_tag);
            }

// Notify of new link (anywhere) and new link in category
            $notification_handler = & xoops_gethandler('notification');

            $tags               = array();
            $tags['VIDEO_NAME'] = $title;
            $tags['VIDEO_URL']
                                = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $cid . '&amp;lid=' . $newid;

            $sql    = 'SELECT title FROM ' . $xoopsDB->prefix('xoopstube_cat') . ' WHERE cid=' . $cid;
            $result = $xoopsDB->query($sql);
            $row    = $xoopsDB->fetchArray($result);

            $tags['CATEGORY_NAME'] = $row['title'];
            $tags['CATEGORY_URL']
                                   = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;
            if (true == xtubeCheckGroups($cid, 'XTubeAutoApp')) {
                $notification_handler->triggerEvent('global', 0, 'new_video', $tags);
                $notification_handler->triggerEvent('category', $cid, 'new_video', $tags);
                redirect_header('index.php', 2, _MD_XOOPSTUBE_ISAPPROVED);
            } else {
                $tags['WAITINGFILES_URL']
                    = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/newvideos.php';
                $notification_handler->triggerEvent('global', 0, 'video_submit', $tags);
                $notification_handler->triggerEvent('category', $cid, 'video_submit', $tags);
                if ($notifypub) {
                    include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    $notification_handler->subscribe(
                        'video',
                        $newid,
                        'approve',
                        XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE
                    );
                }
                redirect_header('index.php', 2, _MD_XOOPSTUBE_THANKSFORINFO);
            }
        } else {
            if (true == xtubeCheckGroups($cid, 'XTubeAutoApp') || $approve == 1) {
                $updated = time();
                $sql     = "UPDATE " . $xoopsDB->prefix('xoopstube_videos') . " SET cid=$cid, title='$title', vidid='$vidid', publisher='$publisher', updated='$updated', offline='$offline', description='$descriptionb', ipaddress='$ipaddress', notifypub='$notifypub', vidrating='$vidrating', time='$time', keywords='$keywords', item_tag='$item_tag', picurl='$picurl' WHERE lid ="
                    . $lid;
                if (!$result = $xoopsDB->query($sql)) {
                    $_error = $xoopsDB->error() . " : " . $xoopsDB->errno();
                    XoopsErrorHandler_HandleError(E_USER_WARNING, $_error, __FILE__, __LINE__);
                }

                $notification_handler = & xoops_gethandler('notification');
                $tags                 = array();
                $tags['VIDEO_NAME']   = $title;
                $tags['VIDEO_URL']
                                      = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $cid . '&amp;lid=' . $lid;
                $sql
                                       = "SELECT title FROM " . $xoopsDB->prefix('xoopstube_cat') . " WHERE cid=" . $cid;
                $result                = $xoopsDB->query($sql);
                $row                   = $xoopsDB->fetchArray($result);
                $tags['CATEGORY_NAME'] = $row['title'];
                $tags['CATEGORY_URL']
                                       = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;

                $notification_handler->triggerEvent('global', 0, 'new_video', $tags);
                $notification_handler->triggerEvent('category', $cid, 'new_video', $tags);
                $_message = _MD_XOOPSTUBE_ISAPPROVED;
            } else {
                $submitter_array = $xoopsDB->fetchArray(
                    $xoopsDB->query(
                        'SELECT submitter FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE lid=' . intval($lid)
                    )
                );
                $modifysubmitter = $xoopsUser->uid();
                $requestid       = $modifysubmitter;
                $requestdate     = time();
                $updated         = xtubeCleanRequestVars($_REQUEST, 'up_dated', time());
                if ($modifysubmitter == $submitter_array['submitter']) {
                    $sql = 'INSERT INTO ' . $xoopsDB->prefix(
                            'xoopstube_mod'
                        ) . ' (requestid, lid, cid, title, vidid, publisher, vidsource, description, modifysubmitter, requestdate, time, keywords, item_tag, picurl)';
                    $sql .= " VALUES ('', $lid, $cid, '$title', '$vidid', '$publisher', '$vidsource', '$descriptionb', '$modifysubmitter', '$requestdate', '$time', '$keywords', '$item_tag', '$picurl')";
                    if (!$result = $xoopsDB->query($sql)) {
                        $_error = $xoopsDB->error() . " : " . $xoopsDB->errno();
                        XoopsErrorHandler_HandleError(E_USER_WARNING, $_error, __FILE__, __LINE__);
                    }
                } else {
                    redirect_header('index.php', 2, _MD_XOOPSTUBE_MODIFYNOTALLOWED);
                }

                $tags = array();
                $tags['MODIFYREPORTS_URL']
                                      = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listModReq';
                $notification_handler = & xoops_gethandler('notification');
                $notification_handler->triggerEvent('global', 0, 'video_modify', $tags);

                $tags['WAITINGFILES_URL']
                    = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listNewvideos';
                $notification_handler->triggerEvent('global', 0, 'video_submit', $tags);
                $notification_handler->triggerEvent('category', $cid, 'video_submit', $tags);
                if ($notifypub) {
                    include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    $notification_handler->subscribe(
                        'video',
                        $newid,
                        'approve',
                        XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE
                    );
                }
                $_message = _MD_XOOPSTUBE_THANKSFORINFO;
            }
            redirect_header('index.php', 2, $_message);
        }
    } else {
        global $xoopsModuleConfig;

        $approve = xtubeCleanRequestVars($_REQUEST, 'approve', 0);

// Show disclaimer
        if ($xoopsModuleConfig['showdisclaimer'] && !isset($_GET['agree']) && $approve == 0) {
            echo '<br /><div style="text-align: center;">' . xtubeRenderImageHeader() . '</div><br />';
            echo '<h4>' . _MD_XOOPSTUBE_DISCLAIMERAGREEMENT . '</h4>';
            echo '<div>' . $xtubemyts->displayTarea($xoopsModuleConfig['disclaimer'], 1, 1, 1, 1, 1) . '</div>';
            echo '<form action="submit.php" method="post">';
            echo '<div style="text-align: center;">' . _MD_XOOPSTUBE_DOYOUAGREE . '</b><br /><br />';
            echo '<input type="button" onclick="location=\'submit.php?agree=1\'" class="formButton" value="' . _MD_XOOPSTUBE_AGREE . '" alt="' . _MD_XOOPSTUBE_AGREE . '" />';
            echo '&nbsp;';
            echo '<input type="button" onclick="location=\'index.php\'" class="formButton" value="' . _CANCEL . '" alt="' . _CANCEL . '" />';
            echo '</div></form>';
            include XOOPS_ROOT_PATH . '/footer.php';
            exit();
        }
        echo '<br /><div style="text-align: center;">' . xtubeRenderImageHeader() . '</div><br />';
        echo '<div>' . _MD_XOOPSTUBE_SUB_SNEWMNAMEDESC . '</div>';
//        echo "<div class='xoopstube_singletitle'>" . _MD_XOOPSTUBE_SUBMITCATHEAD . "</div>\n";

        $sql         = 'SELECT * FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE lid=' . intval($lid);
        $video_array = $xoopsDB->fetchArray($xoopsDB->query($sql));

        $lid          = $video_array['lid'] ? $video_array['lid'] : 0;
        $cid          = $video_array['cid'] ? $video_array['cid'] : 0;
        $title        = $video_array['title'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['title']) : '';
        $vidid        = $video_array['vidid'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['vidid']) : '';
        $publisher    = $video_array['publisher'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['publisher']) : '';
        $screenshot   = $video_array['screenshot'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['screenshot']) : '';
        $descriptionb = $video_array['description'] ? $xtubemyts->htmlSpecialCharsStrip(
            $video_array['description']
        ) : '';
        $published    = $video_array['published'] ? $video_array['published'] : 0;
        $expired      = $video_array['expired'] ? $video_array['expired'] : 0;
        $updated      = $video_array['updated'] ? $video_array['updated'] : 0;
        $offline      = $video_array['offline'] ? $video_array['offline'] : 0;
        $vidsource    = $video_array['vidsource'] ? $video_array['vidsource'] : 0;
        $ipaddress    = $video_array['ipaddress'] ? $video_array['ipaddress'] : 0;
        $notifypub    = $video_array['notifypub'] ? $video_array['notifypub'] : 0;
        $vidrating    = $video_array['vidrating'] ? $video_array['vidrating'] : 1;
        $time         = $video_array['time'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['time']) : '0:00:00';
        $keywords     = $video_array['keywords'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['keywords']) : '';
        $item_tag     = $video_array['item_tag'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['item_tag']) : '';
        $picurl       = $video_array['picurl'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['picurl']) : 'http://';

        $sform = new XoopsThemeForm(_MD_XOOPSTUBE_SUBMITCATHEAD, 'storyform', xoops_getenv('PHP_SELF'));
        $sform->setExtra('enctype="multipart/form-data"');

        xtubeSetNoIndexNoFollow();

// Video title form
        $sform->addElement(new XoopsFormText(_MD_XOOPSTUBE_FILETITLE, 'title', 70, 255, $title), true);

// Video source form
        $vidsource_array  = array(
            0   => _MD_XOOPSTUBE_YOUTUBE,
            1   => _MD_XOOPSTUBE_METACAFE,
            2   => _MD_XOOPSTUBE_IFILM,
            3   => _MD_XOOPSTUBE_PHOTOBUCKET,
            4   => _MD_XOOPSTUBE_VIDDLER,
            100 => _MD_XOOPSTUBE_GOOGLEVIDEO,
            101 => _MD_XOOPSTUBE_MYSPAVETV,
            102 => _MD_XOOPSTUBE_DAILYMOTION,
            103 => _MD_XOOPSTUBE_BLIPTV,
            104 => _MD_XOOPSTUBE_CLIPFISH,
            105 => _MD_XOOPSTUBE_LIVELEAK,
            106 => _MD_XOOPSTUBE_MAKTOOB,
            107 => _MD_XOOPSTUBE_VEOH,
            108 => _MD_XOOPSTUBE_VIMEO,
            109 => _MD_XOOPSTUBE_MEGAVIDEO,
            200 => _MD_XOOPSTUBE_XOOPSTUBE
        );
        $vidsource_select = new XoopsFormSelect(_MD_XOOPSTUBE_VIDSOURCE, 'vidsource', $vidsource);
        $vidsource_select->addOptionArray($vidsource_array);
        $sform->addElement($vidsource_select, false);

// Video code form
        $videocode = new XoopsFormText(_MD_XOOPSTUBE_DLVIDID, 'vidid', 70, 512, $vidid);
        $videocode->setDescription('<br /><span style="font-size: small;">' . _MD_XOOPSTUBE_VIDEO_DLVIDIDDSC . '</span>');
        $sform->addElement($videocode, true);
        $sform->addElement(new XoopsFormLabel('', _MD_XOOPSTUBE_VIDEO_DLVIDID_NOTE));

// Picture url form
        $picurl = new XoopsFormText(_MD_XOOPSTUBE_VIDEO_PICURL, 'picurl', 70, 255, $picurl);
        $picurl->setDescription('<br /><span style="font-weight: normal;">' . _MD_XOOPSTUBE_VIDEO_PICURLNOTE . '</span>');
        $sform->addElement($picurl, false);

// Video publisher form
        $sform->addElement(new XoopsFormText(_MD_XOOPSTUBE_VIDEO_PUBLISHER, 'publisher', 70, 255, $publisher), true);

// Category tree
        $mytree = new XoopstubeTree($xoopsDB->prefix('xoopstube_cat'), 'cid', 'pid');

        $submitcats = array();
        $sql        = 'SELECT * FROM ' . $xoopsDB->prefix('xoopstube_cat') . ' ORDER BY title';
        $result     = $xoopsDB->query($sql);
        while ($myrow = $xoopsDB->fetchArray($result)) {
            if (true == xtubeCheckGroups($myrow['cid'], 'XTubeSubPerm')) {
                $submitcats[$myrow['cid']] = $myrow['title'];
            }
        }

// Video time form
        $timeform = new XoopsFormText(_MD_XOOPSTUBE_TIME, 'time', 7, 7, $time);
        $timeform->setDescription('<span style="font-size: small;">(h:mm:ss)</span>');
        $sform->addElement($timeform, false);

// Video category form
        ob_start();
        $mytree->makeMySelBox('title', 'title', $cid, 0);
        $sform->addElement(new XoopsFormLabel(_MD_XOOPSTUBE_CATEGORYC, ob_get_contents()));
        ob_end_clean();

// Video description form
//        $editor = xtube_getWysiwygForm( _MD_XOOPSTUBE_DESCRIPTIONC, 'descriptionb', $descriptionb, 10, 50, '');
//        $sform -> addElement( $editor, true );

        $optionsTrayNote = new XoopsFormElementTray(_MD_XOOPSTUBE_DESCRIPTIONC, '<br />');
        if (class_exists('XoopsFormEditor')) {
            $options['name']   = 'descriptionb';
            $options['value']  = $descriptionb;
            $options['rows']   = 5;
            $options['cols']   = '100%';
            $options['width']  = '100%';
            $options['height'] = '200px';
            $editor            = new XoopsFormEditor('', $xoopsModuleConfig['form_optionsuser'], $options, $nohtml = false, $onfailure = 'textarea');
            $optionsTrayNote->addElement($editor);
        } else {
            $editor = new XoopsFormDhtmlTextArea(
                '', 'descriptionb', $item->getVar(
                    'descriptionb',
                    'e'
                ), '100%', '100%'
            );
            $optionsTrayNote->addElement($editor);
        }

        $sform->addElement($optionsTrayNote, false);

// Meta keywords form
        $keywords = new XoopsFormTextArea(_MD_XOOPSTUBE_KEYWORDS, 'keywords', $keywords, 5, 50, false);
        $keywords->setDescription('<br /><span style="font-size: smaller;">' . _MD_XOOPSTUBE_KEYWORDS_NOTE . '</span>');
        $sform->addElement($keywords);

        if ($xoopsModuleConfig['usercantag'] == 1) {
// Insert tags if Tag-module is installed
            if (xtubeIsModuleTagInstalled()) {
                include_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
                $text_tags = new XoopsFormTag('item_tag', 70, 255, $video_array['item_tag'], 0);
                $sform->addElement($text_tags);
            }
        } else {
            $sform->addElement(new XoopsFormHidden('item_tag', $video_array['item_tag']));
        }

        $submitter2 = (is_object($xoopsUser) && !empty($xoopsUser)) ? $xoopsUser->getVar('uid') : 0;
        if ($submitter2 > 0) {
            $option_tray = new XoopsFormElementTray(_MD_XOOPSTUBE_OPTIONS, '<br />');

            if (!$approve) {
                $notify_checkbox = new XoopsFormCheckBox('', 'notifypub');
                $notify_checkbox->addOption(1, _MD_XOOPSTUBE_NOTIFYAPPROVE);
                $option_tray->addElement($notify_checkbox);
            } else {
                $sform->addElement(new XoopsFormHidden('notifypub', 0));
            }
        }

        if (true == xtubeCheckGroups($cid, 'XTubeAppPerm') && $lid > 0) {
            $approve_checkbox = new XoopsFormCheckBox('', 'approve', $approve);
            $approve_checkbox->addOption(1, _MD_XOOPSTUBE_APPROVE);
            $option_tray->addElement($approve_checkbox);
        } else {
            if (true == xtubeCheckGroups($cid, 'XTubeAutoApp')) {
                $sform->addElement(new XoopsFormHidden('approve', 1));
            } else {
                $sform->addElement(new XoopsFormHidden('approve', 0));
            }
        }
        $sform->addElement($option_tray);

        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $button_tray->addElement(new XoopsFormHidden('lid', $lid));

        $sform->addElement($button_tray);
        $sform->display();

        include XOOPS_ROOT_PATH . '/footer.php';
    }
} else {
    redirect_header('index.php', 2, _MD_XOOPSTUBE_NOPERMISSIONTOPOST);
    exit();
}
