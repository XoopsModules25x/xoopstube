<table width="100%" cellspacing="1" cellpadding="0" border="0">
    <tr>
        <{foreach item=videoload from=$block.videos}>
            <td class="even" style="text-align: center; padding: 4px; vertical-align: middle;">
                <a href="<{$xoops_url}>/modules/<{$videoload.dirname}>/singlevideo.php?cid=<{$videoload.cid}>&amp;lid=<{$videoload.id}>"><{$videoload.videothumb}></a>
                <br>
                <a href="<{$xoops_url}>/modules/<{$videoload.dirname}>/singlevideo.php?cid=<{$videoload.cid}>&amp;lid=<{$videoload.id}>">
                    <span style="font-size: small;"><{$videoload.title}></span>
                </a><br>
                <span style="font-size: small;">(<{$videoload.date}>)</span>
            </td>
        <{/foreach}>
    </tr>
</table>
