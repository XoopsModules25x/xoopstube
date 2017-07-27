<link rel="stylesheet" type="text/css" href="<{$smarty.const.xoopstube_url}>/assets/css/xtubestyle.css">

<{if $catarray.imageheader != ""}>
    <br>
    <div class="xoopstube_header"><{$catarray.imageheader}></div>
<{/if}>

<div class="xoopstube_path">
    <div style="float: left;">&nbsp;<{$smarty.const._MD_XOOPSTUBE_DISPLAYING}><{$lang_sortby}></div>
    <div style="float: right;"><{$back}>&nbsp;</div>
</div>

<br><br>
<!-- Start ranking loop -->
<{foreach item=ranking from=$rankings}>
    <div class="even" style="font-weight: bold; font-size: 110%;">
        <{$smarty.const._MD_XOOPSTUBE_CATEGORY}>: <{$ranking.title}>
    </div>
    <br>
    <table cellpadding="0" cellspacing="1" width="100%" class="outer">
        <tr>
            <th align="center" width="5%"><{$smarty.const._MD_XOOPSTUBE_RANK}></th>
            <th>&nbsp;<{$smarty.const._MD_XOOPSTUBE_TITLE}></th>
            <th width="25%">&nbsp;<{$smarty.const._MD_XOOPSTUBE_CATEGORY}></th>
            <th align="center" width="7%"><{$smarty.const._MD_XOOPSTUBE_HITS}></th>
            <th align="center" width="7%"><{$smarty.const._MD_XOOPSTUBE_RATING}></th>
            <th align="center" width="7%"><{$smarty.const._MD_XOOPSTUBE_VOTE}></th>
        </tr>
        <!-- Start files loop -->
        <{foreach item=file from=$ranking.file}>
            <tr>
                <td align="center" class="head" style="font-weight: bold;"><{$file.rank}></td>
                <td class="even"><a href="singlevideo.php?cid=<{$file.cid}>&amp;lid=<{$file.id}>"><{$file.title}></a></td>
                <td class="even"><a href="viewcat.php?cid=<{$file.cid}>"><{$file.category}></a></td>
                <td class="even" align="center"><{$file.hits}></td>
                <td class="even" align="center"><{$file.rating}></td>
                <td align="center" class="even"><{$file.votes}></td>
            </tr>
        <{/foreach}>
        <!-- End links loop-->
    </table>
    <br>
<{/foreach}>
<!-- End ranking loop -->
