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

$GLOBALS['xoopsOption']['template_main'] = 'xoopstube_topten.tpl';
include XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet('modules/' . $moduleDirName . '/assets/css/xtubestyle.css');

$mytree = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');

$action_array = [
    'hit'  => 0,
    'rate' => 1
];
$list_array   = ['hits', 'rating'];
$lang_array   = [_MD_XOOPSTUBE_HITS, _MD_XOOPSTUBE_RATING];
$rankings     = [];

$sort     = in_array(Request::getString('list', '', 'GET'), $action_array) ? Request::getString('list', '', 'GET') : 'rate';
$sort_arr = $action_array[$sort];
$sortDB   = $list_array[$sort_arr];

$catarray['imageheader'] = Xoopstube\Utility::renderImageHeader();
$xoopsTpl->assign('catarray', $catarray);

$arr    = [];
$result = $GLOBALS['xoopsDB']->query('SELECT cid, title, pid FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' WHERE pid=0 ORDER BY ' . $GLOBALS['xoopsModuleConfig']['sortcats']);

$e = 0;
while (false !== (list($cid, $ctitle) = $GLOBALS['xoopsDB']->fetchRow($result))) {
    if (true === Xoopstube\Utility::checkGroups($cid)) {
        $query      = 'SELECT lid, cid, title, hits, rating, votes FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time() . ') AND offline = 0 AND (cid=' . $cid;
        $arr        = $mytree->getAllChildId($cid);
        $arrayCount = count($arr);
        for ($i = 0; $i < $arrayCount; ++$i) {
            $query .= ' or cid=' . $arr[$i] . '';
        }
        $query     .= ') order by ' . $sortDB . ' DESC';
        $result2   = $GLOBALS['xoopsDB']->query($query, 10, 0);
        $filecount = $GLOBALS['xoopsDB']->getRowsNum($result2);

        if ($filecount > 0) {
            $rankings[$e]['title'] = $xtubemyts->htmlSpecialCharsStrip($ctitle);
            $rank                  = 1;
            while (false !== (list($did, $dcid, $dtitle, $hits, $rating, $votes) = $GLOBALS['xoopsDB']->fetchRow($result2))) {
                $catpath                = basename($mytree->getPathFromId($dcid, 'title'));
                $dtitle                 = $xtubemyts->htmlSpecialCharsStrip($dtitle);
                $rankings[$e]['file'][] = [
                    'id'       => $did,
                    'cid'      => $dcid,
                    'rank'     => $rank,
                    'title'    => $dtitle,
                    'category' => $catpath,
                    'hits'     => $hits,
                    'rating'   => number_format($rating, 2),
                    'votes'    => $votes
                ];
                ++$rank;
            }
            ++$e;
        }
    }
}
$xoopsTpl->assign('back', '<a href="javascript:history.go(-1)"><img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/icon/back.png"></a>');
$xoopsTpl->assign('lang_sortby', $lang_array[$sort_arr]);
$xoopsTpl->assign('rankings', $rankings);
$xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));

include XOOPS_ROOT_PATH . '/footer.php';
