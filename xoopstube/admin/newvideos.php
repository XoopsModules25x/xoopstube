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

$op  = xtubeCleanRequestVars($_REQUEST, 'op', '');
$lid = xtubeCleanRequestVars($_REQUEST, 'lid', '');
$lid = intval($lid);

switch (strtolower($op)) {
    case 'approve':

        global $xoopsModule;
        $sql
            = 'SELECT cid, title, publisher, notifypub FROM ' . $xoopsDB->prefix('xoopstube_videos')
              . ' WHERE lid=' . $lid;
        if (!$result = $xoopsDB->query($sql)) {
            XoopsErrorHandler_HandleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

            return false;
        }
        list($cid, $title, $publisher, $notifypub) = $xoopsDB->fetchRow($result);

        // Update the database
        $time = time();
//        $publisher = $xoopsUser -> getVar( 'uname' );

//        $xoopsDB->queryF(
//            'UPDATE ' . $xoopsDB->prefix('xoopstube_videos') . ' SET published=' . $time . ', status=1, publisher='
//            . $publisher . ' WHERE lid=' . $lid
//        );

        $sql= 'UPDATE ' . $xoopsDB->prefix('xoopstube_videos') . ' SET published=' . $time . ", status=1, publisher='" . $publisher . "' WHERE lid=" . $lid;
        if (!$result = $xoopsDB->queryF($sql)) {
            XoopsErrorHandler_HandleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

            return false;
        }

        $tags               = array();
        $tags['VIDEO_NAME'] = $title;
        $tags['VIDEO_URL']
                            = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $cid
                              . '&amp;lid=' . $lid;

        $sql = 'SELECT title FROM ' . $xoopsDB->prefix('xoopstube_cat') . ' WHERE cid=' . $cid;
        if (!$result = $xoopsDB->query($sql)) {
            XoopsErrorHandler_HandleError(E_USER_WARNING, $sql, __FILE__, __LINE__);
        } else {
            $row                   = $xoopsDB->fetchArray($result);
            $tags['CATEGORY_NAME'] = $row['title'];
            $tags['CATEGORY_URL']
                                   =
                XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;
            $notification_handler  = & xoops_gethandler('notification');
            $notification_handler->triggerEvent('global', 0, 'new_video', $tags);
            $notification_handler->triggerEvent('category', $cid, 'new_video', $tags);
            if (intval($notifypub) == 1) {
                $notification_handler->triggerEvent('video', $lid, 'approve', $tags);
            }
        }
        redirect_header('main.php', 1, _AM_XTUBE_SUB_NEWFILECREATED);
        break;

    case 'main':
    default:

        global $xoopsModuleConfig;

        $start = xtubeCleanRequestVars($_REQUEST, 'start', 0);
        $sql   = 'SELECT * FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE published = 0 ORDER BY lid DESC';
        if (!$result = $xoopsDB->query($sql)) {
            XoopsErrorHandler_HandleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

            return false;
        }
        $new_array       = $xoopsDB->query($sql, $xoopsModuleConfig['admin_perpage'], $start);
        $new_array_count = $xoopsDB->getRowsNum($xoopsDB->query($sql));

        xoops_cp_header();
        //xtubeRenderAdminMenu( _AM_XTUBE_SUB_SUBMITTEDFILES );

        echo '  <div style="padding:5px; background-color: #EEEEEE; border: 1px solid #D9D9D9;">
                <span style="font-weight: bold; color: #0A3760;">' . _AM_XTUBE_SUB_FILESWAITINGINFO . '<br /><br /></span>
                <span style="padding: 12px;">' . _AM_XTUBE_SUB_FILESWAITINGVALIDATION . '<b>' . $new_array_count . '</b><br /><br /><span>
                <div style="padding: 8px;"><li>&nbsp;&nbsp;' . $xtubeImageArray['approve'] . ' '
             . _AM_XTUBE_SUB_APPROVEWAITINGFILE . '<br />
                <li>&nbsp;&nbsp;' . $xtubeImageArray['editimg'] . ' ' . _AM_XTUBE_SUB_EDITWAITINGFILE . '<br />
                <li>&nbsp;&nbsp;' . $xtubeImageArray['deleteimg'] . ' ' . _AM_XTUBE_SUB_DELETEWAITINGFILE . '</div>
                </div><br />
             ';

        echo '<table width="100%" cellspacing="1" class="outer">';
        echo '<tr style="text-align: center;">';
        echo '<th><span style="font-size: small;">' . _AM_XTUBE_MINDEX_ID . '</span></th>';
        echo '<th style="text-align: left;"><span style="font-size: small;">' . _AM_XTUBE_MINDEX_TITLE . '</span></th>';
        echo '<th style="text-align: center;"><span style="font-size: small;">' . _AM_XTUBE_VIDSOURCE2 . '</span></th>';
        echo '<th><span style="font-size: small;">' . _AM_XTUBE_MINDEX_POSTER . '</span></th>';
        echo '<th><span style="font-size: small;">' . _AM_XTUBE_MINDEX_SUBMITTED . '</span></th>';
        echo '<th><span style="font-size: small;">' . _AM_XTUBE_MINDEX_ACTION . '</span></th>';
        echo '</tr>';
        if ($new_array_count > 0) {
            while ($new = $xoopsDB->fetchArray($new_array)) {
                $lid          = intval($new['lid']);
                $rating       = number_format($new['rating'], 2);
                $title        = $xtubemyts->htmlSpecialCharsStrip($new['title']);
                $vidid        = urldecode($xtubemyts->htmlSpecialCharsStrip($new['vidid']));
                $logourl      = $xtubemyts->htmlSpecialCharsStrip($new['screenshot']);
                $submitter    = xtubeGetLinkedUserNameFromId($new['submitter']);
                $returnsource = xtubeReturnSource($new['vidsource']);
                $datetime     = xtubeGetTimestamp(formatTimestamp($new['date'], $xoopsModuleConfig['dateformatadmin']));

                $icon = ($new['published']) ? $approved
                    : '<a href="newvideos.php?op=approve&amp;lid=' . $lid . '">' . $xtubeImageArray['approve'] . ' </a>';
                $icon .= '<a href="main.php?op=edit&amp;lid=' . $lid . '">' . $xtubeImageArray['editimg'] . ' </a>';
                $icon .= '<a href="main.php?op=delete&amp;lid=' . $lid . '">' . $xtubeImageArray['deleteimg'] . '</a>';

                echo '<tr>';
                echo '<td class="head" style="text-align: center;"><span style="font-size: small;">' . $lid
                     . '</span></td>';
                echo '<td class="even" nowrap><a href="newvideos.php?op=edit&amp;lid=' . $lid
                     . '"><span style="font-size: small;">' . $title . '</span></a></td>';
                echo '<td class="even" style="text-align: center;" nowrap><span style="font-size: small;">'
                     . $returnsource . '</span></td>';
                echo '<td class="even" style="text-align: center;" nowrap><span style="font-size: small;">' . $submitter
                     . '</span></td>';
                echo '<td class="even" style="text-align: center;"><span style="font-size: small;">' . $datetime
                     . '</span></td>';
                echo '<td class="even" style="text-align: center;" nowrap>' . $icon . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td style="text-align: center;" class="head" colspan="6">' . _AM_XTUBE_SUB_NOFILESWAITING
                 . '</td></tr>';
        }
        echo '</table>';

        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
//        $page = ( $new_array_count > $xoopsModuleConfig['admin_perpage'] ) ? _AM_XTUBE_MINDEX_PAGE : '';
        $pagenav = new XoopsPageNav($new_array_count, $xoopsModuleConfig['admin_perpage'], $start, 'start');
        echo '<div align="right" style="padding: 8px;">' . $pagenav->renderNav() . '</div>';
        include 'admin_footer.php';
        break;
}
