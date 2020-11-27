<div class="xoopstube">
    <div id="xoopstube_holder"></div>
    <div id="xoopstube_desc"></div>
    <div id="xoopstube_slider">
        <ul id="xoopstube_mycarousel">
            <{foreach item=video from=$block.videos name=grouploop}>
                <li>
                    <a href="http://www.youtube.com/watch?v=<{$video.vidid}>" title="<{$video.description}>">
                        <img src="http://img.youtube.com/vi/<{$video.vidid}>/default.jpg" alt="<{$video.title}>">
                    </a>
                </li>
            <{/foreach}>
        </ul>
    </div>
</div>
