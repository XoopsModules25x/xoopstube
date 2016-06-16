<table cellspacing="1" align="center" border="0">
    <{foreach item=videorandom from=$block.random}>
        <tr>
            <td class="even" style="text-align:center; padding: 4px;vertical-align: top;">
                <a href="<{$xoops_url}>/modules/<{$videorandom.dirname}>/singlevideo.php?cid=<{$videorandom.cid}>&amp;lid=<{$videorandom.id}>">
                    <span style="font-size: small;"><{$videorandom.title}></span>
                </a>
                <br>
                <span style="font-size: small;">(<{$videorandom.date}>)</span>
                <br>
                <a href="<{$xoops_url}>/modules/<{$videorandom.dirname}>/singlevideo.php?cid=<{$videorandom.cid}>&amp;lid=<{$videorandom.id}>"><{$videorandom.videothumb}></a>
            </td>
        </tr>
    <{/foreach}>
</table>
