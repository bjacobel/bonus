<!-- 
    OPTIONS:
    $bigphoto
    $author_format
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
<? else: ?>
    <div><h1>Error</h1></div>
<? endif; ?>