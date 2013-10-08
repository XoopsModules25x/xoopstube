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
 * @author          Fernando Santos (topet05), fernando@mastop.com.br
 * @copyright       Mastop InfoDigital © 2003-2007
 * @link            http://www.mastop.com.br
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           1.0.6
 * @version         $Id$
 */

include_once 'admin_header.php';
if (!is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid())) {
    exit(MPU_ADM_403);
}
if ($xoopsUser->isAdmin($xoopsModule->mid())) {
    include_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
    $op = "list";
    if (isset($_POST)) {
        foreach ($_POST as $k => $v) {
            $$k = $v;
        }
    }

    if (isset($_GET['op'])) {
        if ($_GET['op'] == "edit" || $_GET['op'] == "delete" || $_GET['op'] == "delete_ok" || $_GET['op'] == "clone") {
            $op  = $_GET['op'];
            $bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;
        }
    }


    /**
     *
     */
    function listBlocks()
    {
        global $xoopsUser, $xoopsConfig, $xoopsModule;
        include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
        $db =& XoopsDatabaseFactory::getDatabaseConnection();
        if (file_exists(
            XOOPS_ROOT_PATH . "/modules/system/language/" . $xoopsConfig['language'] . "/admin/blocksadmin.php"
        )
        ) {
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/" . $xoopsConfig['language'] . "/admin.php");
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/" . $xoopsConfig['language']
                         . "/admin/blocksadmin.php");
            //include_once(XOOPS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin/groups.php");
        } else {
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/portuguesebr/admin.php");
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/portuguesebr/admin/blocksadmin.php");
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/portuguesebr/admin/groups.php");
        }
        $module_handler     =& xoops_gethandler('module');
        $member_handler     =& xoops_gethandler('member');
        $moduleperm_handler =& xoops_gethandler('groupperm');
        $groups             =& $member_handler->getGroups();
        $criteria           = new CriteriaCompo(new Criteria('hasmain', 1));
        $criteria->add(new Criteria('isactive', 1));
        $module_list     =& $module_handler->getList($criteria);
        $module_list[-1] = _AM_SYSTEM_BLOCKS_TOPPAGE;
        $module_list[0]  = _AM_SYSTEM_BLOCKS_ALLPAGES;
        ksort($module_list);
        echo "
        <h4 style='text-align:left;'>" . _AM_XTUBE_BADMIN . "</h4>";
        $module_handler =& xoops_gethandler('module');
        echo "<form action='" . $_SERVER['PHP_SELF'] . "' name='blockadmin' method='post'>
        <table width='100%' class='outer' cellpadding='4' cellspacing='1'>
        <tr valign='middle'><th align='center'>" . _AM_XTUBE_TITLE . "</th><th align='center' nowrap='nowrap'>"
             . _AM_XTUBE_SIDE . "<br />" . _LEFT . "-" . _CENTER . "-" . _RIGHT . "</th><th align='center'>"
             . _AM_XTUBE_WEIGHT . "</th><th align='center'>" . _AM_XTUBE_VISIBLE . "</th><th align='center'>"
             . _AM_SYSTEM_BLOCKS_VISIBLEIN . "</th><th align='center'>" . _AM_SYSTEM_ADGS . "</th><th align='center'>"
             . _AM_XTUBE_ACTION . "</th></tr>
        ";
        $block_arr   =& XoopsBlock::getByModule($xoopsModule->mid());
        $block_count = count($block_arr);
        $class       = 'even';
        foreach ($block_arr as $i) {
            $groups_perms =& $moduleperm_handler->getGroupIds('block_read', $i->getVar("bid"));
            $sql
                          =
                'SELECT module_id FROM ' . $db->prefix('block_module_link') . ' WHERE block_id=' . $i->getVar("bid");
            $result       = $db->query($sql);
            $modules      = array();
            while ($row = $db->fetchArray($result)) {
                $modules[] = intval($row['module_id']);
            }
            $sel0 = $sel1 = $ssel0 = $ssel1 = $ssel2 = $ssel3 = $ssel4 = $ssel5 = $ssel6 = $ssel7 = "";
            if ($i->getVar("visible") == 1) {
                $sel1 = " checked='checked'";
            } else {
                $sel0 = " checked='checked'";
            }
            if ($i->getVar("side") == XOOPS_SIDEBLOCK_LEFT) {
                $ssel0 = " checked='checked'";
            } elseif ($i->getVar("side") == XOOPS_SIDEBLOCK_RIGHT) {
                $ssel1 = " checked='checked'";
            } elseif ($i->getVar("side") == XOOPS_CENTERBLOCK_LEFT) {
                $ssel2 = " checked='checked'";
            } elseif ($i->getVar("side") == XOOPS_CENTERBLOCK_RIGHT) {
                $ssel4 = " checked='checked'";
            } elseif ($i->getVar("side") == XOOPS_CENTERBLOCK_CENTER) {
                $ssel3 = " checked='checked'";
            } elseif ($i->getVar("side") == XOOPS_CENTERBLOCK_BOTTOMLEFT) {
                $ssel5 = " checked='checked'";
            } elseif ($i->getVar("side") == XOOPS_CENTERBLOCK_BOTTOMRIGHT) {
                $ssel6 = " checked='checked'";
            } elseif ($i->getVar("side") == XOOPS_CENTERBLOCK_BOTTOM) {
                $ssel7 = " checked='checked'";
            }
            if ($i->getVar("title") == "") {
                $title = "&nbsp;";
            } else {
                $title = $i->getVar("title");
            }
            $name = $i->getVar("name");
            echo
                "<tr valign='top'><td class='$class' align='center'><input type='text' name='title[" . $i->getVar("bid")
                . "]' value='" . $title . "'></td><td class='$class' align='center' nowrap='nowrap'>
                    <div align='center' >
                    <input type='radio' name='side[" . $i->getVar("bid") . "]' value='" . XOOPS_CENTERBLOCK_LEFT . "'$ssel2 />
                        <input type='radio' name='side[" . $i->getVar("bid") . "]' value='" . XOOPS_CENTERBLOCK_CENTER
                . "'$ssel3 />
                    <input type='radio' name='side[" . $i->getVar("bid") . "]' value='" . XOOPS_CENTERBLOCK_RIGHT . "'$ssel4 />
                    </div>
                    <div>
                        <span style='float:right'><input type='radio' name='side[" . $i->getVar("bid") . "]' value='"
                . XOOPS_SIDEBLOCK_RIGHT . "'$ssel1 /></span>
                    <div align='left'><input type='radio' name='side[" . $i->getVar("bid") . "]' value='"
                . XOOPS_SIDEBLOCK_LEFT . "'$ssel0 /></div>
                    </div>
                    <div align='center'>
                    <input type='radio' name='side[" . $i->getVar("bid") . "]' value='" . XOOPS_CENTERBLOCK_BOTTOMLEFT
                . "'$ssel5 />
                        <input type='radio' name='side[" . $i->getVar("bid") . "]' value='" . XOOPS_CENTERBLOCK_BOTTOM
                . "'$ssel7 />
                    <input type='radio' name='side[" . $i->getVar("bid") . "]' value='" . XOOPS_CENTERBLOCK_BOTTOMRIGHT
                . "'$ssel6 />
                    </div>
                </td><td class='$class' align='center'><input type='text' name='weight[" . $i->getVar("bid")
                . "]' value='" . $i->getVar("weight") . "' size='5' maxlength='5' /></td><td class='$class' align='center' nowrap><input type='radio' name='visible["
                . $i->getVar("bid") . "]' value='1'$sel1>" . _YES . "&nbsp;<input type='radio' name='visible["
                . $i->getVar("bid") . "]' value='0'$sel0>" . _NO . "</td>";

            echo "<td class='$class' align='center'><select size='5' name='bmodule[" . $i->getVar("bid")
                 . "][]' id='bmodule[" . $i->getVar("bid") . "][]' multiple='multiple'>";
            foreach ($module_list as $k => $v) {
                echo "<option value='$k'" . ((in_array($k, $modules)) ? " selected='selected'" : "") . ">$v</option>";
            }
            echo "</select></td>";

            echo "<td class='$class' align='center'><select size='5' name='groups[" . $i->getVar("bid")
                 . "][]' id='groups[" . $i->getVar("bid") . "][]' multiple='multiple'>";
            foreach ($groups as $grp) {
                echo "<option value='" . $grp->getVar('groupid') . "' " . ((in_array(
                        $grp->getVar('groupid'),
                        $groups_perms
                    )) ? " selected='selected'" : "") . ">" . $grp->getVar('name') . "</option>";
            }
            echo "</select></td>";

            echo "<td class='$class' align='center'><a href='" . XOOPS_URL
                 . "/modules/system/admin.php?fct=blocksadmin&amp;op=edit&amp;bid=" . $i->getVar("bid") . "'>" . _EDIT
                 . "</a> <a href='blocksadmin.php?op=clone&amp;bid=" . $i->getVar("bid") . "'>" . _CLONE . "</a>";
            if ($i->getVar('block_type') != 'S' && $i->getVar('block_type') != 'M') {
                echo "&nbsp;<a href='" . XOOPS_URL . "/modules/system/admin.php?fct=blocksadmin&amp;op=delete&amp;bid="
                     . $i->getVar("bid") . "'>" . _DELETE . "</a>";
            }
            echo "
            <input type='hidden' name='oldtitle[" . $i->getVar("bid") . "]' value='" . $i->getVar('title') . "' />
            <input type='hidden' name='oldside[" . $i->getVar("bid") . "]' value='" . $i->getVar('side') . "' />
            <input type='hidden' name='oldweight[" . $i->getVar("bid") . "]' value='" . $i->getVar('weight') . "' />
            <input type='hidden' name='oldvisible[" . $i->getVar("bid") . "]' value='" . $i->getVar('visible') . "' />
            <input type='hidden' name='bid[" . $i->getVar("bid") . "]' value='" . $i->getVar("bid") . "' />
            </td></tr>
            ";
            $class = ($class == 'even') ? 'odd' : 'even';
        }
        echo "<tr><td class='foot' align='center' colspan='7'>
        <input type='hidden' name='op' value='order' />
        " . $GLOBALS['xoopsSecurity']->getTokenHTML() . "
        <input type='submit' name='submit' value='" . _SUBMIT . "' />
        </td></tr></table>
        </form>
        <br /><br />";
    }


    /**
     * @param $bid
     */
    function cloneBlock($bid)
    {
        global $xoopsConfig;
        if (file_exists(
            XOOPS_ROOT_PATH . "/modules/system/language/" . $xoopsConfig['language'] . "/admin/blocksadmin.php"
        )
        ) {
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/" . $xoopsConfig['language'] . "/admin.php");
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/" . $xoopsConfig['language']
                         . "/admin/blocksadmin.php");
            //include_once(XOOPS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin/groups.php");
        } else {
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/portuguesebr/admin.php");
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/portuguesebr/admin/blocksadmin.php");
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/portuguesebr/admin/groups.php");
        }
        mpu_adm_menu();
        $myblock = new XoopsBlock($bid);
        $db      =& XoopsDatabaseFactory::getDatabaseConnection();
        $sql     = 'SELECT module_id FROM ' . $db->prefix('block_module_link') . ' WHERE block_id=' . intval($bid);
        $result  = $db->query($sql);
        $modules = array();
        while ($row = $db->fetchArray($result)) {
            $modules[] = intval($row['module_id']);
        }
        $is_custom = ($myblock->getVar('block_type') == 'C' || $myblock->getVar('block_type') == 'E') ? true : false;
        $block     = array(
            'title'      => $myblock->getVar('title') . ' Clone',
            'form_title' => _AM_CLONEBLOCK,
            'name'       => $myblock->getVar('name'),
            'side'       => $myblock->getVar('side'),
            'weight'     => $myblock->getVar('weight'),
            'visible'    => $myblock->getVar('visible'),
            'content'    => $myblock->getVar('content', 'N'),
            'modules'    => $modules,
            'is_custom'  => $is_custom,
            'ctype'      => $myblock->getVar('c_type'),
            'cachetime'  => $myblock->getVar('bcachetime'),
            'op'         => 'clone_ok',
            'bid'        => $myblock->getVar('bid'),
            'edit_form'  => $myblock->getOptions(),
            'template'   => $myblock->getVar('template'),
            'options'    => $myblock->getVar('options')
        );
        echo '<a href="blocksadmin.php">' . _AM_BADMIN
             . '</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;' . _AM_CLONEBLOCK
             . '<br /><br />';
        include 'blockform.php';
        $form->display();
        xoops_cp_footer();
        exit();
    }


    /**
     * @param $bid
     * @param $bside
     * @param $bweight
     * @param $bvisible
     * @param $bcachetime
     * @param $bmodule
     * @param $options
     */
    function isBlockCloned($bid, $bside, $bweight, $bvisible, $bcachetime, $bmodule, $options)
    {
        global $xoopsUser, $xoopsConfig;
        if (file_exists(
            XOOPS_ROOT_PATH . "/modules/system/language/" . $xoopsConfig['language'] . "/admin/blocksadmin.php"
        )
        ) {
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/" . $xoopsConfig['language'] . "/admin.php");
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/" . $xoopsConfig['language']
                         . "/admin/blocksadmin.php");
            //include_once(XOOPS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin/groups.php");
        } else {
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/portuguesebr/admin.php");
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/portuguesebr/admin/blocksadmin.php");
            include_once(XOOPS_ROOT_PATH . "/modules/system/language/portuguesebr/admin/groups.php");
        }
        $block = new XoopsBlock($bid);
        $clone =& $block->xoopsClone();
        if (empty($bmodule)) {
            xoops_cp_header();
            xoops_error(sprintf(_AM_NOTSELNG, _AM_VISIBLEIN));
            xoops_cp_footer();
            exit();
        }
        $clone->setVar('side', $bside);
        $clone->setVar('weight', $bweight);
        $clone->setVar('visible', $bvisible);
        //$clone->setVar('content', $_POST['bcontent']);
        $clone->setVar('title', $_POST['btitle']);
        $clone->setVar('bcachetime', $bcachetime);
        if (isset($options) && (count($options) > 0)) {
            $options = implode('|', $options);
            $clone->setVar('options', $options);
        }
        $clone->setVar('bid', 0);
        if ($block->getVar('block_type') == 'C' || $block->getVar('block_type') == 'E') {
            $clone->setVar('block_type', 'E');
        } else {
            $clone->setVar('block_type', 'D');
        }
        $newid = $clone->store();
        if (!$newid) {
            xoops_cp_header();
            $clone->getHtmlErrors();
            xoops_cp_footer();
            exit();
        }
        if ($clone->getVar('template') != '') {
            $tplfile_handler =& xoops_gethandler('tplfile');
            $btemplate       =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $bid);
            if (count($btemplate) > 0) {
                $tplclone =& $btemplate[0]->xoopsClone();
                $tplclone->setVar('tpl_id', 0);
                $tplclone->setVar('tpl_refid', $newid);
                $tplfile_handler->insert($tplclone);
            }
        }
        $db =& XoopsDatabaseFactory::getDatabaseConnection();
        foreach ($bmodule as $bmid) {
            $sql = 'INSERT INTO ' . $db->prefix('block_module_link') . ' (block_id, module_id) VALUES (' . $newid . ', '
                   . $bmid . ')';
            $db->query($sql);
        }
        $groups =& $xoopsUser->getGroups();
        $count  = count($groups);
        for ($i = 0; $i < $count; $i++) {
            $sql
                =
                "INSERT INTO " . $db->prefix('group_permission')
                . " (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES (" . $groups[$i] . ", "
                . $newid . ", 1, 'block_read')";
            $db->query($sql);
        }
        redirect_header('blocksadmin.php?op=listar', 1, _AM_DBUPDATED);
    }


    /**
     * @param $bid
     * @param $title
     * @param $weight
     * @param $visible
     * @param $side
     */
    function xtubeSetOrder($bid, $title, $weight, $visible, $side)
    {
        $myblock = new XoopsBlock($bid);
        $myblock->setVar('title', $title);
        $myblock->setVar('weight', $weight);
        $myblock->setVar('visible', $visible);
        $myblock->setVar('side', $side);
        $myblock->store();
    }

    if ($op == "list") {
        xoops_cp_header();
//        mpu_adm_menu();
        listBlocks();
        include 'admin_footer.php';
        exit();
    }

    if ($op == "order") {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($_SERVER['PHP_SELF'], 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
            exit();
        }
        foreach (array_keys($bid) as $i) {
            if ($oldtitle[$i] != $title[$i] || $oldweight[$i] != $weight[$i] || $oldvisible[$i] != $visible[$i]
                || $oldside[$i] != $side[$i]
            ) {
                xtubeSetOrder($bid[$i], $title[$i], $weight[$i], $visible[$i], $side[$i], $bmodule[$i]);
            }
            if (!empty($bmodule[$i]) && count($bmodule[$i]) > 0) {
                $sql = sprintf("DELETE FROM %s WHERE block_id = %u", $xoopsDB->prefix('block_module_link'), $bid[$i]);
                $xoopsDB->query($sql);
                if (in_array(0, $bmodule[$i])) {
                    $sql = sprintf(
                        "INSERT INTO %s (block_id, module_id) VALUES (%u, %d)",
                        $xoopsDB->prefix('block_module_link'),
                        $bid[$i],
                        0
                    );
                    $xoopsDB->query($sql);
                } else {
                    foreach ($bmodule[$i] as $bmid) {
                        $sql = sprintf(
                            "INSERT INTO %s (block_id, module_id) VALUES (%u, %d)",
                            $xoopsDB->prefix('block_module_link'),
                            $bid[$i],
                            intval($bmid)
                        );
                        $xoopsDB->query($sql);
                    }
                }
            }
            $sql = sprintf("DELETE FROM %s WHERE gperm_itemid = %u", $xoopsDB->prefix('group_permission'), $bid[$i]);
            $xoopsDB->query($sql);
            if (!empty($groups[$i])) {
                foreach ($groups[$i] as $grp) {
                    $sql = sprintf(
                        "INSERT INTO %s (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES (%u, %u, 1, 'block_read')",
                        $xoopsDB->prefix('group_permission'),
                        $grp,
                        $bid[$i]
                    );
                    $xoopsDB->query($sql);
                }
            }
        }
        redirect_header($_SERVER['PHP_SELF'], 1, MPU_ADM_SUCESS2);
        exit();
    }
    if ($op == 'clone') {
        cloneBlock($bid);
    }

    if ($op == 'clone_ok') {
        isBlockCloned($bid, $bside, $bweight, $bvisible, $bcachetime, $bmodule, $options);
    }
} else {
    echo MPU_ADM_403;
}
