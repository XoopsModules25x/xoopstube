<div style="text-align:left;">
    <{foreach item=videoload from=$block.videos}>
        <li>
            <a href="<{$xoops_url}>/modules/<{$videoload.dirname}>/singlevideo.php?cid=<{$videoload.cid}>&amp;lid=<{$videoload.id}>"><{$videoload.title}></a>
            <span style="font-size: small;"> (<{$videoload.date}>)</span>
        </li>
    <{/foreach}>
</div>
