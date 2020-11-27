<?php

namespace XoopsModules\Xoopstube;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * XoopsTube module
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xoopstube
 * @since           1.06
 * @author          Xoops Development Team
 */
// CONFIG displayicons

/**
 * interface Constants
 */
interface Constants
{
    /**#@+
     * Constant definition
     */

    public const XOOPSTUBE_DISPLAYICONS_ICON = 1;
    public const XOOPSTUBE_DISPLAYICONS_TEXT = 2;
    public const XOOPSTUBE_DISPLAYICONS_NO = 3;
    // CONFIG submissions
    public const XOOPSTUBE_SUBMISSIONS_NONE = 1;
    public const XOOPSTUBE_SUBMISSIONS_DOWNLOAD = 2;
    public const XOOPSTUBE_SUBMISSIONS_MIRROR = 3;
    public const XOOPSTUBE_SUBMISSIONS_BOTH = 4;
    // CONFIG anonpost
    public const XOOPSTUBE_ANONPOST_NONE = 1;
    public const XOOPSTUBE_ANONPOST_DOWNLOAD = 2;
    public const XOOPSTUBE_ANONPOST_MIRROR = 3;
    public const XOOPSTUBE_ANONPOST_BOTH = 4;
    // CONFIG autoapprove
    public const XOOPSTUBE_AUTOAPPROVE_NONE = 1;
    public const XOOPSTUBE_AUTOAPPROVE_DOWNLOAD = 2;
    public const XOOPSTUBE_AUTOAPPROVE_MIRROR = 3;
    public const XOOPSTUBE_AUTOAPPROVE_BOTH = 4;
    // CONFIG autosummary
    public const XOOPSTUBE_AUTOSUMMARY_NO = 1;
    public const XOOPSTUBE_AUTOSUMMARY_IFBLANK = 2;
    public const XOOPSTUBE_AUTOSUMMARY_YES = 3;
    /**#@-*/
}
