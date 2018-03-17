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

if (!defined('_PATH')) {
    define('_PATH', XOOPS_ROOT_PATH);
}

if (!defined('DEFAULT_PATH')) {
    define('DEFAULT_PATH', XOOPS_UPLOAD_URL . '/blank.gif');
}

/**
 * Class Thumbnails
 */
class Thumbnails
{
    public $_imgName      = 'blank.gif';
    public $_img_path     = 'uploads';
    public $_img_savepath = 'thumbs';

    public $_source_path  = '';
    public $_save_path    = '';
    public $_source_url   = '';
    public $_source_image = '';
    public $_save_image   = '';

    public $_usethumbs       = 0;
    public $_image_type      = 'gd2';
    public $_return_fullpath = 0;

    public $img_width   = 100;
    public $img_height  = 100;
    public $img_quality = 100;
    public $img_update  = 1;
    public $img_aspect  = 1;

    // @access private
    public $_img_info = [];

    /**
     * Constructor
     *
     * @param null $img_name
     * @param null $img_path
     * @param null $img_savepath
     *
     * @internal param string $_imgName
     * @internal param string $_img_path
     * @internal param string $_img_savepath
     */
    public function __construct($img_name = null, $img_path = null, $img_savepath = null)
    {
        if (!preg_match("/\.(jpg|gif|png|jpeg)$/i", $img_name)) {
            return false;
        }

        // The actual image we will be processing
        if (!null === $img_name) {
            $this->_imgName = (string)trim($img_name);
        }

        // The image path
        if (!null === $img_path) {
            $this->_img_path = (string)trim($img_path);
        }

        // The image save path
        if (!null === $img_savepath) {
            $this->_img_savepath = (string)trim($img_savepath);
        }

        $path_to_check = XOOPS_ROOT_PATH . "/$img_path/$img_savepath";

        if (!is_dir($path_to_check)) {
            if (false === mkdir((string)$path_to_check, 0777)) {
                return false;
            }
        }

        return null;
    }

    /**
     * wfThumbsNails::setUseThumbs()
     *
     * @param integer $value
     *
     * @return void
     */
    public function setUseThumbs($value = 1)
    {
        $this->_usethumbs = $value;
    }

    /**
     * XtubeThumbsNails::setImageType()
     *
     * @param string $value
     *
     * @return void
     */
    public function setImageType($value = 'gd2')
    {
        $this->_image_type = $value;
    }

    /**
     * ThumbsNails::createThumbnail()
     *
     * @param int $img_width
     * @param int $img_height
     * @param int $img_quality
     * @param int $img_update
     * @param int $img_aspect
     *
     * @return bool|string
     */
    public function createThumbnail(
        $img_width = null,
        $img_height = null,
        $img_quality = null,
        $img_update = null,
        $img_aspect = null
    ) {
        $this->_source_path  = XOOPS_ROOT_PATH . "/{$this->_img_path}";
        $this->_save_path    = XOOPS_ROOT_PATH . "/{$this->_img_path}/{$this->_img_savepath}";
        $this->_source_url   = XOOPS_URL . "/{$this->_img_path}";
        $this->_source_image = "{$this->_source_path}/{$this->_imgName}";

        if (isset($img_width) && !null === $img_width) {
            $this->img_width = (int)$img_width;
        }

        if (isset($img_height) && !null === $img_height) {
            $this->img_height = (int)$img_height;
        }

        if (isset($img_quality) && !null === $img_quality) {
            $this->img_quality = (int)$img_quality;
        }

        if (isset($img_update) && !null === $img_update) {
            $this->img_update = (int)$img_update;
        }

        if (isset($img_aspect) && !null === $img_aspect) {
            $this->img_aspect = (int)$img_aspect;
        }

        // Return false if we are not using thumb nails
        if (!$this->useThumbs()) {
            return $this->_source_url . '/' . $this->_imgName;
        }
        // Return false if the server does not have gd lib installed or activated
        if (!$this->checkGdLibrary()) {
            return $this->_source_url . '/' . $this->_imgName;
        }

        // Return false if the paths to the file are wrong
        if (!$this->checkPaths()) {
            return DEFAULT_PATH;
        }

        if (!$this->checkImage()) {
            return DEFAULT_PATH;
        }

        $image = $this->resizeThumbnail();

        return false === $image ? DEFAULT_PATH : $image;
    }

    /**
     * @param $value
     */
    public function setImageName($value)
    {
        $this->_imgName = (string)trim($value);
    }

    /**
     * @param $value
     */
    public function setImagePath($value)
    {
        $this->_img_path = (string)trim($value);
    }

    /**
     * @param $value
     */
    public function setImgSavePath($value)
    {
        $this->_img_savepath = (string)trim($value);
    }

    // ThumbsNails::resizeThumbnail()
    // @return
    /**
     * @return bool|string
     */
    public function resizeThumbnail()
    {
        // Get image size and scale ratio
        $scale = min($this->img_width / $this->_img_info[0], $this->img_height / $this->_img_info[1]);
        // If the image is larger than the max shrink it
        $newWidth  = $this->img_width;
        $newHeight = $this->img_height;
        if ($scale < 1 && 1 == $this->img_aspect) {
            $newWidth  = floor($scale * $this->_img_info[0]);
            $newHeight = floor($scale * $this->_img_info[1]);
        }
        $newWidth  = ($newWidth > $this->_img_info[0]) ? $this->_img_info[0] : $newWidth;
        $newHeight = ($newHeight > $this->_img_info[0]) ? $this->_img_info[0] : $newHeight;

        $savefile          = "{$newWidth}x{$newHeight}_{$this->_imgName}";
        $this->_save_image = "{$this->_save_path}/{$savefile}";

        if (0 == $this->img_update && file_exists($this->_save_image)) {
            return 1 == $this->_return_fullpath ? $this->_source_url . "/{$this->_img_savepath}/{$savefile}" : "{$this->_img_savepath}/{$savefile}";
        }

        switch ($this->_image_type) {
            case 'im':
                if (!empty($GLOBALS['xoopsModuleConfig']['path_magick']) && is_dir($GLOBALS['xoopsModuleConfig']['path_magick'])) {
                    if (preg_match("#[A-Z]:|\\\\#Ai", __FILE__)) {
                        $cur_dir     = __DIR__;
                        $src_file_im = '"' . $cur_dir . '\\' . str_replace('/', '\\', $this->_source_image) . '"';
                        $new_file_im = '"' . $cur_dir . '\\' . str_replace('/', '\\', $this->_save_image) . '"';
                    } else {
                        $src_file_im = escapeshellarg($this->_source_image);
                        $new_file_im = escapeshellarg($this->_save_image);
                    }
                    $magick_command = $GLOBALS['xoopsModuleConfig']['path_magick'] . '/convert -quality {$GLOBALS["xoopsModuleConfig"]["imagequality"]} -antialias -sample {$newWidth}x{$newHeight} {$src_file_im} +profile "*" ' . str_replace('\\', '/', $new_file_im) . '';
                    passthru($magick_command);

                    return $this->_source_url . "/{$this->_img_savepath}/{$savefile}";
                } else {
                    return false;
                }

                break;

            case 'gd1':
            case 'gd2':
            default:

                $imageCreateFunction = (function_exists('imagecreatetruecolor') && 'gd2' === $this->_image_type) ? 'imagecreatetruecolor' : 'imagecreate';
                $imageCopyfunction   = (function_exists('ImageCopyResampled') && 'gd2' === $this->_image_type) ? 'imagecopyresampled' : 'imagecopyresized';

                switch ($this->_img_info[2]) {
                    case 1:
                        // GIF image
                        $img     = function_exists('imagecreatefromgif') ? imagecreatefromgif($this->_source_image) : imagecreatefrompng($this->_source_image);
                        $tmp_img = $imageCreateFunction($newWidth, $newHeight);
                        $imageCopyfunction($tmp_img, $img, 0, 0, 0, 0, $newWidth, $newHeight, $this->_img_info[0], $this->_img_info[1]);
                        if (function_exists('imagegif')) {
                            imagegif($tmp_img, $this->_save_image);
                        } else {
                            imagepng($tmp_img, $this->_save_image);
                        }
                        imagedestroy($tmp_img);
                        break;

                    case 2:
                        // echo $this->_save_image;
                        $img     = function_exists('imagecreatefromjpeg') ? imagecreatefromjpeg($this->_source_image) : imagecreatefrompng($this->_source_image);
                        $tmp_img = $imageCreateFunction($newWidth, $newHeight);
                        $imageCopyfunction($tmp_img, $img, 0, 0, 0, 0, $newWidth, $newHeight, $this->_img_info[0], $this->_img_info[1]);
                        if (function_exists('imagejpeg')) {
                            imagejpeg($tmp_img, $this->_save_image, $this->img_quality);
                        } else {
                            imagepng($tmp_img, $this->_save_image);
                        }
                        imagedestroy($tmp_img);
                        break;

                    case 3:
                        // PNG image
                        $img     = imagecreatefrompng($this->_source_image);
                        $tmp_img = $imageCreateFunction($newWidth, $newHeight);
                        $imageCopyfunction($tmp_img, $img, 0, 0, 0, 0, $newWidth, $newHeight, $this->_img_info[0], $this->_img_info[1]);
                        imagepng($tmp_img, $this->_save_image);
                        imagedestroy($tmp_img);
                        break;
                    default:
                        return false;
                }
                if (1 == $this->_return_fullpath) {
                    return $this->_source_url . "/{$this->_img_savepath}/{$savefile}";
                } else {
                    return "{$this->_img_savepath}/{$savefile}";
                }
                break;
        }
        //        return FALSE;
    }

    // ThumbsNails::checkPaths()
    // @return
    /**
     * @return bool
     */
    public function checkPaths()
    {
        if (file_exists($this->_source_image) || is_readable($this->_source_image)) {
            return true;
        }
        if (is_dir($this->_save_image) || is_writable($this->_save_image)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function checkImage()
    {
        $this->_img_info = getimagesize($this->_source_image, $imageinfo);
        if (null === $this->_img_info) {
            return false;
        }

        return true;
    }

    // wfsThumbs::checkGdLibrary()
    // Private function
    // @return false if gd lib not found on the system
    /**
     * @return array|bool
     */
    public function checkGdLibrary()
    {
        if (!extension_loaded('gd')) {
            return false;
        }
        $gdlib = function_exists('gd_info');
        if (false === $gdlib = gd_info()) {
            return false;
        }

        return $gdlib;
    }

    // ThumbsNails::useThumbs()
    //
    // @return
    /**
     * @return bool
     */
    public function useThumbs()
    {
        if ($this->_usethumbs) {
            return true;
        }

        return false;
    }
}
