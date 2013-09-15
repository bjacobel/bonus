<figure id="attachment<?=$id?>" data-attachment-id="<?=$id?>" data-attachment-type="<?=$type?>" class="articlemedia html-attachment <?= ($big ? 'bigphoto' : '') ?>">

    <? if(bonus()): ?>
        <div id="deleteAttachment<?=$id?>" data-attachment-id="<?=$id?>" class="delete deleteAttachment">&times;</div>
        <div id="bigEnable<?=$id?>"  data-attachment-id="<?=$id?>" data-toggle="true"  class="bigAttachmentToggle <?= ($big ? 'hide' : '') ?>">&#8689;</div>
        <div id="bigDisable<?=$id?>" data-attachment-id="<?=$id?>" data-toggle="false" class="bigAttachmentToggle <?= ($big ? '' : 'hide') ?>">&#8690;</div>
    <? endif; ?>

    <? $width = ($big ? '890' : '500'); ?>

    <div width="<?=$width?>" class="html-content">
    	<?= $content1 ?>
    </div>

    <script>
        // make the embed element smaller if it's width-constrained
        if (parseFloat($(".html-content").children()[0].width) > <?=$width?>) {
            zoom = parseFloat(<?= $width ?>) / parseFloat($(".html-content").children()[0].width);
            $(".html-content").css("zoom", zoom);
        } else { // float the non-size-constrained embed element in the middle
            margin = (<?= $width ?> - parseFloat($(".html-content").children()[0].width))/2;
            $($(".html-content").children()[0]).css("margin-left", margin);
        }
    </script>

    <figcaption>
        <? if(!empty($author_id) && !bonus()): ?>
            <p id="attachmentcredit<?=$id?>" class="photocredit">
                <?= anchor('author/'.$author_id, $author_name) ?>
            </p>
        <? elseif(bonus()): ?>
            <p id="attachmentcredit<?=$id?>" class="photocredit" contenteditable="true" title="Author"><?= (!empty($author_name) ? $author_name : ''); ?></p>
        <? endif; ?>
        <p id="attachmentcaption<?=$id?>" class="photocaption" <?if(bonus()):?>contenteditable="true" title="Caption"<?endif;?>><?= (!empty($content2) ? $content2 : ''); ?></p>
    </figcaption>

</figure>