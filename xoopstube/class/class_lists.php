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
class FileList
{

    public $filelist = array();

    public $value;
    public $selected;
    public $path = 'uploads';
    public $size;
    public $emptySelect;
    public $type;
    public $prefix;
    public $suffix;
    public $noSelection;

    /**
     * fileList::construct()
     *
     * @param string  $path
     * @param null    $value
     * @param string  $selected
     * @param integer $size
     *
     * @internal param int $emptySelect
     * @internal param int $type
     * @internal param string $prefix
     * @internal param string $suffix
     * @return \fileList
     */
    public function __construct($path = 'uploads', $value = null, $selected = '', $size = 1)
    {
        $this->value     = $value;
        $this->selection = $selected;
        $this->size      = intval($size);

        $pathToCheck = XOOPS_ROOT_PATH . "/{$path}";
        if (!is_dir($pathToCheck)) {
            if (false === @mkdir("$pathToCheck", 0777)) {
                XoopsErrorHandler_HandleError(
                    E_USER_WARNING,
                    $pathToCheck . _AM_XOOPSTUBE_DOESNOTEXIST,
                    __FILE__,
                    __LINE__
                );

                return false;
            }
        }
        $this->path = $path;

        return true;
    }

    /**
     * SpotList::setNoSelection()
     *
     * @param integer $value
     *
     * @return void
     */

    public function setEmptySelect($value = 0)
    {
        $this->emptySelect = (intval($value) != 1) ? 0 : 1;
    }

    /**
     * @param int $value
     */
    public function setNoSelection($value = 0)
    {
        $this->noSelection = (intval($value) != 1) ? 0 : 1;
    }

    /**
     * @param string $value
     */
    public function setPrefix($value = '')
    {
        $this->prefix = (strval($value) != '') ? strval($value) : '';
    }

    /**
     * @param string $value
     */
    public function setSuffix($value = '')
    {
        $this->suffix = (strval($value) != '') ? strval($value) : '';
    }

    /**
     * @param string $value
     */
    public function setListType($value = 'images')
    {
        $this->type = strval(strtolower($value));
    }

    /**
     * SpotList::showSelection()
     *
     * @return string
     */
    function &showSelection()
    {
        $ret = "<select size='" . $this->size() . "' name='$this->value()'>";
        if ($this->emptySelect) {
            $ret .= "<option value='" . $this->value() . "'>----------------------</option>";
        }
        foreach ($this->filelist as $content) {
            $optSelected = "";

            if ($content[0] == $this->selected()) {
                $optSelected = "selected='selected'";
            }
            $ret .= "<option value='" . $content . "' $optSelected>" . $content . "</option>";
        }
        $ret .= "</select>";

        return $ret;
    }

    /**
     * SpotList::getListTypeAsArray()
     *
     * @return array
     */
    function &getListTypeAsArray()
    {
        $filelist = array();
        switch (trim($this->type)) {
            case 'images':
                $types = '[.gif|.jpg|.png]';
                if ($this->noSelection) {
                    $this->filelist[0] = _AM_XOOPSTUBE_NOIMAGE;
                }
                break;
            case 'media':
                $types = '[.aac|.flv|.mp3|.mp4|.swf]';
                if ($this->noSelection) {
                    $this->filelist[0] = _AM_XOOPSTUBE_NOVIDEO;
                }
                break;

            case 'html':
                $types = '[.htm|.tpl|.html|.xhtml|.php|.php3|.phtml|.txt]';
                if ($this->noSelection) {
                    $this->filelist[0] = _AM_XOOPSTUBE_NOSELECT;
                }
                break;

            default:
                $types = '';
                if ($this->noSelection) {
                    $this->filelist[0] = _AM_XOOPSTUBE_NOFILESELECT;
                }
                break;
        }

        if (substr($this->path, -1) == '/') {
            $this->path = substr($this->path, 0, -1);
        }

        $_full_path = XOOPS_ROOT_PATH . "/{$this->path}";

        if (is_dir($_full_path) && $handle = opendir($_full_path)) {
            while (false !== ($file = readdir($handle))) {
                if (!preg_match("/^[.]{1,2}$/", $file) && preg_match("/$types$/i", $file)
                    && is_file(
                        $_full_path . '/' . $file
                    )
                ) {
                    if (strtolower($file) == 'blank.gif') {
                        continue;
                    }
                    $file                  = $this->prefix . $file;
                    $this->filelist[$file] = $file;
                }
            }
            closedir($handle);
            asort($this->filelist);
            reset($this->filelist);
        }

        return $this->filelist;
    }

    /**
     * @return null
     */
    public function value()
    {
        return $this->value;
    }

    public function selected()
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

    public function emptySelect()
    {
        return $this->emptySelect;
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
