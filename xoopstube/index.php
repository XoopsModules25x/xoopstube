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

$start = xtubeCleanRequestVars($_REQUEST, 'start', 0);
$start = intval($start);

$xoopsOption['template_main'] = 'xoopstube_index.tpl';
include XOOPS_ROOT_PATH . '/header.php';

global $xoopsModuleConfig;

$mytree = new XoopstubeTree($xoopsDB->prefix('xoopstube_cat'), 'cid', 'pid');

// Begin Main page Heading etc
$sql      = 'SELECT * FROM ' . $xoopsDB->prefix('xoopstube_indexpage');
$head_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));

$catarray['imageheader']      = xtubeRenderImageHeader($head_arr['indeximage'], $head_arr['indexheading']);
$catarray['indexheaderalign'] = $xtubemyts->htmlSpecialCharsStrip($head_arr['indexheaderalign']);
$catarray['indexfooteralign'] = $xtubemyts->htmlSpecialCharsStrip($head_arr['indexfooteralign']);

$html   = ($head_arr['nohtml']) ? 0 : 1;
$smiley = ($head_arr['nosmiley']) ? 0 : 1;
$xcodes = ($head_arr['noxcodes']) ? 0 : 1;
$images = ($head_arr['noimages']) ? 0 : 1;
$breaks = ($head_arr['nobreak']) ? 1 : 0;

$catarray['indexheading'] = $xtubemyts->displayTarea(
    $head_arr['indexheading'],
    $html,
    $smiley,
    $xcodes,
    $images,
    $breaks
);
$catarray['indexheader']  = $xtubemyts->displayTarea(
    $head_arr['indexheader'],
    $html,
    $smiley,
    $xcodes,
    $images,
    $breaks
);
$catarray['indexfooter']  = $xtubemyts->displayTarea(
    $head_arr['indexfooter'],
    $html,
    $smiley,
    $xcodes,
    $images,
    $breaks
);
$catarray['letters']      = xtubeGetLetters();
$xoopsTpl->assign('catarray', $catarray);
// End main page Headers

$count   = 1;
$chcount = 0;
$countin = 0;

// Begin Main page linkload info
$listings = xtubeGetTotalItems();
// get total amount of categories
$total_cat = xtubeGetTotalCategoryCount();

$catsort = $xoopsModuleConfig['sortcats'];
$sql     = 'SELECT * FROM ' . $xoopsDB->prefix('xoopstube_cat') . ' WHERE pid=0 ORDER BY ' . $catsort;
$result  = $xoopsDB->query($sql);
while ($myrow = $xoopsDB->fetchArray($result)) {
    ++$countin;
    $subtotalvideoload = 0;
    $totalvideoload    = xtubeGetTotalItems($myrow['cid'], 1);
    $indicator         = xtubeIsNewImage($totalvideoload['published']);
    if (xtubeCheckGroups($myrow['cid'])) {
        $title = $xtubemyts->htmlSpecialCharsStrip($myrow['title']);

        $arr = array();
        $arr = $mytree->getFirstChild($myrow['cid'], 'title');

        $space         = 1;
        $chcount       = 1;
        $subcategories = '';
        foreach ($arr as $ele) {
            if (true == xtubeCheckGroups($ele['cid'])) {
                if ($xoopsModuleConfig['subcats'] == 1) {
                    $chtitle = $xtubemyts->htmlSpecialCharsStrip($ele['title']);
                    if ($chcount > 5) {
                        $subcategories .= '...';
                        break;
                    }
                    if ($space > 0) {
                        $subcategories .= '<br />';
                    }
                    $subcategories
                        .= '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $ele['cid'] . '">' . $chtitle . '</a>';
                    ++$space;
                    ++$chcount;
                }
            }
        }

        // This code is copyright WF-Projects
        // Using this code without our permission or removing this code voids the license agreement
        $_image = ($myrow['imgurl']) ? urldecode($myrow['imgurl']) : '';
        if ($_image != "" && $xoopsModuleConfig['usethumbs']) {
            $_thumb_image = new XtubeThumbsNails($_image, $xoopsModuleConfig['catimage'], 'thumbs');
            if ($_thumb_image) {
                $_thumb_image->setUseThumbs(1);
                $_thumb_image->setImageType('gd2');
                $_image = $_thumb_image->createThumbnail(
                    $xoopsModuleConfig['shotwidth'],
                    $xoopsModuleConfig['shotheight'],
                    $xoopsModuleConfig['imagequality'],
                    $xoopsModuleConfig['updatethumbs'],
                    $xoopsModuleConfig['keepaspect']
                );
            }
        }
        if (empty($_image) || $_image == '') {
            $imgurl  = $indicator['image'];
            $_width  = 33;
            $_height = 24;
        } else {
            $imgurl  = "{$xoopsModuleConfig['catimage']}/$_image";
            $_width  = $xoopsModuleConfig['shotwidth'];
            $_height = $xoopsModuleConfig['shotheight'];
        }
        // End

        $xoopsTpl->append(
            'categories',
            array(
                'image'         => XOOPS_URL . "/$imgurl",
                'id'            => $myrow['cid'],
                'title'         => $title,
                'subcategories' => $subcategories,
                'totalvideos'   => $totalvideoload['count'],
                'width'         => $_width,
                'height'        => $_height,
                'count'         => $count,
                'alttext'       => $myrow['description']
            )
        );
        ++$count;
    }
}
switch ($total_cat) {
    case '1':
        $lang_thereare = _MD_XOOPSTUBE_THEREIS;
        break;
    default:
        $lang_thereare = _MD_XOOPSTUBE_THEREARE;
        break;
}

$time = time();

$sql        = $xoopsDB->query('SELECT lastvideosyn, lastvideostotal FROM ' . $xoopsDB->prefix('xoopstube_indexpage'));
$lastvideos = $xoopsDB->fetchArray($sql);

if ($lastvideos['lastvideosyn'] == 1 && $lastvideos['lastvideostotal'] > 0) {

    $result = $xoopsDB->query(
        'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE published > 0
                                AND published <= ' . $time . '
                                AND (expired = 0 OR expired > ' . $time . ')
                                AND offline = 0
                                ORDER BY published DESC',
        0,
        0
    );
    list($count) = $xoopsDB->fetchRow($result);

    $count = (($count > $lastvideos['lastvideostotal'])
        && ($lastvideos['lastvideostotal'] != 0)) ? $lastvideos['lastvideostotal'] : $count;
    $limit = (($start + $xoopsModuleConfig['perpage']) > $count) ? ($count - $start) : $xoopsModuleConfig['perpage'];

    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE published > 0
                                AND published <= ' . time() . '
                                AND (expired = 0 OR expired > ' . time() . ')
                                AND offline = 0
                                ORDER BY published DESC',
        $limit,
        $start
    );

    while ($video_arr = $xoopsDB->fetchArray($result)) {
        if (xtubeCheckGroups($video_arr['cid']) == true) {
            $res_type = 0;
            $moderate = 0;
            $cid      = $video_arr['cid'];
            require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/videoloadinfo.php';
            $xoopsTpl->append('video', $video);
        }
    }

    $pagenav = new XoopsPageNav($count, $xoopsModuleConfig['perpage'], $start, 'start');
    $xoopsTpl->assign('pagenav', $pagenav->renderNav());
    $xoopsTpl->assign('showlatest', $lastvideos['lastvideosyn']);
}

$xoopsTpl->assign('cat_columns', $xoopsModuleConfig['catcolumns']);
$xoopsTpl->assign('lang_thereare', sprintf($lang_thereare, $total_cat, $listings['count']));
$xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));

include XOOPS_ROOT_PATH . '/footer.php';
