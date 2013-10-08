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


// Module Info
// The name of this module
define('_MI_XTUBE_NAME', "XoopsTube");

// A brief description of this module
define('_MI_XTUBE_DESC', "With the module <em>XoopsTube</em> you can add and categorize movies from sites like <br />YouTube, DailyMotion, LiveLeak, etc. to your website.");

// Names of blocks for this module (Not all module has blocks)
define('_MI_XTUBE_BNAME1', "Recent videos (t)");
define('_MI_XTUBE_BNAME2', "Recent videos");
define('_MI_XTUBE_BNAME3', "Top videos (t)");
define('_MI_XTUBE_BNAME4', "Top videos");
define('_MI_XTUBE_BNAME5', "Recent videos (h)");
define('_MI_XTUBE_BNAME6', "Random videos");
define('_MI_XTUBE_BNAME7', "Random videos (h)");

// Sub menu titles
define('_MI_XTUBE_SMNAME1', "Submit");
define('_MI_XTUBE_SMNAME2', "Popular");
define('_MI_XTUBE_SMNAME3', "Top Rated");
define('_MI_XTUBE_SMNAME4', "Latest Listings");

// Names of admin menu items
define('_MI_XTUBE_BINDEX', "Admin");
define('_MI_XTUBE_INDEXPAGE', "Index Page");
define('_MI_XTUBE_MCATEGORY', "Categories ");
define('_MI_XTUBE_MVIDEOS', "Local Videos");
define('_MI_XTUBE_MUPLOADS', "Image Upload");
define('_MI_XTUBE_PERMISSIONS', "Permissions");
define('_MI_XTUBE_BLOCKADMIN', "Block Settings");
define('_MI_XTUBE_MVOTEDATA', "Votes");

// Title of config items
define('_MI_XTUBE_POPULAR', "video Popular Count");
define('_MI_XTUBE_POPULARDSC', "The number of hits before a video status will be considered as popular.");

define('_MI_XTUBE_ICONDISPLAY', "videos Popular and New:");
define('_MI_XTUBE_DISPLAYICONDSC', "Select how to display the popular and new icons in video listing.");
define('_MI_XTUBE_DISPLAYICON1', "Display As Icons");
define('_MI_XTUBE_DISPLAYICON2', "Display As Text");
define('_MI_XTUBE_DISPLAYICON3', "Do Not Display");

define('_MI_XTUBE_DAYSNEW', "videos Days New:");
define('_MI_XTUBE_DAYSNEWDSC', "The number of days a video status will be considered as new.");

define('_MI_XTUBE_DAYSUPDATED', "videos Days Updated:");
define('_MI_XTUBE_DAYSUPDATEDDSC', "The amount of days a video status will be considered as updated.");

define('_MI_XTUBE_PERPAGE', "video Listing Count:");
define('_MI_XTUBE_PERPAGEDSC', "Number of videos to display in each category listing.");

define('_MI_XTUBE_ADMINPAGE', "Admin Index Videos Count:");
define('_MI_XTUBE_AMDMINPAGEDSC', "Number of new Videos to display in module admin area.");

define('_MI_XTUBE_ARTICLESSORT', "Default video Order:");
define('_MI_XTUBE_ARTICLESSORTDSC', "Select the default order for the video listings.");

define('_MI_XTUBE_SORTCATS', "Sort categories by:");
define('_MI_XTUBE_SORTCATSDSC', "Select how categories and sub-categories are sorted.");

define('_MI_XTUBE_SUBCATS', "Sub-Categories:");
define('_MI_XTUBE_SUBCATSDSC', "Select Yes to display sub-categories. Selecting No will hide sub-categories from the listings");

define('_MI_XTUBE_EDITOR', "Editor to use (admin):");
define('_MI_XTUBE_EDITORCHOICE', "Select the editor to use for admin side. If you have a 'simple' install (e.g you use only XOOPS core editor class, provided in the standard xoops core package), then you can just select DHTML and Compact");
define('_MI_XTUBE_EDITORUSER', "Editor to use (user):");
define('_MI_XTUBE_EDITORCHOICEUSER', "Select the editor to use for user side. If you have a 'simple' install (e.g you use only XOOPS core editor class, provided in the standard xoops core package), then you can just select DHTML and Compact");
define('_MI_XTUBE_FORM_DHTML', "DHTML");
define('_MI_XTUBE_FORM_COMPACT', "Compact");
define('_MI_XTUBE_FORM_SPAW', "Spaw Editor");
define('_MI_XTUBE_FORM_HTMLAREA', "HtmlArea Editor");
define('_MI_XTUBE_FORM_FCK', "FCK Editor");
define('_MI_XTUBE_FORM_KOIVI', "Koivi Editor");
define('_MI_XTUBE_FORM_TINYEDITOR', "TinyEditor");

define('_MI_XTUBE_USESHOTS', "Display Screenshot Images?");
define('_MI_XTUBE_USESHOTSDSC', "Select yes to display screenshot images for each video item");

define('_MI_XTUBE_USETHUMBS', "Use Thumbnails:");
define('_MI_XTUBE_USETHUMBSDSC', "Supported link types: JPG, GIF, PNG.<div style='padding-top: 8px;'>WF-Links will use thumb nails for images. Set to 'No' to use orginal image if the server does not support this option.</div>");

define('_MI_XTUBE_IMGUPDATE', "Update Thumbnails?");
define('_MI_XTUBE_IMGUPDATEDSC', "If selected Thumbnail images will be updated at each page render, otherwise the first thumbnail image will be used regardless. <br /><br />");

define('_MI_XTUBE_SHOTWIDTH', "Screenshot Display Width");
define('_MI_XTUBE_SHOTWIDTHDSC', "Display width for screenshot image");

define('_MI_XTUBE_SHOTHEIGHT', "Screenshot Display Height");
define('_MI_XTUBE_SHOTHEIGHTDSC', "Display height for screenshot image");

define('_MI_XTUBE_MAINIMGDIR', "Main Image Directory");

define('_MI_XTUBE_CATEGORYIMG', "Category Image Upload Directory");

define('_MI_XTUBE_DATEFORMAT', "Timestamp:");
define('_MI_XTUBE_DATEFORMATDSC', "Default Timestamp for XoopsTube");

define('_MI_XTUBE_TOTALCHARS', "Set total amount of characters for description?");
define('_MI_XTUBE_TOTALCHARSDSC', "Set total amount of characters for description on Index Page.");

define('_MI_XTUBE_OTHERVIDEOS', "Show other videos submitted by Submitter?");
define('_MI_XTUBE_OTHERVIDEOSDSC', "Select Yes if other videos of the submitter should be displayed.");

define('_MI_XTUBE_SHOWSUBMITTER', "Show Submitter?");
define('_MI_XTUBE_SHOWSUBMITTERDSC', "Select Yes if you would like to show the Submitter of each video.");

define('_MI_XTUBE_SHOWSBOOKMARKS', "Show Social Bookmarks?");
define('_MI_XTUBE_SHOWSBOOKMARKSDSC', "Select Yes if you want Social Bookmark icons to be displayed under video.");

define('_MI_XTUBE_USEMETADESCR', "Generate meta description:");
define('_MI_XTUBE_USEMETADSC', "With this option the meta description will be based on the video description.");

define('_MI_XTUBE_USERTAGDESCR', "User can submit Tags:");
define('_MI_XTUBE_USERTAGDSC', "Select Yes if user is allowed to submit tags.");

define('_MI_XTUBE_SHOWDISCLAIMER', "Show Disclaimer before User Submission?");
define('_MI_XTUBE_SHOWDISCLAIMERDSC', "Before a User can submit a Video show the Entry regulations?");

define('_MI_XTUBE_DISCLAIMER', "Enter Submission Disclaimer Text:");

define('_MI_XTUBE_SHOWVIDEODISCL', "Show Disclaimer before User video?");
define('_MI_XTUBE_SHOWVIDEODISCLDSC', "Show video regulations before open a video?");

define('_MI_XTUBE_VIDEODISCLAIMER', "Enter video Disclaimer Text:");

define('_MI_XTUBE_COPYRIGHT', "Copyright Notice:");
define('_MI_XTUBE_COPYRIGHTDSC', "Select to display a copyright notice on video page.");

define('_MI_XTUBE_CHECKHOST', "Disallow direct video linking? (leeching)");
define('_MI_XTUBE_REFERERS', "These sites can directly video to your videos <br />Separate with #");
define('_MI_XTUBE_ANONPOST', "Anonymous User Submission:");
define('_MI_XTUBE_ANONPOSTDSC', "Allow Anonymous users to submit or upload to your website?");
define('_MI_XTUBE_AUTOAPPROVE', "Auto Approve Submitted videos");
define('_MI_XTUBE_AUTOAPPROVEDSC', "Select to approve submitted videos without moderation.");

define('_MI_XTUBE_MAXFILESIZE', "Upload Size (KB)");
define('_MI_XTUBE_MAXFILESIZEDSC', "Maximum video size permitted with video uploads.");
define('_MI_XTUBE_IMGWIDTH', "Upload Image width");
define('_MI_XTUBE_IMGWIDTHDSC', "Maximum image width permitted when uploading image videos");
define('_MI_XTUBE_IMGHEIGHT', "Upload Image height");
define('_MI_XTUBE_IMGHEIGHTDSC', "Maximum image height permitted when uploading image videos");

define('_MI_XTUBE_UPLOADDIR', "Upload Directory (No trailing slash)");
define('_MI_XTUBE_ALLOWSUBMISS', "User Submissions:");
define('_MI_XTUBE_ALLOWSUBMISSDSC', "Allow Users to Submit new videos");
define('_MI_XTUBE_ALLOWUPLOADS', "User Uploads:");
define('_MI_XTUBE_ALLOWUPLOADSDSC', "Allow Users to upload videos directly to your website");
define('_MI_XTUBE_SCREENSHOTS', "Screenshots Upload Directory");

define('_MI_XTUBE_SUBMITART', "video Submission:");
define('_MI_XTUBE_SUBMITARTDSC', "Select groups that can submit new videos.");
define('_MI_XTUBE_RATINGGROUPS', "video Ratings:");
define('_MI_XTUBE_RATINGGROUPSDSC', "Select groups that can rate a video.");

define('_MI_XTUBE_QUALITY', "Thumb Nail Quality:");
define('_MI_XTUBE_QUALITYDSC', "Quality Lowest: 0 Highest: 100");
define('_MI_XTUBE_KEEPASPECT', "Keep Image Aspect Ratio?");
define('_MI_XTUBE_KEEPASPECTDSC', "");

define('_MI_XTUBE_TITLE', "Title");
define('_MI_XTUBE_RATING', "Rating");
define('_MI_XTUBE_WEIGHT', "Weight");
define('_MI_XTUBE_POPULARITY', "Popularity");
define('_MI_XTUBE_SUBMITTED2', "Submission Date");

// Text for notifications
define('_MI_XTUBE_GLOBAL_NOTIFY', "Global");
define('_MI_XTUBE_GLOBAL_NOTIFYDSC', "Global videos notification options.");
define('_MI_XTUBE_CATEGORY_NOTIFY', "Category");
define('_MI_XTUBE_CATEGORY_NOTIFYDSC', "Notification options that apply to the current video category.");
define('_MI_XTUBE_VIDEO_NOTIFY', "Video");
define('_MI_XTUBE_FILE_NOTIFYDSC', "Notification options that apply to the current video.");
define('_MI_XTUBE_GLOBAL_NEWCATEGORY_NOTIFY', "New Category");
define('_MI_XTUBE_GLOBAL_NEWCATEGORY_NOTIFYCAP', "Notify me when a new video category is created.");
define('_MI_XTUBE_GLOBAL_NEWCATEGORY_NOTIFYDSC', "Receive notification when a new video category is created.");
define('_MI_XTUBE_GLOBAL_NEWCATEGORY_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify : New video category");

define('_MI_XTUBE_GLOBAL_VIDEOMODIFY_NOTIFY', "Modify Video Requested");
define('_MI_XTUBE_GLOBAL_VIDEOMODIFY_NOTIFYCAP', "Notify me of any video modification request.");
define('_MI_XTUBE_GLOBAL_VIDEOMODIFY_NOTIFYDSC', "Receive notification when any video modification request is submitted.");
define('_MI_XTUBE_GLOBAL_VIDEOMODIFY_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify : Video Modification Requested");

define('_MI_XTUBE_GLOBAL_VIDEOBROKEN_NOTIFY', "Broken Video Submitted");
define('_MI_XTUBE_GLOBAL_VIDEOBROKEN_NOTIFYCAP', "Notify me of any broken video report.");
define('_MI_XTUBE_GLOBAL_VIDEOBROKEN_NOTIFYDSC', "Receive notification when any broken video report is submitted.");
define('_MI_XTUBE_GLOBAL_VIDEOBROKEN_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify : Broken Video Reported");

define('_MI_XTUBE_GLOBAL_VIDEOSUBMIT_NOTIFY', "Video Submitted");
define('_MI_XTUBE_GLOBAL_VIDEOSUBMIT_NOTIFYCAP', "Notify me when any new video is submitted (awaiting approval).");
define('_MI_XTUBE_GLOBAL_VIDEOSUBMIT_NOTIFYDSC', "Receive notification when any new video is submitted (awaiting approval).");
define('_MI_XTUBE_GLOBAL_VIDEOSUBMIT_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify : New video submitted");

define('_MI_XTUBE_GLOBAL_NEWVIDEO_NOTIFY', "New Video");
define('_MI_XTUBE_GLOBAL_NEWVIDEO_NOTIFYCAP', "Notify me when any new video is posted.");
define('_MI_XTUBE_GLOBAL_NEWVIDEO_NOTIFYDSC', "Receive notification when any new video is posted.");
define('_MI_XTUBE_GLOBAL_NEWVIDEO_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify : New video");

define('_MI_XTUBE_CATEGORY_FILESUBMIT_NOTIFY', "Video Submitted");
define('_MI_XTUBE_CATEGORY_FILESUBMIT_NOTIFYCAP', "Notify me when a new video is submitted (awaiting approval) to the current category.");
define('_MI_XTUBE_CATEGORY_FILESUBMIT_NOTIFYDSC', "Receive notification when a new video is submitted (awaiting approval) to the current category.");
define('_MI_XTUBE_CATEGORY_FILESUBMIT_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify : New video submitted in category");

define('_MI_XTUBE_CATEGORY_NEWVIDEO_NOTIFY', "New Video");
define('_MI_XTUBE_CATEGORY_NEWVIDEO_NOTIFYCAP', "Notify me when a new video is posted to the current category.");
define('_MI_XTUBE_CATEGORY_NEWVIDEO_NOTIFYDSC', "Receive notification when a new video is posted to the current category.");
define('_MI_XTUBE_CATEGORY_NEWVIDEO_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify : New video in category");

define('_MI_XTUBE_VIDEO_APPROVE_NOTIFY', "Video Approved");
define('_MI_XTUBE_VIDEO_APPROVE_NOTIFYCAP', "Notify me when this Video is approved.");
define('_MI_XTUBE_VIDEO_APPROVE_NOTIFYDSC', "Receive notification when this video is approved.");
define('_MI_XTUBE_VIDEO_APPROVE_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify : Video Approved");

define('_MI_XTUBE_AUTHOR_INFO', "Developer Information");
define('_MI_XTUBE_AUTHOR_NAME', "Developer");
define('_MI_XTUBE_AUTHOR_DEVTEAM', "Development Team");
define('_MI_XTUBE_AUTHOR_WEBSITE', "Developer website");
define('_MI_XTUBE_AUTHOR_EMAIL', "Developer email");
define('_MI_XTUBE_AUTHOR_CREDITS', "Credits");
define('_MI_XTUBE_MODULE_INFO', "Module Development Information");
define('_MI_XTUBE_MODULE_STATUS', "Development Status");
define('_MI_XTUBE_MODULE_DEMO', "Demo Site");
define('_MI_XTUBE_MODULE_SUPPORT', "Official support site");
define('_MI_XTUBE_MODULE_BUG', "Report a bug for this module");
define('_MI_XTUBE_MODULE_FEATURE', "Suggest a new feature for this module");
define('_MI_XTUBE_MODULE_DISCLAIMER', "Disclaimer");
define('_MI_XTUBE_RELEASE', "Release Date");

define('_MI_XTUBE_MODULE_MAILLIST', "WF-Project Mailing Lists");
define('_MI_XTUBE_MODULE_MAILANNOUNCEMENTS', "Announcements Mailing List");
define('_MI_XTUBE_MODULE_MAILBUGS', "Bug Mailing List");
define('_MI_XTUBE_MODULE_MAILFEATURES', "Features Mailing List");
define('_MI_XTUBE_MODULE_MAILANNOUNCEMENTSDSC', "Get the latest announcements from XOOPS.");
define('_MI_XTUBE_MODULE_MAILBUGSDSC', "Bug Tracking and submission mailing list");
define('_MI_XTUBE_MODULE_MAILFEATURESDSC', "Request New Features mailing list.");

define('_MI_XTUBE_WARNINGTEXT', "THE SOFTWARE IS PROVIDED BY XOOPS \"AS IS\" AND \"WITH ALL FAULTS.\"
XOOPS MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY KIND CONCERNING
THE QUALITY, SAFETY OR SUITABILITY OF THE SOFTWARE, EITHER EXPRESS OR
IMPLIED, INCLUDING WITHOUT LIMITATION ANY IMPLIED WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, OR NON-INFRINGEMENT.
FURTHER, XOOPS MAKES NO REPRESENTATIONS OR WARRANTIES AS TO THE TRUTH,
ACCURACY OR COMPLETENESS OF ANY STATEMENTS, INFORMATION OR MATERIALS
CONCERNING THE SOFTWARE THAT IS CONTAINED IN XOOPS WEBSITE. IN NO
EVENT WILL XOOPS BE LIABLE FOR ANY INDIRECT, PUNITIVE, SPECIAL,
INCIDENTAL OR CONSEQUENTIAL DAMAGES HOWEVER THEY MAY ARISE AND EVEN IF
XOOPS HAS BEEN PREVIOUSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGES..");

define('_MI_XTUBE_AUTHOR_CREDITSTEXT', "WF-Projects Team");
define('_MI_XTUBE_AUTHOR_BUGFIXES', "Bug Fix History");

// version 1.02
define('_MI_XTUBE_FORM_TINYMCE', "TinyMCE");
define('_MI_XTUBE_FORM_DHTMLEXT', "DHTML Extended");

// Version 1.03
define('_MI_XTUBE_DATEFORMATADMIN', "Timestamp administration:");
define('_MI_XTUBE_DATEFORMATADMINDSC', "Default admininstration Timestamp for XoopsTube");

// Version 1.04 RC-1
define('_MI_XTUBE_VIDEODIR', "Video upload directory:");
define('_MI_XTUBE_VIDEODIRDSC', "Set the directory for XoopsTube videos files");
define('_MI_XTUBE_VIDEOIMGDIR', "Video screenshots upload directory:");
define('_MI_XTUBE_VIDEOIMGDIRDSC', "Set the directory for XoopsTube video screenshots");
define('_MI_XTUBE_FLVPLAYER_CREDITS', "FLV Player");
define('_MI_XTUBE_FLVPLAYER_CREDITDSC', "JW FLV Media Player by LongTail Video (Jeroen Wijering)");
define('_MI_XTUBE_VUPLOADS', "Video upload");
define('_MI_XTUBE_CATEGORYIMGDSC', "");
define('_MI_XTUBE_MAINIMGDIRDSC', "");
define('_MI_XTUBE_BNAME8', "XoopsTube Sponsor Statistics");
define('_MI_XTUBE_ICONS_CREDITS', "Icons");

// Version 1.04 RC-2
define('_MI_XTUBE_MODULE_MANUAL', "Manual");
define('_MI_XTUBE_MODULE_MANUALWIKI', "Wiki Manual");
define('_MI_XTUBE_MODULE_REQUESTNEWFEATURE', "Request a new feature");
define('_MI_XTUBE_MODULE_SUBMITBUG', "Submit a Bug");
define('_MI_XTUBE_BNAME9', "XoopsTube Tag Cloud");
define('_MI_XTUBE_BNAME10', "XoopsTube Top Tags");
define('_MI_XTUBE_FLVPLAYER_MANUAL', "FLV Player Manual");
define('_MI_XTUBE_FLVPLAYER_WIKI', "JW Player Wiki");

// Version 1.05 RC-1
define('_MI_XTUBE_CATCOLUMNS', "Select amount of category columns:");
define('_MI_XTUBE_CATCOLUMNSDSC', "Select the amount of columns, default is 2");
define('_MI_XTUBE_RATINGDISPLAY', "Display rating features?");
define('_MI_XTUBE_RATINGDISPLAYDSC', "Select <em>Yes</em> to display the Rating features. Select <em>No</em> if you don't want to display the Rating features.");
define('_MI_XTUBE_AUTOPLAY', "Autoplay the video");
define('_MI_XTUBE_AUTOPLAYDSC', "Select <em>Yes</em> to have the videoclips play automatically.");
define('_MI_XTUBE_VERSION', "Version");
define('_MI_XTUBE_LICENSE', "License");
define('_MI_XTUBE_LICENSEDSC', "GNU General Public License (GPL) - a copy of the GNU license is enclosed (license.txt).");

define('_MI_XTUBE_ADD_VIDEO', "Add Video");
define('_MI_XTUBE_ADD_CATEGORY', "Add Category");

// Admin Summary
define('_MI_XTUBE_SCATEGORY', "Category");
define('_MI_XTUBE_SFILES', "Videos");
define('_MI_XTUBE_SNEWFILESVAL', "Submitted");
define('_MI_XTUBE_SMODREQUEST', "Modified");
define('_MI_XTUBE_SREVIEWS', "Reviews: ");
define('_MI_XTUBE_SBROKENSUBMIT', "Broken");

//Version 1.05 RC2
define('_MI_XTUBE_BNAME11', "Spotlight videos");
