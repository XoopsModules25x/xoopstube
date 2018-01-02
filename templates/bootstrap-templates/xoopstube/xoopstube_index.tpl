<div class="row">
   <div class="col-md-12">
   <ol class="breadcrumb">
       <li><{$module_dir}></li>
   </ol>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        <{if $catarray.imageheader != ""}>
            <div class="xoopstube-header text-center">
                <{$catarray.imageheader}>
            </div>
        <{/if}>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    <{if $catarray.indexheading != ""}>
        <div class="text-center xoopstube-header-text">
            <h1><{$catarray.indexheading}></h1>
        </div>
        <!-- .xoopstube-header-text -->
    <{/if}>
    </div>
    
    <div class="col-md-12">
        <{if $catarray.indexheader != ""}>
            <div class="xoopstube-description text-center">
                <{$catarray.indexheader}>
            </div>
            <!-- .xoopstube-description -->
        <{/if}>
    </div>
    <div class="col-md-12">
        <div class="text-center xoopstube-navigation">
        <{$catarray.letters}>
            <{$catarray.letters2}>
        </div><!-- .xoopstube-navigation -->
    </div>
    
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <h4 style="margin-top: 0;"><{$smarty.const._MD_XOOPSTUBE_MAINLISTING}></h4>
    </div>
</div>
<{if count($categories) gt 0}>
<div class="row">
    <{foreach item=category from=$categories}>
        <div class="col-md-3 col-sm-4 col-xs-6" style="margin-bottom: 5px;">

            <a href="<{$xoops_url}>/modules/<{$module_dir}>/viewcat.php?cid=<{$category.id}>" title="<{$category.title}>">
                 <{$category.title}> <span class="badge"><{$category.totalvideos}></span>
            </a>

            <{if $category.subcategories}>
               <small><{$category.subcategories}></small>
            <{/if}>
        </div>
    <{/foreach}>
</div>
<{/if}>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success" style="margin-top: 15px; margin-bottom: 0;" role="alert"><{$lang_thereare}></div>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-md-12">
        <{if $showlatest}>
            <h4 style="margin-top:0;"><{$smarty.const._MD_XOOPSTUBE_LATESTLIST}></h4>
            <{if $pagenav}>
                <{$pagenav}>
            <{/if}>
            <{section name=i loop=$video}>
                <{include file="db:xoopstube_videoload.tpl" video=$video[i]}>
            <{/section}>
            <{if $pagenav}>
                <{$pagenav}>
            <{/if}>
        <{/if}>
    </div>
</div>

<div class="row">
    <div class="col-md-12"><{$catarray.indexfooter}></div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
         <{include file="db:system_notification_select.tpl"}>
    </div>
</div>
