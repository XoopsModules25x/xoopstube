<ul>
    <{foreach item=videoload from=$block.videos}>
        <li>
            <a href="<{$xoops_url}>/modules/<{$videoload.dirname}>/singlevideo.php?cid=<{$videoload.cid}>&amp;lid=<{$videoload.id}>"><{$videoload.title}></a>
            <span style="font-size: small;">(<{$videoload.hits}>&nbsp;hits)</span>
        </li>
    <{/foreach}>
</ul>
