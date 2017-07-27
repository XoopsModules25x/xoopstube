<?php
/**
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package
 * @since           2.5.9
 * @author          Michael Beck (aka Mamba)
 */

use Xmf\Request;

require_once __DIR__ . '/../../../mainfile.php';

if (!isset($moduleDirName)) {
    $moduleDirName = basename(dirname(__DIR__));
}

$op = Request::getCmd('op', '');

switch ($op) {
    case 'load':
        loadSampleData();
        break;
}

// XMF TableLoad for SAMPLE data

function loadSampleData()
{
    xoops_loadLanguage('admin', $moduleDirName);
    $items = \Xmf\Yaml::readWrapped('item-data.yml');
    $cat = \Xmf\Yaml::readWrapped('cat-data.yml');

    \Xmf\Database\TableLoad::truncateTable('xoopstube_videos');
    \Xmf\Database\TableLoad::truncateTable('xoopstube_cat');

    \Xmf\Database\TableLoad::loadTableFromArray('xoopstube_cat', $cat);
    \Xmf\Database\TableLoad::loadTableFromArray('xoopstube_videos', $items);

    redirect_header('../admin/main.php', 1, _AM_XOOPSTUBE_SAMPLEDATA_SUCCESS);
}
