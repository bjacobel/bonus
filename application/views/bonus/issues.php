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

    <h3>Add PDF Issue</h3>
    
    <script>
    function validateIssueForm()
    {
        var scribd_id_value=document.forms["create_issue"]["id"].value;
        var volume_value=document.forms["create_issue"]["volume"].value;
        var number_value=document.forms["create_issue"]["number"].value;
        if (!/^\d+$/i.test(volume_value))
        {
            alert("Volume must be a number.");
            return false;
        }
        if (!(number_value>=0 && number_value <=24))
        {
            alert("Issue number must be between 0 and 24.");
            return false;
        }
        if (!/^\d{9}$/i.test(scribd_id_value))
        {
            alert("Scribd IDs are nine-digit numbers found in the URLs of Scribd pages.");
            return false;
        }
        return confirm('Are you sure you want to create a new issue and Scribd preview?');
    }
    </script>
    <? 
        $attributes = array(
            'name' => 'create_issue',
            'class' => 'create_issue', 
            'id' => 'create_issue',
            'onsubmit'  => "return validateIssueForm()"
            );
        $hidden = array(
            'form_name' => 'create_issue',
            );
        echo form_open('bonus/issues',$attributes,$hidden);
    ?>
    Volume: <input type="text" name="volume"><br/>
    Number: <input type="text" name="number"><br/>
    Publish date: <input type="datetime" name="publish_date" value="<?=date("Y-m-d",time())?>"><br/>
    Scribd ID: <input type="textarea" name="id"/><br/>
    <?= form_submit('submit',"Add issue") ?>
    <?= form_close() ?>

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