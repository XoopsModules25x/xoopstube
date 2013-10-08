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


include 'header.php';

$xoopsOption['template_main'] = 'xoopstube_topten.html';
include XOOPS_ROOT_PATH . '/header.php';

$mytree = new XoopstubeTree($xoopsDB->prefix('xoopstube_cat'), 'cid', 'pid');

$action_array = array(
    'hit'  => 0,
    'rate' => 1
);
$list_array   = array('hits', 'rating');
$lang_array   = array(_MD_XTUBE_HITS, _MD_XTUBE_RATING);
$rankings     = array();

$sort     = (isset($_GET['list']) && in_array($_GET['list'], $action_array)) ? $_GET['list'] : 'rate';
$sort_arr = $action_array[$sort];
$sortDB   = $list_array[$sort_arr];

$catarray['imageheader'] = xtubeRenderImageHeader();
$xoopsTpl->assign('catarray', $catarray);

$arr    = array();
$result = $xoopsDB->query(
    'SELECT cid, title, pid FROM ' . $xoopsDB->prefix('xoopstube_cat') . ' WHERE pid=0 ORDER BY '
    . $xoopsModuleConfig['sortcats']
);

$e = 0;
while (list($cid, $ctitle) = $xoopsDB->fetchRow($result)) {
    if (true == xtubeCheckGroups($cid)) {
        $query = 'SELECT lid, cid, title, hits, rating, votes FROM ' . $xoopsDB->prefix('xoopstube_videos')
                 . ' WHERE published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time()
                 . ') AND offline = 0 AND (cid=' . $cid;
        $arr   = $mytree->getAllChildId($cid);
        for ($i = 0; $i < count($arr); $i++) {
            $query .= ' or cid=' . $arr[$i] . '';
        }
        $query .= ') order by ' . $sortDB . ' DESC';
        $result2   = $xoopsDB->query($query, 10, 0);
        $filecount = $xoopsDB->getRowsNum($result2);

        if ($filecount > 0) {
            $rankings[$e]['title'] = $xtubemyts->htmlSpecialCharsStrip($ctitle);
            $rank                  = 1;
            while (list($did, $dcid, $dtitle, $hits, $rating, $votes) = $xoopsDB->fetchRow($result2)) {
                $catpath                = basename($mytree->getPathFromId($dcid, 'title'));
                $dtitle                 = $xtubemyts->htmlSpecialCharsStrip($dtitle);
                $rankings[$e]['file'][] = array(
                    'id'       => $did,
                    'cid'      => $dcid,
                    'rank'     => $rank,
                    'title'    => $dtitle,
                    'category' => $catpath,
                    'hits'     => $hits,
                    'rating'   => number_format($rating, 2),
                    'votes'    => $votes
                );
                $rank++;
            }
            $e++;
        }
    }
}
$xoopsTpl->assign(
    'back',
    '<a href="javascript:history.go(-1)"><img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getvar('dirname')
    . '/images/icon/back.png" /></a>'
);
$xoopsTpl->assign('lang_sortby', $lang_array[$sort_arr]);
$xoopsTpl->assign('rankings', $rankings);
$xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));

include XOOPS_ROOT_PATH . '/footer.php';
