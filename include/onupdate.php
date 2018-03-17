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

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

$i = 0;
//Make changes to table xoopstube_videos
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . " CHANGE forumid vidsource INT(11) NOT NULL DEFAULT '0'");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' CHANGE url vidid TEXT NULL');
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . " CHANGE urlrating vidrating TINYINT(1) NOT NULL DEFAULT '0'");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' MODIFY description LONGTEXT NULL');
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' ADD COLUMN item_tag TEXT NULL AFTER keywords');
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' ADD COLUMN picurl TEXT NULL AFTER item_tag');
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
$ret[$i]   = true;
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' MODIFY keywords TEXT NULL');
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . " MODIFY vidsource INT(11) UNSIGNED NOT NULL DEFAULT '0'");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . " MODIFY time VARCHAR(7) NOT NULL DEFAULT '0:00:00'");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
//++$i;
//$ret[$i] = true;
//$query[$i] = sprintf("ALTER TABLE " . $GLOBALS['xoopsDB'] -> prefix( 'xoopstube_videos') . " ADD COLUMN hq TINYINT(1) NOT NULL DEFAULT '0' AFTER picurl");
//$ret[$i] = $ret[$i] && $GLOBALS['xoopsDB'] -> query( $query[$i] );

//Make changes to table xoopstube_cat
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . " ADD COLUMN client_id INT(5) NOT NULL DEFAULT '0' AFTER weight");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . " ADD COLUMN banner_id INT(5) NOT NULL DEFAULT '0' AFTER client_id");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);

//Make changes to table xoopstube_mod
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . " ADD COLUMN time VARCHAR(5) NOT NULL DEFAULT '00:00' AFTER vidrating");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . ' ADD COLUMN keywords TEXT NULL AFTER time');
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . ' ADD COLUMN item_tag TEXT NULL AFTER keywords');
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . ' ADD COLUMN picurl TEXT NULL AFTER item_tag');
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . ' MODIFY description LONGTEXT NULL');
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . " CHANGE forumid vidsource INT(11) NOT NULL DEFAULT '0'");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . ' CHANGE url vidid TEXT NULL');
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . " CHANGE urlrating vidrating TINYINT(1) NOT NULL DEFAULT '0'");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . ' MODIFY keywords TEXT NULL');
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . " MODIFY vidsource INT(11) UNSIGNED NOT NULL DEFAULT '0'");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod') . " MODIFY time VARCHAR(7) NOT NULL DEFAULT '0:00:00'");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
//++$i;
//$ret[$i] = true;
//$query[$i] = sprintf("ALTER TABLE " . $GLOBALS['xoopsDB'] -> prefix( 'xoopstube_mod') . " ADD COLUMN hq TINYINT(1) NOT NULL DEFAULT '0' AFTER picurl");
//$ret[$i] = $ret[$i] && $GLOBALS['xoopsDB'] -> query( $query[$i] );

//Make changes to table xoopstube_indexpage
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_indexpage') . " ADD COLUMN lastvideosyn TINYINT(1) NOT NULL DEFAULT '0' AFTER indexfooteralign");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_indexpage') . " ADD COLUMN lastvideostotal VARCHAR(5) NOT NULL DEFAULT '5' AFTER lastvideosyn");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);

// Make changes to xoopstube_broken
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . " MODIFY confirmed INT(1) UNSIGNED NOT NULL DEFAULT '0'");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);
++$i;
$ret[$i]   = true;
$query[$i] = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . " MODIFY acknowledged INT(1) NOT NULL DEFAULT '0'");
$ret[$i]   = $ret[$i] && $GLOBALS['xoopsDB']->query($query[$i]);

//TODO delete xoopstube.sql
