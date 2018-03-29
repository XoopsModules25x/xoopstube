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

//use XoopsModules\Xoopstube;
//use XoopsModules\Xoopstube\Common;

/**
 * Class TextSanitizer
 * @package XoopsModules\Xoopstube
 */
class TextSanitizer extends \MyTextSanitizer
{
    /**
     * @param $text
     *
     * @return string
     */
    public function htmlSpecialCharsStrip($text)
    {
        return $this->htmlSpecialChars($this->stripSlashesGPC($text));
    }
}
