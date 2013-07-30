<!-- 
    OPTIONS:
    $bigphoto
    $author_format
    $photos
 -->
<? if($type==1): ?>
    <!-- in browse.php -->
    <div id="carousel">
        <div id="swipeview_wrapper"></div>
        <div id="swipeview_relative_nav">
            <span id="prev" onclick="carousel.prev();hasInteracted=true">&laquo;</span>
            <span id="next" onclick="carousel.next();hasInteracted=true">&raquo;</span>
        </div>
        <ul id="swipeview_nav">
            <? foreach($popular as $key => $article): ?>
            <li <? if($key==0): ?>class="selected"<? endif; ?> onclick="carousel.goToPage(<?=$key?>);hasInteracted=true"></li>
            <? endforeach; ?>
        </ul>           
    </div>
<? elseif($type==2): ?>
    <!-- in article.php -->
    <figure class="articlemedia <? if(isset($bigphoto)): ?>bigphoto<? endif; ?>">
      <div id="swipeview_wrapper" <? if(isset($author_format)): ?>class="author-swipeview"<? endif; ?>></div>
      <div id="swipeview_relative_nav">
        <span id="prev" onclick="carousel.prev();hasInteracted=true">&laquo;</span>
        <span id="next" onclick="carousel.next();hasInteracted=true">&raquo;</span>
      </div>
      <ul id="swipeview_nav">
        <? foreach($photos as $key => $photo): ?>
        <li <? if($key==0): ?>class="selected"<? endif; ?> onclick="carousel.goToPage(<?=$key; ?>);hasInteracted=true"></li>
        <? endforeach; ?>
      </ul>
    </figure>
<? elseif($type=="new"): ?>
    <div id="carousel" class="swipe">
        <div class="swipe-wrap homepage">
            <? foreach($photos as $key => $photo): ?>
                <div style="background-image:url(<?= $photo ?>); height:100%; background-repeat:no-repeat; background-size:cover"></div>
            <? endforeach; ?>
        </div>
    </div>
    <script type="text/javascript">
        window.carousel = new Swipe(document.getElementById('carousel'), {
            speed: 400,
            auto: 5500,
            continuous: true,
            disableScroll: false,
            stopPropagation: false,
        });
        $("#carousel").mouseenter(function(){window.carousel.pause();});
        $("#carousel").mouseleave(function(){window.carousel.resume();});
    </script>
<? else: ?>
    <div><h1>Error</h1></div>
<? endif; ?>