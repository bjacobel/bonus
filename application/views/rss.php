<?='<?xml version="1.0"?><rss version="2.0">'?> 
<?php
function xmlclean($string) {
	return str_replace(array("&", "<", ">", "\"", "'"),
        array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"), $string);
}
?>
  <channel>
    <title><?= xmlclean($title) ?></title>
    <link><?= $url ?></link>
    <description><?= xmlclean($description) ?></description>
    <? foreach($articles as $article): ?>
    <item>
       <guid><?= $article->id ?></guid>
       <title><?= xmlclean($article->title) ?></title>
       <link>http://bowdoinorient.com/article/<?= $article->id ?></link>
       <pubDate><?= gmdate(DATE_RSS, strtotime($article->date)); ?></pubDate>
       <description><?= xmlclean($article->pullquote) ?></description>
    </item>
    <? endforeach; ?>
  </channel>
</rss>