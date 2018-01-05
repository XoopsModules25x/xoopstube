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

mt_srand((double)microtime() * 1000000);

/**
 * Class MediaUploader
 */
class MediaUploader
{
    public $mediaName;
    public $mediaType;
    public $mediaSize;
    public $mediaTmpName;
    public $mediaError;
    public $uploadDir        = '';
    public $allowedMimeTypes = [];
    public $maxFileSize      = 0;
    public $maxWidth;
    public $maxHeight;
    public $targetFileName;
    public $prefix;
    public $ext;
    public $dimension;
    public $errors           = [];
    public $savedDestination;
    public $savedFileName;
    /**
     * No admin check for uploads
     */
    public $noAdminSizeCheck;

    /**
     * Constructor
     *
     * @param string    $uploadDir
     * @param array|int $allowedMimeTypes
     * @param int       $maxFileSize
     * @param int       $maxWidth
     * @param int       $maxHeight
     *
     * @internal param int $cmodvalue
     */
    public function __construct($uploadDir, $allowedMimeTypes = 0, $maxFileSize, $maxWidth = 0, $maxHeight = 0)
    {
        if (is_array($allowedMimeTypes)) {
            $this->allowedMimeTypes =& $allowedMimeTypes;
        }
        $this->uploadDir   = $uploadDir;
        $this->maxFileSize = (int)$maxFileSize;
        if (isset($maxWidth)) {
            $this->maxWidth = (int)$maxWidth;
        }
        if (isset($maxHeight)) {
            $this->maxHeight = (int)$maxHeight;
        }
    }

    /**
     * @param $value
     */
    public function noAdminSizeCheck($value)
    {
        $this->noAdminSizeCheck = $value;
    }

    /**
     * Fetch the uploaded file
     *
     * @param string  $media_name Name of the file field
     * @param int     $index      Index of the file (if more than one uploaded under that name)
     *
     * @global        $HTTP_POST_FILES
     * @return bool
     */
    public function fetchMedia($media_name, $index = null)
    {
        global $_FILES;

        if (!isset($_FILES[$media_name])) {
            $this->setErrors(_AM_XOOPSTUBE_READWRITEERROR);

            return false;
        } elseif (is_array($_FILES[$media_name]['name']) && isset($index)) {
            $index              = (int)$index;
            $this->mediaName    = $_FILES[$media_name]['name'][$index];
            $this->mediaType    = $_FILES[$media_name]['type'][$index];
            $this->mediaSize    = $_FILES[$media_name]['size'][$index];
            $this->mediaTmpName = $_FILES[$media_name]['tmp_name'][$index];
            $this->mediaError   = !empty($_FILES[$media_name]['error'][$index]) ? $_FILES[$media_name]['error'][$index] : 0;
        } else {
            $media_name         = @$_FILES[$media_name];
            $this->mediaName    = $media_name['name'];
            $this->mediaType    = $media_name['type'];
            $this->mediaSize    = $media_name['size'];
            $this->mediaTmpName = $media_name['tmp_name'];
            $this->mediaError   = !empty($media_name['error']) ? $media_name['error'] : 0;
        }
        $this->dimension = getimagesize($this->mediaTmpName);

        $this->errors = [];

        if ((int)$this->mediaSize < 0) {
            $this->setErrors(_AM_XOOPSTUBE_INVALIDFILESIZE);

            return false;
        }
        if ('' === $this->mediaName) {
            $this->setErrors(_AM_XOOPSTUBE_FILENAMEEMPTY);

            return false;
        }

        if ('none' === $this->mediaTmpName) {
            $this->setErrors(_AM_XOOPSTUBE_NOFILEUPLOAD);

            return false;
        }

        if (!is_uploaded_file($this->mediaTmpName)) {
            switch ($this->mediaError) {
                case 0: // no error; possible file attack!
                    $this->setErrors(_AM_XOOPSTUBE_UPLOADERRORZERO);
                    break;
                case 1: // uploaded file exceeds the upload_max_filesize directive in php.ini
                    $this->setErrors(_AM_XOOPSTUBE_UPLOADERRORONE);
                    break;
                case 2: // uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
                    $this->setErrors(_AM_XOOPSTUBE_UPLOADERRORTWO);
                    break;
                case 3: // uploaded file was only partially uploaded
                    $this->setErrors(_AM_XOOPSTUBE_UPLOADERRORTHREE);
                    break;
                case 4: // no file was uploaded
                    $this->setErrors(_AM_XOOPSTUBE_UPLOADERRORFOUR);
                    break;
                default: // a default error, just in case!  :)
                    $this->setErrors(_AM_XOOPSTUBE_UPLOADERRORFIVE);
                    break;
            }

            return false;
        }

        return true;
    }

    /**
     * Set the target filename
     *
     * @param string $value
     */
    public function setTargetFileName($value)
    {
        $this->targetFileName = (string)trim($value);
    }

    /**
     * Set the prefix
     *
     * @param string $value
     */
    public function setPrefix($value)
    {
        $this->prefix = (string)trim($value);
    }

    /**
     * Get the uploaded filename
     *
     * @return string
     */
    public function getMediaName()
    {
        return $this->mediaName;
    }

    /**
     * Get the type of the uploaded file
     *
     * @return string
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Get the size of the uploaded file
     *
     * @return int
     */
    public function getMediaSize()
    {
        return $this->mediaSize;
    }

    /**
     * Get the temporary name that the uploaded file was stored under
     *
     * @return string
     */
    public function getMediaTmpName()
    {
        return $this->mediaTmpName;
    }

    /**
     * Get the saved filename
     *
     * @return string
     */
    public function getSavedFileName()
    {
        return $this->savedFileName;
    }

    /**
     * Get the destination the file is saved to
     *
     * @return string
     */
    public function getSavedDestination()
    {
        return $this->savedDestination;
    }

    /**
     * Check the file and copy it to the destination
     *
     * @param int $chmod
     *
     * @return bool
     */
    public function upload($chmod = 0644)
    {
        if ('' === $this->uploadDir) {
            $this->setErrors(_AM_XOOPSTUBE_NOUPLOADDIR);

            return false;
        }

        if (!is_dir($this->uploadDir)) {
            $this->setErrors(_AM_XOOPSTUBE_FAILOPENDIR . $this->uploadDir);
        }

        if (!is_writable($this->uploadDir)) {
            $this->setErrors(_AM_XOOPSTUBE_FAILOPENDIRWRITEPERM . $this->uploadDir);
        }

        if (!$this->checkMaxFileSize()) {
            $this->setErrors(sprintf(_AM_XOOPSTUBE_FILESIZEMAXSIZE, $this->mediaSize, $this->maxFileSize));
        }

        if (is_array($this->dimension)) {
            if (!$this->checkMaxWidth($this->dimension[0])) {
                $this->setErrors(sprintf(_AM_XOOPSTUBE_FILESIZEMAXWIDTH, $this->dimension[0], $this->maxWidth));
            }
            if (!$this->checkMaxHeight($this->dimension[1])) {
                $this->setErrors(sprintf(_AM_XOOPSTUBE_FILESIZEMAXHEIGHT, $this->dimension[1], $this->maxHeight));
            }
        }

        if (!$this->checkMimeType()) {
            $this->setErrors(_AM_XOOPSTUBE_MIMENOTALLOW . $this->mediaType);
        }

        if (!$this->_copyFile($chmod)) {
            $this->setErrors(_AM_XOOPSTUBE_FAILEDUPLOADING . $this->mediaName);
        }

        if (count($this->errors) > 0) {
            return false;
        }

        return true;
    }

    /**
     * Copy the file to its destination
     *
     * @param $chmod
     *
     * @return bool
     */
    public function _copyFile($chmod)
    {
        $matched = [];
        if (!preg_match("/\.([a-zA-Z0-9]+)$/", $this->mediaName, $matched)) {
            return false;
        }
        if (isset($this->targetFileName)) {
            $this->savedFileName = $this->targetFileName;
        } elseif (isset($this->prefix)) {
            $this->savedFileName = uniqid($this->prefix, true) . '.' . strtolower($matched[1]);
        } else {
            $this->savedFileName = strtolower($this->mediaName);
        }
        $this->savedFileName    = preg_replace('!\s+!', '_', $this->savedFileName);
        $this->savedDestination = $this->uploadDir . $this->savedFileName;
        if (is_file($this->savedDestination) && !!is_dir($this->savedDestination)) {
            $this->setErrors(_AM_XOOPSTUBE_FILE . $this->mediaName . _AM_XOOPSTUBE_ALREADYEXISTTRYAGAIN);

            return false;
        }
        if (!move_uploaded_file($this->mediaTmpName, $this->savedDestination)) {
            return false;
        }
        @chmod($this->savedDestination, $chmod);

        return true;
    }

    /**
     * Is the file the right size?
     *
     * @return bool
     */
    public function checkMaxFileSize()
    {
        if ($this->noAdminSizeCheck) {
            return true;
        }
        if ($this->mediaSize > $this->maxFileSize) {
            return false;
        }

        return true;
    }

    /**
     * Is the picture the right width?
     *
     * @param $dimension
     *
     * @return bool
     */
    public function checkMaxWidth($dimension)
    {
        if (!isset($this->maxWidth)) {
            return true;
        }
        if ($dimension > $this->maxWidth) {
            return false;
        }

        return true;
    }

    /**
     * Is the picture the right height?
     *
     * @param $dimension
     *
     * @return bool
     */
    public function checkMaxHeight($dimension)
    {
        if (!isset($this->maxHeight)) {
            return true;
        }
        if ($dimension > $this->maxWidth) {
            return false;
        }

        return true;
    }

    /**
     * Is the file the right Mime type
     *
     * (is there a right type of mime? ;-)
     *
     * @return bool
     */
    public function checkMimeType()
    {
        if (count($this->allowedMimeTypes) > 0 && !in_array($this->mediaType, $this->allowedMimeTypes)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Add an error
     *
     * @param string $error
     */
    public function setErrors($error)
    {
        $this->errors[] = trim($error);
    }

    /**
     * Get generated errors
     *
     * @param bool $ashtml Format using HTML?
     *
     * @return array |string    Array of array messages OR HTML string
     */
    public function &getErrors($ashtml = true)
    {
        if (!$ashtml) {
            return $this->errors;
        } else {
            $ret = '';
            if (count($this->errors) > 0) {
                $ret = _AM_XOOPSTUBE_ERRORSRETURNUPLOAD;
                foreach ($this->errors as $error) {
                    $ret .= $error . '<br>';
                }
            }

            return $ret;
        }
    }
}
