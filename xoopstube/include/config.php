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

// WARNING: ONCE SET DO NOT CHANGE! Improper use will render this module useless and unworkable.
// Only Change if you know what you are doing.

$mydirname = basename(dirname(__DIR__));

//These are names of the current tables

if (!defined('xoopstube_broken')) {
    define('xoopstube_broken', 'xoopstube_broken');
}
if (!defined('xoopstube_cat')) {
    define('xoopstube_cat', 'xoopstube_cat');
}
if (!defined('xoopstube_videos')) {
    define('xoopstube_videos', 'xoopstube_videos');
}
if (!defined('xoopstube_mod')) {
    define('xoopstube_mod', 'xoopstube_mod');
}
if (!defined('xoopstube_votedata')) {
    define('xoopstube_votedata', 'xoopstube_votedata');
}
if (!defined('xoopstube_indexpage')) {
    define('xoopstube_indexpage', 'xoopstube_indexpage');
}
if (!defined('xoopstube_altcat')) {
    define('xoopstube_altcat', 'xoopstube_altcat');
}
if (!defined('xoopstube_url')) {
    define('xoopstube_url', XOOPS_URL . '/modules/' . $mydirname . '/');
}
