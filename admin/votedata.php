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

$op  = Request::getCmd('op', Request::getCmd('op', '', 'POST'), 'GET'); //cleanRequestVars($_REQUEST, 'op', '');
$lid = Request::getInt('rid', Request::getInt('rid', 0, 'POST'), 'GET'); //cleanRequestVars($_REQUEST, 'rid', 0);
$lid = Request::getInt('lid', Request::getInt('lid', 0, 'POST'), 'GET'); //cleanRequestVars($_REQUEST, 'lid', 0);

switch (strtolower($op)) {
    case 'delvote':
        $sql    = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_votedata') . ' WHERE ratingid=' . $rid;
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        Xoopstube\Utility::updateRating($lid);
        redirect_header('votedata.php', 1, _AM_XOOPSTUBE_VOTEDELETED);
        break;

    case 'main':
    default:
        $start = Request::getInt('start', 0); //cleanRequestVars($_REQUEST, 'start', 0);
        xoops_cp_header();
        //renderAdminMenu( _AM_XOOPSTUBE_VOTE_RATINGINFOMATION );
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        $_vote_data = Xoopstube\Utility::getVoteDetails($lid);

        $text_info = '
        <table width="100%">
         <tr>
          <td width="50%" valign="top">
           <div><b>' . _AM_XOOPSTUBE_VOTE_TOTALRATE . ': </b>' . (int)$_vote_data['rate'] . '</div>
           <div><b>' . _AM_XOOPSTUBE_VOTE_USERAVG . ': </b>' . (int)round($_vote_data['avg_rate'], 2) . '</div>
           <div><b>' . _AM_XOOPSTUBE_VOTE_MAXRATE . ': </b>' . (int)$_vote_data['min_rate'] . '</div>
           <div><b>' . _AM_XOOPSTUBE_VOTE_MINRATE . ': </b>' . (int)$_vote_data['max_rate'] . '</div>
          </td>
          <td>
           <div><b>' . _AM_XOOPSTUBE_VOTE_MOSTVOTEDTITLE . ': </b>' . (int)$_vote_data['max_title'] . '</div>
           <div><b>' . _AM_XOOPSTUBE_VOTE_LEASTVOTEDTITLE . ': </b>' . (int)$_vote_data['min_title'] . '</div>
           <div><b>' . _AM_XOOPSTUBE_VOTE_REGISTERED . ': </b>' . ((int)$_vote_data['rate'] - $_vote_data['null_ratinguser']) . '</div>
           <div><b>' . _AM_XOOPSTUBE_VOTE_NONREGISTERED . ': </b>' . (int)$_vote_data['null_ratinguser'] . '</div>
          </td>
         </tr>
        </table>';

        echo '
         <fieldset style="border: #e8e8e8 1px solid;">
         <legend style="display: inline; font-weight: bold; color: #0A3760;">' . _AM_XOOPSTUBE_VOTE_DISPLAYVOTES . '</legend>
         <div style="padding: 8px;">' . $text_info . '<br>
         <li>' . $xtubeImageArray['deleteimg'] . ' ' . _AM_XOOPSTUBE_VOTE_DELETEDSC . '</li>
         </div>
         </fieldset>

        <table width="100%" cellspacing="1" cellpadding="2" class="outer" style="font-size: smaller;">
        <tr>
        <th style="text-align: center;">' . _AM_XOOPSTUBE_VOTE_ID . '</th>
        <th style="text-align: center;">' . _AM_XOOPSTUBE_VOTE_USER . '</th>
        <th style="text-align: center;">' . _AM_XOOPSTUBE_VOTE_IP . '</th>
        <th style="text-align: left;">&nbsp;' . _AM_XOOPSTUBE_VOTE_FILETITLE . '</th>
        <th style="text-align: center;">' . _AM_XOOPSTUBE_VOTE_RATING . '</th>
        <th style="text-align: center;">' . _AM_XOOPSTUBE_VOTE_DATE . '</th>
        <th style="text-align: center;">' . _AM_XOOPSTUBE_MINDEX_ACTION . '</th></tr>';

        $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_votedata');
        if ($lid > 0) {
            $sql .= ' WHERE lid=' . $lid;
        }
        $sql .= ' ORDER BY ratingtimestamp DESC';

        $results = $GLOBALS['xoopsDB']->query($sql, $GLOBALS['xoopsModuleConfig']['admin_perpage'], $start);
        $votes   = $GLOBALS['xoopsDB']->getRowsNum($GLOBALS['xoopsDB']->query($sql));

        if (0 == $votes) {
            echo '<tr><td style="text-align: center;" colspan="7" class="head">' . _AM_XOOPSTUBE_VOTE_NOVOTES . '</td></tr>';
        } else {
            while (false !== (list($ratingid, $lid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp, $title) = $GLOBALS['xoopsDB']->fetchRow($results))) {
                $formatted_date = Xoopstube\Utility::getTimestamp(formatTimestamp($ratingtimestamp, $GLOBALS['xoopsModuleConfig']['dateformat']));
                $ratinguname    = XoopsUser::getUnameFromId($ratinguser);
                echo '
                    <tr>
                    <td class="head" style="text-align: center;">' . $ratingid . '</td>
                    <td class="even" style="text-align: center;">' . $ratinguname . '</td>
                    <td class="even" style="text-align: center;">' . $ratinghostname . '</td>
                    <td class="even" style="text-align: left;">&nbsp;' . $title . '</td>
                    <td class="even" style="text-align: center;">' . $rating . '</td>
                    <td class="even" style="text-align: center;">' . $formatted_date . '</td>
                    <td class="even" style="text-align: center;"><a href="votedata.php?op=delvote&amp;lid=' . $lid . '&amp;rid=' . $ratingid . '">' . $xtubeImageArray['deleteimg'] . '</a></td>
                    </tr>';
            }
        }
        echo '</table>';
        // Include page navigation
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $page    = ($votes > $GLOBALS['xoopsModuleConfig']['admin_perpage']) ? _AM_XOOPSTUBE_MINDEX_PAGE : '';
        $pagenav = new \XoopsPageNav($page, $GLOBALS['xoopsModuleConfig']['admin_perpage'], $start, 'start');
        echo '<div align="right" style="padding: 8px;">' . $pagenav->renderNav() . '</div>';
        break;
}
require_once __DIR__ . '/admin_footer.php';
