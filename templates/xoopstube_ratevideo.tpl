<link rel="stylesheet" type="text/css" href="<{$smarty.const.xoopstube_url}>/assets/css/xtubestyle.css"/>

<{if $catarray.imageheader != ""}>
    <br/>
    <div class="xoopstube_header"><{$catarray.imageheader}></div>
<{/if}>
<div style="padding-bottom: 12px; text-align: center;" class="xoopstube_itemTitle"><{$catarray.letters}></div>
<div style="padding-bottom: 12px; text-align: center;"><{$catarray.toolbar}></div>

<table border="0" cellpadding="1" cellspacing="2" width="80%" align="center">
    <tr>
        <td>
            <ul>
                <li><{$smarty.const._MD_XOOPSTUBE_VOTEONCE}></li>
                <li><{$smarty.const._MD_XOOPSTUBE_RATINGSCALE}></li>
                <li><{$smarty.const._MD_XOOPSTUBE_BEOBJECTIVE}></li>
                <li><{$smarty.const._MD_XOOPSTUBE_DONOTVOTE}></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td align="center">
            <div style="font-weight: bold;"><{$smarty.const._MD_XOOPSTUBE_VOTEFORTITLE}>: <{$video.title}></div>
            <br/>

            <form method="post" action="ratevideo.php">
                <input type="hidden" name="lid" value="<{$video.id}>"/>
                <input type="hidden" name="cid" value="<{$video.cid}>"/>
                <input type="hidden" name="title" value="<{$video.title}>"/>
                <select name="rating">
                    <option>--</option>
                    <option>10</option>
                    <option>9</option>
                    <option>8</option>
                    <option>7</option>
                    <option>6</option>
                    <option>5</option>
                    <option>4</option>
                    <option>3</option>
                    <option>2</option>
                    <option>1</option>
                </select>&nbsp;&nbsp;
                <input type="submit" name="submit" value="<{$smarty.const._MD_XOOPSTUBE_RATEIT}>"
                       title="<{$smarty.const._MD_XOOPSTUBE_RATEIT}>" alt="<{$smarty.const._MD_XOOPSTUBE_RATEIT}>"/>
                <input type="button" value="<{$smarty.const._CANCEL}>" title="<{$smarty.const._CANCEL}>"
                       alt="<{$smarty.const._CANCEL}>"
                       onclick="location='<{$xoops_url}>/modules/<{$module_dir}>/singlevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>'"/>
            </form>
        </td>
    </tr>
</table>
