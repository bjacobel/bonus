<div class="attachment video" data-afterpar="<?=$afterpar?>">
    <? if ($type == 'vimeo'): ?>
        <iframe src="//player.vimeo.com/video/<?=$content1?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    <? elseif ($type == 'youtube'): ?>
        <iframe src="//www.youtube.com/embed/<?=$content1?>?html5=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    <? endif; ?>
    
    <script>
        $vid = $("iframe", $(".video"));
        $aspectratio = $vid.height() / $vid.width();
        //$vid.css("width", "100%");
        //$vid.css("height", $vid.css("width") * $aspectratio);
    </script>
</div>