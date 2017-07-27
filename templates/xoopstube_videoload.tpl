<{if $show_categort_title == true}>
    <div style="margin-bottom: 4px;"><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_CATEGORYC}></span><{$video.category}>
    </div>
<{/if}>

<div class="even" style="display: table; width: 99%;">
    <span style="float: left;">
        <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/singlevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>"><{$video.title}></a><{$video.icons}>
        &nbsp;<{if $xoops_isadmin}><{$video.adminvideo}><{/if}>
    </span>
    <{if $video.published > 0 }>
        <span style="float: right; vertical-align: middle; padding-left: 10px;">
            <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/singlevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>">
                <img src="<{$xoops_url}>/modules/<{$video.module_dir}>/assets/images/icon/play.png"
                     alt="<{$smarty.const._MD_XOOPSTUBE_VIEWDETAILS}>" title="<{$smarty.const._MD_XOOPSTUBE_VIEWDETAILS}>">
            </a>
        </span>
    <{/if}>
</div>

<br>

<div style="float: right; width: 250px;">
    <div class="xoopstube_infoblock">
        <{if $video.showsubmitterx}>
            <span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_SUBMITTER}>:</span>
            <{$video.submitter}>
            <br>
        <{/if}>
        <span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_PUBLISHER}>:</span>&nbsp;<{$video.publisher}><br>
        <span style="font-weight: bold;"><{$lang_subdate}>:</span>&nbsp;&nbsp;<{$video.updated}><br>
        <{$video.hits|wordwrap:50:"\n":true}><br>
        <{$smarty.const._MD_XOOPSTUBE_TIMEB}>&nbsp;<{$video.time}>
    </div>

    <{if $video.showrating}>
        <br>
        <div class="xoopstube_infoblock">
            <span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_RATINGC}></span>&nbsp;<img
                    src="<{$xoops_url}>/modules/<{$video.module_dir}>/assets/images/icon/<{$video.rateimg}>" alt="" align="middle">&nbsp;&nbsp;(<{$video.votes}>)
        </div>
    <{/if}>
</div>

<div style="float: left; padding: 0 4px 4px 0;">
    <{if $video.screen_shot}>
        <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/singlevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>"
           target=""><{$video.videothumb}></a>
    <{/if}>
</div>

<div>
    <span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_DESCRIPTIONC}></span><br>
    <{$video.description|truncate:$video.total_chars}>
</div>

<div style="clear: both; width: 99%;">&nbsp;</div>
