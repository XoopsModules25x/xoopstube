# MySQL file for XoopsTube
# Version: 1.0.1
# Author: McDonald

#
# Table structure for table 'xoopstube_altcat'
#

CREATE TABLE `xoopstube_altcat` (
  `lid` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `cid` INT(5) UNSIGNED  NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`, `cid`)
)
  ENGINE =MyISAM
  COMMENT ='XoopsTube by McDonald';

#
# Table structure for table 'xoopstube_broken'
#

CREATE TABLE `xoopstube_broken` (
  `reportid`     INT(5)       NOT NULL AUTO_INCREMENT,
  `lid`          INT(11)      NOT NULL DEFAULT '0',
  `sender`       INT(11)      NOT NULL DEFAULT '0',
  `ip`           VARCHAR(20)  NOT NULL DEFAULT '',
  `date`         VARCHAR(11)  NOT NULL DEFAULT '0',
  `confirmed`    INT(1)       NOT NULL DEFAULT '0',
  `acknowledged` INT(1)       NOT NULL DEFAULT '0',
  `title`        VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`reportid`),
  KEY `lid` (`lid`),
  KEY `sender` (`sender`),
  KEY `ip` (`ip`)
)
  ENGINE =MyISAM
  COMMENT ='XoopsTube by McDonald'
  AUTO_INCREMENT =1;

#
# Table structure for table 'xoopstube_cat'
#

CREATE TABLE `xoopstube_cat` (
  cid          INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  pid          INT(5) UNSIGNED NOT NULL DEFAULT '0',
  title        VARCHAR(50)     NOT NULL DEFAULT '',
  imgurl       VARCHAR(150)    NOT NULL DEFAULT '',
  description  TEXT            NOT NULL ,
  total        INT(11)         NOT NULL DEFAULT '0',
  spotlighttop INT(11)         NOT NULL DEFAULT '0',
  spotlighthis INT(11)         NOT NULL DEFAULT '0',
  nohtml       INT(1)          NOT NULL DEFAULT '0',
  nosmiley     INT(1)          NOT NULL DEFAULT '0',
  noxcodes     INT(1)          NOT NULL DEFAULT '0',
  noimages     INT(1)          NOT NULL DEFAULT '0',
  nobreak      INT(1)          NOT NULL DEFAULT '1',
  weight       INT(11)         NOT NULL DEFAULT '0',
  client_id    INT(5)          NOT NULL DEFAULT '0',
  banner_id    INT(5)          NOT NULL DEFAULT '0',
  PRIMARY KEY (cid),
  KEY pid (pid)
)
  ENGINE = MyISAM
  COMMENT ='XoopsTube by McDonald'
  AUTO_INCREMENT = 1;

#
# Table structure for table 'xoopstube_indexpage'
#

CREATE TABLE `xoopstube_indexpage` (
  `indeximage`       VARCHAR(255) NOT NULL DEFAULT 'blank.gif',
  `indexheading`     VARCHAR(255) NOT NULL DEFAULT 'XoopsTube',
  `indexheader`      TEXT         NOT NULL,
  `indexfooter`      TEXT         NOT NULL,
  `nohtml`           TINYINT(8)   NOT NULL DEFAULT '1',
  `nosmiley`         TINYINT(8)   NOT NULL DEFAULT '1',
  `noxcodes`         TINYINT(8)   NOT NULL DEFAULT '1',
  `noimages`         TINYINT(8)   NOT NULL DEFAULT '1',
  `nobreak`          TINYINT(4)   NOT NULL DEFAULT '0',
  `indexheaderalign` VARCHAR(25)  NOT NULL DEFAULT 'left',
  `indexfooteralign` VARCHAR(25)  NOT NULL DEFAULT 'center',
  lastvideosyn       TINYINT(1)   NOT NULL DEFAULT '0',
  lastvideostotal    VARCHAR(5)   NOT NULL DEFAULT '5',
  FULLTEXT KEY `indexheading` (`indexheading`),
  FULLTEXT KEY `indexheader` (`indexheader`),
  FULLTEXT KEY `indexfooter` (`indexfooter`)
)
  ENGINE =MyISAM
  COMMENT ='XoopsTube by McDonald';


INSERT INTO `xoopstube_indexpage` (`indeximage`, `indexheading`, `indexheader`, `indexfooter`, `nohtml`, `nosmiley`, `noxcodes`, `noimages`, `nobreak`, `indexheaderalign`, `indexfooteralign`) VALUES
  ('logo-en.png', '', 'Please report any unavailable video clips by using the [i]Report Broken[/i] option. Thanks!',
   '<a href=\"http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&promoid=BIOW\" target=\"_blank\"><img src=\"assets/images/get_flash_player.png\" alt=\"Get Adobe Flash Player\" border=\"0\" /></a>',
   '0', '0', '0', '0', '1', 'left', 'left');


#
# Table structure for table 'xoopstube_mod'
#

CREATE TABLE `xoopstube_mod` (
  `requestid`       INT(11)          NOT NULL AUTO_INCREMENT,
  `lid`             INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `cid`             INT(5) UNSIGNED  NOT NULL DEFAULT '0',
  `title`           VARCHAR(255)     NOT NULL DEFAULT '',
  `vidid`           VARCHAR(255)     NOT NULL DEFAULT '',
  `screenshot`      VARCHAR(255)     NOT NULL DEFAULT '',
  `submitter`       INT(11)          NOT NULL DEFAULT '0',
  `publisher`       TEXT             NOT NULL,
  `status`          TINYINT(2)       NOT NULL DEFAULT '0',
  `date`            INT(10)          NOT NULL DEFAULT '0',
  `hits`            INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `rating`          DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
  `votes`           INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `comments`        INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `vidsource`       INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `published`       INT(10)          NOT NULL DEFAULT '0',
  `expired`         INT(10)          NOT NULL DEFAULT '0',
  `updated`         INT(11)          NOT NULL DEFAULT '0',
  `offline`         TINYINT(1)       NOT NULL DEFAULT '0',
  `description`     TEXT             NOT NULL,
  `modifysubmitter` INT(11)          NOT NULL DEFAULT '0',
  `requestdate`     INT(11)          NOT NULL DEFAULT '0',
  `vidrating`       TINYINT(1)       NOT NULL DEFAULT '0',
  `time`            VARCHAR(7)       NOT NULL DEFAULT '0:00:00',
  `keywords`        VARCHAR(255)     NOT NULL DEFAULT '',
  `item_tag`        TEXT             NOT NULL,
  `picurl`          TEXT             NOT NULL,
  PRIMARY KEY (`requestid`)
)
  ENGINE =MyISAM
  COMMENT ='XoopsTube by McDonald'
  AUTO_INCREMENT =1;


#
# Table structure for table 'xoopstube_videos'
#

CREATE TABLE `xoopstube_videos` (
  `lid`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cid`         INT(5) UNSIGNED  NOT NULL DEFAULT '0',
  `title`       VARCHAR(100)     NOT NULL DEFAULT '',
  `vidid`       TEXT             NOT NULL,
  `screenshot`  VARCHAR(255)     NOT NULL DEFAULT '',
  `submitter`   INT(11)          NOT NULL DEFAULT '0',
  `publisher`   VARCHAR(255)     NOT NULL DEFAULT '',
  `status`      TINYINT(2)       NOT NULL DEFAULT '0',
  `date`        INT(10)          NOT NULL DEFAULT '0',
  `hits`        INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `rating`      DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
  `votes`       INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `comments`    INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `vidsource`   INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `published`   INT(11)          NOT NULL DEFAULT '0',
  `expired`     INT(10)          NOT NULL DEFAULT '0',
  `updated`     INT(11)          NOT NULL DEFAULT '0',
  `offline`     TINYINT(1)       NOT NULL DEFAULT '0',
  `description` LONGTEXT         NOT NULL,
  `ipaddress`   VARCHAR(120)     NOT NULL DEFAULT '0',
  `notifypub`   INT(1)           NOT NULL DEFAULT '0',
  `vidrating`   TINYINT(1)       NOT NULL DEFAULT '0',
  `time`        VARCHAR(7)       NOT NULL DEFAULT '0:00:00',
  `keywords`    VARCHAR(255)     NOT NULL DEFAULT '',
  `item_tag`    TEXT             NOT NULL,
  `picurl`      TEXT             NOT NULL,
  PRIMARY KEY (`lid`),
  KEY `cid` (`cid`),
  KEY `status` (`status`),
  KEY `title` (`title`(40))
)
  ENGINE =MyISAM
  COMMENT ='XoopsTube by McDonald'
  AUTO_INCREMENT =1;

#
# Table structure for table 'xoopstube_votedata'
#

CREATE TABLE `xoopstube_votedata` (
  `ratingid`        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `lid`             INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  `ratinguser`      INT(11)             NOT NULL DEFAULT '0',
  `rating`          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `ratinghostname`  VARCHAR(60)         NOT NULL DEFAULT '',
  `ratingtimestamp` INT(10)             NOT NULL DEFAULT '0',
  `title`           VARCHAR(255)        NOT NULL DEFAULT '',
  PRIMARY KEY (`ratingid`),
  KEY `ratinguser` (`ratinguser`),
  KEY `ratinghostname` (`ratinghostname`),
  KEY `lid` (`lid`)
)
  ENGINE =MyISAM
  COMMENT ='XoopsTube by McDonald'
  AUTO_INCREMENT =1;


