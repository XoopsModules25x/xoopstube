<link rel="stylesheet" type="text/css" href="<{$smarty.const.xoopstube_url}>/assets/css/xtubestyle.css">
<{if $catarray.imageheader != ""}>
    <br>
    <div class="xoopstube_header"><{$catarray.imageheader}></div>
<{/if}>
<{if $catarray.indexheading != ""}>
    <h4 style="text-align: center;"><{$catarray.indexheading}></h4>
<{/if}>
<div style="padding-bottom: 12px; text-align: <{$catarray.indexheaderalign}>;"><{$catarray.indexheader}></div>

<{*<div style="padding-bottom: 12px; text-align: center;" class="itemPermaLink"><{$catarray.letters}></div>*}>


<{*-------------Letter Choice Start -----------------------------*}>

<{if $catarray.letters}>
    <div class="xoopstube_head_catletters" align="center">
        <{$letterChoiceTitle}>
        <{$catarray.letters}></div>
    <br>
<{/if}>
<{*-------------Letter Choice End -----------------------------*}>



<{if count($categories) gt 0}>
    <div class="even" style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_MAINLISTING}></div>
    <table width="100%" cellspacing="1" cellpadding="3" summary='' style="text-align: center;">
        <tr>
            <td colspan="2">&nbsp;</td>
        <tr>
        <tr>
            <!-- Start category loop -->
            <{foreach item=category from=$categories}>
            <td width="5%" style="text-align: center;">
                <a href="<{$xoops_url}>/modules/<{$module_dir}>/viewcat.php?cid=<{$category.id}>"><img
                            src="<{$category.image}>" title="<{$category.alttext}>" alt="<{$category.alttext}>" align="middle"></a>
            </td>
            <td width="35%" style="text-align: left; vertical-align: middle;">
                <a href="<{$xoops_url}>/modules/<{$module_dir}>/viewcat.php?cid=<{$category.id}>"
                   style="font-weight: bold;"><{$category.title}></a>&nbsp;(<{$category.totalvideos}>)<br>
                <{if $category.subcategories}>
                    <div style="margin-bottom: 3px; margin-left: 16px; font-size: smaller; vertical-align: top;">
                        <{$category.subcategories}>
                    </div>
                <{/if}>
            </td>
            <{if $cat_columns != 0 && $category.count % $cat_columns == 0}>
        </tr>
        <tr><{/if}> <{/foreach}>
            <!-- End category loop -->
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        <tr>
    </table>
    <div class="odd" style="text-align: left; font-size: smaller;"><{$lang_thereare}></div>
    <div class="xoopstube_legend">
        <img src="<{$xoops_url}>/modules/<{$module_dir}>/assets/images/icon/linkload1_small.png"
             title="<{$smarty.const._MD_XOOPSTUBE_LEGENDTEXTNEW}>" alt="" align="middle">&nbsp;<{$smarty.const._MD_XOOPSTUBE_LEGENDTEXTNEW}>
        <img src="<{$xoops_url}>/modules/<{$module_dir}>/assets/images/icon/linkload2_small.png"
             title="<{$smarty.const._MD_XOOPSTUBE_LEGENDTEXTNEWTHREE}>" alt="" align="middle">&nbsp;<{$smarty.const._MD_XOOPSTUBE_LEGENDTEXTNEWTHREE}>
        <img src="<{$xoops_url}>/modules/<{$module_dir}>/assets/images/icon/linkload3_small.png"
             title="<{$smarty.const._MD_XOOPSTUBE_LEGENDTEXTTHISWEEK}>" alt="" align="middle">&nbsp;<{$smarty.const._MD_XOOPSTUBE_LEGENDTEXTTHISWEEK}>
        <img src="<{$xoops_url}>/modules/<{$module_dir}>/assets/images/icon/linkload4_small.png"
             title="<{$smarty.const._MD_XOOPSTUBE_LEGENDTEXTNEWLAST}>" alt="" align="middle">&nbsp;<{$smarty.const._MD_XOOPSTUBE_LEGENDTEXTNEWLAST}>
    </div>
<{/if}>
<div style="padding-bottom: 12px;text-align: <{$catarray.indexfooteralign}>;"><{$catarray.indexfooter}></div>

<{if $showlatest}>
    <br>
    <br>
    <div class="odd" style="font-size: larger; font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_LATESTLIST}></div>
    <br>
    <{if $pagenav}>
        <div><{$pagenav}></div>
        <br>
    <{/if}>
    <table width="100%" cellspacing="0" cellpadding="10" border="0">
        <tr>
            <td width="100%">
                <!-- Start video loop -->
                <{section name=i loop=$video}>
                    <{include file="db:xoopstube_videoload.tpl" video=$video[i]}>
                <{/section}>
                <!-- End video loop -->
            </td>
        </tr>
    </table>
    <{if $pagenav}>
        <div align="right"><{$pagenav}></div>
    <{/if}>
<{/if}>

<{include file="db:system_notification_select.tpl"}>
