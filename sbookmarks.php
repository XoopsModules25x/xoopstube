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
 * @param $lid
 *
 * @return string
 * @author          XOOPS Development Team
 * @copyright       2001-2016 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org/
 * @since           1.0.6
 *
 * @category        Module
 * @package         Xoopstube
 */

use XoopsModules\Xoopstube;

/**
 * @param $lid
 * @return string
 */
function getSocialBookmarks($lid)
{
    global $xoopsModule;

//    $myts           = new Xoopstube\TextSanitizer();
    $sbmarkArray          = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query('SELECT lid, title FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . (int)$lid));
    $sbmarkArray['title'] = htmlspecialchars($sbmarkArray['title'], ENT_QUOTES | ENT_HTML5);
    $sbmarkArray['link']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?lid=' . (int)$lid;

    //Definitions for social bookmarks

    //Backflip
    $sbmarks['blackflip'] = '<a href="https://www.backflip.com/add_page_pop.ihtml?url='
                            . $sbmarkArray['link']
                            . '&title='
                            . $sbmarkArray['title']
                            . '"'
                            . ' target="_blank"><img border="0" src="'
                            . XOOPS_URL
                            . '/modules/'
                            . $xoopsModule->getVar('dirname')
                            . '/assets/images/sbookmarks/backflip.png" align="middle" title="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'BackFlip" alt="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'BackFlip"></a>';

    //Bibsonomy
    $sbmark['bibsonomy'] = '<a href="https://www.bibsonomy.org/ShowBookmarkEntry?c=b&jump=yes&url='
                           . $sbmarkArray['link']
                           . '&description='
                           . $sbmarkArray['title']
                           . '"'
                           . ' target="_blank"><img border="0" src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/sbookmarks/bibsonomy.png" align="middle" title="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Bibsonomy alt="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Bibsonomy"></a>';

    //BlinkList
    $sbmarks['blinklist'] = '<a href="https://www.blinklist.com/index.php?Action=Blink/addblink.php&Quick=true&url='
                            . $sbmarkArray['link']
                            . '&Title='
                            . $sbmarkArray['title']
                            . '&Pop=yes" target="_blank"><img border="0" src="'
                            . XOOPS_URL
                            . '/modules/'
                            . $xoopsModule->getVar('dirname')
                            . '/assets/images/sbookmarks/blinklist.png" align="middle" title="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'BlinkList" alt="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'BlinkList"></a>';

    //Blogmark
    $sbmark['blogmark'] = '<a href="https://blogmarks.net/my/new.php?title='
                          . $sbmarkArray['title']
                          . '&url='
                          . $sbmarkArray['link']
                          . '"'
                          . ' target="_blank"><img border="0" src="'
                          . XOOPS_URL
                          . '/modules/'
                          . $xoopsModule->getVar('dirname')
                          . '/assets/images/sbookmarks/blogmarks.png" align="middle" title="'
                          . _MD_XOOPSTUBE_ADDTO
                          . 'BlogMarks" alt="'
                          . _MD_XOOPSTUBE_ADDTO
                          . 'BlogMarks"></a>';

    //CiteUlike
    $sbmark['citeulike'] = '<a href="https://www.citeulike.org/posturl?url='
                           . $sbmarkArray['link']
                           . '&title='
                           . $sbmarkArray['title']
                           . '"'
                           . ' target="_blank"><img border="0" src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/sbookmarks/citeulike.png" align="middle" title="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'CiteUlike" alt="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'CiteUlike"></a>';

    //Connotea
    $sbmarks['connotea'] = '<a href="https://www.connotea.org/add?continue=return&uri='
                           . $sbmarkArray['link']
                           . '&title='
                           . $sbmarkArray['title']
                           . '"'
                           . ' target="_blank"><img border="0" src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/sbookmarks/connotea.png" align="middle" title="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Connotea" alt="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Connotea"></a>';

    //del.icio.us
    $sbmarks['delicio'] = '<a href="https://del.icio.us/post?v=4&noui&jump=close&url='
                          . $sbmarkArray['link']
                          . '&title='
                          . $sbmarkArray['title']
                          . '"'
                          . ' target="_blank"><img border="0" src="'
                          . XOOPS_URL
                          . '/modules/'
                          . $xoopsModule->getVar('dirname')
                          . '/assets/images/sbookmarks/del.png" align="middle" title="'
                          . _MD_XOOPSTUBE_ADDTO
                          . 'del.icio.us" alt="'
                          . _MD_XOOPSTUBE_ADDTO
                          . 'del.icio.us"></a>';

    //Digg
    $sbmarks['digg'] = '<a href="https://digg.com/submit?phase=2&url='
                       . $sbmarkArray['link']
                       . '&title='
                       . $sbmarkArray['title']
                       . '"'
                       . ' target="_blank"><img border="0" src="'
                       . XOOPS_URL
                       . '/modules/'
                       . $xoopsModule->getVar('dirname')
                       . '/assets/images/sbookmarks/digg.png" align="middle" title="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Digg" alt="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Digg"></a>';

    //Diigo
    $sbmarks['diigo'] = '<a href="https://www.diigo.com/post?url='
                        . $sbmarkArray['link']
                        . '&title='
                        . $sbmarkArray['title']
                        . '"'
                        . ' target="_blank"><img border="0" src="'
                        . XOOPS_URL
                        . '/modules/'
                        . $xoopsModule->getVar('dirname')
                        . '/assets/images/sbookmarks/diigo.png" align="middle" title="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'Diigo" alt="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'Diigo"></a>';

    //DZone
    $sbmarks['dzone'] = '<a href="https://www.dzone.com/links/add.html?url='
                        . $sbmarkArray['link']
                        . '&title='
                        . $sbmarkArray['title']
                        . '"'
                        . ' target="_blank"><img border="0" src="'
                        . XOOPS_URL
                        . '/modules/'
                        . $xoopsModule->getVar('dirname')
                        . '/assets/images/sbookmarks/dzone.png" align="middle" title="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'DZone" alt="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'DZone"></a>';

    //Earthlink
    $sbmarks['earthlink'] = '<a href="https://myfavorites.earthlink.net/my/add_favorite?v=1&url='
                            . $sbmarkArray['link']
                            . '&title='
                            . $sbmarkArray['title']
                            . '"'
                            . ' target="_blank"><img border="0" src="'
                            . XOOPS_URL
                            . '/modules/'
                            . $xoopsModule->getVar('dirname')
                            . '/assets/images/sbookmarks/earthlink.png" align="middle" title="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'EarthLink MyFavorites" alt="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'EarthLink MyFavorites"></a>';

    //EatMyHamster
    $sbmarks['eatmyhamster'] = '<a href="https://www.eatmyhamster.com/post?u='
                               . $sbmarkArray['link']
                               . '&h='
                               . $sbmarkArray['title']
                               . '"'
                               . ' target="_blank"><img border="0" src="'
                               . XOOPS_URL
                               . '/modules/'
                               . $xoopsModule->getVar('dirname')
                               . '/assets/images/sbookmarks/eatmyhamster.png" align="middle" title="'
                               . _MD_XOOPSTUBE_ADDTO
                               . 'EatMyHamster" alt="'
                               . _MD_XOOPSTUBE_ADDTO
                               . 'EatMyHamster"></a>';

    //FaceBook
    $sbmarks['facebook'] = '<a href="https://www.facebook.com/sharer.php?u='
                           . $sbmarkArray['link']
                           . '&title='
                           . $sbmarkArray['title']
                           . '"'
                           . ' target="_blank"> <img border="0" src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/sbookmarks/facebook.png" align="middle" title="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Facebook" alt="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Facebook"></a>';

    //Fantacular
    $sbmarks['fantacular'] = '<a href="https://fantacular.com/add.asp?url='
                             . $sbmarkArray['link']
                             . '&title='
                             . $sbmarkArray['title']
                             . '"'
                             . ' target="_blank"><img border="0" src="'
                             . XOOPS_URL
                             . '/modules/'
                             . $xoopsModule->getVar('dirname')
                             . '/assets/images/sbookmarks/fantacular.png" align="middle" title="'
                             . _MD_XOOPSTUBE_ADDTO
                             . 'Fantacular" alt="'
                             . _MD_XOOPSTUBE_ADDTO
                             . 'Fantacular"></a>';

    //Fark
    $sbmarks['fark'] = '<a href="https://cgi.fark.com/cgi/fark/edit.pl?new_url='
                       . $sbmarkArray['link']
                       . '&new_comment='
                       . $sbmarkArray['title']
                       . '"'
                       . ' target="_blank"><img border="0" src="'
                       . XOOPS_URL
                       . '/modules/'
                       . $xoopsModule->getVar('dirname')
                       . '/assets/images/sbookmarks/fark.png" align="middle" title="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Fark" alt="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Fark"></a>';

    //FeedMarker
    $sbmarks['feedmarker'] = '<a href="https://www.feedmarker.com/admin.php?do=bookmarklet_mark&url='
                             . $sbmarkArray['link']
                             . '&title='
                             . $sbmarkArray['title']
                             . '"'
                             . ' target="_blank"><img border="0" src="'
                             . XOOPS_URL
                             . '/modules/'
                             . $xoopsModule->getVar('dirname')
                             . '/assets/images/sbookmarks/feedmarker.png" align="middle" title="'
                             . _MD_XOOPSTUBE_ADDTO
                             . 'FeedMarker" alt="'
                             . _MD_XOOPSTUBE_ADDTO
                             . 'FeedMarker"></a>';

    //FeedMeLinks
    $sbmarks['feedmelinks'] = '<a href="https://feedmelinks.com/categorize?from=toolbar&op=submit&name='
                              . $sbmarkArray['title']
                              . '&url='
                              . $sbmarkArray['link']
                              . '"'
                              . ' target="_blank"><img border="0" src="'
                              . XOOPS_URL
                              . '/modules/'
                              . $xoopsModule->getVar('dirname')
                              . '/assets/images/sbookmarks/feedmelinks.png" align="middle" title="'
                              . _MD_XOOPSTUBE_ADDTO
                              . 'FeedMeLinks" alt="'
                              . _MD_XOOPSTUBE_ADDTO
                              . 'FeedMeLinks"></a>';

    //Furl
    $sbmarks['furl'] = '<a href="https://www.furl.net/storeIt.jsp?t='
                       . $sbmarkArray['title']
                       . '&u='
                       . $sbmarkArray['link']
                       . '"'
                       . ' target="_blank"><img border="0" src="'
                       . XOOPS_URL
                       . '/modules/'
                       . $xoopsModule->getVar('dirname')
                       . '/assets/images/sbookmarks/furl.png" align="middle" title="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Furk" alt="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Furl"></a>';

    //Google
    $sbmarks['google'] = '<a href="https://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk='
                         . $sbmarkArray['link']
                         . '&title='
                         . $sbmarkArray['title']
                         . '"'
                         . ' target="_blank"><img border="0" src="'
                         . XOOPS_URL
                         . '/modules/'
                         . $xoopsModule->getVar('dirname')
                         . '/assets/images/sbookmarks/google.png" align="middle" title="'
                         . _MD_XOOPSTUBE_ADDTO
                         . 'Google" alt="'
                         . _MD_XOOPSTUBE_ADDTO
                         . 'Google"></a>';

    //Gravee
    $sbmarks['gravee'] = '<a href="https://www.gravee.com/account/bookmarkpop?u='
                         . $sbmarkArray['link']
                         . '&t='
                         . $sbmarkArray['title']
                         . '"'
                         . ' target="_blank"><img border="0" src="'
                         . XOOPS_URL
                         . '/modules/'
                         . $xoopsModule->getVar('dirname')
                         . '/assets/images/sbookmarks/gravee.png" align="middle" title="'
                         . _MD_XOOPSTUBE_ADDTO
                         . 'Gravee" alt="'
                         . _MD_XOOPSTUBE_ADDTO
                         . 'Gravee"></a>';

    //igooi
    $sbmarks['igooi'] = '<a href="https://www.igooi.com/addnewitem.aspx?self=1&noui=yes&jump=close&url='
                        . $sbmarkArray['link']
                        . '&title='
                        . $sbmarkArray['title']
                        . '"'
                        . ' target="_blank"><img border="0" src="'
                        . XOOPS_URL
                        . '/modules/'
                        . $xoopsModule->getVar('dirname')
                        . '/assets/images/sbookmarks/igooi.png" align="middle" title="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'igooi" alt="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'igooi"></a>';

    //iTalkNews
    $sbmarks['italknews'] = '<a href="https://italknews.com/member/write_link.php?content='
                            . $sbmarkArray['link']
                            . '&headline='
                            . $sbmarkArray['title']
                            . '"'
                            . ' target="_blank"><img border="0" src="'
                            . XOOPS_URL
                            . '/modules/'
                            . $xoopsModule->getVar('dirname')
                            . '/assets/images/sbookmarks/italknews.png" align="middle" title="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'iTalkNews" alt="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'iTalkNews"></a>';

    //Jookster
    $sbmarks['jookster'] = '<a href="https://www.jookster.com/JookThis.aspx?url='
                           . $sbmarkArray['link']
                           . '"'
                           . 'target="_blank"><img border="0" src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/sbookmarks/jookster.png" align="middle" title="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Jookster" alt="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Jookster"></a>';

    //Kinja
    $sbmarks['kinja'] = '<a href="https://kinja.com/id.knj?url='
                        . $sbmarkArray['link']
                        . '"'
                        . 'target="_blank"><img border="0" src="'
                        . XOOPS_URL
                        . '/modules/'
                        . $xoopsModule->getVar('dirname')
                        . '/assets/images/sbookmarks/kinja.png" align="middle" title="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'Kinja" alt="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'Kinja"></a>';

    //Linkagogo
    $sbmarks['linkagogo'] = '<a href="https://www.linkagogo.com/go/AddNoPopup?title='
                            . $sbmarkArray['title']
                            . '&url='
                            . $sbmarkArray['link']
                            . '"'
                            . 'target="_blank"><img border="0" src="'
                            . XOOPS_URL
                            . '/modules/'
                            . $xoopsModule->getVar('dirname')
                            . '/assets/images/sbookmarks/linkagogo.png" align="middle" title="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'Linkagogo" alt="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'Linkagogo"></a>';

    //LinkRoll
    $sbmarks['linkroll'] = '<a href="https://linkroll.com/insert.php?url='
                           . $sbmarkArray['link']
                           . '&title='
                           . $sbmarkArray['title']
                           . '"'
                           . ' target="_blank"><img border="0" src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/sbookmarks/linkroll.png" align="middle" title="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'LinkRoll" alt="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'LinkRoll"></a>';

    //linuxquestions.org
    $sbmarks['linuxquestions'] = '<a href="https://bookmarks.linuxquestions.org/linux/post?uri='
                                 . $sbmarkArray['link']
                                 . '&title='
                                 . $sbmarkArray['title']
                                 . '&when_done=go_back"'
                                 . 'target="_blank"><img border="0" src="'
                                 . XOOPS_URL
                                 . '/modules/'
                                 . $xoopsModule->getVar('dirname')
                                 . '/assets/images/sbookmarks/linuxquestions.png" align="middle" title="'
                                 . _MD_XOOPSTUBE_ADDTO
                                 . 'linuxquestions.org" alt="'
                                 . _MD_XOOPSTUBE_ADDTO
                                 . 'linuxquestions.org"></a>';

    //LookMarks
    $sbmarks['lookmarks'] = '<a href="https://www.lookmarks.com/AddLinkFrame.aspx?url='
                            . $sbmarkArray['link']
                            . '&Title='
                            . $sbmarkArray['title']
                            . '"'
                            . ' target="_blank"><img border="0" src="'
                            . XOOPS_URL
                            . '/modules/'
                            . $xoopsModule->getVar('dirname')
                            . '/assets/images/sbookmarks/lookmarks.png" align="middle" title="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'LookMarks" alt="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'LookMarks"></a>';

    //Lycos
    $sbmarks['lycos'] = '<a href="https://iq.lycos.co.uk/lili/my/add?url='
                        . $sbmarkArray['link']
                        . '"'
                        . ' target="_blank"><img border="0" src="'
                        . XOOPS_URL
                        . '/modules/'
                        . $xoopsModule->getVar('dirname')
                        . '/assets/images/sbookmarks/lycos.png" align="middle" title="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'Lycos" alt="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'Lycos"></a>';

    //Windows Live
    $sbmarks['live'] = '<a href="https://favorites.live.com/quickadd.aspx?marklet=1&mkt=en-us&title='
                       . $sbmarkArray['title']
                       . '&url='
                       . $sbmarkArray['link']
                       . '&top=1'
                       . '"'
                       . 'target="_blank"><img border="0" src="'
                       . XOOPS_URL
                       . '/modules/'
                       . $xoopsModule->getVar('dirname')
                       . '/assets/images/sbookmarks/windows_live.png" align="middle" title="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Windows Live" alt="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Windows Live"></a>';

    //Magnolia
    $sbmarks['magnolia'] = '<a href="https://ma.gnolia.com/bookmarklet/add?url='
                           . $sbmarkArray['link']
                           . '&title='
                           . $sbmarkArray['title']
                           . '"'
                           . ' target="_blank"><img border="0" src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/sbookmarks/magnolia.png" align="middle" title="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Ma.gnolia" alt="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Ma.gnolia"></a>';

    //Markabboo
    $sbmarks['markabboo'] = '<a href="https://www.markaboo.com/resources/new?url='
                            . $sbmarkArray['link']
                            . '&title='
                            . $sbmarkArray['title']
                            . '"'
                            . ' target="_blank"><img border="0" src="'
                            . XOOPS_URL
                            . '/modules/'
                            . $xoopsModule->getVar('dirname')
                            . '/assets/images/sbookmarks/markabboo.png" align="middle" title="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'Markabboo" alt="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'Markabboo"></a>';

    //Netscape
    $sbmarks['netscape'] = '<a href="https://www.netscape.com/submit/?U='
                           . $sbmarkArray['link']
                           . '&T='
                           . $sbmarkArray['title']
                           . '"'
                           . ' target="_blank"><img border="0" src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/sbookmarks/netscape.png" align="middle" title="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Netscape" alt="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Netscape"></a>';

    //Netvouz
    $sbmarks['netvouz'] = '<a href="https://www.netvouz.com/action/submitBookmark?url='
                          . $sbmarkArray['link']
                          . '&title='
                          . $sbmarkArray['title']
                          . '&popup=no"'
                          . ' target="_blank"><img border="0" src="'
                          . XOOPS_URL
                          . '/modules/'
                          . $xoopsModule->getVar('dirname')
                          . '/assets/images/sbookmarks/netvouz.png" align="middle" title="'
                          . _MD_XOOPSTUBE_ADDTO
                          . 'Netvouz" alt="'
                          . _MD_XOOPSTUBE_ADDTO
                          . 'Netvouz"></a>';

    //Newsvine
    $sbmarks['newsvine'] = '<a href="https://www.newsvine.com/_tools/seed&save?u='
                           . $sbmarkArray['link']
                           . '&h='
                           . $sbmarkArray['title']
                           . '"'
                           . ' target="_blank"><img border="0" src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/sbookmarks/newsvine.png" align="middle" title="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Newsvine" alt="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'Newsvine"></a>';

    //Ning
    $sbmarks['ning'] = '<a href="https://bookmarks.ning.com/addItem.php?url='
                       . $sbmarkArray['link']
                       . '&title='
                       . $sbmarkArray['title']
                       . '"'
                       . ' target="_blank"><img border="0" src="'
                       . XOOPS_URL
                       . '/modules/'
                       . $xoopsModule->getVar('dirname')
                       . '/assets/images/sbookmarks/ning.png" align="middle" title="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Ning" alt="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Ning"></a>';

    //NowPublic
    $sbmarks['nowpublic'] = '<a href="https://view.nowpublic.com/?src='
                            . $sbmarkArray['link']
                            . '&t='
                            . $sbmarkArray['title']
                            . '"'
                            . ' target="_blank"><img border="0" src="'
                            . XOOPS_URL
                            . '/modules/'
                            . $xoopsModule->getVar('dirname')
                            . '/assets/images/sbookmarks/nowpublic.png" align="middle" title="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'NowPublic" alt="'
                            . _MD_XOOPSTUBE_ADDTO
                            . 'NowPublic"></a>';

    //RawSugar
    $sbmarks['rawsugar'] = '<a href="https://www.rawsugar.com/pages/tagger.faces?turl='
                           . $sbmarkArray['link']
                           . '&tttl='
                           . $sbmarkArray['title']
                           . '"'
                           . ' target="_blank"><img border="0" src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/sbookmarks/rawsugar.png" align="middle" title="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'RawSugar" alt="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'RawSugar"></a>';

    //Reddit
    $sbmarks['reddit'] = '<a href="https://reddit.com/submit?url='
                         . $sbmarkArray['link']
                         . '&title='
                         . $sbmarkArray['title']
                         . '"'
                         . ' target="_blank"><img border="0" src="'
                         . XOOPS_URL
                         . '/modules/'
                         . $xoopsModule->getVar('dirname')
                         . '/assets/images/sbookmarks/reddit.png" align="middle" title="'
                         . _MD_XOOPSTUBE_ADDTO
                         . 'reddit" alt="'
                         . _MD_XOOPSTUBE_ADDTO
                         . 'reddit"></a>';

    //Riffs
    $sbmarks['riffs'] = '<a href="https://www.riffs.com/item.cgi?section=init_url&url='
                        . $sbmarkArray['link']
                        . '&name='
                        . $sbmarkArray['title']
                        . '"'
                        . ' target="_blank"><img border="0" src="'
                        . XOOPS_URL
                        . '/modules/'
                        . $xoopsModule->getVar('dirname')
                        . '/assets/images/sbookmarks/riffs.png" align="middle" title="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'Riffs" alt="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'Riffs"></a>';

    //Rojo
    $sbmarks['rojo'] = '<a href="https://www.rojo.com/submit/?title='
                       . $sbmarkArray['title']
                       . '&url='
                       . $sbmarkArray['link']
                       . '"'
                       . ' target="_blank"><img border="0" src="'
                       . XOOPS_URL
                       . '/modules/'
                       . $xoopsModule->getVar('dirname')
                       . '/assets/images/sbookmarks/rojo.png" align="middle" title="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Rojo" alt="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Rojo"></a>';

    //Shadows
    $sbmarks['shadow'] = '<a href="https://www.shadows.com/features/tcr.htm?title='
                         . $sbmarkArray['title']
                         . '&url='
                         . $sbmarkArray['link']
                         . '"'
                         . ' target="_blank"><img border="0" src="'
                         . XOOPS_URL
                         . '/modules/'
                         . $xoopsModule->getVar('dirname')
                         . '/assets/images/sbookmarks/shadows.png" align="middle" title="'
                         . _MD_XOOPSTUBE_ADDTO
                         . 'Shadows" alt="'
                         . _MD_XOOPSTUBE_ADDTO
                         . 'Shadows"></a>';

    //Simpy
    //    $sbmarks['simpy']
    //        = '<a href="https://simpy.com/simpy/LinkAdd.do?title=' . $sbmarkArray['title'] . '&href=' . $sbmarkArray['link']
    //          . '"' . ' target="_blank"><img border="0" src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname')
    //          . '/assets/images/sbookmarks/simpy.png" align="middle" title="' . _MD_XOOPSTUBE_ADDTO . 'Simpy" alt="'
    //          . _MD_XOOPSTUBE_ADDTO . 'Simpy"></a>';

    //Spurl
    //    $sbmarks['spurl']
    //        = '<a href="https://www.spurl.net/spurl.php?url=' . $sbmarkArray['link'] . '&title=' . $sbmarkArray['title'] . '"'
    //          . ' target="_blank"><img border="0" src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname')
    //          . '/assets/images/sbookmarks/spurl.png" align="middle" title="' . _MD_XOOPSTUBE_ADDTO . 'Spurl" alt="'
    //          . _MD_XOOPSTUBE_ADDTO . 'Spurl"></a>';

    //Squidoo
    $sbmarks['squidoo'] = '<a href="https://www.squidoo.com/lensmaster/bookmark?'
                          . $sbmarkArray['link']
                          . '"'
                          . ' target="_blank"><img border="0" src="'
                          . XOOPS_URL
                          . '/modules/'
                          . $xoopsModule->getVar('dirname')
                          . '/assets/images/sbookmarks/squidoo.png" align="middle" title="'
                          . _MD_XOOPSTUBE_ADDTO
                          . 'Squidoo" alt="'
                          . _MD_XOOPSTUBE_ADDTO
                          . 'Squidoo"></a>';

    //StumbleUpon
    $sbmarks['stumble'] = '<a href="https://www.stumbleupon.com/submit?url='
                          . $sbmarkArray['link']
                          . '&title='
                          . $sbmarkArray['title']
                          . '"'
                          . ' target="_blank"><img border="0" src="'
                          . XOOPS_URL
                          . '/modules/'
                          . $xoopsModule->getVar('dirname')
                          . '/assets/images/sbookmarks/stumbleupon.png" align="middle" title="'
                          . _MD_XOOPSTUBE_ADDTO
                          . 'StumbleUpon" alt="'
                          . _MD_XOOPSTUBE_ADDTO
                          . 'StumbleUpon"></a>';

    //tagtooga
    $sbmarks['tagtooga'] = '<a href="https://www.tagtooga.com/tapp/db.exe?c=jsEntryForm&b=fx&title='
                           . $sbmarkArray['title']
                           . '&url='
                           . $sbmarkArray['link']
                           . '"'
                           . ' target="_blank"><img border="0" src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/sbookmarks/tagtooga.png" align="middle" title="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'tagtooga" alt="'
                           . _MD_XOOPSTUBE_ADDTO
                           . 'tagtooga"></a>';

    //Technorati
    $sbmarks['techno'] = '<a="https://www.technorati.com/faves?add='
                         . $sbmarkArray['link']
                         . $sbmarkArray['title']
                         . '"'
                         . ' target="_blank"><img border="0" src="'
                         . XOOPS_URL
                         . '/modules/'
                         . $xoopsModule->getVar('dirname')
                         . '/assets/images/sbookmarks/technorati.png" align="middle" title="'
                         . _MD_XOOPSTUBE_ADDTO
                         . 'Technorati" alt="'
                         . _MD_XOOPSTUBE_ADDTO
                         . 'Technorati"></a>';

    //Wink
    //    $sbmarks['wink']
    //        = '<a href="https://www.wink.com/_/tag?url=' . $sbmarkArray['link'] . '&doctitle=' . $sbmarkArray['title'] . '"'
    //          . ' target="_blank"><img border="0" src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname')
    //          . '/assets/images/sbookmarks/wink.png" align="middle" title="' . _MD_XOOPSTUBE_ADDTO . 'Wink" alt="'
    //          . _MD_XOOPSTUBE_ADDTO . 'Wink"></a>';

    // Yahoo
    $sbmarks['yahoo'] = '<a href="https://myweb2.search.yahoo.com/myresults/bookmarklet?t='
                        . $sbmarkArray['title']
                        . '&u='
                        . $sbmarkArray['link']
                        . '"'
                        . ' target="_blank"><img border="0" src="'
                        . XOOPS_URL
                        . '/modules/'
                        . $xoopsModule->getVar('dirname')
                        . '/assets/images/sbookmarks/yahoo.png" align="middle" title="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'Yahoo MyWeb" alt="'
                        . _MD_XOOPSTUBE_ADDTO
                        . 'Yahoo MyWeb"></a>';

    //Information
    $sbmarks['info'] = '<a href="https://en.wikipedia.org/wiki/Social_bookmarking" target="_blank"><img border="0" src="'
                       . XOOPS_URL
                       . '/modules/'
                       . $xoopsModule->getVar('dirname')
                       . '/assets/images/sbookmarks/what.png" align="middle" title="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Information" alt="'
                       . _MD_XOOPSTUBE_ADDTO
                       . 'Information"></a>';

    // Make list of selected social bookmarks
    // Comment out those social bookmarks which should not be visible

    $sbmarks['sbmarks'] = //$sbmarks['blackflip'] . " " .
        //$sbmark['bibsonomy'] . " " .
        $sbmarks['blinklist'] . ' ' . //$sbmark['blogmark'] . " " .
        //$sbmark['citeulike'] . " " .
        //$sbmarks['connotea'] . " " .
        $sbmarks['delicio'] . ' ' . $sbmarks['digg'] . ' ' . //$sbmarks['diigo'] . " " .
        //$sbmarks['dzone'] . " " .
        //$sbmarks['earthlink'] . " " .
        //$sbmarks['eatmyhamster'] . " " .
        $sbmarks['facebook'] . ' ' . //$sbmarks['fantacular'] . " " .
        //$sbmarks['fark'] . " " .
        //$sbmarks['feedmarker'] . " " .
        //$sbmarks['feedmelinks'] . " " .
        $sbmarks['furl'] . ' ' . $sbmarks['google'] . ' ' . //$sbmarks['gravee'] . " " .
        //$sbmarks['igooi'] . " " .
        //$sbmarks['italknews'] . " " .
        //$sbmarks['jookster'] . " " .
        //$sbmarks['kinja'] . " " .
        //$sbmarks['linkagogo'] . " " .
        //$sbmarks['linkroll'] . " " .
        //$sbmarks['linuxquestions'] . " " .
        //$sbmarks['live'] . " " .         <==== Don't use doesn't work properly
        //$sbmarks['lookmarks'] . " " .
        //$sbmarks['lycos'] . " " .
        //$sbmarks['magnolia'] . " " .
        //$sbmarks['markabboo'] . " " .
        $sbmarks['netscape'] . ' ' . //$sbmarks['netvouz'] . " " .
        //$sbmarks['newsvine'] . " " .
        //$sbmarks['ning'] . " " .
        //$sbmarks['nowpublic'] . " " .
        //$sbmarks['rawsugar'] . " " .
        $sbmarks['reddit'] . ' ' . //$sbmarks['riffs'] . " " .
        //$sbmarks['rojo'] . " " .
        //$sbmarks['shadow'] . " " .
        //        $sbmarks['simpy'] . " "
        //        . $sbmarks['spurl'] . " "
        //$sbmarks['squidoo'] . " "
        $sbmarks['stumble'] . ' ' . //$sbmarks['tagtooga'] . " " .
        //$sbmarks['techno'] . " " .
        //        $sbmarks['wink'] . " ".
        $sbmarks['yahoo'] . ' ' . $sbmarks['info'];

    return $sbmarks['sbmarks'];
}
