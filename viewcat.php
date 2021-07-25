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
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org/
 * @since           1.0.6
 */

use Xmf\Request;
use XoopsModules\Xoopstube\{Common\LetterChoice,
    Helper,
    Thumbnails,
    Tree,
    Utility,
    VideosHandler
};

/** @var Helper $helper */

$GLOBALS['xoopsOption']['template_main'] = 'xoopstube_viewcat.tpl';

require_once __DIR__ . '/header.php';

$helper = Helper::getInstance();

// Begin Main page Heading etc
$cid        = Request::getInt('cid', 0, 'GET'); //cleanRequestVars($_REQUEST, 'cid', 0);
$selectdate = Request::getString('selectdate', ''); //cleanRequestVars($_REQUEST, 'selectdate', '');
$list       = Request::getString('letter', ''); // cleanRequestVars($_REQUEST, 'letter', '');
$start      = Request::getInt('start', 0, 'GET'); //cleanRequestVars($_REQUEST, 'start', 0);

$catsort = $GLOBALS['xoopsModuleConfig']['sortcats'];
$mytree  = new Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');
$arr     = $mytree->getFirstChild($cid, $catsort);

if (is_array($arr) > 0 && !$list && !$selectdate) {
    if (false === Utility::checkGroups($cid)) {
        redirect_header('index.php', 1, _MD_XOOPSTUBE_MUSTREGFIRST);
    }
}

//require_once XOOPS_ROOT_PATH . '/header.php';

//$xoTheme->addStylesheet('modules/' . $moduleDirName . '/assets/css/xtubestyle.css');

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
//$xoTheme->addScript(XOOPSTUBE_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
//$xoTheme->addStylesheet(XOOPSTUBE_URL . '/assets/js/magnific/magnific-popup.css');
$xoTheme->addStylesheet(XOOPSTUBE_URL . '/assets/css/module.css');

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
//$xoTheme->addScript(XOOPSTUBE_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
//$xoTheme->addStylesheet(XOOPSTUBE_URL . '/assets/js/magnific/magnific-popup.css');
$xoTheme->addStylesheet(XOOPSTUBE_URL . '/assets/css/module.css');

$xoopsTpl->assign('mod_url', XOOPSTUBE_URL . '/');

global $xoopsModule;

$myts = \MyTextSanitizer::getInstance(); // MyTextSanitizer object

/*
$catarray['letters'] = Utility::getLetters();
//$catarray['letters']     = Utility::getLettersChoice();
$catarray['imageheader'] = Utility::renderImageHeader();
$xoopsTpl->assign('catarray', $catarray);
*/

// Letter Choice Start ---------------------------------------

Helper::getInstance()->loadLanguage('common');
$xoopsTpl->assign('letterChoiceTitle', constant('CO_' . $moduleDirNameUpper . '_' . 'BROWSETOTOPIC'));
$db             = \XoopsDatabaseFactory::getDatabaseConnection();
$videosHandler  = $helper->getHandler('Videos'); //new VideosHandler($db);
$choicebyletter = new LetterChoice($videosHandler, null, null, range('a', 'z'), 'letter');
//$choicebyletter = new LetterChoice($videosHandler, null, null, range('a', 'z'), 'init', XOOPSTUBE_URL . '/letter.php');
$catarray['letters'] = $choicebyletter->render();
//$catarray['letters']  = $choicebyletter->render($alphaCount, $howmanyother);

$xoopsTpl->assign('catarray', $catarray);

// Letter Choice End ------------------------------------

//$catArray['letters'] = Utility::getLettersChoice();
//$catArray['letters'] = Utility::getLettersChoice();
//$catArray['toolbar'] = xoopstube_toolbar();
//$xoopsTpl->assign('catarray', $catArray);

//$catArray['imageheader'] = Utility::headerImage();
////$catArray['letters']     = Wfdownloads\Utility::lettersChoice();
///** @var \XoopsDatabase $db */
//$db           = \XoopsDatabaseFactory::getDatabaseConnection();
//downloadHandler = new Xoopstube\DownloadHandler($db);
//$choicebyletter = new LetterChoice(downloadHandler, null, null, range('a', 'z'), 'letter');
//$catarray['letters']  = $choicebyletter->render();

// Breadcrumb
$pathstring = '<li><a href="index.php">' . _MD_XOOPSTUBE_MAIN . '</a></li>';
$pathstring .= $mytree->getNicePathFromId($cid, 'title', 'viewcat.php?op=');
$xoopsTpl->assign('category_path', $pathstring);
$xoopsTpl->assign('category_id', $cid);

// Display Sub-categories for selected Category
if (is_array($arr) > 0 && !$list && !$selectdate) {
    $scount = 1;
    foreach ($arr as $ele) {
        if (false === Utility::checkGroups($ele['cid'])) {
            continue;
        }
        $sub_arr         = [];
        $sub_arr         = $mytree->getFirstChild($ele['cid'], $catsort);
        $space           = 1;
        $chcount         = 1;
        $infercategories = '';
        foreach ($sub_arr as $sub_ele) {
            // Subitem file count
            $hassubitems = Utility::getTotalItems($sub_ele['cid']);
            // Filter group permissions
            if (true === Utility::checkGroups($sub_ele['cid'])) {
                // If subcategory count > 5 then finish adding subcats to $infercategories and end
                if ($chcount > 5) {
                    $infercategories .= '...';
                    break;
                }
                if ($space > 0) {
                    $infercategories .= ', ';
                }

                $infercategories .= '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $sub_ele['cid'] . '">' . htmlspecialchars($sub_ele['title'], ENT_QUOTES | ENT_HTML5) . '</a> (' . $hassubitems['count'] . ')';
                ++$space;
                ++$chcount;
            }
        }
        $totalvideos = Utility::getTotalItems($ele['cid']);
        $indicator   = Utility::isNewImage($totalvideos['published']);

        // This code is copyright WF-Projects
        // Using this code without our permission or removing this code voids the license agreement

        $_image = $ele['imgurl'] ? urldecode($ele['imgurl']) : '';
        if ('' !== $_image && $GLOBALS['xoopsModuleConfig']['usethumbs']) {
            $_thumb_image = new Thumbnails($_image, $GLOBALS['xoopsModuleConfig']['catimage'], 'thumbs');
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
        /*
        * End
        */

        $xoopsTpl->append(
            'subcategories',
            [
                'title'           => htmlspecialchars($ele['title'], ENT_QUOTES | ENT_HTML5),
                'id'              => $ele['cid'],
                'image'           => XOOPS_URL . "/$imgurl",
                'width'           => $_width,
                'height'          => $_height,
                'infercategories' => $infercategories,
                'totalvideos'     => $totalvideos['count'],
                'count'           => $scount,
                'alttext'         => $ele['description'],
            ]
        );
        ++$scount;
    }
}

// Show Description for Category listing
$sql             = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' WHERE cid=' . $cid;
$head_arr        = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));
$html            = isset($head_arr['nohtml']) ? 0 : 1;
$smiley          = isset($head_arr['nosmiley']) ? 0 : 1;
$xcodes          = isset($head_arr['noxcodes']) ? 0 : 1;
$images          = isset($head_arr['noimages']) ? 0 : 1;
$breaks          = isset($head_arr['nobreak']) ? 1 : 0;
$tempDescription = $head_arr['description'] ?? '';
$description     = &$myts->displayTarea($tempDescription, $html, $smiley, $xcodes, $images, $breaks);
$xoopsTpl->assign('description', $description);
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$versioninfo   = $moduleHandler->get($xoopsModule->getVar('mid'));
if (isset($head_arr['title']) && '' !== $head_arr['title']) {
    $xoopsTpl->assign('xoops_pagetitle', $versioninfo->getInfo('name') . ':&nbsp;' . $head_arr['title']);
} else {
    $xoopsTpl->assign('xoops_pagetitle', $versioninfo->getInfo('name'));
}

if (isset($head_arr['client_id']) && (int)$head_arr['client_id'] > 0) {
    $catarray['imageheader'] = Utility::getBannerFromClientId($head_arr['client_id']);
} elseif (isset($head_arr['banner_id']) && (int)$head_arr['banner_id'] > 0) {
    $catarray['imageheader'] = Utility::getBannerFromBannerId($head_arr['banner_id']);
} else {
    $catarray['imageheader'] = Utility::renderImageHeader();
}
$xoopsTpl->assign('catarray', $catarray);
// Extract linkload information from database
$xoopsTpl->assign('show_category_title', true);

$orderby0 = (isset($_REQUEST['orderby'])
             && !empty($_REQUEST['orderby'])) ? Utility::convertOrderByIn(htmlspecialchars($_REQUEST['orderby'], ENT_QUOTES | ENT_HTML5)) : Utility::convertOrderByIn($GLOBALS['xoopsModuleConfig']['linkxorder']);
$orderby  = Request::getString('orderby', '', 'GET') ? Utility::convertOrderByIn(Request::getString('orderby', '', 'GET')) : Utility::convertOrderByIn($GLOBALS['xoopsModuleConfig']['linkxorder']);

if ($selectdate) {
    $d = date('j', $selectdate);
    $m = date('m', $selectdate);
    $y = date('Y', $selectdate);

    $stat_begin = mktime(0, 0, 0, $m, $d, $y);
    $stat_end   = mktime(23, 59, 59, $m, $d, $y);

    $query  = ' WHERE published>=' . $stat_begin . ' AND published<=' . $stat_end . ' AND (expired=0 OR expired>' . time() . ') AND offline=0 AND cid>0';
    $sql    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . $query . ' ORDER BY ' . $orderby;
    $result = $GLOBALS['xoopsDB']->query($sql, $GLOBALS['xoopsModuleConfig']['perpage'], $start);

    $sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . $query;
    [$count] = $GLOBALS['xoopsDB']->fetchRow($GLOBALS['xoopsDB']->query($sql));

    $list_by = 'selectdate=' . $selectdate;

    $xoopsTpl->assign('is_selectdate', true);
    $xoopsTpl->assign('selected_date', Utility::getTimestamp(formatTimestamp($selectdate, $GLOBALS['xoopsModuleConfig']['dateformat'])));
} elseif ($list) {
    $query = " WHERE title LIKE '$list%' AND (published>0 AND published<=" . time() . ') AND (expired=0 OR expired>' . time() . ') AND offline=0 AND cid>0';

    $sql    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . $query . ' ORDER BY ' . $orderby;
    $result = $GLOBALS['xoopsDB']->query($sql, $GLOBALS['xoopsModuleConfig']['perpage'], $start);

    $sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . $query;
    [$count] = $GLOBALS['xoopsDB']->fetchRow($GLOBALS['xoopsDB']->query($sql));
    $list_by = "letter=$list";
} else {
    $query = 'WHERE a.published>0 AND a.published<=' . time() . ' AND (a.expired=0 OR a.expired>' . time() . ') AND a.offline=0' . ' AND (b.cid=a.cid OR (a.cid=' . $cid . ' OR b.cid=' . $cid . '))';

    $sql    = 'SELECT DISTINCT a.* FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' a LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('xoopstube_altcat') . ' b ON b.lid=a.lid ' . $query . ' ORDER BY ' . $orderby;
    $result = $GLOBALS['xoopsDB']->query($sql, $GLOBALS['xoopsModuleConfig']['perpage'], $start);

    //    $sql2 = 'SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' a LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('xoopstube_altcat') . ' b ON b.lid=a.lid ' . $query;
    //    [$count] = $GLOBALS['xoopsDB']->fetchRow($GLOBALS['xoopsDB']->query($sql2));

    $count = $result->num_rows;

    $order   = Utility::convertOrderByOut($orderby);
    $list_by = 'cid=' . $cid . '&orderby=' . $order;
    $xoopsTpl->assign('show_category_title', false);
}
$pagenav = new \XoopsPageNav($count, $GLOBALS['xoopsModuleConfig']['perpage'], $start, 'start', $list_by);

// Show videos
if ($count > 0) {
    $moderate = 0;

    while (false !== ($videoArray = $GLOBALS['xoopsDB']->fetchArray($result))) {
        if (true === Utility::checkGroups($videoArray['cid'])) {
            require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/videoloadinfo.php';
            $xoopsTpl->append('video', $video);
        }
    }

    unset($videoArray);

    // Show order box
    $xoopsTpl->assign('show_videos', false);
    if ($count > 1 && 0 !== $cid) {
        $xoopsTpl->assign('show_videos', true);
        $orderbyTrans = Utility::convertOrderByTrans($orderby);
        $xoopsTpl->assign('lang_cursortedby', sprintf(_MD_XOOPSTUBE_CURSORTBY, Utility::convertOrderByTrans($orderby)));
        $orderby = Utility::convertOrderByOut($orderby);
    }

    // Screenshots display
    $xoopsTpl->assign('show_screenshot', false);
    if (isset($GLOBALS['xoopsModuleConfig']['screenshot']) && 1 == $GLOBALS['xoopsModuleConfig']['screenshot']) {
        $xoopsTpl->assign('shotwidth', $GLOBALS['xoopsModuleConfig']['shotwidth']);
        $xoopsTpl->assign('shotheight', $GLOBALS['xoopsModuleConfig']['shotheight']);
        $xoopsTpl->assign('show_screenshot', true);
    }

    // Nav page render
    $page_nav = $pagenav->renderNav();
    $istrue   = (isset($page_nav) && !empty($page_nav));
    $xoopsTpl->assign('page_nav', $istrue);
    $xoopsTpl->assign('pagenav', $page_nav);
    $xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));
}

$xoopsTpl->assign('cat_columns', $GLOBALS['xoopsModuleConfig']['catcolumns']);

require_once XOOPS_ROOT_PATH . '/footer.php';
