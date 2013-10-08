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


define('_MD_XTUBE_NOVIDEO', "This video does not exist!");
define('_MD_XTUBE_SUBCATLISTING', "Category Listing");
define('_MD_XTUBE_ISADMINNOTICE', "Webmaster: There is a problem with this image.");
define('_MD_XTUBE_THANKSFORINFO', "Thank you for your submission.<br />You will be notified once your request has be approved by the Webmaster.");
define('_MD_XTUBE_ISAPPROVED', "Thank-you for your submission.<br />Your request has been approved and will now appear in our listing.");
define('_MD_XTUBE_THANKSFORHELP', "Thank you for helping us to maintain the integrity of this directory.");
define('_MD_XTUBE_FORSECURITY', "For security reasons your username and IP address will be temporarily recorded.");
define('_MD_XTUBE_DESCRIPTION', "Description");
define('_MD_XTUBE_SUBMITCATHEAD', "Submit video Form");
define('_MD_XTUBE_MAIN', "Home");
define('_MD_XTUBE_POPULAR', "Popular");
define('_MD_XTUBE_NEWTHISWEEK', "New this week");
define('_MD_XTUBE_UPTHISWEEK', "Updated this week");
define('_MD_XTUBE_POPULARITYLTOM', "Popularity (Least to Most Views)");
define('_MD_XTUBE_POPULARITYMTOL', "Popularity (Most to Least Views)");
define('_MD_XTUBE_TITLEATOZ', "Title (A to Z)");
define('_MD_XTUBE_TITLEZTOA', "Title (Z to A)");
define('_MD_XTUBE_DATEOLD', "Date (Old videos Listed First)");
define('_MD_XTUBE_DATENEW', "Date (New videos Listed First)");
define('_MD_XTUBE_RATINGLTOH', "Rating (Lowest Score to Highest Score)");
define('_MD_XTUBE_RATINGHTOL', "Rating (Highest Score to Lowest Score)");
define('_MD_XTUBE_DESCRIPTIONC', "Description: ");
define('_MD_XTUBE_CATEGORYC', "Category: ");
define('_MD_XTUBE_VERSION', "Version");
define('_MD_XTUBE_SUBMITDATE', "Submitted");
define('_MD_XTUBE_VIDEOHITS', "<span style='font-weight: bold;'>Views:</span> %s");
define('_MD_XTUBE_PUBLISHERC', "Publisher: ");
define('_MD_XTUBE_RATINGC', "Rating: ");
define('_MD_XTUBE_ONEVOTE', "1 Vote");
define('_MD_XTUBE_NUMVOTES', "%s Votes");
define('_MD_XTUBE_RATETHISFILE', "Rate this video");
define('_MD_XTUBE_MODIFY', "Modify");
define('_MD_XTUBE_REPORTBROKEN', "Report Broken");
define('_MD_XTUBE_BROKENREPORT', "Report Broken Video");
define('_MD_XTUBE_SUBMITBROKEN', "Submit");
define('_MD_XTUBE_BEFORESUBMIT', "Before submitting a broken video request, please check that the actual source of the video you're about to report as broken, is no longer there.");
define('_MD_XTUBE_TELLAFRIEND', "Recommend");
define('_MD_XTUBE_EDIT', "Edit");
define('_MD_XTUBE_THEREARE', "There are <span style='font-weight: bold;'>%s</span> <em>Categories</em> and <span style='font-weight: bold;'>%s</span> <em>Videos</em> listed");
define('_MD_XTUBE_THEREIS', "There is <span style='font-weight: bold;'>%s</span> <em>Category</em> and <span style='font-weight: bold;'>%s</span> <em>Videos</em> listed");
define('_MD_XTUBE_LATESTLIST', "Latest Listings");
define('_MD_XTUBE_FILETITLE', "Video title: ");
define('_MD_XTUBE_DLVIDID', "Video id-code: ");
define('_MD_XTUBE_VIDEO_DLVIDID_NOTE', "
<span style='font-size: small;'><span style='font-weight: bold;'>YouTube:</span> http://www.youtube.com/watch?v=<span style='color: #FF0000;'>IsOtq-qCqZ4</span><br />
<span style='font-weight: bold;'>MetaCafe:</span> http://www.metacafe.com/watch/<span style='color: #FF0000;'>191543/sperm_whale_encounters_underwater_robot</span>/<br />
<span style='font-weight: bold;'>SPIKE/ifilm:</span> http://www.ifilm.com/video/<span style='color: #FF0000;'>2718605</span><br />
<span style='font-weight: bold;'>Photobucket:</span> http://i39.photobucket.com/albums/<span style='color: #FF0000;'>e168/vailtom/th_BigSquid</span>.jpg<br />
<span style='font-weight: bold;'>Google Video:</span> http://video.google.com/videoplay?docid=<span style='color: #FF0000;'>4761076111111865377</span>&q=rov&total=913&start=...<br />
<span style='font-weight: bold;'>MySpace TV:</span> http://vids.myspace.com/index.cfm?fuseaction=vids.individual&videoid=<span style='color: #FF0000;'>13171141</span><br />
<span style='font-weight: bold;'>DailyMotion:</span> http://www.dailymotion.com/video/<span style='color: #FF0000;'>x40bhg</span>_blondesecretary_fun<br />
<span style='font-weight: bold;'>Blip.tv </span>embed code<span style='font-weight: bold;'>:</span> http://blip.tv/play/<span style='color: #FF0000;'>Abe6EwA</span> ...<br />
<span style='font-weight: bold;'>ClipFish:</span> http://www.clipfish.de/player.php?videoid=<span style='color: #FF0000;'>MTg1NTYyfDM1Ng</span>%3D%3D<br />
<span style='font-weight: bold;'>LiveLeak:</span> http://www.liveleak.com/view?i=<span style='color: #FF0000;'>a59_1205566630</span><br />
<span style='font-weight: bold;'>Maktoob:</span> file=http://<span style='color: #FF0000;'>m003.maktoob.com/files/23/42/95531c25b690e48a3d69966b6a33b8d2/video/131102</span>.flv (from embed-code)<br />
<span style='font-weight: bold;'>Veoh:</span> http://www.veoh.com/videos/<span style='color: #FF0000;'>v15069875yApCz7r3</span>?jsonParams=...<br />
<span style='font-weight: bold;'>Vimeo:</span> http://www.vimeo.com/<span style='color: #FF0000;'>2246499</span><br />
<span style='font-weight: bold;'>Megavideo:</span> http://www.megavideo.com/?v=<span style='color: #FF0000;'>J6YSRE0T</span><br />
<span style='font-weight: bold;'>Viddler:</span> http://www.viddler.com/player/<span style='color: #FF0000;'>d32d2b94</span>/ (see embed code)</span>");
define('_MD_XTUBE_VIDEO_PICURL', "Picture url: ");
define('_MD_XTUBE_VIDEO_PICURLNOTE', "<span style='font-size: small;'>Picture url when source is Google Video, MySpace TV, DailyMotion, Blip.tv, ClipFish, LiveLeak, Veoh, Vimeo, Viddler or Maktoob</span>");
define('_MD_XTUBE_VIDSOURCE', "Video source:");
define('_MD_XTUBE_YOUTUBE', "YouTube");
define('_MD_XTUBE_METACAFE', "MetaCafe");
define('_MD_XTUBE_IFILM', "Spike");
define('_MD_XTUBE_GOOGLEVIDEO', "Google Video");
define('_MD_XTUBE_MYSPAVETV', "MySpace TV");
define('_MD_XTUBE_PHOTOBUCKET', "Photobucket");
define('_MD_XTUBE_DAILYMOTION', "DailyMotion");
define('_MD_XTUBE_VIDEO_PUBLISHER', "Video Publisher Name:");
define('_MD_XTUBE_HOMEPAGEC', "Home Page: ");
define('_MD_XTUBE_NOTSPECIFIED', "Not Specified");
define('_MD_XTUBE_PUBLISHER', "Publisher");
define('_MD_XTUBE_UPDATEDON', "Updated On");
define('_MD_XTUBE_PRICEFREE', "Free");
define('_MD_XTUBE_VIEWDETAILS', "View Video Clip");
define('_MD_XTUBE_OPTIONS', "Options: ");
define('_MD_XTUBE_NOTIFYAPPROVE', "Notify me when this video is approved");
define('_MD_XTUBE_VOTEAPPRE', "Your vote is appreciated.");
define('_MD_XTUBE_THANKYOU', "Thank you for taking the time to vote here at %s"); // %s is your site name
define('_MD_XTUBE_VOTEONCE', "Please do not vote for the same resource more than once.");
define('_MD_XTUBE_RATINGSCALE', "The scale is 1 - 10, with 1 being poor and 10 being excellent.");
define('_MD_XTUBE_BEOBJECTIVE', "Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
define('_MD_XTUBE_DONOTVOTE', "Do not vote for your own resource.");
define('_MD_XTUBE_RATEIT', "Rate It!");
define('_MD_XTUBE_INTFILEFOUND', "Here is a good video to look at %s"); // %s is your site name
define('_MD_XTUBE_RANK', "Rank");
define('_MD_XTUBE_CATEGORY', "Category");
define('_MD_XTUBE_HITS', "Views");
define('_MD_XTUBE_RATING', "Rating");
define('_MD_XTUBE_VOTE', "Vote");
define('_MD_XTUBE_SORTBY', "Sort by:");
define('_MD_XTUBE_TITLE', "Title");
define('_MD_XTUBE_DATE', "Date");
define('_MD_XTUBE_POPULARITY', "Popularity");
define('_MD_XTUBE_TOPRATED', "Rating");
define('_MD_XTUBE_CURSORTBY', "Videos currently sorted by: %s");
define('_MD_XTUBE_CANCEL', "Cancel");
define('_MD_XTUBE_ALREADYREPORTED', "You have already submitted a broken report for this resource.");
define('_MD_XTUBE_MUSTREGFIRST', "Sorry, you don't have the permission to perform this action.<br />Please register or login first!");
define('_MD_XTUBE_NORATING', "No rating selected.");
define('_MD_XTUBE_VOTEFORTITLE', "Rate This Video: ");
define('_MD_XTUBE_CANTVOTEOWN', "You cannot vote on the resource you submitted.<br />All votes are logged and reviewed.");
define('_MD_XTUBE_SUBMITVIDEO', "Submit video");
define('_MD_XTUBE_SUB_SNEWMNAMEDESC', "<ul><li>All new videos are subject to validation and may take up to 24 hours before they appear in our listing.</li><li>We reserve the rights to refuse any submitted video or change the content without approval.</li></ul>");
define('_MD_XTUBE_MAINLISTING', "Main Category Listings");
define('_MD_XTUBE_LASTWEEK', "Last Week");
define('_MD_XTUBE_LAST30DAYS', "Last 30 Days");
define('_MD_XTUBE_1WEEK', "1 Week");
define('_MD_XTUBE_2WEEKS', "2 Weeks");
define('_MD_XTUBE_30DAYS', "30 Days");
define('_MD_XTUBE_SHOW', "Show");
define('_MD_XTUBE_DAYS', "days");
define('_MD_XTUBE_NEWlinks', "New videos");
define('_MD_XTUBE_TOTALNEWVIDEOS', "Total new videos");
define('_MD_XTUBE_DTOTALFORLAST', "Total new videos for last");
define('_MD_XTUBE_AGREE', "I Agree");
define('_MD_XTUBE_DOYOUAGREE', "Do you agree to the above terms?");
define('_MD_XTUBE_DISCLAIMERAGREEMENT', "Disclaimer");
define('_MD_XTUBE_DUPLOADSCRSHOT', "Upload Screenshot Image:");
define('_MD_XTUBE_RESOURCEID', "Resource id#: ");
define('_MD_XTUBE_REPORTER', "Original Reporter: ");
define('_MD_XTUBE_DATEREPORTED', "Date Reported: ");
define('_MD_XTUBE_RESOURCEREPORTED', "Resource Reported Broken");
define('_MD_XTUBE_RESOURCEREPORTED2', "This video has been already reported as broken, we are working on a fix");
define('_MD_XTUBE_BROWSETOTOPIC', "<span style='font-weight: bold;'>Browse videos by alphabetical listing</span>");
define('_MD_XTUBE_WEBMASTERACKNOW', "Broken Report Acknowledged: ");
define('_MD_XTUBE_WEBMASTERCONFIRM', "Broken Report Confirmed: ");
define('_MD_XTUBE_ERRORSENDEMAIL', "Broken Report Confirmed But Error sending notification email to webmaster.");

define('_MD_XTUBE_DELETE', "Delete");
define('_MD_XTUBE_DISPLAYING', "Displayed by: ");
define('_MD_XTUBE_LEGENDTEXTNEW', "New Today");
define('_MD_XTUBE_LEGENDTEXTNEWTHREE', "New 3 Days");
define('_MD_XTUBE_LEGENDTEXTTHISWEEK', "New This Week");
define('_MD_XTUBE_LEGENDTEXTNEWLAST', "Over 1 Week");
define('_MD_XTUBE_THISFILEDOESNOTEXIST', "Error: This video does not exist!");
define('_MD_XTUBE_BROKENREPORTED', "Broken video Reported");

define('_MD_XTUBE_REV_SNEWMNAMEDESC', "Please completely fill out the form below, and we'll add your review as soon as possible.<br /><br />
Thank you for taking the time to submit your opinion. We want to give our users a possibility to find quality software faster.<br /><br />
All reviews will be reviewed by one of our webmasters before they are put up on the web site.");
define('_MD_XTUBE_ISNOTAPPROVED', "Your submission has to be approved by a moderator first.");
define('_MD_XTUBE_HOMEPAGETITLEC', "Home Page Title: ");
define('_MD_XTUBE_SCREENSHOT', "Screenshot:");
define('_MD_XTUBE_SCREENSHOTCLICK', "Display full image");
define('_MD_XTUBE_OTHERBYUID', "Other videos by: ");
define('_MD_XTUBE_NOOTHERBYUID', "No other videos by: ");
define('_MD_XTUBE_LINKTIMES', "video Times: ");
define('_MD_XTUBE_MAINTOTAL', "Total Videos: ");
define('_MD_XTUBE_VIDEONOW', "View video");
define('_MD_XTUBE_PAGES', "<span style='font-weight: bold;'>Pages</span>");
define('_MD_XTUBE_RATEDRESOURCE', "Rated Resource");
define('_MD_XTUBE_SUBMITTER', "Submitter");
define('_MD_XTUBE_ERROR', "Error Updating Database: Information not saved");
define('_MD_XTUBE_COPYRIGHT', "copyright");
define('_MD_XTUBE_INFORUM', "Discuss In Forum");
// added frankblack
define('_MD_XTUBE_NOTALLOWESTOSUBMIT', "You are not allowed to submit videos");
define('_MD_XTUBE_INFONOSAVEDB', "Information not saved to database: <br /><br />");

define('_MD_XTUBE_NEWLAST', "New Submitted Before Last Week");
define('_MD_XTUBE_NEWTHIS', "New Submitted Within This week");
define('_MD_XTUBE_THREE', "New Submitted Within Last Three days");
define('_MD_XTUBE_TODAY', "New Submitted Today");
define('_MD_XTUBE_NO_FILES', "No Videos Yet");

define('_MD_XTUBE_NOPERMISSIONTOPOST', "You do not have permission to post in this category.");
define('_MD_XTUBE_RESOURCES', "Resources");

define('_MD_XTUBE_PUBLISHDATE', "Published");
define('_MD_XTUBE_APPROVE', "Approve");
define('_MD_XTUBE_MODERATOR_OPTIONS', "Moderator Options");

// added by McDonald
define('_MD_XTUBE_TIME', "Time:");
define('_MD_XTUBE_TIMEB', "<span style='font-weight: bold;'>Time:</span>");
define('_MD_XTUBE_KEYWORDS', "Keywords:");
define('_MD_XTUBE_KEYWORDS_NOTE', "Keywords should be separated with a comma (keyword1, keyword2, keyword3)");
define('_MD_XTUBE_NOVIDEOLOAD', "Thanks for your post!");
define('_MD_XTUBE_LINKID', "Video id");
define('_MD_XTUBE_ADDTO', "Add to: ");
define('_MD_XTUBE_NEWVIDEOS', "Latest Videos");
define('_MD_XTUBE_TAKINGUBACK', "Taking you back.");
define('_MD_XTUBE_ADMINSECTION', "Administrative Section");

// Version 1.04 RC-1
define('_MD_XTUBE_BLIPTV', "Blip.tv");

// Version 1.04 RC-2
define('_MD_XTUBE_CLIPFISH', "ClipFish");
define('_MD_XTUBE_LIVELEAK', "LiveLeak");
define('_MD_XTUBE_MAKTOOB', "Maktoob");
define('_MD_XTUBE_VEOH', "Veoh");
define('_MD_XTUBE_STOPIT', "STOP IT YOU FOOL!!");
define('_MD_XTUBE_VIDEO_DLVIDIDDSC', "Take over red part as given in the examples below");

//Version 1.04 RC-3
define('_MD_XTUBE_MODIFYNOTALLOWED', "You're not allowed to modify others videos!!");

// Version 1.05 RC-1
define('_MD_XTUBE_VIMEO', "Vimeo");
define('_MD_XTUBE_MEGAVIDEO', "Megavideo");
define('_MD_XTUBE_VIDDLER', "Viddler");
define('_MD_XTUBE_UPDATED', "Updated!");
define('_MD_XTUBE_NEW', "New!");
define('_MD_XTUBE_XOOPSTUBE', "XoopsTube");

function getXtubeAlphabet()
{
    $xtubeAlphabet = array(
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z'
    );

    return $xtubeAlphabet;
}
