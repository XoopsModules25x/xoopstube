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

// Module Info
// The name of this module
define('_MI_XOOPSTUBE_NAME', 'XoopsTube');

// A brief description of this module
define('_MI_XOOPSTUBE_DESC', 'With the module <em>XoopsTube</em> you can add and categorize movies from sites like <br>YouTube, DailyMotion, LiveLeak, etc. to your website.');

// Names of blocks for this module (Not all module has blocks)
define('_MI_XOOPSTUBE_BNAME1', 'Recent videos (t)');
define('_MI_XOOPSTUBE_BNAME2', 'Recent videos');
define('_MI_XOOPSTUBE_BNAME3', 'Top videos (t)');
define('_MI_XOOPSTUBE_BNAME4', 'Top videos');
define('_MI_XOOPSTUBE_BNAME5', 'Recent videos (h)');
define('_MI_XOOPSTUBE_BNAME6', 'Random videos');
define('_MI_XOOPSTUBE_BNAME7', 'Random videos (h)');

// Sub menu titles
define('_MI_XOOPSTUBE_SMNAME1', 'Submit');
define('_MI_XOOPSTUBE_SMNAME2', 'Popular');
define('_MI_XOOPSTUBE_SMNAME3', 'Top Rated');
define('_MI_XOOPSTUBE_SMNAME4', 'Latest Listings');

// Names of admin menu items
define('_MI_XOOPSTUBE_BINDEX', 'Admin');
define('_MI_XOOPSTUBE_INDEXPAGE', 'Index Page');
define('_MI_XOOPSTUBE_MCATEGORY', 'Categories ');
define('_MI_XOOPSTUBE_MVIDEOS', 'Local Videos');
define('_MI_XOOPSTUBE_MUPLOADS', 'Image Upload');
define('_MI_XOOPSTUBE_PERMISSIONS', 'Permissions');
define('_MI_XOOPSTUBE_BLOCKADMIN', 'Block Settings');
define('_MI_XOOPSTUBE_MVOTEDATA', 'Votes');
define('_MI_XOOPSTUBE_HOME', 'Home');
define('_MI_XOOPSTUBE_ABOUT', 'About');

// Title of config items
define('_MI_XOOPSTUBE_POPULAR', 'video Popular Count');
define('_MI_XOOPSTUBE_POPULARDSC', 'The number of hits before a video status will be considered as popular.');

define('_MI_XOOPSTUBE_ICONDISPLAY', 'videos Popular and New:');
define('_MI_XOOPSTUBE_DISPLAYICONDSC', 'Select how to display the popular and new icons in video listing.');
define('_MI_XOOPSTUBE_DISPLAYICON1', 'Display As Icons');
define('_MI_XOOPSTUBE_DISPLAYICON2', 'Display As Text');
define('_MI_XOOPSTUBE_DISPLAYICON3', 'Do Not Display');

define('_MI_XOOPSTUBE_DAYSNEW', 'videos Days New:');
define('_MI_XOOPSTUBE_DAYSNEWDSC', 'The number of days a video status will be considered as new.');

define('_MI_XOOPSTUBE_DAYSUPDATED', 'videos Days Updated:');
define('_MI_XOOPSTUBE_DAYSUPDATEDDSC', 'The amount of days a video status will be considered as updated.');

define('_MI_XOOPSTUBE_PERPAGE', 'video Listing Count:');
define('_MI_XOOPSTUBE_PERPAGEDSC', 'Number of videos to display in each category listing.');

define('_MI_XOOPSTUBE_ADMINPAGE', 'Admin Index Videos Count:');
define('_MI_XOOPSTUBE_AMDMINPAGEDSC', 'Number of new Videos to display in module admin area.');

define('_MI_XOOPSTUBE_ARTICLESSORT', 'Default video Order:');
define('_MI_XOOPSTUBE_ARTICLESSORTDSC', 'Select the default order for the video listings.');

define('_MI_XOOPSTUBE_SORTCATS', 'Sort categories by:');
define('_MI_XOOPSTUBE_SORTCATSDSC', 'Select how categories and sub-categories are sorted.');

define('_MI_XOOPSTUBE_SUBCATS', 'Sub-Categories:');
define('_MI_XOOPSTUBE_SUBCATSDSC', 'Select Yes to display sub-categories. Selecting No will hide sub-categories from the listings');

define('_MI_XOOPSTUBE_EDITOR', 'Editor to use (admin):');
define('_MI_XOOPSTUBE_EDITORCHOICE', "Select the editor to use for admin side. If you have a 'simple' install (e.g you use only XOOPS core editor class, provided in the standard xoops core package), then you can just select DHTML and Compact");
define('_MI_XOOPSTUBE_EDITORUSER', 'Editor to use (user):');
define('_MI_XOOPSTUBE_EDITORCHOICEUSER', "Select the editor to use for user side. If you have a 'simple' install (e.g you use only XOOPS core editor class, provided in the standard xoops core package), then you can just select DHTML and Compact");
define('_MI_XOOPSTUBE_FORM_DHTML', 'DHTML');
define('_MI_XOOPSTUBE_FORM_COMPACT', 'Compact');
define('_MI_XOOPSTUBE_FORM_SPAW', 'Spaw Editor');
define('_MI_XOOPSTUBE_FORM_HTMLAREA', 'HtmlArea Editor');
define('_MI_XOOPSTUBE_FORM_FCK', 'FCK Editor');
define('_MI_XOOPSTUBE_FORM_KOIVI', 'Koivi Editor');
define('_MI_XOOPSTUBE_FORM_TINYEDITOR', 'TinyEditor');

define('_MI_XOOPSTUBE_USESHOTS', 'Display Screenshot Images?');
define('_MI_XOOPSTUBE_USESHOTSDSC', 'Select yes to display screenshot images for each video item');

define('_MI_XOOPSTUBE_USETHUMBS', 'Use Thumbnails:');
define('_MI_XOOPSTUBE_USETHUMBSDSC', "Supported link types: JPG, GIF, PNG.<div style='padding-top: 8px;'>XoopsTube will use thumb nails for images. Set to 'No' to use orginal image if the server does not support this option.</div>");

define('_MI_XOOPSTUBE_IMGUPDATE', 'Update Thumbnails?');
define('_MI_XOOPSTUBE_IMGUPDATEDSC', 'If selected Thumbnail images will be updated at each page render, otherwise the first thumbnail image will be used regardless. <br><br>');

define('_MI_XOOPSTUBE_SHOTWIDTH', 'Screenshot Display Width');
define('_MI_XOOPSTUBE_SHOTWIDTHDSC', 'Display width for screenshot image');

define('_MI_XOOPSTUBE_SHOTHEIGHT', 'Screenshot Display Height');
define('_MI_XOOPSTUBE_SHOTHEIGHTDSC', 'Display height for screenshot image');

define('_MI_XOOPSTUBE_MAINIMGDIR', 'Main Image Directory');

define('_MI_XOOPSTUBE_CATEGORYIMG', 'Category Image Upload Directory');

define('_MI_XOOPSTUBE_DATEFORMAT', 'Timestamp:');
define('_MI_XOOPSTUBE_DATEFORMATDSC', 'Default Timestamp for XoopsTube');

define('_MI_XOOPSTUBE_TOTALCHARS', 'Set total amount of characters for description?');
define('_MI_XOOPSTUBE_TOTALCHARSDSC', 'Set total amount of characters for description on Index Page.');

define('_MI_XOOPSTUBE_OTHERVIDEOS', 'Show other videos submitted by Submitter?');
define('_MI_XOOPSTUBE_OTHERVIDEOSDSC', 'Select Yes if other videos of the submitter should be displayed.');

define('_MI_XOOPSTUBE_SHOWSUBMITTER', 'Show Submitter?');
define('_MI_XOOPSTUBE_SHOWSUBMITTERDSC', 'Select Yes if you would like to show the Submitter of each video.');

define('_MI_XOOPSTUBE_SHOWSBOOKMARKS', 'Show Social Bookmarks?');
define('_MI_XOOPSTUBE_SHOWSBOOKMARKSDSC', 'Select Yes if you want Social Bookmark icons to be displayed under video.');

define('_MI_XOOPSTUBE_USEMETADESCR', 'Generate meta description:');
define('_MI_XOOPSTUBE_USEMETADSC', 'With this option the meta description will be based on the video description.');

define('_MI_XOOPSTUBE_USERTAGDESCR', 'User can submit Tags:');
define('_MI_XOOPSTUBE_USERTAGDSC', 'Select Yes if user is allowed to submit tags.');

define('_MI_XOOPSTUBE_SHOWDISCLAIMER', 'Show Disclaimer before User Submission?');
define('_MI_XOOPSTUBE_SHOWDISCLAIMERDSC', 'Before a User can submit a Video show the Entry regulations?');

define('_MI_XOOPSTUBE_DISCLAIMER', 'Enter Submission Disclaimer Text:');

define('_MI_XOOPSTUBE_SHOWVIDEODISCL', 'Show Disclaimer before User video?');
define('_MI_XOOPSTUBE_SHOWVIDEODISCLDSC', 'Show video regulations before open a video?');

define('_MI_XOOPSTUBE_VIDEODISCLAIMER', 'Enter video Disclaimer Text:');

define('_MI_XOOPSTUBE_COPYRIGHT', 'Copyright Notice:');
define('_MI_XOOPSTUBE_COPYRIGHTDSC', 'Select to display a copyright notice on video page.');

define('_MI_XOOPSTUBE_CHECKHOST', 'Disallow direct video linking? (leeching)');
define('_MI_XOOPSTUBE_REFERERS', 'These sites can directly video to your videos <br>Separate with #');
define('_MI_XOOPSTUBE_ANONPOST', 'Anonymous User Submission:');
define('_MI_XOOPSTUBE_ANONPOSTDSC', 'Allow Anonymous users to submit or upload to your website?');
define('_MI_XOOPSTUBE_AUTOAPPROVE', 'Auto Approve Submitted videos');
define('_MI_XOOPSTUBE_AUTOAPPROVEDSC', 'Select to approve submitted videos without moderation.');

define('_MI_XOOPSTUBE_MAXFILESIZE', 'Upload Size (KB)');
define('_MI_XOOPSTUBE_MAXFILESIZEDSC', 'Maximum video size permitted with video uploads.');
define('_MI_XOOPSTUBE_IMGWIDTH', 'Upload Image width');
define('_MI_XOOPSTUBE_IMGWIDTHDSC', 'Maximum image width permitted when uploading image videos');
define('_MI_XOOPSTUBE_IMGHEIGHT', 'Upload Image height');
define('_MI_XOOPSTUBE_IMGHEIGHTDSC', 'Maximum image height permitted when uploading image videos');

define('_MI_XOOPSTUBE_UPLOADDIR', 'Upload Directory (No trailing slash)');
define('_MI_XOOPSTUBE_ALLOWSUBMISS', 'User Submissions:');
define('_MI_XOOPSTUBE_ALLOWSUBMISSDSC', 'Allow Users to Submit new videos');
define('_MI_XOOPSTUBE_ALLOWUPLOADS', 'User Uploads:');
define('_MI_XOOPSTUBE_ALLOWUPLOADSDSC', 'Allow Users to upload videos directly to your website');
define('_MI_XOOPSTUBE_SCREENSHOTS', 'Screenshots Upload Directory');

define('_MI_XOOPSTUBE_SUBMITART', 'video Submission:');
define('_MI_XOOPSTUBE_SUBMITARTDSC', 'Select groups that can submit new videos.');
define('_MI_XOOPSTUBE_RATINGGROUPS', 'video Ratings:');
define('_MI_XOOPSTUBE_RATINGGROUPSDSC', 'Select groups that can rate a video.');

define('_MI_XOOPSTUBE_QUALITY', 'Thumb Nail Quality:');
define('_MI_XOOPSTUBE_QUALITYDSC', 'Quality Lowest: 0 Highest: 100');
//define('_MI_XOOPSTUBE_KEEPASPECT', 'Keep Image Aspect Ratio?');
//define('_MI_XOOPSTUBE_KEEPASPECTDSC', '');

define('_MI_XOOPSTUBE_TITLE', 'Title');
define('_MI_XOOPSTUBE_RATING', 'Rating');
define('_MI_XOOPSTUBE_WEIGHT', 'Weight');
define('_MI_XOOPSTUBE_POPULARITY', 'Popularity');
define('_MI_XOOPSTUBE_SUBMITTED2', 'Submission Date');

// Text for notifications
define('_MI_XOOPSTUBE_GLOBAL_NOTIFY', 'Global');
define('_MI_XOOPSTUBE_GLOBAL_NOTIFYDSC', 'Global videos notification options.');
define('_MI_XOOPSTUBE_CATEGORY_NOTIFY', 'Category');
define('_MI_XOOPSTUBE_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current video category.');
define('_MI_XOOPSTUBE_VIDEO_NOTIFY', 'Video');
define('_MI_XOOPSTUBE_FILE_NOTIFYDSC', 'Notification options that apply to the current video.');
define('_MI_XOOPSTUBE_GLOBAL_NEWCATEGORY_NOTIFY', 'New Category');
define('_MI_XOOPSTUBE_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notify me when a new video category is created.');
define('_MI_XOOPSTUBE_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Receive notification when a new video category is created.');
define('_MI_XOOPSTUBE_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New video category');

define('_MI_XOOPSTUBE_GLOBAL_VIDEOMODIFY_NOTIFY', 'Modify Video Requested');
define('_MI_XOOPSTUBE_GLOBAL_VIDEOMODIFY_NOTIFYCAP', 'Notify me of any video modification request.');
define('_MI_XOOPSTUBE_GLOBAL_VIDEOMODIFY_NOTIFYDSC', 'Receive notification when any video modification request is submitted.');
define('_MI_XOOPSTUBE_GLOBAL_VIDEOMODIFY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Video Modification Requested');

define('_MI_XOOPSTUBE_GLOBAL_VIDEOBROKEN_NOTIFY', 'Broken Video Submitted');
define('_MI_XOOPSTUBE_GLOBAL_VIDEOBROKEN_NOTIFYCAP', 'Notify me of any broken video report.');
define('_MI_XOOPSTUBE_GLOBAL_VIDEOBROKEN_NOTIFYDSC', 'Receive notification when any broken video report is submitted.');
define('_MI_XOOPSTUBE_GLOBAL_VIDEOBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Broken Video Reported');

define('_MI_XOOPSTUBE_GLOBAL_VIDEOSUBMIT_NOTIFY', 'Video Submitted');
define('_MI_XOOPSTUBE_GLOBAL_VIDEOSUBMIT_NOTIFYCAP', 'Notify me when any new video is submitted (awaiting approval).');
define('_MI_XOOPSTUBE_GLOBAL_VIDEOSUBMIT_NOTIFYDSC', 'Receive notification when any new video is submitted (awaiting approval).');
define('_MI_XOOPSTUBE_GLOBAL_VIDEOSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New video submitted');

define('_MI_XOOPSTUBE_GLOBAL_NEWVIDEO_NOTIFY', 'New Video');
define('_MI_XOOPSTUBE_GLOBAL_NEWVIDEO_NOTIFYCAP', 'Notify me when any new video is posted.');
define('_MI_XOOPSTUBE_GLOBAL_NEWVIDEO_NOTIFYDSC', 'Receive notification when any new video is posted.');
define('_MI_XOOPSTUBE_GLOBAL_NEWVIDEO_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New video');

define('_MI_XOOPSTUBE_CATEGORY_FILESUBMIT_NOTIFY', 'Video Submitted');
define('_MI_XOOPSTUBE_CATEGORY_FILESUBMIT_NOTIFYCAP', 'Notify me when a new video is submitted (awaiting approval) to the current category.');
define('_MI_XOOPSTUBE_CATEGORY_FILESUBMIT_NOTIFYDSC', 'Receive notification when a new video is submitted (awaiting approval) to the current category.');
define('_MI_XOOPSTUBE_CATEGORY_FILESUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New video submitted in category');

define('_MI_XOOPSTUBE_CATEGORY_NEWVIDEO_NOTIFY', 'New Video');
define('_MI_XOOPSTUBE_CATEGORY_NEWVIDEO_NOTIFYCAP', 'Notify me when a new video is posted to the current category.');
define('_MI_XOOPSTUBE_CATEGORY_NEWVIDEO_NOTIFYDSC', 'Receive notification when a new video is posted to the current category.');
define('_MI_XOOPSTUBE_CATEGORY_NEWVIDEO_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New video in category');

define('_MI_XOOPSTUBE_VIDEO_APPROVE_NOTIFY', 'Video Approved');
define('_MI_XOOPSTUBE_VIDEO_APPROVE_NOTIFYCAP', 'Notify me when this Video is approved.');
define('_MI_XOOPSTUBE_VIDEO_APPROVE_NOTIFYDSC', 'Receive notification when this video is approved.');
define('_MI_XOOPSTUBE_VIDEO_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Video Approved');

define('_MI_XOOPSTUBE_AUTHOR_INFO', 'Developer Information');
define('_MI_XOOPSTUBE_AUTHOR_NAME', 'Developer');
define('_MI_XOOPSTUBE_AUTHOR_DEVTEAM', 'Development Team');
define('_MI_XOOPSTUBE_AUTHOR_WEBSITE', 'Developer website');
define('_MI_XOOPSTUBE_AUTHOR_EMAIL', 'Developer email');
define('_MI_XOOPSTUBE_AUTHOR_CREDITS', 'Credits');
define('_MI_XOOPSTUBE_MODULE_INFO', 'Module Development Information');
define('_MI_XOOPSTUBE_MODULE_STATUS', 'Development Status');
define('_MI_XOOPSTUBE_MODULE_DEMO', 'Demo Site');
define('_MI_XOOPSTUBE_MODULE_SUPPORT', 'Official support site');
define('_MI_XOOPSTUBE_MODULE_BUG', 'Report a bug for this module');
define('_MI_XOOPSTUBE_MODULE_FEATURE', 'Suggest a new feature for this module');
define('_MI_XOOPSTUBE_MODULE_DISCLAIMER', 'Disclaimer');
define('_MI_XOOPSTUBE_RELEASE', 'Release Date');

define('_MI_XOOPSTUBE_MODULE_MAILLIST', 'WF-Project Mailing Lists');
define('_MI_XOOPSTUBE_MODULE_MAILANNOUNCEMENTS', 'Announcements Mailing List');
define('_MI_XOOPSTUBE_MODULE_MAILBUGS', 'Bug Mailing List');
define('_MI_XOOPSTUBE_MODULE_MAILFEATURES', 'Features Mailing List');
define('_MI_XOOPSTUBE_MODULE_MAILANNOUNCEMENTSDSC', 'Get the latest announcements from XOOPS.');
define('_MI_XOOPSTUBE_MODULE_MAILBUGSDSC', 'Bug Tracking and submission mailing list');
define('_MI_XOOPSTUBE_MODULE_MAILFEATURESDSC', 'Request New Features mailing list.');

define('_MI_XOOPSTUBE_WARNINGTEXT', 'THE SOFTWARE IS PROVIDED BY XOOPS "AS IS" AND "WITH ALL FAULTS."
XOOPS MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY KIND CONCERNING
THE QUALITY, SAFETY OR SUITABILITY OF THE SOFTWARE, EITHER EXPRESS OR
IMPLIED, INCLUDING WITHOUT LIMITATION ANY IMPLIED WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, OR NON-INFRINGEMENT.
FURTHER, XOOPS MAKES NO REPRESENTATIONS OR WARRANTIES AS TO THE TRUTH,
ACCURACY OR COMPLETENESS OF ANY STATEMENTS, INFORMATION OR MATERIALS
CONCERNING THE SOFTWARE THAT IS CONTAINED IN XOOPS WEBSITE. IN NO
EVENT WILL XOOPS BE LIABLE FOR ANY INDIRECT, PUNITIVE, SPECIAL,
INCIDENTAL OR CONSEQUENTIAL DAMAGES HOWEVER THEY MAY ARISE AND EVEN IF
XOOPS HAS BEEN PREVIOUSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGES..');

define('_MI_XOOPSTUBE_AUTHOR_CREDITSTEXT', 'WF-Projects Team');
define('_MI_XOOPSTUBE_AUTHOR_BUGFIXES', 'Bug Fix History');

// version 1.02
define('_MI_XOOPSTUBE_FORM_TINYMCE', 'TinyMCE');
define('_MI_XOOPSTUBE_FORM_DHTMLEXT', 'DHTML Extended');

// Version 1.03
define('_MI_XOOPSTUBE_DATEFORMATADMIN', 'Timestamp administration:');
define('_MI_XOOPSTUBE_DATEFORMATADMINDSC', 'Default admininstration Timestamp for XoopsTube');

// Version 1.04 RC-1
define('_MI_XOOPSTUBE_VIDEODIR', 'Video upload directory:');
define('_MI_XOOPSTUBE_VIDEODIRDSC', 'Set the directory for XoopsTube videos files');
define('_MI_XOOPSTUBE_VIDEOIMGDIR', 'Video screenshots upload directory:');
define('_MI_XOOPSTUBE_VIDEOIMGDIRDSC', 'Set the directory for XoopsTube video screenshots');
define('_MI_XOOPSTUBE_FLVPLAYER_CREDITS', 'FLV Player');
define('_MI_XOOPSTUBE_FLVPLAYER_CREDITDSC', 'JW FLV Media Player by LongTail Video (Jeroen Wijering)');
define('_MI_XOOPSTUBE_VUPLOADS', 'Video upload');
define('_MI_XOOPSTUBE_CATEGORYIMGDSC', '');
define('_MI_XOOPSTUBE_MAINIMGDIRDSC', '');
define('_MI_XOOPSTUBE_BNAME8', 'XoopsTube Sponsor Statistics');
define('_MI_XOOPSTUBE_ICONS_CREDITS', 'Icons');

// Version 1.04 RC-2
define('_MI_XOOPSTUBE_MODULE_MANUAL', 'Manual');
define('_MI_XOOPSTUBE_MODULE_MANUALWIKI', 'Wiki Manual');
define('_MI_XOOPSTUBE_MODULE_REQUESTNEWFEATURE', 'Request a new feature');
define('_MI_XOOPSTUBE_MODULE_SUBMITBUG', 'Submit a Bug');
define('_MI_XOOPSTUBE_BNAME9', 'XoopsTube Tag Cloud');
define('_MI_XOOPSTUBE_BNAME10', 'XoopsTube Top Tags');
define('_MI_XOOPSTUBE_FLVPLAYER_MANUAL', 'FLV Player Manual');
define('_MI_XOOPSTUBE_FLVPLAYER_WIKI', 'JW Player Wiki');

// Version 1.05 RC-1
define('_MI_XOOPSTUBE_CATCOLUMNS', 'Select amount of category columns:');
define('_MI_XOOPSTUBE_CATCOLUMNSDSC', 'Select the amount of columns, default is 2');
define('_MI_XOOPSTUBE_RATINGDISPLAY', 'Display rating features?');
define('_MI_XOOPSTUBE_RATINGDISPLAYDSC', "Select <em>Yes</em> to display the Rating features. Select <em>No</em> if you don't want to display the Rating features.");
define('_MI_XOOPSTUBE_AUTOPLAY', 'Autoplay the video');
define('_MI_XOOPSTUBE_AUTOPLAYDSC', 'Select <em>Yes</em> to have the videoclips play automatically.');
define('_MI_XOOPSTUBE_VERSION', 'Version');
define('_MI_XOOPSTUBE_LICENSE', 'License');
define('_MI_XOOPSTUBE_LICENSEDSC', 'GNU General Public License (GPL) - a copy of the GNU license is enclosed (license.txt).');

define('_MI_XOOPSTUBE_ADD_VIDEO', 'Add Video');
define('_MI_XOOPSTUBE_ADD_CATEGORY', 'Add Category');

// Admin Summary
define('_MI_XOOPSTUBE_SCATEGORY', 'Category');
define('_MI_XOOPSTUBE_SFILES', 'Videos');
define('_MI_XOOPSTUBE_SNEWFILESVAL', 'Submitted');
define('_MI_XOOPSTUBE_SMODREQUEST', 'Modified');
define('_MI_XOOPSTUBE_SREVIEWS', 'Reviews: ');
define('_MI_XOOPSTUBE_SBROKENSUBMIT', 'Broken');

//Version 1.05 RC2
define('_MI_XOOPSTUBE_BNAME11', 'Spotlight videos');

//define('_MI_XOOPSTUBE_PREFERENCES_DIRECTIORIES', '<span style="font-weight: bold; color: #FF0000; font-size: small;"><b>Directories Setup</span> ');
define('_MI_XOOPSTUBE_PREFERENCES_DIRECTIORIES', '<span style="font-weight: bold; "><b>Directories Setup</span> ');
define('_MI_XOOPSTUBE_PREFERENCES_COMMENTS', '<span style="font-weight: bold; "><b>Comments/Notifications</span> ');
define('_MI_XOOPSTUBE_PREFERENCES_OTHERS', '<span style="font-weight: bold; "><b>Other Preferences</span> ');

define('_MI_XOOPSTUBE_PREFERENCES_DIRMODE', 'Directory writing permissions');
define('_MI_XOOPSTUBE_PREFERENCES_DIRMODE_DESC', 'Set the directory writing permissions as defined for Unix (e.g. 0777 is the default for upload directories)');

// 1.06 RC2

define('_MI_XOOPSTUBE_BNAME1_DESC', 'Shows recently added video');
define('_MI_XOOPSTUBE_BNAME2_DESC', 'Shows recently added video');
define('_MI_XOOPSTUBE_BNAME3_DESC', 'Shows top clicked videos');
define('_MI_XOOPSTUBE_BNAME4_DESC', 'Shows top clicked videos');
define('_MI_XOOPSTUBE_BNAME5_DESC', 'Shows recently added video');
define('_MI_XOOPSTUBE_BNAME6_DESC', 'Shows random video');
define('_MI_XOOPSTUBE_BNAME7_DESC', 'Shows random video');
define('_MI_XOOPSTUBE_BNAME8_DESC', 'Shows top clicked banners');
define('_MI_XOOPSTUBE_BNAME9_DESC', 'Show tag cloud');
define('_MI_XOOPSTUBE_BNAME10_DESC', 'Show top tag');
define('_MI_XOOPSTUBE_BNAME11_DESC', 'Shows spotlight video');

//1.07
define('_MI_XOOPSTUBE_IMAGEASPECT', 'Keep Image Aspect Ratio?');
define('_MI_XOOPSTUBE_IMAGEASPECTDSC', 'To keep the ration, leave it at 1');

define('_MI_XOOPSTUBE_SHOW_SAMPLE_BUTTON', 'Show Sample Button?');
define('_MI_XOOPSTUBE_SHOW_SAMPLE_BUTTON_DESC', 'If yes, the "Add Sample Data" button will be visible to the Admin. It is Yes as a default for first installation.');
