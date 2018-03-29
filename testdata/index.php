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

use XoopsModules\Xoopstube;

require_once __DIR__ . '/../../../mainfile.php';

include __DIR__ . '/../preloads/autoloader.php';

$op = \Xmf\Request::getCmd('op', '');

$moduleDirName = basename(dirname(__DIR__));

switch ($op) {
    case 'load':
        loadSampleData();
        break;
    case 'save':
        saveSampleData();
        break;
}

// XMF TableLoad for SAMPLE data

function loadSampleData()
{
    $moduleDirName      = basename(dirname(__DIR__));
    xoops_loadLanguage('admin', $moduleDirName);
    $items = \Xmf\Yaml::readWrapped('item-data.yml');
    $cat   = \Xmf\Yaml::readWrapped('cat-data.yml');

    \Xmf\Database\TableLoad::truncateTable($moduleDirName . '_' . 'videos');
    \Xmf\Database\TableLoad::truncateTable($moduleDirName . '_' . 'cat');

    \Xmf\Database\TableLoad::loadTableFromArray($moduleDirName . '_' . 'cat', $cat);
    \Xmf\Database\TableLoad::loadTableFromArray($moduleDirName . '_' . 'videos', $items);

    redirect_header('../admin/main.php', 1, _AM_XOOPSTUBE_SAMPLEDATA_SUCCESS);
}

function saveSampleData()
{
    $moduleDirName      = basename(dirname(__DIR__));
    $moduleDirNameUpper = strtoupper($moduleDirName);

    $tables = ['videos', 'cat'];

    foreach ($tables as $table) {
        \Xmf\Database\TableLoad::saveTableToYamlFile($moduleDirName . '_' . $table, $table . '_' . date("Y-m-d H-i-s") . '.yml');
    }

    redirect_header('../admin/index.php', 1, constant('CO_' . $moduleDirNameUpper . '_' . 'SAMPLEDATA_SUCCESS'));
}
