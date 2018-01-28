<link rel="stylesheet" type="text/css" href="<{$smarty.const.xoopstube_url}>/assets/css/xtubestyle.css">
<{if $catarray.imageheader != ""}>
    <br>
    <div align="center"><{$catarray.imageheader}></div>
    <br>
<{/if}>
<div><{$description}></div><br>
<{*<div style="padding-bottom: 12px; text-align: center;" class="itemPermaLink"><{$catarray.letters}></div>*}>

<{*-------------Letter Choice Start -----------------------------*}>

<{if $catarray.letters}>
    <div class="xoopstube_head_catletters" align="center">
        <{$letterChoiceTitle}>
        <{$catarray.letters}></div>
    <br>
<{/if}>
<{*-------------Letter Choice End -----------------------------*}>

<div class="even" style="font-size: smaller; font-weight: bold;"><{$category_path}></div><br>
<{if $subcategories}>
    <fieldset>
        <legend class="xoopstube_legend"><{$smarty.const._MD_XOOPSTUBE_SUBCATLISTING}></legend>
        <div align="left" style="margin-left: 5px; padding: 0;">
            <table width="80%">
                <tr>
                    <{foreach item=subcat from=$subcategories}>
                    <td>
                        <a href="viewcat.php?cid=<{$subcat.id}>"><img src="<{$subcat.image}>" title="<{$subcat.alttext}>"
                                                                      alt="<{$subcat.alttext}>" align="middle"></a>
                        <a href="viewcat.php?cid=<{$subcat.id}>"><{$subcat.title}></a>&nbsp;(<{$subcat.totalvideos}>)<br>
                        <{if $subcat.infercategories}>
                            &nbsp;&nbsp;<{$subcat.infercategories}>
                        <{/if}>
                    </td>
                    <{if $cat_columns != 0 && $subcat.count % $cat_columns == 0}>
                </tr>
                <tr>
                    <{/if}>
                    <{/foreach}>
                </tr>
            </table>
        </div>
    </fieldset>
    <br>
<{/if}>

<{if $show_videos == true}>
    <div align="center" style="vertical-align: middle; font-size: smaller;">
        <span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_SORTBY}></span>&nbsp;<{$smarty.const._MD_XOOPSTUBE_TITLE}> (
        <a href="viewcat.php?cid=<{$category_id}>&amp;orderby=titleA">
            <img src="<{xoModuleIcons16 up.png}>" align="middle" alt=""></a>
        <a href="viewcat.php?cid=<{$category_id}>&amp;orderby=titleD">
            <img src="<{xoModuleIcons16 down.png}>" align="middle" alt=""></a>
        )
        &nbsp;
        <{$smarty.const._MD_XOOPSTUBE_DATE}> (
        <a href="viewcat.php?cid=<{$category_id}>&amp;orderby=dateA">
            <img src="<{xoModuleIcons16 up.png}>" align="middle" alt=""></a>
        <a href="viewcat.php?cid=<{$category_id}>&amp;orderby=dateD">
            <img src="<{xoModuleIcons16 down.png}>" align="middle" alt=""></a>
        )
        &nbsp;
        <{$smarty.const._MD_XOOPSTUBE_RATING}> (
        <a href="viewcat.php?cid=<{$category_id}>&amp;orderby=ratingA">
            <img src="<{xoModuleIcons16 up.png}>" align="middle" alt=""></a>
        <a href="viewcat.php?cid=<{$category_id}>&amp;orderby=ratingD">
            <img src="<{xoModuleIcons16 down.png}>" align="middle" alt=""></a>
        )
        &nbsp;
        <{$smarty.const._MD_XOOPSTUBE_POPULARITY}> (
        <a href="viewcat.php?cid=<{$category_id}>&amp;orderby=hitsA">
            <img src="<{xoModuleIcons16 up.png}>" align="middle" alt=""></a>
        <a href="viewcat.php?cid=<{$category_id}>&amp;orderby=hitsD">
            <img src="<{xoModuleIcons16 down.png}>" align="middle" alt=""></a>
        )
        <br>
        <span style="font-weight: bold;"><{$lang_cursortedby}></span><br><br>
    </div>
    <br>
<{/if}>

<{if $page_nav == true}>
    <div style="text-align: left;"><{$pagenav}></div>
    <br>
<{/if}>

<div>
    <!-- Start link loop -->
    <{section name=i loop=$video}>
        <{include file="db:xoopstube_videoload.tpl" video=$video[i]}>
    <{/section}>
    <!-- End link loop -->
</div>
<{if $page_nav == true}>
    <div style="text-align: right;"><{$pagenav}></div>
<{/if}>

<{if $moderate == true}>
    <div style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_MODERATOR_OPTIONS}></div>
    <br>
    <div>
        <!-- Start link loop -->
        <{section name=a loop=$mod_arr}>
            <{include file="db:xoopstube_videoload.tpl" video=$mod_arr[a]}>
        <{/section}>
        <!-- End link loop -->
    </div>
<{/if}>
<{include file="db:system_notification_select.tpl"}>
