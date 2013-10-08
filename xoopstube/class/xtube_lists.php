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

class XtubeLists
{
    public $value;
    public $selected;
    public $path = 'uploads';
    public $size;
    public $emptyselect;
    public $type;
    public $prefix;
    public $suffix;

    public function __construct(
        $path = 'uploads',
        $value = null,
        $selected = '',
        $size = 1,
        $emptyselect = 0,
        $type = 0,
        $prefix = '',
        $suffix = ''
    ) {
        $this->value       = $value;
        $this->selection   = $selected;
        $this->path        = $path;
        $this->size        = intval($size);
        $this->emptyselect = ($emptyselect) ? 0 : 1;
        $this->type        = $type;
    }

    function &getarray($this_array)
    {
        $ret = "<select size='" . $this->size() . "' name='$this->value()'>";
        if ($this->emptyselect) {
            $ret .= "<option value='" . $this->value() . "'>----------------------</option>";
        }
        foreach ($this_array as $content) {
            $opt_selected = "";

            if ($content[0] == $this->selected()) {
                $opt_selected = "selected='selected'";
            }
            $ret .= "<option value='" . $content . "' $opt_selected>" . $content . "</option>";
        }
        $ret .= "</select>";

        return $ret;
    }

    /**
     * Private to be called by other parts of the class
     */
    function &getDirListAsArray($dirname)
    {
        $dirlist = array();
        if (is_dir($dirname) && $handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if (!preg_match("/^[.]{1,2}$/", $file)) {
                    if (strtolower($file) != 'cvs' && is_dir($dirname . $file)) {
                        $dirlist[$file] = $file;
                    }
                }
            }
            closedir($handle);

            reset($dirlist);
        }

        return $dirlist;
    }

    static function &getListTypeAsArray($dirname, $type = '', $prefix = '', $noselection = 1)
    {
        $filelist = array();
        switch (trim($type)) {
            case 'images':
                $types = '[.gif|.jpg|.png]';
                if ($noselection) {
                    $filelist[''] = _AM_XTUBE_NOIMAGE;
                }
                break;
            case 'media':
                $types = '[.aac|.flv|.mp3|.mp4|.swf]';
                if ($noselection) {
                    $filelist[''] = _AM_XTUBE_NOVIDEO;
                }
                break;
            case 'html':
                $types = '[.htm|.html|.xhtml|.php|.php3|.phtml|.txt]';
                if ($noselection) {
                    $filelist[''] = _AM_XTUBE_NOSELECT;
                }
                break;
            default:
                $types = '';
                if ($noselection) {
                    $filelist[''] = _AM_XTUBE_NOFILESELECT;
                }
                break;
        }

        if (substr($dirname, -1) == '/') {
            $dirname = substr($dirname, 0, -1);
        }

        if (is_dir($dirname) && $handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if (!preg_match("/^[.]{1,2}$/", $file) && preg_match("/$types$/i", $file)
                    && is_file(
                        $dirname . '/' . $file
                    )
                ) {
                    if (strtolower($file) == 'blank.gif') {
                        continue;
                    }
                    $file            = $prefix . $file;
                    $filelist[$file] = $file;
                }
            }
            closedir($handle);
            asort($filelist);
            reset($filelist);
        }

        return $filelist;
    }

    public function value()
    {
        return $this->value;
    }

    public function selected()
    {
        return $this->selected;
    }

    public function paths()
    {
        return $this->path;
    }

    public function size()
    {
        return $this->size;
    }

    public function emptyselect()
    {
        return $this->emptyselect;
    }

    public function type()
    {
        return $this->type;
    }

    public function prefix()
    {
        return $this->prefix;
    }

    public function suffix()
    {
        return $this->suffix;
    }
}
