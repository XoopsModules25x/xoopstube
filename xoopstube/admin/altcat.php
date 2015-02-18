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

include_once __DIR__ . '/admin_header.php';

global $xoopsModuleConfig;

$op  = xtubeCleanRequestVars($_REQUEST, 'op', '');
$lid = xtubeCleanRequestVars($_REQUEST, 'lid', 0);

/**
 * @param        $xt
 * @param        $itemid
 * @param        $title
 * @param        $checks
 * @param string $order
 */
function makeTreeCheckTable($xt, $itemid, $title, $checks, $order = '')
{
    global $xtubemyts;

    echo '<div style="text-align: left;">';
    echo '<form name="altcat" method="post" action="' . xoops_getenv('PHP_SELF') . '">';
    echo '<table width="100%" callspacing="1" class="outer">';
    $sql = 'SELECT ' . $xt->id . ', ' . $title . ' FROM ' . $xt->table . ' WHERE ' . $xt->pid . '=0' . ' ORDER BY ' . $title;
    if ($order != '') {
        $sql .= ' ORDER BY ' . $order;
    }
    $result = $xt->db->query($sql);

    while (list($cid, $name) = $xt->db->fetchRow($result)) {
        $checked  = array_key_exists($cid, $checks) ? "checked='checked'" : "";
        $disabled = ($cid == intval($_GET['cid'])) ? "disabled='yes'" : "";
        $level    = 1;
        echo '
        <tr style="text-align: left;">
         <td width="30%" class="head">' . $name . '</td>
         <td class="head">
             <input type="checkbox" name="cid-' . $cid . '" value="0" ' . $checked . ' ' . $disabled . '/>
         </td>
        </tr>';
        $arr = $xt->getChildTreeArray($cid, $order);
        foreach ($arr as $cat) {
            $cat['prefix'] = str_replace('.', '-', $cat['prefix']);
            $catpath       = '&nbsp;' . $cat['prefix'] . '&nbsp;' . $xtubemyts->htmlSpecialCharsStrip($cat[$title]);
            $checked       = array_key_exists($cat['cid'], $checks) ? "checked='checked'" : "";
            $disabled      = ($cat['cid'] == intval($_GET['cid'])) ? "disabled='yes'" : "";
            $level         = substr_count($cat['prefix'], '-') + 1;
//          echo "<tr><td>" . $catpath . "<input type='checkbox' name='cid-" . $cat['cid'] . "' value='0' " . $checked . " " . $disabled . "/></td></tr>\n";
            echo '
        <tr style="text-align: left;">
         <td width="30%" class="even">' . $catpath . '</td>
         <td class="even">
             <input type="checkbox" name="cid-' . $cat['cid'] . '" value="0" ' . $checked . ' ' . $disabled . '/>
         </td>
        </tr>';
        }

    }
    echo '<tr>
           <td width="30%"></td>
           <td style="text-align: left;">
            <input type="submit" class="mainbutton" value="save" />
            <input type="hidden" name="op" value="save" />
            <input type="hidden" name="lid" value="' . $itemid . '" />
            </td>
          </tr>';
    echo '</table></form></div>';
}

switch (strtolower($op)) {
    case 'save':
        // first delete all alternate categories for this topic
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('xoopstube_altcat') . ' WHERE lid=' . $lid;
        if (!$result = $xoopsDB->query($sql)) {
            XoopsErrorHandler_HandleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

            return false;
        }

        $k = array_keys($_REQUEST);
        foreach ($k as $sid) {
            if (preg_match("/cid-([0-9]*)/", $sid, $cid)) {
                $sql
                    = 'INSERT INTO ' . $xoopsDB->prefix('xoopstube_altcat') . '(cid, lid) VALUES("' . $cid[1] . '","' . $lid . '")';
                if (!$result = $xoopsDB->query($sql)) {
                    XoopsErrorHandler_HandleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                    return false;
                }
            }
        }
        redirect_header('index.php', 1, _AM_XOOPSTUBE_ALTCAT_CREATED);
        break;

    case 'main':
    default:
        xoops_cp_header();
        //xtubeRenderAdminMenu(_AM_XOOPSTUBE_MALTCAT);
        echo '<fieldset><legend style="font-weight: bold; color: #0A3760;">' . _AM_XOOPSTUBE_ALTCAT_MODIFYF . '</legend>
          <div style="padding: 8px;">' . _AM_XOOPSTUBE_ALTCAT_INFOTEXT . '</div>
          </fieldset>';

        echo '<div style="text-align: left;"><h3> ' . $_REQUEST['title'] . ' </h3></div>';
        // Get an array of all alternate categories for this topic
        $sql     = $xoopsDB->query(
            'SELECT cid FROM ' . $xoopsDB->prefix('xoopstube_altcat') . ' WHERE lid="' . $lid . '" ORDER BY lid'
        );
        $altcats = array();
        while ($altcat = $xoopsDB->fetchArray($sql)) {
            $altcats[$altcat['cid']] = true;
        }
        $mytree = new XoopstubeTree($xoopsDB->prefix('xoopstube_cat'), 'cid', 'pid');
        makeTreeCheckTable($mytree, $lid, 'title', $altcats);
        xoops_cp_footer();
}
