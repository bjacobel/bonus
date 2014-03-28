<ul class="article-list" id="latest">
    <?foreach ($latest as $article):?>
        <li><p class="article-choice" id="<?=$article->id?>"><?=$article->title?></p></li>
    <?endforeach;?>
</ul>

<ul class="article-list" id="popular_week" style="display:none">
    <?foreach ($popular_week as $article):?>
        <li><p class="article-choice" id="<?=$article->id?>"><?=$article->title?></p></li>
    <?endforeach;?>
</ul>

<ul class="article-list" id="popular_semester" style="display:none">
    <?foreach ($popular_semester as $article):?>
        <li><p class="article-choice" id="<?=$article->id?>"><?=$article->title?></p></li>
    <?endforeach;?>
</ul>