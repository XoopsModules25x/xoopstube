<div class="row">
    <div class="col-md-12"><{$video.showvideo}></div>
    <div class="col-md-12">
        <h4><{$video.title}></h4>
    </div>
    <div class="col-md-12">
        <ul class="list-unstyled xt-list">
            <small>
            <li><strong><{$smarty.const._MD_XOOPSTUBE_PUBLISHER}>:</strong> <{$video.publisher}></li>
            <li><{$video.hits|wordwrap:50:"\n":true}></li>
            <li><strong><{$smarty.const._MD_XOOPSTUBE_SUBMITTER}>:</strong> <{$video.submitter}> | <{$video.updated|wordwrap:50:"\n":true}></li>
            <li><strong><{$smarty.const._MD_XOOPSTUBE_CATEGORYC}></strong> <{$video.category}></li>
            <li><strong><{$smarty.const._MD_XOOPSTUBE_TIMEB}></strong> <{$video.time}></li>
            <li><strong><{$smarty.const._MD_XOOPSTUBE_DESCRIPTIONC}></strong></li>
            <p><{$video.description2}></p>
            </small>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
            <{if $video.showsbookmarx > 0}>
                <div class='shareaholic-canvas' data-app='share_buttons' data-app-id=''></div>
            <{/if}>
        </div>
</div>

<div class="row">
    <ol class="breadcrumb">
        <{if $video.showrating}>
            <{if $video.allow_rating}>
                <i class="glyphicon glyphicon-thumbs-up"></i>
                <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/ratevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>" title="<{$smarty.const._MD_XOOPSTUBE_RATETHISFILE}>">
                    <{$smarty.const._MD_XOOPSTUBE_RATETHISFILE}>
                </a>
             <{/if}>
        &nbsp;
        <{/if}>
        
        <i class="glyphicon glyphicon-warning-sign"></i>
        <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/brokenvideo.php?lid=<{$video.id}>" title="<{$smarty.const._MD_XOOPSTUBE_REPORTBROKEN}>">
            <{$smarty.const._MD_XOOPSTUBE_REPORTBROKEN}>
        </a>
        &nbsp;
        
        <i class="glyphicon glyphicon-share-alt"></i>
        <a href="mailto:?subject=<{$video.mail_subject}>&body=<{$video.mail_body}>" title="<{$smarty.const._MD_XOOPSTUBE_TELLAFRIEND}>">
            <{$smarty.const._MD_XOOPSTUBE_TELLAFRIEND}>
        </a>
        &nbsp;
        <{if $video.comment_rules > 0}>
            <i class="glyphicon glyphicon-comment"></i>
            <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/singlevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>" title="<{$smarty.const._COMMENTS}>">
                <{$smarty.const._COMMENTS}> (<{$video.comments}>)
            </a>
        &nbsp;
        <{/if}>
        <{if $video.useradminvideo}>
            <i class="glyphicon glyphicon-edit"></i><{$video.usermodify}>
        <{/if}>
    </ol>
</div>
<{if $video.othervideox > 0}>
<div class="row">
    <div class="col-md-12">
        <h4><{$other_videos}></h4>
        <ul class="list-unstyled">
            <{foreach item=video_user from=$video_uid}>
                <li>
                    <i class="glyphicon glyphicon-film"></i>
                    <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/singlevideo.php?cid=<{$video_user.cid}>&amp;lid=<{$video_user.lid}>" title="<{$video_user.title}>">
                        <{$video_user.title}>
                    </a>
                    <span>(<{$video_user.published}>)</span>
                 </li>
             <{/foreach}>
         </ul>
    </div>
</div>
<hr>
<{/if}>



<{$commentsnav}> <{$lang_notice}>

<{if $comment_mode == "flat"}>
    <{include file="db:system_comments_flat.tpl"}>
<{elseif $comment_mode == "thread"}>
    <{include file="db:system_comments_thread.tpl"}>
<{elseif $comment_mode == "nest"}>
    <{include file="db:system_comments_nest.tpl"}>
<{/if}>

<{include file="db:system_notification_select.tpl"}>
