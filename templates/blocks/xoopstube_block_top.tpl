<table cellspacing="1" align="center" border="0">
    <{foreach item=videoload from=$block.videos}>
        <tr>
            <td align="center" class="even" style="padding: 4px;vertical-align: top;">
                <a href="<{$xoops_url}>/modules/<{$videoload.dirname}>/singlevideo.php?cid=<{$videoload.cid}>&amp;lid=<{$videoload.id}>">
                    <span style="font-size: small;"><{$videoload.title}></span>
                </a>
                <br>
                <span style="font-size: small;">(<{$videoload.hits}>&nbsp;hits)</span>
                <br>
                <a href="<{$xoops_url}>/modules/<{$videoload.dirname}>/singlevideo.php?cid=<{$videoload.cid}>&amp;lid=<{$videoload.id}>"><{$videoload.videothumb}></a>
                <br>
            </td>
        </tr>
    <{/foreach}>
</table>
