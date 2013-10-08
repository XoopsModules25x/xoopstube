<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * xoopstube module for xoops
 *
 * @copyright       The TXMod XOOPS Project http://sourceforge.net/projects/thmod/
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @package         xoopstube
 * @since           2.5.x
 * @author          TXMod Xoops (Timgno) - (support@txmodxoops.org) - (http://www.txmodxoops.org)
 * @version         $Id: 1.37 11855 2013-07-25 14:29:35Z timgno $
 */
$indexFile = XOOPS_UPLOAD_PATH.'/index.html';
$blankFile = XOOPS_UPLOAD_PATH.'/blank.gif';
//Creation du dossier 'uploads' pour le module à la racine du site
$xoopstube = XOOPS_UPLOAD_PATH.'/xoopstube';
if(!is_dir($xoopstube))
	mkdir($xoopstube, 0777);
	chmod($xoopstube, 0777);
copy($indexFile, XOOPS_UPLOAD_PATH.'/xoopstube/index.html');
			
//Creation du fichier banner dans uploads
$category = $xoopstube.'/category';
if(!is_dir($category))
	mkdir($category, 0777);
	chmod($category, 0777);
copy($indexFile, $category.'/index.html');

//Creation du fichier banner dans uploads
$videos = $xoopstube.'/videos';
if(!is_dir($videos))
	mkdir($videos, 0777);
	chmod($videos, 0777);
copy($indexFile, $videos.'/index.html');
copy($blankFile, $videos.'/blank.gif');

//Creation du fichier banner dans uploads
$screenshots = $xoopstube.'/screenshots';
if(!is_dir($screenshots))
	mkdir($screenshots, 0777);
	chmod($screenshots, 0777);
copy($indexFile, $screenshots.'/index.html');
copy($blankFile, $screenshots.'/blank.gif');