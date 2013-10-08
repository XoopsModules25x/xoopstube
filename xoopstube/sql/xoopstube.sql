# MySQL file for XoopsTube
# Version: 1.0.1
# Author: McDonald

#
# Table structure for table 'xoopstube_altcat'
#

CREATE TABLE `xoopstube_altcat` (
  `lid` int(11) unsigned NOT NULL default '0',
  `cid` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`lid`,`cid`)
) ENGINE=MyISAM COMMENT='XoopsTube by McDonald' ;

#
# Table structure for table 'xoopstube_broken'
#

CREATE TABLE `xoopstube_broken` (
  `reportid` int(5) NOT NULL auto_increment,
  `lid` int(11) NOT NULL default '0',
  `sender` int(11) NOT NULL default '0',
  `ip` varchar(20) NOT NULL default '',
  `date` varchar(11) NOT NULL default '0',
  `confirmed` int(1) NOT NULL default '0',
  `acknowledged` int(1) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`reportid`),
  KEY `lid` (`lid`),
  KEY `sender` (`sender`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM COMMENT='XoopsTube by McDonald' AUTO_INCREMENT=1 ;

#
# Table structure for table 'xoopstube_cat'
#

CREATE TABLE `xoopstube_cat` (
  cid int(5) unsigned NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  imgurl varchar(150) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  total int(11) NOT NULL default '0',
  spotlighttop int(11) NOT NULL default '0',
  spotlighthis int(11) NOT NULL default '0',
  nohtml int(1) NOT NULL default '0',
  nosmiley int(1) NOT NULL default '0',
  noxcodes int(1) NOT NULL default '0',
  noimages int(1) NOT NULL default '0',
  nobreak int(1) NOT NULL default '1',
  weight int(11) NOT NULL default '0',
  client_id int(5) NOT NULL default '0',
  banner_id int(5) NOT NULL default '0',
  PRIMARY KEY  (cid),
  KEY pid (pid)
) ENGINE=MyISAM COMMENT='XoopsTube by McDonald' AUTO_INCREMENT=1 ;

#
# Table structure for table 'xoopstube_indexpage'
#

CREATE TABLE `xoopstube_indexpage` (
  `indeximage` varchar(255) NOT NULL default 'blank.gif',
  `indexheading` varchar(255) NOT NULL default 'XoopsTube',
  `indexheader` text NOT NULL,
  `indexfooter` text NOT NULL,
  `nohtml` tinyint(8) NOT NULL default '1',
  `nosmiley` tinyint(8) NOT NULL default '1',
  `noxcodes` tinyint(8) NOT NULL default '1',
  `noimages` tinyint(8) NOT NULL default '1',
  `nobreak` tinyint(4) NOT NULL default '0',
  `indexheaderalign` varchar(25) NOT NULL default 'left',
  `indexfooteralign` varchar(25) NOT NULL default 'center',
  lastvideosyn tinyint(1) NOT NULL default '0',
  lastvideostotal varchar(5) NOT NULL default '5',
  FULLTEXT KEY `indexheading` (`indexheading`),
  FULLTEXT KEY `indexheader` (`indexheader`),
  FULLTEXT KEY `indexfooter` (`indexfooter`)
) ENGINE=MyISAM COMMENT='XoopsTube by McDonald' ;



INSERT INTO `xoopstube_indexpage` (`indeximage`,`indexheading`,`indexheader`,`indexfooter`,`nohtml`,`nosmiley`,`noxcodes`,`noimages`,`nobreak`,`indexheaderalign`,`indexfooteralign`) VALUES ('logo-en.png','','Please report any unavailable video clips by using the [i]Report Broken[/i] option. Thanks!','<a href=\"http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&promoid=BIOW\" target=\"_blank\"><img src=\"images/get_flash_player.png\" alt=\"Get Adobe Flash Player\" border=\"0\" /></a>','0','0','0','0','1','left','left');


#
# Table structure for table 'xoopstube_mod'
#

CREATE TABLE `xoopstube_mod` (
  `requestid` int(11) NOT NULL auto_increment,
  `lid` int(11) unsigned NOT NULL default '0',
  `cid` int(5) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `vidid` varchar(255) NOT NULL default '',
  `screenshot` varchar(255) NOT NULL default '',
  `submitter` int(11) NOT NULL default '0',
  `publisher` text NOT NULL,
  `status` tinyint(2) NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `hits` int(11) unsigned NOT NULL default '0',
  `rating` double(6,4) NOT NULL default '0.0000',
  `votes` int(11) unsigned NOT NULL default '0',
  `comments` int(11) unsigned NOT NULL default '0',
  `vidsource` int(11) unsigned NOT NULL default '0',
  `published` int(10) NOT NULL default '0',
  `expired` int(10) NOT NULL default '0',
  `updated` int(11) NOT NULL default '0',
  `offline` tinyint(1) NOT NULL default '0',
  `description` text NOT NULL,
  `modifysubmitter` int(11) NOT NULL default '0',
  `requestdate` int(11) NOT NULL default '0',
  `vidrating` tinyint(1) NOT NULL default '0',
  `time` varchar(7) NOT NULL default '0:00:00',
  `keywords` varchar(255) NOT NULL default '',
  `item_tag` text NOT NULL default '',
  `picurl` text NOT NULL default '',
  PRIMARY KEY  (`requestid`)
) ENGINE=MyISAM COMMENT='XoopsTube by McDonald' AUTO_INCREMENT=1 ;


#
# Table structure for table 'xoopstube_videos'
#

CREATE TABLE `xoopstube_videos` (
  `lid` int(11) unsigned NOT NULL auto_increment,
  `cid` int(5) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `vidid` text NOT NULL,
  `screenshot` varchar(255) NOT NULL default '',
  `submitter` int(11) NOT NULL default '0',
  `publisher` varchar(255) NOT NULL default '',
  `status` tinyint(2) NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `hits` int(11) unsigned NOT NULL default '0',
  `rating` double(6,4) NOT NULL default '0.0000',
  `votes` int(11) unsigned NOT NULL default '0',
  `comments` int(11) unsigned NOT NULL default '0',
  `vidsource` int(11) unsigned NOT NULL default '0',
  `published` int(11) NOT NULL default '0',
  `expired` int(10) NOT NULL default '0',
  `updated` int(11) NOT NULL default '0',
  `offline` tinyint(1) NOT NULL default '0',
  `description` longtext NOT NULL,
  `ipaddress` varchar(120) NOT NULL default '0',
  `notifypub` int(1) NOT NULL default '0',
  `vidrating` tinyint(1) NOT NULL default '0',
  `time` varchar(7) NOT NULL default '0:00:00',
  `keywords` varchar(255) NOT NULL default '',
  `item_tag` text NOT NULL default '',
  `picurl` text NOT NULL default '',
  PRIMARY KEY  (`lid`),
  KEY `cid` (`cid`),
  KEY `status` (`status`),
  KEY `title` (`title`(40))
) ENGINE=MyISAM COMMENT='XoopsTube by McDonald' AUTO_INCREMENT=1 ;

#
# Table structure for table 'xoopstube_votedata'
#

CREATE TABLE `xoopstube_votedata` (
  `ratingid` int(11) unsigned NOT NULL auto_increment,
  `lid` int(11) unsigned NOT NULL default '0',
  `ratinguser` int(11) NOT NULL default '0',
  `rating` tinyint(3) unsigned NOT NULL default '0',
  `ratinghostname` varchar(60) NOT NULL default '',
  `ratingtimestamp` int(10) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ratingid`),
  KEY `ratinguser` (`ratinguser`),
  KEY `ratinghostname` (`ratinghostname`),
  KEY `lid` (`lid`)
) ENGINE=MyISAM COMMENT='XoopsTube by McDonald' AUTO_INCREMENT=1 ;


