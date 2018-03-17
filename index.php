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

$moduleDirName = basename(__DIR__);
$moduleDirNameUpper = strtoupper($moduleDirName);

$start = Request::getInt('start', Request::getInt('start', 0, 'POST'), 'GET');

$GLOBALS['xoopsOption']['template_main'] = 'xoopstube_index.tpl';
include XOOPS_ROOT_PATH . '/header.php';

$mytree = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');
$xtubemyts = new Xoopstube\TextSanitizer(); // MyTextSanitizer object

// Begin Main page Heading etc
$sql      = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_indexpage');
$head_arr = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));

$catarray['imageheader']      = Xoopstube\Utility::renderImageHeader($head_arr['indeximage'], $head_arr['indexheading']);
$catarray['indexheaderalign'] = $xtubemyts->htmlSpecialCharsStrip($head_arr['indexheaderalign']);
$catarray['indexfooteralign'] = $xtubemyts->htmlSpecialCharsStrip($head_arr['indexfooteralign']);

$html   = $head_arr['nohtml'] ? 0 : 1;
$smiley = $head_arr['nosmiley'] ? 0 : 1;
$xcodes = $head_arr['noxcodes'] ? 0 : 1;
$images = $head_arr['noimages'] ? 0 : 1;
$breaks = $head_arr['nobreak'] ? 1 : 0;

$catarray['indexheading'] = $xtubemyts->displayTarea($head_arr['indexheading'], $html, $smiley, $xcodes, $images, $breaks);
$catarray['indexheader']  = $xtubemyts->displayTarea($head_arr['indexheader'], $html, $smiley, $xcodes, $images, $breaks);
$catarray['indexfooter']  = $xtubemyts->displayTarea($head_arr['indexfooter'], $html, $smiley, $xcodes, $images, $breaks);
//$catarray['letters']      = Xoopstube\Utility::getLetters();


// Letter Choice Start ---------------------------------------

XoopsModules\Xoopstube\Helper::getInstance()->loadLanguage('common');
$xoopsTpl->assign('letterChoiceTitle', constant('CO_' . $moduleDirNameUpper . '_' . 'BROWSETOTOPIC'));
/** @var \XoopsDatabase $db */
$db           = \XoopsDatabaseFactory::getDatabaseConnection();
$objHandler = new Xoopstube\VideosHandler($db);
$choicebyletter = new Xoopstube\Common\LetterChoice($objHandler, null, null, range('a', 'z'), 'letter');
//$choicebyletter = new Xoopstube\Common\LetterChoice($objHandler, null, null, range('a', 'z'), 'init', XOOPSTUBE_URL . '/letter.php');
$catarray['letters']  = $choicebyletter->render();
//$catarray['letters']  = $choicebyletter->render($alphaCount, $howmanyother);

$xoopsTpl->assign('catarray', $catarray);

// Letter Choice End ------------------------------------



// End main page Headers

$count   = 1;
$chcount = 0;
$countin = 0;

// Begin Main page linkload info
$listings = Xoopstube\Utility::getTotalItems();
// get total amount of categories
$total_cat = Xoopstube\Utility::getTotalCategoryCount();

$catsort = $GLOBALS['xoopsModuleConfig']['sortcats'];
$sql     = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' WHERE pid=0 ORDER BY ' . $catsort;
$result  = $GLOBALS['xoopsDB']->query($sql);
while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
    ++$countin;
    $subtotalvideoload = 0;
    $totalvideoload    = Xoopstube\Utility::getTotalItems($myrow['cid'], 1);
    $indicator         = Xoopstube\Utility::isNewImage($totalvideoload['published']);
    if (Xoopstube\Utility::checkGroups($myrow['cid'])) {
        $title = $xtubemyts->htmlSpecialCharsStrip($myrow['title']);

        $arr = [];
        $arr = $mytree->getFirstChild($myrow['cid'], 'title');

        $space         = 1;
        $chcount       = 1;
        $subcategories = '';
        foreach ($arr as $ele) {
            if (true === Xoopstube\Utility::checkGroups($ele['cid'])) {
                if (1 == $GLOBALS['xoopsModuleConfig']['subcats']) {
                    $chtitle = $xtubemyts->htmlSpecialCharsStrip($ele['title']);
                    if ($chcount > 5) {
                        $subcategories .= '...';
                        break;
                    }
                    if ($space > 0) {
                        $subcategories .= '<br>';
                    }
                    $subcategories .= '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $ele['cid'] . '">' . $chtitle . '</a>';
                    ++$space;
                    ++$chcount;
                }
            }
        }

        // This code is copyright WF-Projects
        // Using this code without our permission or removing this code voids the license agreement
        $_image = $myrow['imgurl'] ? urldecode($myrow['imgurl']) : '';
        if ('' !== $_image && $GLOBALS['xoopsModuleConfig']['usethumbs']) {
            $_thumb_image = new Xoopstube\Thumbnails($_image, $GLOBALS['xoopsModuleConfig']['catimage'], 'thumbs');
            if ($_thumb_image) {
                $_thumb_image->setUseThumbs(1);
                $_thumb_image->setImageType('gd2');
                $_image = $_thumb_image->createThumbnail($GLOBALS['xoopsModuleConfig']['shotwidth'], $GLOBALS['xoopsModuleConfig']['shotheight'], $GLOBALS['xoopsModuleConfig']['imagequality'], $GLOBALS['xoopsModuleConfig']['updatethumbs'], $GLOBALS['xoopsModuleConfig']['imageAspect']);
            }
        }
        if (empty($_image) || '' == $_image) {
            $imgurl  = $indicator['image'];
            $_width  = 33;
            $_height = 24;
        } else {
            $imgurl  = "{$GLOBALS['xoopsModuleConfig']['catimage']}/$_image";
            $_width  = $GLOBALS['xoopsModuleConfig']['shotwidth'];
            $_height = $GLOBALS['xoopsModuleConfig']['shotheight'];
        }
        // End

        $xoopsTpl->append('categories', [
            'image'         => XOOPS_URL . "/$imgurl",
            'id'            => $myrow['cid'],
            'title'         => $title,
            'subcategories' => $subcategories,
            'totalvideos'   => $totalvideoload['count'],
            'width'         => $_width,
            'height'        => $_height,
            'count'         => $count,
            'alttext'       => $myrow['description']
        ]);
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

$sql        = $GLOBALS['xoopsDB']->query('SELECT lastvideosyn, lastvideostotal FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_indexpage'));
$lastvideos = $GLOBALS['xoopsDB']->fetchArray($sql);

if (1 == $lastvideos['lastvideosyn'] && $lastvideos['lastvideostotal'] > 0) {
    $result = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published > 0
                                AND published <= ' . $time . '
                                AND (expired = 0 OR expired > ' . $time . ')
                                AND offline = 0
                                ORDER BY published DESC', 0, 0);
    list($count) = $GLOBALS['xoopsDB']->fetchRow($result);

    $count = (($count > $lastvideos['lastvideostotal']) && (0 !== $lastvideos['lastvideostotal'])) ? $lastvideos['lastvideostotal'] : $count;
    $limit = (($start + $GLOBALS['xoopsModuleConfig']['perpage']) > $count) ? ($count - $start) : $GLOBALS['xoopsModuleConfig']['perpage'];

    $result = $GLOBALS['xoopsDB']->query('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published > 0
                                AND published <= ' . time() . '
                                AND (expired = 0 OR expired > ' . time() . ')
                                AND offline = 0
                                ORDER BY published DESC', $limit, $start);

    while (false !== ($video_arr = $GLOBALS['xoopsDB']->fetchArray($result))) {
        if (true === Xoopstube\Utility::checkGroups($video_arr['cid'])) {
            $res_type = 0;
            $moderate = 0;
            $cid      = $video_arr['cid'];
            require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/videoloadinfo.php';
            $xoopsTpl->append('video', $video);
        }
    }

    $pagenav = new \XoopsPageNav($count, $GLOBALS['xoopsModuleConfig']['perpage'], $start, 'start');
    $xoopsTpl->assign('pagenav', $pagenav->renderNav());
    $xoopsTpl->assign('showlatest', $lastvideos['lastvideosyn']);
}

$xoopsTpl->assign('cat_columns', $GLOBALS['xoopsModuleConfig']['catcolumns']);
$xoopsTpl->assign('lang_thereare', sprintf($lang_thereare, $total_cat, $listings['count']));
$xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));



include XOOPS_ROOT_PATH . '/footer.php';
