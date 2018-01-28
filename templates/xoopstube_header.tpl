<!-- Thank you for keeping this line in the template :-) //-->
<div style="display: none;"><{$ref_smartfactory}></div>
<!-- Thank you for keeping this line in the template :-) //-->


<{$xoopstube_breadcrumb}>



<{if $catarray.imageheader != ""}>
<br>
<div class="xoopstube_head_catimageheader"><{$catarray.imageheader}></div>
<br>
<{/if}>

<{if $down.imageheader != ""}>
<br>
<div class="xoopstube_head_downimageheader"><{$down.imageheader}></div>
<br>
<{/if}>

<{if $imageheader != ""}>
<br>
<div class="xoopstube_head_imageheader"><{$imageheader}></div>
<br>
<{/if}>

<{if $catarray.indexheader}>
<div class="xoopstube_head_catindexheader" align="<{$catarray.indexheaderalign}>"><p><{$catarray.indexheader}></p></div>
<br>
<{/if}>
<{if $catarray.letters}>
<div class="xoopstube_head_catletters" align="center"><{$catarray.letters}></div>
<br>
<{/if}>
<{if $catarray.toolbar}>
<div class="xoopstube_head_cattoolbar" align="center"><{$catarray.toolbar}></div>
<br>
<{/if}>
