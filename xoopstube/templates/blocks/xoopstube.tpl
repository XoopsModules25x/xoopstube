<div>
    <table border="0" cellspacing="1" cellpadding="0" class="outer">
        <tr height="14px" class="even" style="font-weight: bold; text-align: center;">
            <th><{$smarty.const._MB_XOOPSTUBE_ID}></th>
            <th><{$smarty.const._MB_XOOPSTUBE_CLIENT}></th>
            <th><{$smarty.const._MB_XOOPSTUBE_BANNERID}></th>
            <th><{$smarty.const._MB_XOOPSTUBE_CATTITLE}></th>
            <th><{$smarty.const._MB_XOOPSTUBE_IMPMADE}></th>
            <th><{$smarty.const._MB_XOOPSTUBE_IMPLEFT}></th>
            <th><{$smarty.const._MB_XOOPSTUBE_CLICKS}></th>
            <th><{$smarty.const._MB_XOOPSTUBE_CLICKS}>%</th>
        </tr>
        <{foreach item=bannerload from=$block.banners}>
            <tr style="text-align: left;">
                <td class="head" align="center"><{$bannerload.cid}></td>
                <td class="even"><{$bannerload.client}></td>
                <td class="even" align="center"><{$bannerload.bid}></td>
                <td class="even" align="left">
                    <a href="<{$xoops_url}>/modules/<{$bannerload.dirname}>/viewcat.php?cid=<{$bannerload.cat}>"><{$bannerload.cattitle}></a>
                </td>
                <td class="even" align="center"><{$bannerload.impmade}></td>
                <td class="even" align="center"><{$bannerload.impleft}></td>
                <td class="even" align="center"><{$bannerload.clicks}></td>
                <td class="even" align="center"><{$bannerload.percent}>%</td>
            </tr>
        <{/foreach}>
    </table>
</div>
