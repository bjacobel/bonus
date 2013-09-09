<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>BONUS - Issues</title>
    <link rel="shortcut icon" href="<?=base_url()?>img/o-32-invert.png">
    <link rel="stylesheet" media="screen" href="<?=base_url()?>/css/bonus.css?v=1">
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script type="text/javascript" src="http://use.typekit.com/rmt0nbm.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</head>

<body>

<div id="container">

<header>
    <h1>/BONUS</h1>
</header>

<div id="content">
        
    <h2>Issues</h2>
    
    <nav>
    <ul>
    <li><?=anchor('bonus/dashboard','Dashboard')?></li>
    <li><?=anchor('bonus/authors','Authors')?></li>
    <li><?=anchor('bonus/alerts','Alerts')?></li>
    <li><?=anchor('bonus/issues','Issues')?></li>
    </ul>
    </nav>  

    <h3>Existing PDF issues</h3>
    
    <? if(!empty($issues)): ?>
    <table>
    <tr>
        <th>Vol.</th>
        <th>No.</th>
        <th>Date</th>
        <th>Scribd URL</th>
        <th>Preview</th>
    </tr>
    <? foreach($issues as $issue): ?>
    <tr>
        <td><?=$issue->volume; ?></td>
        <td><?=$issue->issue_number; ?></td>
        <td><?=$issue->issue_date; ?></td>
        <td><a href="http://scribd.com/doc/<?=$issue->scribd?>">http://scribd.com/doc/<?=$issue->scribd;?></a></td>
        <td><img src="<?=$issue->preview?>" class="issue_thumb"></td>
    <tr>
    <? endforeach; ?>
    </table>
    <? endif; ?>

</div>
    
<footer>
    <p class="bonusquote">&ldquo;<?=$quote->quote?>&rdquo;</p>
    <p class="bonusquoteattribution">&mdash; <?=$quote->attribution?></p>
    <p class="sunbug"><a href="<?=base_url()?>">&#x2600;</a></p>
    <p class="about">Bowdoin Orient Network Update System 2.0</p>
</footer>

</div>

<? $this->load->view('bonus/bonusbar', TRUE); ?>

</body>

</html>