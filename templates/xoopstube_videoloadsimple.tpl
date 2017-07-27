<{if $show_categort_title == true}>
    <div style="margin-bottom: 4px;"><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_CATEGORYC}></span><{$video.category}>
    </div>
<{/if}>


<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
        <td width="80%" align="left">
            <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/visit.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>"
               target="_blank"><{$video.title}></a>
            <{$video.icons}>
        </td>
        <td align="right">
            <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/singlevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>"><span
                        class="itemTitle style2"><{$smarty.const._MD_XOOPSTUBE_VIEWDETAILS}></span></a>
        </td>
    </tr>
    <tr>
        <td height="1" colspan="2" bgcolor="#000000"></td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr class="even">
                    <td width="65%">
                        <span style="font-size: small;"><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_SUBMITTER}>:</span> <{$video.submitter}> <{$video.adminvideo}>
                        </span>
                    </td>
                    <td>
                        <div align="right">
                            <span style="font-size: small;"><span style="font-weight: bold;"><{$lang_subdate}>:</span>&nbsp;&nbsp;<{$video.updated}></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td width="65%" valign="top"> <{if $show_screenshot == true}> <{if $video.screenshot_full != ''}>

                        <{/if}> <{/if}>
                        <div style="margin-left: 6px;" align="justify"> &nbsp;
                            <img src="http://sjl-static4.sjl.youtube.com/vi/<{$video.description}>/2.jpg" alt=""
                                 align="absmiddle">&nbsp;<{$video.description}>
                        </div>
                        <div><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_DESCRIPTIONC}></span><br>
                            <{$video.description}>
                        </div>
                        <br>

                        <div align="justify"><span
                                    style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_DESCRIPTIONC}></span><br>
                            <{$video.description}>
                        </div>
                    </td>
                    <td width="35%">
                        <div class="outer style2"
                             style="margin-left: 10px; margin-right: 10px; padding: 4px; background-color:#e6e6e6; border-color:#999999;">
                            <!-- <div><span style="font-size: small;"><{$video.urlrating|wordwrap:50:"\n":true}></span></div> -->
                            <div>
                                <span style="font-size: small;"><{$video.hits|wordwrap:50:"\n":true}></span>
                            </div>
                            <div>
                                <span style="font-size: small;"><{$smarty.const._MD_XOOPSTUBE_TIMEB}>&nbsp;</span>
                                <img src="<{$video.country}>" alt="<{$video.countryname}>" align="middle"></a></div>

                        </div>
                        <br>

                        <div style="margin-left: 10px; margin-right: 10px; padding: 4px;" class="outer">
                            <span style="font-size: small;">
                                <span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_RATINGC}></span>&nbsp;<img
                                        src="<{$xoops_url}>/modules/<{$video.module_dir}>/assets/images/icon/<{$video.rateimg}>"
                                        alt="" align="middle">&nbsp;&nbsp;(<{$video.votes}>)
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">
                        <div class="even" align="center">
                            <span style="font-size: small;">
                                <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/ratevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>"><{$smarty.const._MD_XOOPSTUBE_RATETHISFILE}></a>
                                |
                                <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/brokenvideo.php?lid=<{$video.id}>"><{$smarty.const._MD_XOOPSTUBE_REPORTBROKEN}></a>
                                | <{if $video.useradminvideo}>
                                    <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/submit.php?lid=<{$video.id}>">
                                        <{$smarty.const._MD_XOOPSTUBE_MODIFY}></a>
                                    | <{/if}>
                                <a target="_top"
                                   href="mailto:?subject=<{$video.mail_subject}>&body=<{$video.mail_body}>"><{$smarty.const._MD_XOOPSTUBE_TELLAFRIEND}></a>
                                |
                                <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/singlevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>"><{$smarty.const._COMMENTS}>
                                    (<{$video.comments}>)</a></span>
                        </div>
                    </td>
                </tr>
            </table>
    </tr>
</table><br>
