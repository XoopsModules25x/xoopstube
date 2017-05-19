<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="index.php"><{$module_dir}></a></li>
            <li><{$smarty.const._MD_XOOPSTUBE_NEWVIDEOS}></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4><{$smarty.const._MD_XOOPSTUBE_NEWVIDEOS}></h4>
        <table class="table table-bordered">
            <tr>
                <th colspan="2"><{$smarty.const._MD_XOOPSTUBE_TOTALNEWVIDEOS}>:</th>
            </tr>
            <tr>
                <td class="odd"><{$smarty.const._MD_XOOPSTUBE_LASTWEEK}></td>
                <td width="5%" class="odd" align="center"><{$allweekvideos}></td>
            </tr>
            <tr>
                <td class="odd"><{$smarty.const._MD_XOOPSTUBE_LAST30DAYS}></td>
                <td width="15%" class="odd" align="center"><{$allmonthvideos}></td>
            </tr>
        </table>        
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4><{$smarty.const._MD_XOOPSTUBE_SHOW}>:</h4>
        <button class="btn btn-xs btn-primary"  onclick="window.location.href='<{$xoops_url}>/modules/<{$module_dir}>/newlist.php?newvideoshowdays=7'"><{$smarty.const._MD_XOOPSTUBE_1WEEK}></button>
        <button class="btn btn-xs btn-primary"  onclick="window.location.href='<{$xoops_url}>/modules/<{$module_dir}>/newlist.php?newvideoshowdays=14'"><{$smarty.const._MD_XOOPSTUBE_2WEEKS}></button>
        <button class="btn btn-xs btn-primary"  onclick="window.location.href='<{$xoops_url}>/modules/<{$module_dir}>/newlist.php?newvideoshowdays=30'"><{$smarty.const._MD_XOOPSTUBE_30DAYS}></button>
       
    </div>
</div>
<br>
<table class="table table-bordered" style="max-width: 400px;">
    <thead>
    <tr>
        <th colspan="2"><{$smarty.const._MD_XOOPSTUBE_DTOTALFORLAST}> <{$newvideoshowdays}>
            <{$smarty.const._MD_XOOPSTUBE_DAYS}>
        </th>
    </tr>
    </thead>
    <tbody>
    <{if count($dailyvideos) gt 0}>
    <!-- Start day loop -->
    <{foreach item=dailyvideo from=$dailyvideos}>
        <tr>
            <td class="odd">
                <a href="<{$xoops_url}>/modules/<{$module_dir}>/viewcat.php?selectdate=<{$dailyvideo.newvideodayRaw}>"><{$dailyvideo.newvideoView}></a>
            </td>
            <td class="odd" width="15%" align="center"><{$dailyvideo.totalvideos}></td>
        </tr>
    <{/foreach}>
    </tbody>
</table>
<{/if}>
