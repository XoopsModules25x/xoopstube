<div style="background-color:#eee; width: 520px; height:360px; text-align:center; clear:both;">

    <{foreach item=videoload from=$block.videos name=grouploop}>

    <{if $smarty.foreach.grouploop.first}>
    <div style="float:right; background-color:#efefef; width: 380px; height:100%; position:relative;">
        <div style="background-color:#ddd; width:100%; position:absolute; top:50%; height:320px; margin-top:-160px; left:0;">
            <!--<a href="<{$xoops_url}>/modules/<{$videoload.dirname}>/singlevideo.php?cid=<{$videoload.cid}>&amp;lid=<{$videoload.id}>"><{$videoload.videothumb}></a> -->
            <div style="padding: 10px 0; width:100%;">
                <{$videoload.showvideo}>
                <br/>
                <a href="<{$xoops_url}>/modules/<{$videoload.dirname}>/singlevideo.php?cid=<{$videoload.cid}>&amp;lid=<{$videoload.id}>"
                   style="font-size: small;"><{$videoload.title}></a>
            </div>
        </div>
    </div>

    <div style="float:left; background-color:#ccc; width:140px; height:100%; position:relative">
        <div style="background-color:#bbb; width:100%; position:absolute; top:50%; height:320px; margin-top:-160px; left:0;">
            <div style="padding: 9px 0;">
                <{else}>
                <div style="padding: 2px 0;">
                    <a href="<{$xoops_url}>/modules/<{$videoload.dirname}>/singlevideo.php?cid=<{$videoload.cid}>&amp;lid=<{$videoload.id}>"><{$videoload.videothumb}></a>
                    <br/>
                    <a href="<{$xoops_url}>/modules/<{$videoload.dirname}>/singlevideo.php?cid=<{$videoload.cid}>&amp;lid=<{$videoload.id}>"
                       style="font-size: small;"><{$videoload.title}></a>
                </div>
                <{/if}>

                <{/foreach}>
            </div>
        </div>
    </div>
</div>
