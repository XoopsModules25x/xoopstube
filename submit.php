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

include __DIR__ . '/header.php';
include XOOPS_ROOT_PATH . '/header.php';
include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$mytree = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');

global $xoopsModule, $xtubemyts;

$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="' . $moduleDirName . '/assets/css/xtubestyle.css">');

$cid = Request::getInt('cid', 0); //(int) cleanRequestVars($_REQUEST, 'cid', 0);
$lid = Request::getInt('lid', 0); //(int) cleanRequestVars($_REQUEST, 'lid', 0);

if (false === Xoopstube\Utility::checkGroups($cid, 'XTubeSubPerm')) {
    redirect_header('index.php', 1, _MD_XOOPSTUBE_NOPERMISSIONTOPOST);
}

if (true === Xoopstube\Utility::checkGroups($cid, 'XTubeSubPerm')) {
    echo '<div class="row">
    <div class="col-md-12">';
    echo '<ol class="breadcrumb">
        <li><a href="index.php">' . $moduleDirName . '</a></li>
        <li>' . _MD_XOOPSTUBE_SUBMITCATHEAD . '</li>
    </ol>
    ';
    //    if (cleanRequestVars($_REQUEST, 'submit', 0)) {
    if (Request::hasVar('submit')) {
        if (false === Xoopstube\Utility::checkGroups($cid, 'XTubeSubPerm')) {
            redirect_header('index.php', 1, _MD_XOOPSTUBE_NOPERMISSIONTOPOST);
        }

        $submitter    = (is_object($GLOBALS['xoopsUser']) && !empty($GLOBALS['xoopsUser'])) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
        $vidsource    = Request::getInt('vidsource', 0, 'POST'); // cleanRequestVars($_REQUEST, 'vidsource', 0);
        $offline      = Request::getInt('offline', 0, 'POST'); // cleanRequestVars($_REQUEST, 'offline', 0);
        $notifypub    = Request::getInt('notifypub', 0, 'POST'); // cleanRequestVars($_REQUEST, 'notifypub', 0);
        $approve      = Request::getInt('approve', 0, 'POST'); // cleanRequestVars($_REQUEST, 'approve', 0);
        $vidrating    = Request::getInt('vidrating', 0, 'POST'); // cleanRequestVars($_REQUEST, 'vidrating', 0);
        $vidid        = Request::getString('vidid', 0, 'POST'); // $xtubemyts->addslashes(ltrim(Request::getInt('vidid', 0, 'POST')));
        $title        = Request::getString('title', '', 'POST'); // $xtubemyts->addslashes(ltrim($_REQUEST['title']));
        $descriptionb = Request::getString('descriptionb', '', 'POST'); // $xtubemyts->addslashes(ltrim($_REQUEST['descriptionb']));
        $publisher    = Request::getString('publisher', '', 'POST'); // $xtubemyts->addslashes(trim($_REQUEST['publisher']));
        $time         = Request::getString('time', '', 'POST'); // $xtubemyts->addslashes(ltrim($_REQUEST['time']));
        $keywords     = Request::getString('keywords', '', 'POST'); // $xtubemyts->addslashes(trim($_REQUEST['keywords']));
        $item_tag     = Request::getString('item_tag', '', 'POST'); // $xtubemyts->addslashes(ltrim($_REQUEST['item_tag']));
        $picurl       = Request::getString('picurl', '', 'POST'); // $xtubemyts->addslashes(ltrim($_REQUEST['picurl']));
        $date         = time();
        $publishdate  = 0;
        $ipaddress    = $_SERVER['REMOTE_ADDR'];

        if (0 == $lid) {
            $status      = 0;
            $publishdate = 0;
            $message     = _MD_XOOPSTUBE_THANKSFORINFO;
            if (true === Xoopstube\Utility::checkGroups($cid, 'XTubeAutoApp')) {
                $publishdate = time();
                $status      = 1;
                $message     = _MD_XOOPSTUBE_ISAPPROVED;
            }
            $sql = 'INSERT INTO '
                   . $GLOBALS['xoopsDB']->prefix('xoopstube_videos')
                   . '  (lid, cid, title, vidid, submitter, publisher, status, date, hits, rating, votes, comments, vidsource, published, expired, offline, description, ipaddress, notifypub, vidrating, time, keywords, item_tag, picurl) ';
            $sql .= " VALUES    ('', $cid, '$title', '$vidid', '$submitter', '$publisher', '$status', '$date', 0, 0, 0, 0, '$vidsource', '$publishdate', 0, '$offline', '$descriptionb', '$ipaddress', '$notifypub', '$vidrating', '$time', '$keywords', '$item_tag', '$picurl')";
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                $_error = $GLOBALS['xoopsDB']->error() . ' : ' . $GLOBALS['xoopsDB']->errno();
                /** @var \XoopsLogger $logger */
                $logger = \XoopsLogger::getInstance();
                $logger->handleError(E_USER_WARNING, $_error, __FILE__, __LINE__);
            }
            $newid = $GLOBALS['xoopsDB']->getInsertId();

            // Add item_tag to Tag-module
            if (0 == $lid) {
                $tagupdate = Xoopstube\Utility::updateTag($newid, $item_tag);
            } else {
                $tagupdate = Xoopstube\Utility::updateTag($lid, $item_tag);
            }

            // Notify of new link (anywhere) and new link in category
            $notificationHandler = xoops_getHandler('notification');

            $tags               = [];
            $tags['VIDEO_NAME'] = $title;
            $tags['VIDEO_URL']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $cid . '&amp;lid=' . $newid;

            $sql    = 'SELECT title FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' WHERE cid=' . $cid;
            $result = $GLOBALS['xoopsDB']->query($sql);
            $row    = $GLOBALS['xoopsDB']->fetchArray($result);

            $tags['CATEGORY_NAME'] = $row['title'];
            $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;
            if (true === Xoopstube\Utility::checkGroups($cid, 'XTubeAutoApp')) {
                $notificationHandler->triggerEvent('global', 0, 'new_video', $tags);
                $notificationHandler->triggerEvent('category', $cid, 'new_video', $tags);
                redirect_header('index.php', 2, _MD_XOOPSTUBE_ISAPPROVED);
            } else {
                $tags['WAITINGFILES_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/newvideos.php';
                $notificationHandler->triggerEvent('global', 0, 'video_submit', $tags);
                $notificationHandler->triggerEvent('category', $cid, 'video_submit', $tags);
                if ($notifypub) {
                    require_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    $notificationHandler->subscribe('video', $newid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
                }
                redirect_header('index.php', 2, _MD_XOOPSTUBE_THANKSFORINFO);
            }
        } else {
            if (true === Xoopstube\Utility::checkGroups($cid, 'XTubeAutoApp') || 1 === $approve) {
                $updated = time();
                $sql     = 'UPDATE '
                           . $GLOBALS['xoopsDB']->prefix('xoopstube_videos')
                           . " SET cid=$cid, title='$title', vidid='$vidid', publisher='$publisher', updated='$updated', offline='$offline', description='$descriptionb', ipaddress='$ipaddress', notifypub='$notifypub', vidrating='$vidrating', time='$time', keywords='$keywords', item_tag='$item_tag', picurl='$picurl' WHERE lid ="
                           . $lid;
                if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                    $_error = $GLOBALS['xoopsDB']->error() . ' : ' . $GLOBALS['xoopsDB']->errno();
                    $logger->handleError(E_USER_WARNING, $_error, __FILE__, __LINE__);
                }

                $notificationHandler   = xoops_getHandler('notification');
                $tags                  = [];
                $tags['VIDEO_NAME']    = $title;
                $tags['VIDEO_URL']     = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $cid . '&amp;lid=' . $lid;
                $sql                   = 'SELECT title FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' WHERE cid=' . $cid;
                $result                = $GLOBALS['xoopsDB']->query($sql);
                $row                   = $GLOBALS['xoopsDB']->fetchArray($result);
                $tags['CATEGORY_NAME'] = $row['title'];
                $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;

                $notificationHandler->triggerEvent('global', 0, 'new_video', $tags);
                $notificationHandler->triggerEvent('category', $cid, 'new_video', $tags);
                $_message = _MD_XOOPSTUBE_ISAPPROVED;
            } else {
                $submitter_array = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query('SELECT submitter FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid));
                $modifysubmitter = $GLOBALS['xoopsUser']->uid();
                $requestid       = $modifysubmitter;
                $requestdate     = time();
                $updated         = Request::getInt('up_dated', time(), 'POST'); //cleanRequestVars($_REQUEST, 'up_dated', time());
                if ($modifysubmitter === $submitter_array['submitter']) {
                    $sql = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . ' (requestid, lid, cid, title, vidid, publisher, vidsource, description, modifysubmitter, requestdate, time, keywords, item_tag, picurl)';
                    $sql .= " VALUES ('', $lid, $cid, '$title', '$vidid', '$publisher', '$vidsource', '$descriptionb', '$modifysubmitter', '$requestdate', '$time', '$keywords', '$item_tag', '$picurl')";
                    if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                        $_error = $GLOBALS['xoopsDB']->error() . ' : ' . $GLOBALS['xoopsDB']->errno();
                        $logger->handleError(E_USER_WARNING, $_error, __FILE__, __LINE__);
                    }
                } else {
                    redirect_header('index.php', 2, _MD_XOOPSTUBE_MODIFYNOTALLOWED);
                }

                $tags                      = [];
                $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listModReq';
                $notificationHandler       = xoops_getHandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'video_modify', $tags);

                $tags['WAITINGFILES_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listNewvideos';
                $notificationHandler->triggerEvent('global', 0, 'video_submit', $tags);
                $notificationHandler->triggerEvent('category', $cid, 'video_submit', $tags);
                if ($notifypub) {
                    require_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    $notificationHandler->subscribe('video', $newid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
                }
                $_message = _MD_XOOPSTUBE_THANKSFORINFO;
            }
            redirect_header('index.php', 2, $_message);
        }
    } else {
        $approve = Request::getInt('approve', 0, 'POST'); // cleanRequestVars($_REQUEST, 'approve', 0);

        // Show disclaimer
        if ($GLOBALS['xoopsModuleConfig']['showdisclaimer'] && !Request::getInt('agree', '', 'GET') && 0 == $approve) {
            echo '<br><div style="text-align: center;">' . Xoopstube\Utility::renderImageHeader() . '</div><br>';
            echo '<h4>' . _MD_XOOPSTUBE_DISCLAIMERAGREEMENT . '</h4>';
            echo '<div>' . $xtubemyts->displayTarea($GLOBALS['xoopsModuleConfig']['disclaimer'], 1, 1, 1, 1, 1) . '</div>';
            echo '<form action="submit.php" method="post">';
            echo '<div style="text-align: center;">' . _MD_XOOPSTUBE_DOYOUAGREE . '</b><br><br>';
            echo '<input type="button" onclick="location=\'submit.php?agree=1\'" class="formButton" value="' . _MD_XOOPSTUBE_AGREE . '" alt="' . _MD_XOOPSTUBE_AGREE . '">';
            echo '&nbsp;';
            echo '<input type="button" onclick="location=\'index.php\'" class="formButton" value="' . _CANCEL . '" alt="' . _CANCEL . '">';
            echo '</div></form>';
            include XOOPS_ROOT_PATH . '/footer.php';
            exit();
        }
        //        echo '<br><div style="text-align: center;">' . Xoopstube\Utility::renderImageHeader() . '</div><br>';
        echo '<div>' . _MD_XOOPSTUBE_SUB_SNEWMNAMEDESC . '</div>';
        //        echo "<div class='xoopstube_singletitle'>" . _MD_XOOPSTUBE_SUBMITCATHEAD . "</div>\n";

        $sql         = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid;
        $video_array = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));

        $lid          = $video_array['lid'] ?: 0;
        $cid          = $video_array['cid'] ?: 0;
        $title        = $video_array['title'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['title']) : '';
        $vidid        = $video_array['vidid'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['vidid']) : '';
        $publisher    = $video_array['publisher'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['publisher']) : '';
        $screenshot   = $video_array['screenshot'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['screenshot']) : '';
        $descriptionb = $video_array['description'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['description']) : '';
        $published    = $video_array['published'] ?: 0;
        $expired      = $video_array['expired'] ?: 0;
        $updated      = $video_array['updated'] ?: 0;
        $offline      = $video_array['offline'] ?: 0;
        $vidsource    = $video_array['vidsource'] ?: 0;
        $ipaddress    = $video_array['ipaddress'] ?: 0;
        $notifypub    = $video_array['notifypub'] ?: 0;
        $vidrating    = $video_array['vidrating'] ?: 1;
        $time         = $video_array['time'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['time']) : '0:00:00';
        $keywords     = $video_array['keywords'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['keywords']) : '';
        $item_tag     = $video_array['item_tag'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['item_tag']) : '';
        $picurl       = $video_array['picurl'] ? $xtubemyts->htmlSpecialCharsStrip($video_array['picurl']) : 'http://';

        $sform = new \XoopsThemeForm(_MD_XOOPSTUBE_SUBMITCATHEAD, 'storyform', xoops_getenv('PHP_SELF'), 'post', true);
        $sform->setExtra('enctype="multipart/form-data"');

        Xoopstube\Utility::setNoIndexNoFollow();

        // Video title form
        $sform->addElement(new \XoopsFormText(_MD_XOOPSTUBE_FILETITLE, 'title', 70, 255, $title), true);

        // Video source form
        $vidsource_array  = [
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
        ];
        $vidsource_select = new \XoopsFormSelect(_MD_XOOPSTUBE_VIDSOURCE, 'vidsource', $vidsource);
        $vidsource_select->addOptionArray($vidsource_array);
        $sform->addElement($vidsource_select, false);

        // Video code form
        $videocode = new \XoopsFormText(_MD_XOOPSTUBE_DLVIDID, 'vidid', 70, 512, $vidid);
        $videocode->setDescription('<br><span style="font-size: small;">' . _MD_XOOPSTUBE_VIDEO_DLVIDIDDSC . '</span>');
        $sform->addElement($videocode, true);
        $sform->addElement(new \XoopsFormLabel('', _MD_XOOPSTUBE_VIDEO_DLVIDID_NOTE));

        // Picture url form
        $picurl = new \XoopsFormText(_MD_XOOPSTUBE_VIDEO_PICURL, 'picurl', 70, 255, $picurl);
        $picurl->setDescription('<br><span style="font-weight: normal;">' . _MD_XOOPSTUBE_VIDEO_PICURLNOTE . '</span>');
        $sform->addElement($picurl, false);

        // Video publisher form
        $sform->addElement(new \XoopsFormText(_MD_XOOPSTUBE_VIDEO_PUBLISHER, 'publisher', 70, 255, $publisher), true);

        // Category tree
        $mytree = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');

        $submitcats = [];
        $sql        = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' ORDER BY title';
        $result     = $GLOBALS['xoopsDB']->query($sql);
        while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
            if (true === Xoopstube\Utility::checkGroups($myrow['cid'], 'XTubeSubPerm')) {
                $submitcats[$myrow['cid']] = $myrow['title'];
            }
        }

        // Video time form
        $timeform = new \XoopsFormText(_MD_XOOPSTUBE_TIME, 'time', 7, 7, $time);
        $timeform->setDescription('<span style="font-size: small;">(h:mm:ss)</span>');
        $sform->addElement($timeform, false);

        // Video category form
        ob_start();
        $mytree->makeMySelBox('title', 'title', $cid, 0);
        $sform->addElement(new \XoopsFormLabel(_MD_XOOPSTUBE_CATEGORYC, ob_get_contents()));
        ob_end_clean();

        // Video description form
        //        $editor = xtube_getWysiwygForm( _MD_XOOPSTUBE_DESCRIPTIONC, 'descriptionb', $descriptionb, 10, 50, '');
        //        $sform -> addElement( $editor, true );

        $optionsTrayNote = new \XoopsFormElementTray(_MD_XOOPSTUBE_DESCRIPTIONC, '<br>');
        if (class_exists('XoopsFormEditor')) {
            $options['name']   = 'descriptionb';
            $options['value']  = $descriptionb;
            $options['rows']   = 5;
            $options['cols']   = '100%';
            $options['width']  = '100%';
            $options['height'] = '200px';
            $editor            = new \XoopsFormEditor('', $GLOBALS['xoopsModuleConfig']['form_optionsuser'], $options, $nohtml = false, $onfailure = 'textarea');
            $optionsTrayNote->addElement($editor);
        } else {
            $editor = new \XoopsFormDhtmlTextArea('', 'descriptionb', $item->getVar('descriptionb', 'e'), '100%', '100%');
            $optionsTrayNote->addElement($editor);
        }

        $sform->addElement($optionsTrayNote, false);

        // Meta keywords form
        $keywords = new \XoopsFormTextArea(_MD_XOOPSTUBE_KEYWORDS, 'keywords', $keywords, 5, 50, false);
        $keywords->setDescription('<br><span style="font-size: smaller;">' . _MD_XOOPSTUBE_KEYWORDS_NOTE . '</span>');
        $sform->addElement($keywords);

        if (1 == $GLOBALS['xoopsModuleConfig']['usercantag']) {
            // Insert tags if Tag-module is installed
            if (Xoopstube\Utility::isModuleTagInstalled()) {
                require_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
                $text_tags = new TagFormTag('item_tag', 70, 255, $video_array['item_tag'], 0);
                $sform->addElement($text_tags);
            }
        } else {
            $sform->addElement(new \XoopsFormHidden('item_tag', $video_array['item_tag']));
        }

        $submitter2 = (is_object($GLOBALS['xoopsUser']) && !empty($GLOBALS['xoopsUser'])) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
        if ($submitter2 > 0) {
            $option_tray = new \XoopsFormElementTray(_MD_XOOPSTUBE_OPTIONS, '<br>');

            if (!$approve) {
                $notify_checkbox = new \XoopsFormCheckBox('', 'notifypub');
                $notify_checkbox->addOption(1, _MD_XOOPSTUBE_NOTIFYAPPROVE);
                $option_tray->addElement($notify_checkbox);
            } else {
                $sform->addElement(new \XoopsFormHidden('notifypub', 0));
            }
        }

        if (true === Xoopstube\Utility::checkGroups($cid, 'XTubeAppPerm') && $lid > 0) {
            $approve_checkbox = new \XoopsFormCheckBox('', 'approve', $approve);
            $approve_checkbox->addOption(1, _MD_XOOPSTUBE_APPROVE);
            $option_tray->addElement($approve_checkbox);
        } else {
            if (true === Xoopstube\Utility::checkGroups($cid, 'XTubeAutoApp')) {
                $sform->addElement(new \XoopsFormHidden('approve', 1));
            } else {
                $sform->addElement(new \XoopsFormHidden('approve', 0));
            }
        }
        $sform->addElement($option_tray);

        $button_tray = new \XoopsFormElementTray('', '');
        $button_tray->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $button_tray->addElement(new \XoopsFormHidden('lid', $lid));

        $sform->addElement($button_tray);
        $sform->display();

        echo '</div></div>';

        include XOOPS_ROOT_PATH . '/footer.php';
    }
} else {
    redirect_header('index.php', 2, _MD_XOOPSTUBE_NOPERMISSIONTOPOST);
}
