<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <{$category_path}>
            <{if $is_selectdate}>
            <li><a href="newlist.php?newvideoshowdays=7"><{$smarty.const._MD_XOOPSTUBE_LATESTLIST}></a></li>
            <li><{$selected_date}></li>
            <{/if}>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-sm-12" style="margin-bottom: 15px;">
        <{if $catarray.imageheader != ""}>
            <div class="xoopstube-header text-center">
                <{$catarray.imageheader}>
            </div>
            <!-- .xoopstube-header -->
        <{/if}>
    </div>
    <{if $description || $subcategories}>
    <div class="col-md-12">
        <div class="panel panel-default">
        <div class="panel-body">
            <{$description}>
            <{if $subcategories}>
                <br>
                <strong><{$smarty.const._MD_XOOPSTUBE_SUBCATLISTING}>:</strong>
                <{foreach item=subcat from=$subcategories}>
                    <a href="viewcat.php?cid=<{$subcat.id}>"><{$subcat.title}></a> (<{$subcat.totalvideos}>)
                    <{if $subcat.infercategories}>
                        <{$subcat.infercategories}>
                    <{/if}>
                <{/foreach}>
            <{/if}>
        </div>
        </div>
    </div>
    <{/if}>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="text-center xoopstube-navigation">
            <{$catarray.letters}>
        </div><!-- .xoopstube-navigation -->
    </div>
</div>

<div class="row">
    <div class="col-md-12" style="text-align: center; margin-top: 15px;">
        <h4><{$smarty.const._MD_XOOPSTUBE_SORTBY}></h4>
    </div>
    <div class="col-md-12" style="text-align: center;">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-default">
            <a href="viewcat.php?cid=<{$category_id}>&orderby=titleA">
                <span class="glyphicon glyphicon-chevron-up"></span>
            </a>
            <span><{$smarty.const._MD_XOOPSTUBE_TITLE}></span>
            <a href="viewcat.php?cid=<{$category_id}>&orderby=titleD">
                <span class="glyphicon glyphicon-chevron-down"></span>
            </a>
          </button>
          
          <button type="button" class="btn btn-sm btn-default">
              <a href="viewcat.php?cid=<{$category_id}>&orderby=dateA">
                  <span class="glyphicon glyphicon-chevron-up"></span>
              </a>
              <span><{$smarty.const._MD_XOOPSTUBE_DATE}></span>
              <a href="viewcat.php?cid=<{$category_id}>&orderby=dateD">
                  <span class="glyphicon glyphicon-chevron-down"></span>
              </a>
          </button>
          
          <button type="button" class="btn btn-sm btn-default">
              <a href="viewcat.php?cid=<{$category_id}>&orderby=ratingA">
                  <span class="glyphicon glyphicon-chevron-up"></span>
              </a>
              <{$smarty.const._MD_XOOPSTUBE_RATING}>
              <a href="viewcat.php?cid=<{$category_id}>&orderby=ratingD">
                  <span class="glyphicon glyphicon-chevron-down"></span>
              </a>
          </button>
          
          <button type="button" class="btn btn-sm btn-default">
              <a href="viewcat.php?cid=<{$category_id}>&orderby=hitsA">
                  <span class="glyphicon glyphicon-chevron-up"></span>
              </a>
              <{$smarty.const._MD_XOOPSTUBE_POPULARITY}>
              <a href="viewcat.php?cid=<{$category_id}>&orderby=hitsD">
                  <span class="glyphicon glyphicon-chevron-down"></span>
              </a>
          </button>
         
        </div>
        <!--<div class="alert alert-success" role="alert" style="margin-top: 5px"><{$lang_cursortedby}></div>-->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <{if $page_nav == true}>
            <{$pagenav}>
        <{/if}>
    </div>
    <hr>
    <div class="col-md-12">
    <{section name=i loop=$video}>
        <{include file="db:xoopstube_videoload.tpl" video=$video[i]}>
    <{/section}>
    </div>
    
    <div class="col-md-12">
        <{if $page_nav == true}>
            <{$pagenav}>
        <{/if}>
    </div>
    <div class="col-md-12">
        <{if $moderate == true}>
            <{$smarty.const._MD_XOOPSTUBE_MODERATOR_OPTIONS}>
    
            <{section name=a loop=$mod_arr}>
                <{include file="db:xoopstube_videoload.tpl" video=$mod_arr[a]}>
            <{/section}>
        <{/if}>
    </div>
    
    <div class="col-md-12" style="margin-top: 15px;">
      <{include file="db:system_notification_select.tpl"}>  
    </div>
</div>
