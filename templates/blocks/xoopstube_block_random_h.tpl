<table width="100%" cellspacing="1" cellpadding="0" border="0">
    <tr>
        <{foreach item=videorandomh from=$block.random}>
            <td class="even" style="text-align: center; padding: 4px; vertical-align: middle;">
                <a href="<{$xoops_url}>/modules/<{$videorandomh.dirname}>/singlevideo.php?cid=<{$videorandomh.cid}>&amp;lid=<{$videorandomh.id}>"><{$videorandomh.videothumb}></a>
                <br>
                <a href="<{$xoops_url}>/modules/<{$videorandomh.dirname}>/singlevideo.php?cid=<{$videorandomh.cid}>&amp;lid=<{$videorandomh.id}>">
                    <span style="font-size: small;"><{$videorandomh.title}></span>
                </a><br>
                <span style="font-size: small;">(<{$videorandomh.date}>)</span>
            </td>
        <{/foreach}>
    </tr>
</table>
