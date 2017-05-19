<!--<{$video.icons}>-->
<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-12">
    <div class="panel panel-default" style="margin-bottom: 0;">
      <div class="panel-heading">
        <h3 class="panel-title">
            <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/singlevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>" title="<{$video.title}>">
                <{$video.title}>
            </a>
        </h3>
      </div>
      <div class="panel-body">
        <div class="row">
        <{if $video.screen_shot}>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <a href="<{$xoops_url}>/modules/<{$video.module_dir}>/singlevideo.php?cid=<{$video.cid}>&amp;lid=<{$video.id}>" title="<{$video.title}>">
                <{$video.videothumb}>
            </a>
        </div>
        <{/if}>
        
        <{if $video.screen_shot}>   
        <div class="col-md-8 col-sm-6 col-xs-12">
        <{else}>
        <div class="col-md-12">
        <{/if}>
        
            <ul class="list-unstyled xt-list">
                <small>
                
                <li><strong><{$smarty.const._MD_XOOPSTUBE_SUBMITTER}>:</strong> <{$video.submitter}></li>
    
                <li><strong><{$smarty.const._MD_XOOPSTUBE_PUBLISHER}>:</strong> <{$video.publisher}></li>
    
                <li><strong><{$lang_subdate}>:</strong> <{$video.updated}></li>
    
                <li><{$video.hits|wordwrap:50:"\n":true}></li>
    
                <{if $video.showrating}>
                    <li>
                        <strong><{$smarty.const._MD_XOOPSTUBE_RATINGC}></strong>
                        <img src="<{$xoops_url}>/modules/<{$video.module_dir}>/assets/images/icon/<{$video.rateimg}>" alt=""> (<{$video.votes}>)
                    </li>
                <{/if}>
                <li><strong><{$smarty.const._MD_XOOPSTUBE_DESCRIPTIONC}></strong>
    
                    <p><{$video.description|truncate:$video.total_chars}></p>
                </li>
                 <{if $xoops_isadmin}>
                    <li><{$video.adminvideo}></li>
                <{/if}>
                </small>
            </ul>
        </div><!-- end right column -->
    </div><!-- end panel-body -->
        </div>
  </div><!-- end col-md-12 -->
 </div><!-- end panel -->
</div><!-- end row -->

