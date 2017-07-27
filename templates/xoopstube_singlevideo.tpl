<link rel="stylesheet" type="text/css" href="<{$smarty.const.xoopstube_url}>/assets/css/xtubestyle.css">
<{if $video.imageheader != ""}>
    <div class="xoopstube_header"><{$video.imageheader}></div>
<{/if}>
<div class="xoopstube_path">
    <div style="float: left;">&nbsp;<{$video.path}></div>
    <div style="float: right;"><{$back}>&nbsp;</div>
</div>
<div>&nbsp;</div>
<div class="even" style="font-weight: bold; font-size: 110%;">
    <{$video.title}>
    <{$video.icons}>
    <span>
        <{if $xoops_isadmin}>
            &nbsp;<{$video.adminvideo}>
        <{/if}>
    </span>
</div>
<div>&nbsp;</div>

<div align="center">
    <{$video.showvideo}>
</div>

<div>&nbsp;</div>
<{if $video.showsbookmarx > 0}>
    <div class="xoopstube_socbookmark" align="center"><{$video.sbmarks}></div>
<{/if}>

<div style="clear: both;">&nbsp;</div>

<fieldset class="xoopstube_description">
    <span style="float: left; width: 48%;">
        <{if $video.showsubmitterx}>
            <span style="font-size: small;"><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_SUBMITTER}>:</span>&nbsp;<{$video.submitter}></span>
            <br>
        <{/if}>
        <span style="font-size: small;"><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_PUBLISHER}>:</span>&nbsp;<{$video.publisher}></span><br>
        <span style="font-size: small;"><span style="font-weight: bold;"><{$lang_subdate}>:</span>&nbsp;<{$video.updated|wordwrap:50:"\n":true}></span>
    </span>

    <span style="float: right; width: 48%;">
        <span style="font-size: small;"><{$video.hits|wordwrap:50:"\n":true}></span><br>
        <span style="font-size: small;"><{$smarty.const._MD_XOOPSTUBE_TIMEB}>&nbsp;<{$video.time}></span>
        <{if $video.showrating}>
            <br>
            <span style="font-size: small;">
                <span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_RATINGC}></span>&nbsp;<img
                        src="<{$xoops_url}>/modules/<{$video.module_dir}>/assets/images/icon/<{$video.rateimg}>" alt=""
                        align="middle">&nbsp;&nbsp;(<{$video.votes}>)
            </span>
        <{/if}>

        <{if $tagbar}>
            <br>
            <span style="font-size: small;"><{include file="db:tag_bar.tpl"}></span>
        <{/if}>
    </span>

    <div style="clear: both; height: 25px;">&nbsp;</div>
    <span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_DESCRIPTIONC}></span><br>
    <{$video.description2}>
</fieldset>

<br>

<div class="even" align="center">
    <span style="font-size: small;">[&nbsp;
        <{if $video.showrating}>
            <{if $video.allow_rating}>
                <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/ratevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>"><{$smarty.const._MD_XOOPSTUBE_RATETHISFILE}></a>
                |
            <{/if}>
        <{/if}>
        <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/brokenvideo.php?lid=<{$video.id}>"><{$smarty.const._MD_XOOPSTUBE_REPORTBROKEN}></a>
        |
        <{if $video.useradminvideo}>
            <{$video.usermodify}>
        <{/if}>
        <a href="mailto:?subject=<{$video.mail_subject}>&amp;body=<{$video.mail_body}>" target="_top"><{$smarty.const._MD_XOOPSTUBE_TELLAFRIEND}></a>
        <{if $video.comment_rules > 0}>
        |
        <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/singlevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>"><{$smarty.const._COMMENTS}>
            (<{$video.comments}>)</a>
        <{/if}>&nbsp;]
    </span>
</div>

<br>
<{if $video.othervideox > 0}>
    <{$other_videos}>
    <{foreach item=video_user from=$video_uid}>
        <div style="margin-left: 10px;">
            <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/singlevideo.php?cid=<{$video_user.cid}>&amp;lid=<{$video_user.lid}>"><{$video_user.title}></a>
            (<{$video_user.published}>)
        </div>
    <{/foreach}>
<{/if}>
<br><br>
<div align="center"><{$lang_copyright}></div>
<div style="text-align: center; padding: 3px; margin:3px;">
    <{$commentsnav}> <{$lang_notice}>
</div>
<!-- start comments loop -->
<{if $comment_mode == "flat"}>
    <{include file="db:system_comments_flat.tpl"}>
<{elseif $comment_mode == "thread"}>
    <{include file="db:system_comments_thread.tpl"}>
<{elseif $comment_mode == "nest"}>
    <{include file="db:system_comments_nest.tpl"}>
<{/if}>
<!-- end comments loop -->
<{include file="db:system_notification_select.tpl"}>
