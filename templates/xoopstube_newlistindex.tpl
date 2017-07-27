<link rel="stylesheet" type="text/css" href="<{$smarty.const.xoopstube_url}>/assets/css/xtubestyle.css">
<{if $catarray.imageheader != ""}>
    <br>
    <div class="xoopstube_header"><{$catarray.imageheader}></div>
<{/if}> <br>
<div style="text-align: right;"><{$back}>&nbsp;</div>
<div class="even" style="font-weight: bold; font-size: 110%;">&nbsp;<{$smarty.const._MD_XOOPSTUBE_NEWVIDEOS}></div>
<br>

<table class="outer" style="width: 400px; margin-left: 8px;" border="0" cellspacing="2" cellpadding="0">
    <tr>
        <th height="5" colspan="2" style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_TOTALNEWVIDEOS}>:</th>
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
<br><br>

<div style="float: left; margin-left: 8px; margin-right: 8px;">
    <span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_SHOW}>:</span>
</div>
<div style="float: left; margin-right: 3px;">
    <a class="xoopstube_button" href="<{$xoops_url}>/modules/<{$module_dir}>/newlist.php?newvideoshowdays=7"><{$smarty.const._MD_XOOPSTUBE_1WEEK}></a>
    <a class="xoopstube_button" href="<{$xoops_url}>/modules/<{$module_dir}>/newlist.php?newvideoshowdays=14"><{$smarty.const._MD_XOOPSTUBE_2WEEKS}></a>
    <a class="xoopstube_button" href="<{$xoops_url}>/modules/<{$module_dir}>/newlist.php?newvideoshowdays=30"><{$smarty.const._MD_XOOPSTUBE_30DAYS}></a>
</div>

<br><br><br>

<table class="outer" style="width: 400px; margin-left: 8px;" border="0" cellspacing="2" cellpadding="0">
    <tr>
        <th colspan="2" style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_DTOTALFORLAST}> <{$newvideoshowdays}>
            <{$smarty.const._MD_XOOPSTUBE_DAYS}>
        </th>
    </tr>


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
</table>
<!-- End day loop -->

<!-- <h4><{$smarty.const._MD_XOOPSTUBE_LATESTLIST}></h4> -->
    <table width="100%" cellspacing="0" cellpadding="2" border="0">
        <tr>
            <td width="100%">
                <!-- Start video loop -->
                <{section name=i loop=$video}>
                    <{include file="db:xtube_videoload.tpl" video=$video[i]}>
                <{/section}>
                <!-- End video loop -->
            </td>
        </tr>
    </table>
<{/if}>
