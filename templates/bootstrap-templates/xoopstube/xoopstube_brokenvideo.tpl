<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="index.php"><{$module_dir}></a></li>
            <li><{$smarty.const._MD_XOOPSTUBE_BROKENREPORT}></li>            
        </ol>        
    </div>
</div>

<{if $brokenreport == true}>
    <div class="row">
        <div class="col-md-12">
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
    </div>

<{else}>

<div class="row">
    <div class="col-md-12">
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
                <input class="btn btn-xs btn-primary" type="submit" name="op" value="<{$smarty.const._MD_XOOPSTUBE_SUBMITBROKEN}>" title="<{$smarty.const._MD_XOOPSTUBE_SUBMITBROKEN}>" 
                    alt="<{$smarty.const._MD_XOOPSTUBE_SUBMITBROKEN}>">
                <input class="btn btn-xs btn-danger" type="button" value="<{$smarty.const._MD_XOOPSTUBE_CANCEL}>" title="<{$smarty.const._MD_XOOPSTUBE_CANCEL}>" 
                    alt="<{$smarty.const._MD_XOOPSTUBE_CANCEL}>" onclick="history.go(-1)">
            </form>
        </div>
        
    </div>
</div>
<{/if}>
