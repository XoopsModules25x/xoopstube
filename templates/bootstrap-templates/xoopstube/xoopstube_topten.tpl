<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="index.php"><{$module_dir}></a></li>
            <li><{$smarty.const._MD_XOOPSTUBE_POPULARITY}></li>
        </ol>        
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info" role="alert"><{$smarty.const._MD_XOOPSTUBE_DISPLAYING}><{$lang_sortby}></div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <{foreach item=ranking from=$rankings}>
            <h4><{$smarty.const._MD_XOOPSTUBE_CATEGORY}>: <{$ranking.title}></h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><{$smarty.const._MD_XOOPSTUBE_RANK}></th>
                        <th><{$smarty.const._MD_XOOPSTUBE_TITLE}></th>
                        <th><{$smarty.const._MD_XOOPSTUBE_CATEGORY}></th>
                        <th><{$smarty.const._MD_XOOPSTUBE_HITS}></th>
                        <th><{$smarty.const._MD_XOOPSTUBE_RATING}></th>
                        <th><{$smarty.const._MD_XOOPSTUBE_VOTE}></th>
                    </tr>
                </thead>
                <tbody>
                    <{foreach item=file from=$ranking.file}>
                    <tr>
                        <td><{$file.rank}></td>
                        <td><a href="singlevideo.php?cid=<{$file.cid}>&amp;lid=<{$file.id}>"><{$file.title}></a></td>
                        <td><a href="viewcat.php?cid=<{$file.cid}>"><{$file.category}></a></td>
                        <td><{$file.hits}></td>
                        <td><{$file.rating}></td>
                        <td><{$file.votes}></td>
                    </tr>
                    <{/foreach}>
                </tbody>
            </table>
        <{/foreach}>
    </div>
</div>