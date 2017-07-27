<{if $catarray.imageheader != ""}>
    <div align="center"><{$catarray.imageheader}></div>
<{/if}>

<{if $brokenreport == true}>
    <div style="text-align:left;">
        <h4><{$smarty.const._MD_XOOPSTUBE_RESOURCEREPORTED}></h4>

        <div align="center">
            <h4><{$smarty.const._MD_XOOPSTUBE_RESOURCEREPORTED}></h4>

            <div><{$smarty.const._MD_XOOPSTUBE_RESOURCEREPORTED}></div>
            <br>

            <div><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_FILETITLE}></span><{$broken.title}></div>
            <div><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_RESOURCEID}></span><{$broken.id}></div>
            <div><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_REPORTER}></span> <{$broken.reporter}></div>
            <div><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_DATEREPORTED}></span> <{$broken.date}></div>
            <br>

            <div><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_WEBMASTERACKNOW}></span>
                <{$broken.acknowledged}>
            </div>
            <div><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_WEBMASTERCONFIRM}></span> <{$broken.confirmed}>
            </div>
        </div>
    </div>
<{else}>
    <div align="left">
        <h4><{$smarty.const._MD_XOOPSTUBE_BROKENREPORT}></h4>

        <div><{$smarty.const._MD_XOOPSTUBE_THANKSFORHELP}></div>
        <div><{$smarty.const._MD_XOOPSTUBE_FORSECURITY}></div>
        <br>

        <div><{$smarty.const._MD_XOOPSTUBE_BEFORESUBMIT}></div>
        <br>

        <div align="center">
            <div><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_FILETITLE}></span><{$video.title}></div>
            <div><span style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_SUBMITTER}>:</span> <{$video.publisher}></div>
            <div><span style="font-weight: bold;"><{$lang_subdate}>:</span> <{$video.updated}></div>
            <br>

            <form action="brokenvideo.php" method="POST">
                <{securityToken}><{*//mb*}>
                <input type="hidden" name="lid" value="<{$video_id}>">
                <input type="hidden" name="title" value="<{$video.title}>">
                <input type="submit" name="op" value="<{$smarty.const._MD_XOOPSTUBE_SUBMITBROKEN}>"
                       title="<{$smarty.const._MD_XOOPSTUBE_SUBMITBROKEN}>" alt="<{$smarty.const._MD_XOOPSTUBE_SUBMITBROKEN}>">
                &nbsp;<input type="button" value="<{$smarty.const._MD_XOOPSTUBE_CANCEL}>"
                             title="<{$smarty.const._MD_XOOPSTUBE_CANCEL}>" alt="<{$smarty.const._MD_XOOPSTUBE_CANCEL}>"
                             onclick="history.go(-1)">
            </form>
        </div>
    </div>
<{/if}>
