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

define('_AM_XOOPSTUBE_WARNINSTALL1', "<span style='font-weight: bold;'>WARNING:</span> <span style='text-decoration: underline;'>Directory</span> %s exists on your server.<br>Please remove this directory for security reasons.");
define('_AM_XOOPSTUBE_WARNINSTALL2', "<span style='font-weight: bold;'>WARNING:</span> <span style='text-decoration: underline;'>File</span> %s exists on your server.<br>Please remove this directory for security reasons.");
define('_AM_XOOPSTUBE_WARNINSTALL3', "<span style='font-weight: bold;'>WARNING:</span> <span style='text-decoration: underline;'>Folder</span> %s does not exists on your server.<br>This folder is required by XoopsTube.");

define('_AM_XOOPSTUBE_MODULE_NAME', 'XoopsTube');

define('_AM_XOOPSTUBE_BMODIFY', 'Modify');
define('_AM_XOOPSTUBE_BDELETE', 'Delete');
define('_AM_XOOPSTUBE_BCREATE', 'Create');
define('_AM_XOOPSTUBE_BADD', 'Add');
define('_AM_XOOPSTUBE_BAPPROVE', 'Approve');
define('_AM_XOOPSTUBE_BIGNORE', 'Ignore');
define('_AM_XOOPSTUBE_BCANCEL', 'Cancel');
define('_AM_XOOPSTUBE_BSAVE', 'Save');
define('_AM_XOOPSTUBE_BRESET', 'Reset');
define('_AM_XOOPSTUBE_BMOVE', 'Move Videos');
define('_AM_XOOPSTUBE_BUPLOAD', 'Upload');
define('_AM_XOOPSTUBE_BDELETEIMAGE', 'Delete Selected Image');
define('_AM_XOOPSTUBE_BRETURN', 'Return to where you where!');
define('_AM_XOOPSTUBE_DBERROR', 'Database Access Error');
// Other Options
define('_AM_XOOPSTUBE_TEXTOPTIONS', 'Text Options:');
define('_AM_XOOPSTUBE_DISABLEHTML', ' Disable HTML Tags');
define('_AM_XOOPSTUBE_DISABLESMILEY', ' Disable Smilie Icons');
define('_AM_XOOPSTUBE_DISABLEXCODE', ' Disable XOOPS Codes');
define('_AM_XOOPSTUBE_DISABLEIMAGES', ' Disable Images');
define('_AM_XOOPSTUBE_DISABLEBREAK', ' Use XOOPS linebreak conversion?');
define('_AM_XOOPSTUBE_UPLOADFILE', 'File Uploaded Successfully');
define('_AM_XOOPSTUBE_NOMENUITEMS', 'No menu items within the menu');
// Admin Bread crumb
define('_AM_XOOPSTUBE_PREFS', 'Preferences');
define('_AM_XOOPSTUBE_BUPDATE', 'Module Update');
define('_AM_XOOPSTUBE_BINDEX', 'Main Index');
define('_AM_XOOPSTUBE_BPERMISSIONS', 'Permissions');
// define('_AM_XOOPSTUBE_BLOCKADMIN","Blocks");
define('_AM_XOOPSTUBE_BLOCKADMIN', 'Block Settings');
define('_AM_XOOPSTUBE_GOMODULE', 'Go to module');
define('_AM_XOOPSTUBE_ABOUT', 'About');
// Admin Summary
define('_AM_XOOPSTUBE_SCATEGORY', 'Category:  <strong>%s</strong> ');
define('_AM_XOOPSTUBE_SFILES', 'Videos:  <strong>%s</strong> ');
define('_AM_XOOPSTUBE_SNEWFILESVAL', 'Submitted:  <strong>%s</strong> ');
define('_AM_XOOPSTUBE_SMODREQUEST', 'Modified:  <strong>%s</strong> ');
define('_AM_XOOPSTUBE_SREVIEWS', 'Reviews: ');

// Admin Main Menu
define('_AM_XOOPSTUBE_MCATEGORY', 'Category Management');
define('_AM_XOOPSTUBE_MVIDEOS', 'Video Management');
define('_AM_XOOPSTUBE_MLISTBROKEN', 'List Broken Videos');
define('_AM_XOOPSTUBE_MLISTPINGTIMES', 'List Links Pingtime');
define('_AM_XOOPSTUBE_INDEXPAGE', 'Index Page Management');
define('_AM_XOOPSTUBE_MCOMMENTS', 'Comments');
define('_AM_XOOPSTUBE_MVOTEDATA', 'Vote Data');
define('_AM_XOOPSTUBE_MUPLOADS', 'Image Upload');

// Category defines
define('_AM_XOOPSTUBE_CCATEGORY_CREATENEW', 'Create New Category');
define('_AM_XOOPSTUBE_CCATEGORY_MODIFY', 'Modify Category');
define('_AM_XOOPSTUBE_CCATEGORY_MOVE', 'Move Category Videos');
define('_AM_XOOPSTUBE_CCATEGORY_MODIFY_TITLE', 'Category Title:');
define('_AM_XOOPSTUBE_CCATEGORY_MODIFY_FAILED', 'Failed Moving Videos: Cannot move to this Category');
define('_AM_XOOPSTUBE_CCATEGORY_MODIFY_FAILEDT', 'Failed Moving Videos: Cannot find this Category');
define('_AM_XOOPSTUBE_CCATEGORY_MODIFY_MOVED', 'Videos Moved and Category Deleted');
define('_AM_XOOPSTUBE_CCATEGORY_CREATED', 'New Category Created and Database Updated Successfully');
define('_AM_XOOPSTUBE_CCATEGORY_MODIFIED', 'Selected Category Modified and Database Updated Successfully');
define('_AM_XOOPSTUBE_CCATEGORY_DELETED', 'Selected Category Deleted and Database Updated Successfully');
define('_AM_XOOPSTUBE_CCATEGORY_AREUSURE', 'WARNING: Are you sure you want to delete this Category and ALL its Videos and Comments?');
define('_AM_XOOPSTUBE_CCATEGORY_NOEXISTS', 'You must create a Category before you can add a new video');
define('_AM_XOOPSTUBE_FCATEGORY_GROUPPROMPT', "Category Access Permissions:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Select user groups who will have access to this Category.</span></div>");
define('_AM_XOOPSTUBE_FCATEGORY_SUBGROUPPROMPT', "Category Submission Permissions:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Select user groups who will have permission to submit new videos to this Category.</span></div>");
define('_AM_XOOPSTUBE_FCATEGORY_MODGROUPPROMPT', "Category Moderation Permissions:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Select user groups who will have permission to moderate this Category.</span></div>");

define('_AM_XOOPSTUBE_FCATEGORY_TITLE', 'Category Title:');
define('_AM_XOOPSTUBE_FCATEGORY_WEIGHT', 'Category Weight:');
define('_AM_XOOPSTUBE_FCATEGORY_SUBCATEGORY', 'Set As Sub-Category:');
define('_AM_XOOPSTUBE_FCATEGORY_CIMAGE', 'Select Category Image:');
define('_AM_XOOPSTUBE_FCATEGORY_DESCRIPTION', 'Set Category Description:');
define('_AM_XOOPSTUBE_FCATEGORY_SUMMARY', 'Set Category Summary:');
/**
 * Index page Defines
 */
define('_AM_XOOPSTUBE_IPAGE_UPDATED', 'Index Page Modified and Database Updated Successfully!');
define('_AM_XOOPSTUBE_IPAGE_INFORMATION', 'Index Page Information');
define('_AM_XOOPSTUBE_IPAGE_MODIFY', 'Modify Index Page');
define('_AM_XOOPSTUBE_IPAGE_CIMAGE', 'Select Index Image:');
define('_AM_XOOPSTUBE_IPAGE_CTITLE', 'Index Title:');
define('_AM_XOOPSTUBE_IPAGE_CHEADING', 'Index Heading:');
define('_AM_XOOPSTUBE_IPAGE_CHEADINGA', 'Index Heading Alignment:');
define('_AM_XOOPSTUBE_IPAGE_CFOOTER', 'Index Footer:');
define('_AM_XOOPSTUBE_IPAGE_CFOOTERA', 'Index Footer Alignment:');
define('_AM_XOOPSTUBE_IPAGE_CLEFT', 'Align Left');
define('_AM_XOOPSTUBE_IPAGE_CCENTER', 'Align Center');
define('_AM_XOOPSTUBE_IPAGE_CRIGHT', 'Align Right');
/**
 * Permissions defines
 */
define('_AM_XOOPSTUBE_PERM_MANAGEMENT', 'Permissions Management');
define(
    '_AM_XOOPSTUBE_PERM_PERMSNOTE',
       "<div><span style='font-weight: bold;'>NOTE:</span> Please be aware that even if you've set correct viewing permissions here, a group might not see the articles or blocks if you don't also grant that group permissions to access the module. To do that, go to <span style='font-weight: bold;'>System admin > Groups</span>, choose the appropriate group and click the checkboxes to grant its members the access.</div>"
);
define('_AM_XOOPSTUBE_PERM_CPERMISSIONS', 'Category Permissions');
define('_AM_XOOPSTUBE_PERM_CSELECTPERMISSIONS', 'Select categories that each group is allowed to view');
define('_AM_XOOPSTUBE_PERM_CNOCATEGORY', "Cannot set permission's: No Categories's have been created yet!");
define('_AM_XOOPSTUBE_PERM_FPERMISSIONS', 'Video Permissions');
define('_AM_XOOPSTUBE_PERM_FNOFILES', "Cannot set permission's: No videos have been created yet!");
define('_AM_XOOPSTUBE_PERM_FSELECTPERMISSIONS', 'Select the videos that each group is allowed to view');
/**
 * Upload defines
 */
define('_AM_XOOPSTUBE_VIDEO_IMAGEUPLOAD', 'Image successfully uploaded to server destination');
define('_AM_XOOPSTUBE_VIDEO_NOIMAGEEXIST', 'Error: No image was selected for uploading.  Please try again!');
define('_AM_XOOPSTUBE_VIDEO_IMAGEEXIST', 'Image already exists in upload area!');
define('_AM_XOOPSTUBE_VIDEO_FILEDELETED', 'Image has been deleted.');
define('_AM_XOOPSTUBE_VIDEO_FILEERRORDELETE', 'Error deleting Video: Video not found on server.');
define('_AM_XOOPSTUBE_VIDEO_NOFILEERROR', 'Error deleting Image: No Image Selected For Deleting.');
define('_AM_XOOPSTUBE_VIDEO_DELETEFILE', 'WARNING: Are you sure you want to delete this Image link?');
define('_AM_XOOPSTUBE_VIDEO_IMAGEINFO', 'Server Status');
define('_AM_XOOPSTUBE_VIDEO_SPHPINI', "<span style='font-weight: bold;'>Information taken from PHP ini Link:</span>");
define('_AM_XOOPSTUBE_VIDEO_SAFEMODESTATUS', 'Safe Mode Status: ');
define('_AM_XOOPSTUBE_VIDEO_REGISTERGLOBALS', 'Register Globals: ');
define('_AM_XOOPSTUBE_VIDEO_SERVERUPLOADSTATUS', 'Server Uploads Status: ');
define('_AM_XOOPSTUBE_VIDEO_MAXUPLOADSIZE', 'Max Upload Size Permitted: ');
define('_AM_XOOPSTUBE_VIDEO_MAXPOSTSIZE', 'Max Post Size Permitted: ');
define('_AM_XOOPSTUBE_VIDEO_SAFEMODEPROBLEMS', ' (This May Cause Problems)');
define('_AM_XOOPSTUBE_VIDEO_GDLIBSTATUS', 'GD Library Support: ');
define('_AM_XOOPSTUBE_VIDEO_GDLIBVERSION', 'GD Library Version: ');
define('_AM_XOOPSTUBE_VIDEO_GDON', "<span style='font-weight: bold;'>Enabled</span> (Thumbs Nails Available)");
define('_AM_XOOPSTUBE_VIDEO_GDOFF', "<span style='font-weight: bold;'>Disabled</span> (No Thumb Nails Available)");
define('_AM_XOOPSTUBE_VIDEO_OFF', "<span style='font-weight: bold;'>OFF</span>");
define('_AM_XOOPSTUBE_VIDEO_ON', "<span style='font-weight: bold;'>ON</span>");
define('_AM_XOOPSTUBE_VIDEO_CATIMAGE', 'Category Images');
define('_AM_XOOPSTUBE_VIDEO_SCREENSHOTS', 'Screenshot Images');
define('_AM_XOOPSTUBE_VIDEO_MAINIMAGEDIR', 'Main images');
define('_AM_XOOPSTUBE_VIDEO_FCATIMAGE', 'Category Image Path');
define('_AM_XOOPSTUBE_VIDEO_FSCREENSHOTS', 'Screenshot Image Path');
define('_AM_XOOPSTUBE_VIDEO_FMAINIMAGEDIR', 'Main image Path');
define('_AM_XOOPSTUBE_VIDEO_FUPLOADIMAGETO', 'Upload Image: ');
define('_AM_XOOPSTUBE_VIDEO_FUPLOADPATH', 'Upload Path: ');
define('_AM_XOOPSTUBE_VIDEO_FUPLOADURL', 'Upload URL: ');
define('_AM_XOOPSTUBE_VIDEO_FOLDERSELECTION', 'Select Upload Destination:');
define('_AM_XOOPSTUBE_VIDEO_FSHOWSELECTEDIMAGE', 'Display Selected Image:');
define('_AM_XOOPSTUBE_VIDEO_FUPLOADIMAGE', 'Upload New Image to Selected Destination:');

// Main Index defines
define('_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY', 'Module Admin Summary');
define('_AM_XOOPSTUBE_MINDEX_PUBLISHEDVIDEO', 'Published Videos:');
define('_AM_XOOPSTUBE_MINDEX_AUTOPUBLISHEDVIDEO', 'Auto Published Videos:');
define('_AM_XOOPSTUBE_MINDEX_AUTOEXPIRE', 'Auto Expire Videos:');
define('_AM_XOOPSTUBE_MINDEX_EXPIRED', 'Expired Videos:');
define('_AM_XOOPSTUBE_MINDEX_OFFLINEVIDEO', 'Offline Videos:');
define('_AM_XOOPSTUBE_MINDEX_ID', 'ID');
define('_AM_XOOPSTUBE_MINDEX_TITLE', 'Video Title');
define('_AM_XOOPSTUBE_MINDEX_POSTER', 'Poster');
define('_AM_XOOPSTUBE_MINDEX_ONLINE', 'Status');
define('_AM_XOOPSTUBE_MINDEX_ONLINESTATUS', 'Online Status');
define('_AM_XOOPSTUBE_MINDEX_PUBLISH', 'Published');
define('_AM_XOOPSTUBE_MINDEX_PUBLISHED', 'Published');
define('_AM_XOOPSTUBE_MINDEX_EXPIRE', 'Expired');
define('_AM_XOOPSTUBE_MINDEX_NOTSET', 'Date Not Set');
define('_AM_XOOPSTUBE_MINDEX_SUBMITTED', 'Date Submitted');

define('_AM_XOOPSTUBE_MINDEX_ACTION', 'Action');
define('_AM_XOOPSTUBE_MINDEX_NOVIDEOSFOUND', 'NOTICE: There are no videos that match this criteria');
define('_AM_XOOPSTUBE_MINDEX_PAGE', "<span style='font-weight: bold;'>Page:<span style='font-weight: bold;'> ");
define('_AM_XOOPSTUBE_MINDEX_PAGEINFOTXT', '<ul><li>XoopsTubes main page details.</li><li>You can easily change the image logo, heading, main index header and footer text to suit your own look</li></ul><br>Note: The Logo image choosen will be used throughout XoopsTube.');
define('_AM_XOOPSTUBE_MINDEX_RESPONSE', 'Response Time');
// Submitted Links
define('_AM_XOOPSTUBE_SUB_SUBMITTEDFILES', 'Submitted Videos');
define('_AM_XOOPSTUBE_SUB_FILESWAITINGINFO', 'Waiting Videos Information');
define('_AM_XOOPSTUBE_SUB_FILESWAITINGVALIDATION', 'Videos Waiting Validation: ');
define('_AM_XOOPSTUBE_SUB_APPROVEWAITINGFILE', "<span style='font-weight: bold;'>Approve</span> new video information without validation.");
define('_AM_XOOPSTUBE_SUB_EDITWAITINGFILE', "<span style='font-weight: bold;'>Edit</span> new video information and then approve.");
define('_AM_XOOPSTUBE_SUB_DELETEWAITINGFILE', "<span style='font-weight: bold;'>Delete</span> the new video information.");
define('_AM_XOOPSTUBE_SUB_NOFILESWAITING', 'There are no videos that match this critera');
define('_AM_XOOPSTUBE_SUB_NEWFILECREATED', 'New Video Data Created and Database Updated Successfully');
// Vote Information
define('_AM_XOOPSTUBE_VOTE_RATINGINFOMATION', 'Voting Information');
define('_AM_XOOPSTUBE_VOTE_TOTALVOTES', 'Total votes: ');
define('_AM_XOOPSTUBE_VOTE_REGUSERVOTES', 'Registered User Votes: %s');
define('_AM_XOOPSTUBE_VOTE_ANONUSERVOTES', 'Anonymous User Votes: %s');
define('_AM_XOOPSTUBE_VOTE_USER', 'User');
define('_AM_XOOPSTUBE_VOTE_IP', 'IP Address');
define('_AM_XOOPSTUBE_VOTE_DATE', 'Submitted');
define('_AM_XOOPSTUBE_VOTE_RATING', 'Rating');
define('_AM_XOOPSTUBE_VOTE_NOREGVOTES', 'No Registered User Votes');
define('_AM_XOOPSTUBE_VOTE_NOUNREGVOTES', 'No Unregistered User Votes');
define('_AM_XOOPSTUBE_VOTE_VOTEDELETED', 'Vote data deleted.');
define('_AM_XOOPSTUBE_VOTE_ID', 'ID');
define('_AM_XOOPSTUBE_VOTE_FILETITLE', 'Video Title');
define('_AM_XOOPSTUBE_VOTE_DISPLAYVOTES', 'Voting Data Information');
define('_AM_XOOPSTUBE_VOTE_NOVOTES', 'No User Votes to display');
define('_AM_XOOPSTUBE_VOTE_DELETE', 'No User Votes to display');
define('_AM_XOOPSTUBE_VOTE_DELETEDSC', "<span style='font-weight: bold;'>Deletes</span> the chosen vote information from the database.");
define('_AM_XOOPSTUBE_VOTEDELETED', 'Selected Vote removed database updated');

define('_AM_XOOPSTUBE_VOTE_USERAVG', 'Average User Rating');
define('_AM_XOOPSTUBE_VOTE_TOTALRATE', 'Total Votes');
define('_AM_XOOPSTUBE_VOTE_MAXRATE', 'Max Item Vote');
define('_AM_XOOPSTUBE_VOTE_MINRATE', 'Min Item Vote');
define('_AM_XOOPSTUBE_VOTE_MOSTVOTEDTITLE', 'Most Voted For');
define('_AM_XOOPSTUBE_VOTE_LEASTVOTEDTITLE', 'Least Voted For');
define('_AM_XOOPSTUBE_VOTE_MOSTVOTERSUID', 'Most Active Voter');
define('_AM_XOOPSTUBE_VOTE_REGISTERED', 'Registered Votes');
define('_AM_XOOPSTUBE_VOTE_NONREGISTERED', 'Anonymous Votes');
// Modifications
define('_AM_XOOPSTUBE_MOD_TOTMODREQUESTS', 'Total Modification Requests: ');
define('_AM_XOOPSTUBE_MOD_MODREQUESTS', 'Modified Videos');
define('_AM_XOOPSTUBE_MOD_MODREQUESTSINFO', 'Modified Videos Information');
define('_AM_XOOPSTUBE_MOD_MODID', 'ID');
define('_AM_XOOPSTUBE_MOD_MODTITLE', 'Title');
define('_AM_XOOPSTUBE_MOD_MODPOSTER', 'Original Poster: ');
define('_AM_XOOPSTUBE_MOD_DATE', 'Submitted');
define('_AM_XOOPSTUBE_MOD_NOMODREQUEST', 'There are no requests that match this critera');
define('_AM_XOOPSTUBE_MOD_TITLE', 'video Title: ');
define('_AM_XOOPSTUBE_MOD_LID', 'video ID: ');
define('_AM_XOOPSTUBE_MOD_CID', 'Category: ');
define('_AM_XOOPSTUBE_MOD_URL', 'video Url: ');
define('_AM_XOOPSTUBE_MOD_PUBLISHER', 'Publisher: ');
define('_AM_XOOPSTUBE_MOD_SCREENSHOT', 'Screenshot Image: ');
define('_AM_XOOPSTUBE_MOD_DESCRIPTION', 'Description: ');
define('_AM_XOOPSTUBE_MOD_MODIFYSUBMITTER', 'Submitter: ');
define('_AM_XOOPSTUBE_MOD_MODIFYSUBMIT', 'Submitter');
define('_AM_XOOPSTUBE_MOD_PROPOSED', 'Proposed video Details');
define('_AM_XOOPSTUBE_MOD_ORIGINAL', 'Orginal video Details');
define('_AM_XOOPSTUBE_MOD_REQDELETED', 'Modification request removed from the database');
define('_AM_XOOPSTUBE_MOD_REQUPDATED', 'Selected video Modified and Database Updated Successfully');
define('_AM_XOOPSTUBE_MOD_VIEW', 'View');
// Video management
define('_AM_XOOPSTUBE_VIDEO_ID', 'Video ID: ');
define('_AM_XOOPSTUBE_VIDEO_IP', 'Uploaders IP: ');
define('_AM_XOOPSTUBE_VIDEO_ALLOWEDAMIME', "<div style='padding-top: 4px; padding-bottom: 4px;'><span style='font-weight: bold;'>Allowed Admin Video Extensions</span>:</div>");
define('_AM_XOOPSTUBE_VIDEO_MODIFYFILE', 'Modify Video Information');
define('_AM_XOOPSTUBE_VIDEO_CREATENEWFILE', 'Create New Video');
define('_AM_XOOPSTUBE_VIDEO_TITLE', 'Video Title: ');
define('_AM_XOOPSTUBE_VIDEO_DLVIDID', 'Video id-code: ');
define('_AM_XOOPSTUBE_VIDEO_DLVIDID_NOTE', "
<span style='font-size: small;'><span style='font-weight: bold;'>YouTube:</span> http://www.youtube.com/watch?v=<span style='color: #FF0000;'>IsOtq-qCqZ4</span><br>
<span style='font-weight: bold;'>MetaCafe:</span> http://www.metacafe.com/watch/<span style='color: #FF0000;'>191543/sperm_whale_encounters_underwater_robot</span>/<br>
<span style='font-weight: bold;'>SPIKE/ifilm:</span> http://www.ifilm.com/video/<span style='color: #FF0000;'>2718605</span><br>
<span style='font-weight: bold;'>Photobucket:</span> http://i39.photobucket.com/albums/<span style='color: #FF0000;'>e168/vailtom/th_BigSquid</span>.jpg<br>
<span style='font-weight: bold;'>Google Video:</span> http://video.google.com/videoplay?docid=<span style='color: #FF0000;'>4761076111111865377</span>&q=rov&total=913&start=...<br>
<span style='font-weight: bold;'>MySpace TV:</span> http://vids.myspace.com/index.cfm?fuseaction=vids.individual&videoid=<span style='color: #FF0000;'>13171141</span><br>
<span style='font-weight: bold;'>DailyMotion:</span> http://www.dailymotion.com/video/<span style='color: #FF0000;'>x40bhg</span>_blondesecretary_fun<br>
<span style='font-weight: bold;'>Blip.tv </span>embed code<span style='font-weight: bold;'>:</span> http://blip.tv/play/<span style='color: #FF0000;'>Abe6EwA</span> ...<br>
<span style='font-weight: bold;'>ClipFish:</span> http://www.clipfish.de/player.php?videoid=<span style='color: #FF0000;'>MTg1NTYyfDM1Ng</span>%3D%3D<br>
<span style='font-weight: bold;'>LiveLeak:</span> http://www.liveleak.com/view?i=<span style='color: #FF0000;'>a59_1205566630</span><br>
<span style='font-weight: bold;'>Maktoob:</span> file=http://<span style='color: #FF0000;'>m003.maktoob.com/files/23/42/95531c25b690e48a3d69966b6a33b8d2/video/131102</span>.flv (from embed-code)<br>
<span style='font-weight: bold;'>Veoh:</span> http://www.veoh.com/videos/<span style='color: #FF0000;'>v15069875yApCz7r3</span>?jsonParams=...<br>
<span style='font-weight: bold;'>Vimeo:</span> http://www.vimeo.com/<span style='color: #FF0000;'>2246499</span><br>
<span style='font-weight: bold;'>Megavideo:</span> http://www.megavideo.com/?v=<span style='color: #FF0000;'>J6YSRE0T</span><br>
<span style='font-weight: bold;'>Viddler:</span> http://www.viddler.com/player/<span style='color: #FF0000;'>d32d2b94</span>/ (see embed code)<br>
<span style='font-weight: bold;'>XoopsTube: </span><span style='color: #FF0000;'>Take over from above selection menu</span></span>");
define('_AM_XOOPSTUBE_VIDEO_PICURL', 'Picture url: ');
define('_AM_XOOPSTUBE_VIDEO_PICURLNOTE', 'Picture url when source is: Google Video, MySpace TV, DailyMotion, Blip.tv, ClipFish, LiveLeak, Veoh, Vimeo, Megavideo or Maktoob');
define('_AM_XOOPSTUBE_VIDEO_DESCRIPTION', 'Video Description: ');
define('_AM_XOOPSTUBE_VIDEO_CATEGORY', 'Video Main Category: ');
define('_AM_XOOPSTUBE_VIDEO_FILESSTATUS', " Set video offline?<br><br><span style='font-weight: normal;font-size: smaller;'>Video will not be viewable to all users</span>");
define('_AM_XOOPSTUBE_VIDEO_SETASUPDATED', " Set video Status as Updated?<br><br><span style='font-weight: normal;font-size: smaller;'>Video will display updated icon</span>");
define('_AM_XOOPSTUBE_VIDEO_SHOTIMAGE', 'Video Screenshot Image: ');
define('_AM_XOOPSTUBE_VIDEO_DISCUSSINFORUM', 'Add Discuss in this Forum?');
define('_AM_XOOPSTUBE_VIDEO_PUBLISHDATE', 'Video Publish Date:');
define('_AM_XOOPSTUBE_VIDEO_EXPIREDATE', 'Video Expire Date:');
define('_AM_XOOPSTUBE_VIDEO_CLEARPUBLISHDATE', '<br><br>Remove Publish date:');
define('_AM_XOOPSTUBE_VIDEO_CLEAREXPIREDATE', '<br><br>Remove Expire date:');
define('_AM_XOOPSTUBE_VIDEO_PUBLISHDATESET', ' Publish date set: ');
define('_AM_XOOPSTUBE_VIDEO_SETDATETIMEPUBLISH', ' Set the date/time of publish');
define('_AM_XOOPSTUBE_VIDEO_SETDATETIMEEXPIRE', ' Set the date/time of expire');
define('_AM_XOOPSTUBE_VIDEO_SETPUBLISHDATE', "<span style='font-weight: bold;'>Set Publish Date: </span>");
define('_AM_XOOPSTUBE_VIDEO_SETNEWPUBLISHDATE', "<span style='font-weight: bold;'>Set New Publish Date: </span><br>Published:");
define('_AM_XOOPSTUBE_VIDEO_SETPUBDATESETS', "<span style='font-weight: bold;'>Publish Date Set: </span><br>Publishes On Date:");
define('_AM_XOOPSTUBE_VIDEO_EXPIREDATESET', ' Expire date set: ');
define('_AM_XOOPSTUBE_VIDEO_SETEXPIREDATE', "<span style='font-weight: bold;'>Set Expire Date: </span>");
define('_AM_XOOPSTUBE_VIDEO_DELEDITMESS', "Delete Broken Report?<br><br><span style='font-weight: normal;'>When you choose <span style='font-weight: bold;'>YES</span> the Broken Report will automatically deleted and you confirm that the video now works again.</span>");
define('_AM_XOOPSTUBE_VIDEO_MUSTBEVALID', 'Screenshot image must be a valid image under %s directory (ex. shot.gif). This screenshot is used for XoopsTube internal flv player only.');
define('_AM_XOOPSTUBE_VIDEO_EDITAPPROVE', 'Approve video:');
define('_AM_XOOPSTUBE_VIDEO_NEWFILEUPLOAD', 'New Video Created and Database Updated Successfully');
define('_AM_XOOPSTUBE_VIDEO_FILEMODIFIEDUPDATE', 'Selected Video Modified and Database Updated Successfully');
define('_AM_XOOPSTUBE_VIDEO_REALLYDELETEDTHIS', 'Really delete the selected video?');
define('_AM_XOOPSTUBE_VIDEO_FILEWASDELETED', 'Video %s successfully removed from the database!');
define('_AM_XOOPSTUBE_VIDEO_FILEAPPROVED', 'Video Approved and Database Updated Successfully');
define('_AM_XOOPSTUBE_VIDEO_CREATENEWSSTORY', "<span style='font-weight: bold;'>Create News Story From link</span>");
define('_AM_XOOPSTUBE_VIDEO_SUBMITNEWS', 'Submit New video as News item?');
define('_AM_XOOPSTUBE_VIDEO_NEWSCATEGORY', 'Select News Category to submit News:');
define('_AM_XOOPSTUBE_VIDEO_NEWSTITLE', "News Title:<div style='padding-top: 4px; padding-bottom: 4px;'><span style='font-weight: normal;'>Leave Blank to use Video Title</span></div>");
define('_AM_XOOPSTUBE_VIDEO_PUBLISHER', 'Video Publisher Name: ');

/**
 * Broken links defines
 */
define('_AM_XOOPSTUBE_SBROKENSUBMIT', 'Broken: <strong>%s</strong> ');
//define('_AM_XOOPSTUBE_SBROKENSUBMIT","Broken");
define('_AM_XOOPSTUBE_BROKEN_FILE', 'Broken Reports');
define('_AM_XOOPSTUBE_BROKEN_FILEIGNORED', 'Broken report ignored and successfully removed from the database!');
define('_AM_XOOPSTUBE_BROKEN_NOWACK', 'Acknowledged status changed and database updated!');
define('_AM_XOOPSTUBE_BROKEN_NOWCON', 'Confirmed status changed and database updated!');
define('_AM_XOOPSTUBE_BROKEN_REPORTINFO', 'Broken Report Information');
define('_AM_XOOPSTUBE_BROKEN_REPORTSNO', 'Broken Reports Waiting:');
define('_AM_XOOPSTUBE_BROKEN_IGNOREDESC', "<span style='font-weight: bold;'>Ignores</span> the report and only deletes the broken video report.");
define('_AM_XOOPSTUBE_BROKEN_DELETEDESC', "<span style='font-weight: bold;'>Deletes</span> the reported link data and broken video reports for the link.");
define('_AM_XOOPSTUBE_BROKEN_EDITDESC', "<span style='font-weight: bold;'>Edit</span> the video to fix the problem.");
define('_AM_XOOPSTUBE_BROKEN_ACKDESC', "<span style='font-weight: bold;'>Acknowledged</span> Set Acknowledged state of broken file report.");
define('_AM_XOOPSTUBE_BROKEN_CONFIRMDESC', "<span style='font-weight: bold;'>Confirmed</span> Set confirmed state of broken video report.");
define('_AM_XOOPSTUBE_BROKEN_ACKNOWLEDGED', 'Acknowledged');
define('_AM_XOOPSTUBE_BROKEN_DCONFIRMED', 'Confirmed');

define('_AM_XOOPSTUBE_BROKEN_ID', 'ID');
define('_AM_XOOPSTUBE_BROKEN_TITLE', 'Title');
define('_AM_XOOPSTUBE_BROKEN_REPORTER', 'Reporter');
define('_AM_XOOPSTUBE_BROKEN_FILESUBMITTER', 'Submitter');
define('_AM_XOOPSTUBE_BROKEN_DATESUBMITTED', 'Submit Date');
define('_AM_XOOPSTUBE_BROKEN_ACTION', 'Action');
define('_AM_XOOPSTUBE_BROKEN_NOFILEMATCH', 'There are no Broken reports that match this critera');
define('_AM_XOOPSTUBE_BROKENFILEDELETED', 'video removed from database and broken report removed');
define('_AM_XOOPSTUBE_BROKEN_VIDSOURCE', 'Video source');
/**
 * About defines
 */
define('_AM_XOOPSTUBE_BY', 'by');
// block defines
define('_AM_XOOPSTUBE_BADMIN', 'Block Administration');
define('_AM_XOOPSTUBE_BLKDESC', 'Description');
define('_AM_XOOPSTUBE_TITLE', 'Title');
define('_AM_XOOPSTUBE_SIDE', 'Alignment');
define('_AM_XOOPSTUBE_WEIGHT', 'Weight');
define('_AM_XOOPSTUBE_VISIBLE', 'Visible');
define('_AM_XOOPSTUBE_ACTION', 'Action');
define('_AM_XOOPSTUBE_SBLEFT', 'Left');
define('_AM_XOOPSTUBE_SBRIGHT', 'Right');
define('_AM_XOOPSTUBE_CBLEFT', 'Center Left');
define('_AM_XOOPSTUBE_CBRIGHT', 'Center Right');
define('_AM_XOOPSTUBE_CBCENTER', 'Center Middle');
define('_AM_XOOPSTUBE_ACTIVERIGHTS', 'Active Rights');
define('_AM_XOOPSTUBE_ACCESSRIGHTS', 'Access Rights');
// image admin icon
define('_AM_XOOPSTUBE_ICO_EDIT', 'Edit This Item');
define('_AM_XOOPSTUBE_ICO_DELETE', 'Delete This Item');
define('_AM_XOOPSTUBE_ICO_RESOURCE', 'Edit This Resource');

define('_AM_XOOPSTUBE_ICO_ONLINE', 'Online');
define('_AM_XOOPSTUBE_ICO_OFFLINE', 'Offline');
define('_AM_XOOPSTUBE_ICO_APPROVED', 'Approved');
define('_AM_XOOPSTUBE_ICO_NOTAPPROVED', 'Not Approved');

define('_AM_XOOPSTUBE_ICO_VIDEO', 'Related video');
define('_AM_XOOPSTUBE_ICO_URL', 'Add Related URL');
define('_AM_XOOPSTUBE_ICO_ADD', 'Add');
define('_AM_XOOPSTUBE_ICO_APPROVE', 'Approve');
define('_AM_XOOPSTUBE_ICO_STATS', 'Stats');
define('_AM_XOOPSTUBE_ICO_VIEW', 'View this item');

define('_AM_XOOPSTUBE_ICO_IGNORE', 'Ignore');
define('_AM_XOOPSTUBE_ICO_ACK', 'Broken Report Acknowledged');
define('_AM_XOOPSTUBE_ICO_REPORT', 'Acknowledge Broken Report?');
define('_AM_XOOPSTUBE_ICO_CONFIRM', 'Broken Report Confirmed');
define('_AM_XOOPSTUBE_ICO_CONBROKEN', 'Confirm Broken Report?');
define('_AM_XOOPSTUBE_ICO_RES', 'Edit Resources/Videos for this Item');
define('_AM_XOOPSTUBE_MOD_URLRATING', 'Interent Content Rating:');
// Alternate category
define('_AM_XOOPSTUBE_ALTCAT_CREATEF', 'Add Alternate Category');
define('_AM_XOOPSTUBE_MALTCAT', 'Alternate Category Management');
define('_AM_XOOPSTUBE_ALTCAT_MODIFYF', 'Alternate Category Management');
define('_AM_XOOPSTUBE_ALTCAT_INFOTEXT', '<ul><li>Alternate categories can be added or removed easily via this form.</li></ul>');
define('_AM_XOOPSTUBE_ALTCAT_CREATED', 'Alternate categories was saved!');

define('_AM_XOOPSTUBE_MRESOURCES', 'Resource Management');
define('_AM_XOOPSTUBE_RES_CREATED', 'Resource Management');
define('_AM_XOOPSTUBE_RES_ID', 'ID');
define('_AM_XOOPSTUBE_RES_DESC', 'Description');
define('_AM_XOOPSTUBE_RES_NAME', 'Resource Name');
define('_AM_XOOPSTUBE_RES_TYPE', 'Resource Type');
define('_AM_XOOPSTUBE_RES_USER', 'User');
define('_AM_XOOPSTUBE_RES_CREATEF', 'Add Resource');
define('_AM_XOOPSTUBE_RES_MODIFYF', 'Modify Resource');
define('_AM_XOOPSTUBE_RES_NAMEF', 'Resource name:');
define('_AM_XOOPSTUBE_RES_DESCF', 'Resource description:');
define('_AM_XOOPSTUBE_RES_URLF', 'Resource URL:');
define('_AM_XOOPSTUBE_RES_ITEMIDF', 'Resource Item ID:');
define('_AM_XOOPSTUBE_RES_INFOTEXT', '<ul><li>New resources can be added, edited or removed easily via this form.</li>
    <li>List all resources linked to a video</li>
    <li>Modify resource name and description</li></ul>
    ');
define('_AM_XOOPSTUBE_LISTBROKEN', 'Displays Videos that are possibly broken. NB: These results may not be accurate and should be taken as a rough guide.<br><br>Please check the video does exist first before any action taken.<br><br>');
define('_AM_XOOPSTUBE_PINGTIMES', 'Displays the first estimated round ping time to each video.<br><br>NB: These results may not be accurate and should be taken as a rough guide.<br><br>');

define('_AM_XOOPSTUBE_NO_FORUM', 'No forum Selected');

define('_AM_XOOPSTUBE_PERM_RATEPERMISSIONS', 'Rate Permissions');
define('_AM_XOOPSTUBE_PERM_RATEPERMISSIONS_TEXT', 'Select the groups that can rate a video in the selected categories.');

define('_AM_XOOPSTUBE_PERM_AUTOPERMISSIONS', 'Auto Approve Videos');
define('_AM_XOOPSTUBE_PERM_AUTOPERMISSIONS_TEXT', 'Select the groups that will have new videos auto approved without admin intervention.');

define('_AM_XOOPSTUBE_PERM_SPERMISSIONS', 'Submitter Permissions');
define('_AM_XOOPSTUBE_PERM_SPERMISSIONS_TEXT', 'Select the groups who can submit new videos to selected categories.');

define('_AM_XOOPSTUBE_PERM_APERMISSIONS', 'Moderator Groups');
define('_AM_XOOPSTUBE_PERM_APERMISSIONS_TEXT', 'Select the groups who have moderator privligages for the selected categories.');

define('_AM_XOOPSTUBE_TIME', 'Time:');
define('_AM_XOOPSTUBE_KEYWORDS', 'Keywords:');
define('_AM_XOOPSTUBE_KEYWORDS_NOTE', 'Keywords should be seperated with a comma (keyword1, keyword2, keyword3)');
define('_AM_XOOPSTUBE_VIDEO_META_DESCRIPTION', 'Meta Description');
define('_AM_XOOPSTUBE_VIDEO_META_DESCRIPTION_DSC', 'In order to help Search Engines, you can customize the meta description you would like to use for this article. if you leave this field empty when creating a category, it will automatically be populated with the Summary field of this article.');

define('_AM_XOOPSTUBE_VIDSOURCE', 'Video source:');
define('_AM_XOOPSTUBE_VIDSOURCE2', 'Video source');
define('_AM_XOOPSTUBE_YOUTUBE', 'YouTube');
define('_AM_XOOPSTUBE_METACAFE', 'MetaCafe');
define('_AM_XOOPSTUBE_IFILM', 'Spike');
define('_AM_XOOPSTUBE_GOOGLEVIDEO', 'Google Video');
define('_AM_XOOPSTUBE_MYSPAVETV', 'MySpace TV');
define('_AM_XOOPSTUBE_PHOTOBUCKET', 'Photobucket');
define('_AM_XOOPSTUBE_DAILYMOTION', 'DailyMotion');

// Version 1.04 RC-1
define('_AM_XOOPSTUBE_BLIPTV', 'Blip.tv');
define('_AM_XOOPSTUBE_XOOPSTUBE', 'XoopsTube');
define('_AM_XOOPSTUBE_ICO_EXPIRE', 'Expired');
define('_AM_XOOPSTUBE_MXOOPSTUBE', 'Add XoopsTube Clip');
define('_AM_XOOPSTUBE_VIDEO_CATVIDEOIMG', 'Video images');
define('_AM_XOOPSTUBE_VIDEO_FCATVIDEOIMG', 'Video image path');
define('_AM_XOOPSTUBE_VUPLOAD_VIDEOEXIST', 'Video already exists');
define('_AM_XOOPSTUBE_VUPLOAD_VIDEOUPLOAD', 'Video uploaded');
define('_AM_XOOPSTUBE_VUPLOAD_NOVIDEOEXIST', 'No Video exists');
define('_AM_XOOPSTUBE_VUPLOAD_FILEDELETED', 'Video deleted');
define('_AM_XOOPSTUBE_VUPLOAD_FILEERRORDELETE', 'Error');
define('_AM_XOOPSTUBE_VUPLOAD_NOFILEERROR', 'No File');
define('_AM_XOOPSTUBE_VUPLOAD_DELETEFILE', 'Delete video file');
define('_AM_XOOPSTUBE_VUPLOAD_CATVIDEO', 'Video files');
define('_AM_XOOPSTUBE_VUPLOAD_FCATVIDEO', 'Video upload directory');
define('_AM_XOOPSTUBE_VUPLOADS', 'Video Upload');
define('_AM_XOOPSTUBE_VUPLOAD_FUPLOADPATH', 'Video upload path:');
define('_AM_XOOPSTUBE_VUPLOAD_FUPLOADURL', 'Video upload url:');
define('_AM_XOOPSTUBE_VUPLOAD_FUPLOADVIDEOTO', 'Upload video: ');
define('_AM_XOOPSTUBE_VUPLOAD_FOLDERSELECTION', 'Select upload destination');
define('_AM_XOOPSTUBE_VUPLOAD_FUPLOADVIDEO', 'Video to upload');
define('_AM_XOOPSTUBE_VUPLOAD_FSHOWSELECTEDFILE', 'Select a video file');
define('_AM_XOOPSTUBE_VUPLOAD_FSHOWSELECTEDFILEDSC', 'Video file must be of type FLV');
define('_AM_XOOPSTUBE_BDELETEVIDEO', 'Delete video');
define('_AM_XOOPSTUBE_NOVIDEO', 'Show No Video');
define('_AM_XOOPSTUBE_NOIMAGE', 'Show No Image');
define('_AM_XOOPSTUBE_NOSELECT', 'No Selection');
define('_AM_XOOPSTUBE_NOFILESELECT', 'No Selected File');
define('_AM_XOOPSTUBE_XOOPSTUBEVIDEO', 'XoopsTube Video File:');
define('_AM_XOOPSTUBE_WARNINSTALL4', "<span style='font-weight: bold;'>WARNING:</span> <span style='text-decoration: underline;'>Folder</span> %s is not writeable. <br>This folder needs to be writeable (CHMOD 777) for XoopsTube.");
define('_AM_XOOPSTUBE_CATSPONSOR', 'Select Category Sponsor:');
define('_AM_XOOPSTUBE_CATSPONSORDSC', 'If you select a Client the banner id from the form below will not be saved!');
define('_AM_XOOPSTUBE_BANNER', 'Banner');
define('_AM_XOOPSTUBE_FBANNER', 'Banner');
define('_AM_XOOPSTUBE_BANNERID', 'Select Banner ID:');
define('_AM_XOOPSTUBE_BANNERIDDSC', 'If you have selected a Client in the form above the banner id will not be saved!');
// Uploader class
define('_AM_XOOPSTUBE_READWRITEERROR', 'You either did not choose a file to upload or the server has insufficient read/writes to upload this file!');
define('_AM_XOOPSTUBE_INVALIDFILESIZE', 'Invalid File Size');
define('_AM_XOOPSTUBE_FILENAMEEMPTY', 'Filename Is Empty');
define('_AM_XOOPSTUBE_NOFILEUPLOAD', 'No file uploaded, this is a error');
define('_AM_XOOPSTUBE_UPLOADERRORZERO', 'There was a problem with your upload. Error: 0');
define('_AM_XOOPSTUBE_UPLOADERRORONE', 'The file you are trying to upload is too big. Error: 1');
define('_AM_XOOPSTUBE_UPLOADERRORTWO', 'The file you are trying to upload is too big. Error: 2');
define('_AM_XOOPSTUBE_UPLOADERRORTHREE', 'The file you are trying upload was only partially uploaded. Error: 3');
define('_AM_XOOPSTUBE_UPLOADERRORFOUR', 'No file selected for upload. Error: 4');
define('_AM_XOOPSTUBE_UPLOADERRORFIVE', 'No file selected for upload. Error: 5');
define('_AM_XOOPSTUBE_NOUPLOADDIR', 'Upload directory not set');
define('_AM_XOOPSTUBE_FAILOPENDIR', 'Failed opening directory: ');
define('_AM_XOOPSTUBE_FAILOPENDIRWRITEPERM', 'Failed opening directory with write permission: ');
define('_AM_XOOPSTUBE_FILESIZEMAXSIZE', 'File Size: %u. Maximum Size Allowed: %u');
define('_AM_XOOPSTUBE_FILESIZEMAXWIDTH', 'File width: %u. Maximum width allowed: %u');
define('_AM_XOOPSTUBE_FILESIZEMAXHEIGHT', 'File height: %u. Maximum height allowed: %u');
define('_AM_XOOPSTUBE_MIMENOTALLOW', 'MIME type not allowed: ');
define('_AM_XOOPSTUBE_FAILEDUPLOADING', 'Failed uploading file: ');
define('_AM_XOOPSTUBE_ALREADYEXISTTRYAGAIN', ' already exists on the server. Please rename this file and try again.<br>');
define('_AM_XOOPSTUBE_ERRORSRETURNUPLOAD', '<h4>Errors Returned While Uploading</h4>');
define('_AM_XOOPSTUBE_DOESNOTEXIST', ' does not exist!');

// Version 1.04 RC-2
define('_AM_XOOPSTUBE_CLIPFISH', 'ClipFish');
define('_AM_XOOPSTUBE_LIVELEAK', 'LiveLeak');
define('_AM_XOOPSTUBE_MAKTOOB', 'Maktoob');
define('_AM_XOOPSTUBE_VEOH', 'Veoh');
define('_AM_XOOPSTUBE_FILE', 'File ');
define('_AM_XOOPSTUBE_INFORMATION', 'Video Information');
define('_AM_XOOPSTUBE_VIDEO_DLVIDIDDSC', 'Take over red part as given in the examples below');
define('_AM_XOOPSTUBE_VIDEO_VIEWS', 'Views: ');
define('_AM_XOOPSTUBE_ERROR_CATISCAT', 'You can NOT set a category as a sub-category of itself!');

// Version 1.04 RC-3
define('_AM_XOOPSTUBE_MOD_VIDID', 'Video id-code: ');
define('_AM_XOOPSTUBE_MOD_VIDSOURCE', 'Video source: ');
define('_AM_XOOPSTUBE_MOD_TIME', 'Time: ');
define('_AM_XOOPSTUBE_MOD_KEYWORDS', 'Keywords: ');
define('_AM_XOOPSTUBE_MOD_ITEM_TAG', 'Tags: ');
define('_AM_XOOPSTUBE_MOD_PICURL', 'Picture url: ');
define('_AM_XOOPSTUBE_IPAGE_SHOWLATEST', 'Show Latest Listings?');
define('_AM_XOOPSTUBE_IPAGE_LATESTTOTAL', 'How many videos to show?');
define('_AM_XOOPSTUBE_IPAGE_LATESTTOTAL_DSC', '0 Turns this option off.');

// Version 1.05 RC-1
define('_AM_XOOPSTUBE_VIMEO', 'Vimeo');
define('_AM_XOOPSTUBE_MEGAVIDEO', 'Megavideo');
define('_AM_XOOPSTUBE_VIDDLER', 'Viddler');
define('_AM_XOOPSTUBE_CATTITLE', 'Category');

// 1.06 Beta 2

//Definitions for FileCheck
define('_AM_XOOPSTUBE_FILECHECKS', 'Information');
define('_AM_XOOPSTUBE_UPLOADMAX', 'Maximum upload size: ');
define('_AM_XOOPSTUBE_POSTMAX', 'Maximum post size: ');
define('_AM_XOOPSTUBE_UPLOADS', 'Uploads allowed: ');
define('_AM_XOOPSTUBE_UPLOAD_ON', 'On');
define('_AM_XOOPSTUBE_UPLOAD_OFF', 'Off');
define('_AM_XOOPSTUBE_GDIMGSPPRT', 'GD image lib supported: ');
define('_AM_XOOPSTUBE_GDIMGON', 'Yes');
define('_AM_XOOPSTUBE_GDIMGOFF', 'No');
define('_AM_XOOPSTUBE_GDIMGVRSN', 'GD image lib version: ');
define('_AM_XOOPSTUBE_UPDATE_SUCCESS', 'Updated Successfully');

define('_AM_XOOPSTUBE_WARNING', 'Warning: ');
define('_AM_XOOPSTUBE_NOT_EXISTS', 'does NOT exist');
define('_AM_XOOPSTUBE_UNABLE_TO_WRITE', 'I am unable to write to: ');

define('_AM_XOOPSTUBE_TOGGLE_OFFLINE_SUCCESS', 'Video was set Offline');
define('_AM_XOOPSTUBE_TOGGLE_ONLINE_SUCCESS', 'Video was set Online');
define('_AM_XOOPSTUBE_TOGGLE_FAILED', 'Video Status Toggle Failed: ');
define('_AM_XOOPSTUBE_TOGGLE', 'Toggle Value ');

// missing definitions
/*
_MD_XOOPSTUBE_COUNTRYLTOH
_MD_XOOPSTUBE_COUNTRYHTOL

//Xoopstube\admin\blockform.php
_AM_NAME
_AM_BLKTYPE
_AM_SBLEFT
_AM_SBRIGHT
_AM_CBLEFT
_AM_CBRIGHT
_AM_CBCENTER
_AM_CBBOTTOMLEFT,
_AM_CBBOTTOMRIGHT,
_AM_CBBOTTOM,
_AM_WEIGHT
_AM_VISIBLE,*/

// Navigation
define('_AM_XOOPSTUBE_BLOCKS_ADMIN', 'Blocks Administration');
define('_AM_XOOPSTUBE_BLOCKS_MANAGMENT', 'Manage');
define('_AM_XOOPSTUBE_BLOCKS_ADDBLOCK', 'Add a new block');
define('_AM_XOOPSTUBE_BLOCKS_EDITBLOCK', 'Edit a block');
define('_AM_XOOPSTUBE_BLOCKS_CLONEBLOCK', 'Clone a block');

define('_AM_XOOPSTUBE_TOPPAGE', 'Top Page');
define('_AM_XOOPSTUBE_ALLPAGES', 'All Pages');
//define("_AM_XOOPSTUBE_BADMIN","Page");
//define("_AM_XOOPSTUBE_TITLE","Title");
//define("_AM_XOOPSTUBE_SIDE","Side");
//define("_AM_XOOPSTUBE_WEIGHT","Weight");
//define("_AM_XOOPSTUBE_VISIBLE","Visible");
define('_AM_XOOPSTUBE_VISIBLEIN', 'Visible In');
//define("_AM_XOOPSTUBE_ACTION","Action");
define('_AM_XOOPSTUBE_ERROR403', 'You are not allowed to view this page!');

//directories
define('_AM_XOOPSTUBE_AVAILABLE', "<span style='color : green;'>Available. </span>");
define('_AM_XOOPSTUBE_NOTAVAILABLE', "<span style='color : red;'>is not available. </span>");
define('_AM_XOOPSTUBE_NOTWRITABLE', "<span style='color : red;'>" . ' should have permission ( %1$d ), but it has ( %2$d )' . '</span>');
define('_AM_XOOPSTUBE_CREATETHEDIR', 'Create it');
define('_AM_XOOPSTUBE_SETMPERM', 'Set the permission');

define('_AM_XOOPSTUBE_DIRCREATED', 'The directory has been created');
define('_AM_XOOPSTUBE_DIRNOTCREATED', 'The directory can not be created');
define('_AM_XOOPSTUBE_PERMSET', 'The permission has been set');
define('_AM_XOOPSTUBE_PERMNOTSET', 'The permission can not be set');
define('_AM_XOOPSTUBE_VIDEO_EXPIREWARNING', 'The publishing date is after expiration date!!!');

define('_AM_XOOPSTUBE_ADD_SAMPLEDATA', 'Import Sample Data (will delete ALL current data)');
define('_AM_XOOPSTUBE_SAMPLEDATA_SUCCESS', 'Sample Date uploaded successfully');
