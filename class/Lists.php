<?php namespace XoopsModules\Xoopstube;

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

use XoopsModules\Xoopstube;
use XoopsModules\Xoopstube\Common;

/**
 * Class Lists
 * @package XoopsModules\Xoopstube
 */
class Lists
{
    public $value;
    public $selected;
    public $path = 'uploads';
    public $size;
    public $emptyselect;
    public $type;
    public $prefix;
    public $suffix;

    /**
     * @param string $path
     * @param null   $value
     * @param string $selected
     * @param int    $size
     * @param int    $emptyselect
     * @param int    $type
     * @param string $prefix
     * @param string $suffix
     */
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
        $this->size        = (int)$size;
        $this->emptyselect = $emptyselect ? 0 : 1;
        $this->type        = $type;
    }

    /**
     * @param array $this_array
     *
     * @return string
     */
    public function getarray($this_array)
    {
        $ret = "<select size='" . $this->size() . "' name='$this->value()'>";
        if ($this->emptyselect) {
            $ret .= "<option value='" . $this->value() . "'>----------------------</option>";
        }
        foreach ($this_array as $content) {
            $opt_selected = '';

            if ($content[0] == $this->isSelected()) {
                $opt_selected = "selected='selected'";
            }
            $ret .= "<option value='" . $content . "' $opt_selected>" . $content . '</option>';
        }
        $ret .= '</select>';

        return $ret;
    }

    /**
     * Private to be called by other parts of the class
     *
     * @param $dirname
     *
     * @return array
     */
    public function getDirListAsArray($dirname)
    {
        $dirlist = [];
        if (is_dir($dirname) && $handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if (!preg_match('/^[.]{1,2}$/', $file)) {
                    if ('cvs' !== strtolower($file) && is_dir($dirname . $file)) {
                        $dirlist[$file] = $file;
                    }
                }
            }
            closedir($handle);

            reset($dirlist);
        }

        return $dirlist;
    }

    /**
     * @param        $dirname
     * @param string $type
     * @param string $prefix
     * @param int    $noselection
     *
     * @return array
     */
    public static function getListTypeAsArray($dirname, $type = '', $prefix = '', $noselection = 1)
    {
        $filelist = [];
        switch (trim($type)) {
            case 'images':
                $types = '[.gif|.jpg|.png]';
                if ($noselection) {
                    $filelist[''] = _AM_XOOPSTUBE_NOIMAGE;
                }
                break;
            case 'media':
                $types = '[.aac|.flv|.mp3|.mp4|.swf]';
                if ($noselection) {
                    $filelist[''] = _AM_XOOPSTUBE_NOVIDEO;
                }
                break;
            case 'html':
                $types = '[.htm|.tpl|.html|.xhtml|.php|.php3|.phtml|.txt]';
                if ($noselection) {
                    $filelist[''] = _AM_XOOPSTUBE_NOSELECT;
                }
                break;
            default:
                $types = '';
                if ($noselection) {
                    $filelist[''] = _AM_XOOPSTUBE_NOFILESELECT;
                }
                break;
        }

        if ('/' === substr($dirname, -1)) {
            $dirname = substr($dirname, 0, -1);
        }

        if (is_dir($dirname) && $handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if (!preg_match('/^[.]{1,2}$/', $file) && preg_match("/$types$/i", $file) && is_file($dirname . '/' . $file)) {
                    if ('blank.gif' === strtolower($file)) {
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

    /**
     * @return null
     */
    public function value()
    {
        return $this->value;
    }

    public function isSelected()
    {
        return $this->selected;
    }

    /**
     * @return string
     */
    public function paths()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function size()
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function isEmptySelect()
    {
        return $this->emptyselect;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function getSuffix()
    {
        return $this->suffix;
    }
}
