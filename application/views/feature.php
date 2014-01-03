<?$headdata = new stdClass();
$headdata->viewtype = "feature";
$this->load->view('template/head', $headdata); ?>

<body>
    <? $headerdata->viewtype = "feature";
    $this->load->view('template/bodyheader', $headerdata); ?>

    <div id="container">

        <div id="titlepage" style="background-image:url('<?=base_url().'images/'.$article->date.'/'.$featuremedia->coverphoto->filename_large?>')">
            <div id="header-bg">
                <header>
                    <hgroup class="articletitle-group">
                  
                        <? if($article->series || bonus()): ?>
                            <h3 id="series" class="series"<?if(bonus()):?> contenteditable="true" title="Series"<?endif;?>>
                            <? if(!bonus()): ?><a href="<?=site_url()?>series/<?=$series->id?>"><? endif; ?>
                            <?=$series->name?>
                            <? if(!bonus()): ?></a><? endif; ?>
                            </h3>
                        <? endif; ?>
                      
                        <h2 id="articletitle" class="articletitle <?= ($article->published ? '' : 'draft'); ?>"<?if(bonus()):?> contenteditable="true" title="Title"<?endif;?>><?=$article->title?></h2>
                        <? if(bonus()): ?><div id="title" class="charsremaining"></div><? endif; ?>
                        <h3 id="articlesubtitle" class="articlesubtitle"<?if(bonus()):?> contenteditable="true" title="Subtitle"<?endif;?>><? if(isset($article->subtitle)): ?><?=$article->subtitle?><? endif; ?></h3>
                        <? if(bonus()): ?><div id="subtitle" class="charsremaining"></div><? endif; ?>

                    </hgroup>

                    <div id="authorblock">
                        <? if($authors): ?>
                            <? foreach($authors as $key => $author): ?>
                                <a href="<?=site_url()?>author/<?=$author->authorid?>">
                                    <div id="author<?=$author->articleauthorid?>" class="authortile<? if(bonus()):?> bonus<? endif; ?> <?if($article->opinion == '1'):?>opinion<? endif; ?>">
                                        <? if(bonus()): ?><div id="deleteAuthor<?=$author->articleauthorid?>" class="delete">&times;</div><? endif; ?>
                                        <? if(!empty($author->photo) && $article->opinion): ?><img src="<?=base_url().'images/authors/'.$author->photo?>" class="authorpic"><? endif; ?>
                                        <div class="authortext">
                                            <div class="articleauthor"><?=$author->authorname?></div>
                                            <div class="articleauthorjob"><?=$author->jobname?></div>
                                        </div>
                                    </div>
                                </a>
                            <? endforeach; ?>
                        <? endif; ?>
                        <? if(bonus()): ?>
                            <div class="authortile bonus <?if($article->opinion == '1'):?>opinion<? endif; ?>">
                                <div class="articleauthor" id="addauthor" contenteditable="true" title="Author"></div>
                                <div class="articleauthorjob" id="addauthorjob" contenteditable="true" title="Author job"></div>
                            </div>
                        <? endif; ?>
                    </div>
            
                    <p class="articledate"><time pubdate datetime="<?=$article->date?>"><?=date("F j, Y",strtotime($article->date))?></time></p> 
                </header>
            </div>
        </div>
        
        <!-- mildly hackish -->
        <script type="text/javascript">$("#titlepage").height($(window).height());</script>

        <article id="mainstory" data-article-id="<?=$article->id?>">

            <div class="sidebar-shim"></div>
            <div class="sidebar" id="toc-bar"></div>
            <? if(bonus()): ?>
                <div class="sidebar" id="bonus-bar">       
                </div>
            <? endif; ?>

            <!-- pin the sidebars to their respective sides -->
            <script>
                $offset = $("#mainstory").width() + 0.5 * ($("#container").width() - $("#mainstory").width());
                $(".sidebar#bonus-bar").css("left", $offset - 0.5 * $(".sidebar#bonus-bar").width());
            </script>

            <div id="articlebodycontainer">
        
                <div id="articlebody" class="articlebody"<?if(bonus()):?> contenteditable="true" title="Article body"<?endif;?>>
                    <? if(!empty($body)): ?>
                    <?=$body->body;?>
                    <? endif; ?>
                </div>
        
            </div>

            <div id="articlefooter">
                <? if(!bonus()): ?>
                    <!-- Disqus -->
                    <div id="disqus_thread"></div>
                    <script type="text/javascript">
                    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                        var disqus_shortname = 'bowdoinorient'; // required: replace example with your forum shortname
                        var disqus_title = '<?=addslashes($article->title)?>';

                        //disqus_identifier isn't necessary, because it can use the URL. it's preferable, though, because of different URL schemes.
                        //problem is, we used a different scheme (date&section&priority, e.g. 2012-05-04&2&1) on the old site.
                        //on newer articles (>7308), we just use the new unique article id.
                        <? if($article->id <= 7308): ?>
                            var disqus_identifier = '<?=$article->date."?".$article->section_id."?".$article->priority?>';
                        <? else: ?>
                            var disqus_identifier = '<?=$article->id?>';
                        <? endif; ?>
                            
                        /* * * DON'T EDIT BELOW THIS LINE * * */
                        (function() {
                            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                            dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
                            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                        })();
                    </script>
                    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
                <? endif; ?>
            </div>  
        </article>
    </div>

    <? $this->load->view('template/bodyfooter', $footerdata); ?>

    <? $this->load->view('bonus/bonusbar', TRUE); ?>

    <? if(bonus()): ?>
        <script>

        var titleedited=false;
        var subtitleedited=false;
        var bodyedited=false;
        var photoadded=false;
        var hasphoto=false;
        var photocreditedited=false;
        var photocaptionedited=false;

        $(document).ready(function() {
            // SET TOOLTIPS
            $("#series, #articletitle, #articlesubtitle, #addauthor, #addauthorjob, #photocreditbonus, #photocaptionbonus, #articlebody").each(function() {
                if($("#"+$(this).attr("id")).html().trim() == "" || $("#"+$(this).attr("id")).html().trim() == "<br>" || $("#"+$(this).attr("id")).html().trim() == "<p></p>") {
                    $("#"+$(this).attr("id")).addClass("tooltip");
                }
                $("#"+$(this).attr("id")).focus(function() {
                    $(this).removeClass("tooltip");
                });
                $("#"+$(this).attr("id")).blur(function() {
                    if($(this).html().trim() == "" || $(this).html().trim() == "<br>" || $(this).html().trim() == "<p></p>") {
                        $(this).addClass("tooltip");
                    }
                });
            });
        
            // SET PUBLISHED
            window.published = <?= $article->published ? 'true' : 'false' ?>;
        
            // DETECT CHANGES AND SUCH
            // surely there's a better way to handle this
            // if only i really knew javascript
            // #dry :(

            // toph this is an unbelievable clusterfuck
            // love, brian

            $('#articletitle').keydown(function() {
                titleedited=true;
                $('#articletitle').css("color", "darkred");
                $('#title.charsremaining').html(200 - $('#articletitle').html().length);
                if ($('#articletitle').html().length>200) {
                    $("button#savearticle").attr("disabled", "disabled");
                    $("#articletitle").addClass("toolongwarning");
                } else if((200 - $('#articletitle').html().length) < 25) {
                    $("#articletitle").removeClass("toolongwarning");
                    $("button#savearticle").removeAttr("disabled");
                    $('#title.charsremaining').addClass("lowchars");
                } else {
                    $('#title.charsremaining').removeClass("lowchars");
                }
            });

            $('#articletitle').keyup(function() {
                document.title = $('#articletitle').html() + " — The Bowdoin Orient";
            });

            $("#articletitle").bind('paste', function() {
                titleedited=true;
                $('#articletitle').css("color", "darkred");
            });
        
            $('#articlesubtitle').keydown(function() {
                subtitleedited=true;
                $('#articlesubtitle').css("color", "darkred");
                $('#subtitle.charsremaining').html(200 - $('#articlesubtitle').html().length);
                if ($('#articlesubtitle').html().length>200) {
                    $("button#savearticle").attr("disabled", "disabled");
                    $("#articlesubtitle").addClass("toolongwarning");
                } else if((200 - $('#articlesubtitle').html().length) < 25) {
                    $("#articlesubtitle").removeClass("toolongwarning");
                    $("button#savearticle").removeAttr("disabled");
                    $('#subtitle.charsremaining').addClass("lowchars");
                } else {
                    $('#subtitle.charsremaining').removeClass("lowchars");
                }
            });

            $('#articlesubtitle').bind('paste', function() {
                subtitleedited=true;
                $('#articlesubtitle').css("color", "darkred");
            });
        
            $('#articlebody').keydown(function() {
                bodyedited=true;
                window.onbeforeunload = "You have unsaved changes.";
                window.onbeforeunload = function(e) {
                    return "You have unsaved changes.";
                };
                $('#articlebody').css("color", "darkred");
            });

            $('#articlebody').bind('paste', function() {
                bodyedited=true;
                window.onbeforeunload = "You have unsaved changes.";
                window.onbeforeunload = function(e) {
                    return "You have unsaved changes.";
                };
                $('#articlebody').css("color", "darkred");
            });
        
            $('#photocreditbonus').keydown(function() {
                photocreditedited=true;
                $('#photocreditbonus').css("color", "darkred");
            });

            $('#photocreditbonus').bind('paste', function() {
                photocreditedited=true;
                $('#photocreditbonus').css("color", "darkred");
            });
            
            $('#photocaptionbonus').keydown(function() {
                photocaptionedited=true;
                $('#photocaptionbonus').css("color", "darkred");
            });
            
            $('#photocaptionbonus').bind('paste', function() {
                photocaptionedited=true;
                $('#photocaptionbonus').css("color", "darkred");
            });
        
            $("#publisharticle").click(function() {
                if(confirm("Is this article ready for the world?")) {
                    window.publish = true;
                    window.published = true;
                    $("#savearticle").click();
                }
            });
        
            $("#unpublish").click(function() {
                if(confirm("Are you sure you want to unpublish this article? Unless you, like, JUST published it, that's probs not kosher.")) {
                    window.unpublish = true;
                    window.published = false;
                    $("#savearticle").click();
                }
            });
        
            $("#savearticle").click(function() {
                $("#savenotify").html("Saving...");
                var statusMessage = '';
                var refresh = false;
                var calls = [];
          
                // if an image was added, save it.
                // $('#dnd-holder').length != 0 && $('#dnd-holder').attr('class') == 'backgrounded'
                if(photoadded) {
                    calls.push($.ajax({
                        type: "POST",
                        url: "<?=site_url()?>article/ajax_add_photo/<?=$article->date?>/<?=$article->id?>",
                        data: 
                            "img=" + $('#dnd-holder').css('background-image') + 
                            "&credit=" + urlencode($("#photocreditbonus").html()) +
                            "&caption=" + urlencode($("#photocaptionbonus").html()) +
                            "&hidephoto=" + urlencode($("input.hide-photo").is(':checked')),
                        success: function(result){
                            if(result=="Photo added.") {
                                refresh = true;
                            }
                            statusMessage += result;
                            // set hasphoto to true; set photoadded to false? ugh.
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown){
                            $("#savenotify").html("There was an unknown error. The site could not be reached. "+errorThrown+" "+textStatus);
                        }
                    }));
                }
          
                // save photo credit/caption edits 
                var photoEdits = {};

                $('.articlemedia.photo-wrapper').each( function(index, photo) {
                    var photoId = $("#"+photo.id).data("photo-id");
                    var thisPhotoEdits = {};
                    thisPhotoEdits["credit"] = $("#photocredit"+photoId).html();
                    thisPhotoEdits["caption"] = $("#photocaption"+photoId).html(); 
                    photoEdits[photoId] = thisPhotoEdits;
                });

                if(photoEdits.length===0) {
                    // if array is empty, i.e. no attachments were found...
                    var photoEditsJSON = false;
                } else {
                    // else serialize array for ajaxing
                    var photoEditsJSON = JSON.stringify(photoEdits);
                }
          
                // save attachment credit/caption edits
                var attachmentEdits = {};

                $('.articlemedia.video-wrapper').each( function(index, attachment) {
                    // gets attachment id from data-attachment-id attribute of figure
                    // note that this.data-attachment-id doesn't work, so i do this roundabout jquery select thing
                    var attachmentId = $("#"+attachment.id).data("attachment-id");
                    var thisAttachmentEdits = {};
                    thisAttachmentEdits["credit"]  = $("#attachmentcredit"+attachmentId).html();
                    thisAttachmentEdits["caption"] = $("#attachmentcaption"+attachmentId).html(); 
                    attachmentEdits[attachmentId] = thisAttachmentEdits;
                });
            
                if(attachmentEdits.length===0) {
                    // if array is empty, i.e. no attachments were found...
                    var attachmentEditsJSON = false;
                } else {
                    // else serialize array for ajaxing
                    var attachmentEditsJSON = JSON.stringify(attachmentEdits);
                }
          
                var ajaxrequest = {
                    title:          urlencode($("#articletitle").html()),
                    subtitle:       urlencode($("#articlesubtitle").html()),
                    series:         urlencode($("#series").html()),
                    author:         urlencode($("#addauthor").html()),
                    authorjob:      urlencode($("#addauthorjob").html()),
                    volume:         urlencode($('input[name=volume]').val()),
                    issue_number:   urlencode($('input[name=issue_number]').val()),
                    section_id:     urlencode($('input[name=section_id]').val()),
                    priority:       urlencode($('input[name=priority]').val()),
                    published:      window.published,
                    featured:       $('input[name=featured]').prop('checked'),
                    opinion:        $('input[name=opinion]').prop('checked')
                };

                if(photoEditsJSON)      { ajaxrequest.photoEdits =      urlencode(photoEditsJSON); }
                if(attachmentEditsJSON) { ajaxrequest.attachmentEdits = urlencode(attachmentEditsJSON); }
                if(bodyedited)          { ajaxrequest.body =            urlencode($("#articlebody").html()); }
          
                // write title, subtitle, author, authorjob, bonus-meta stuff
                // (regardless of whether they've been edited. sloppy.)
                // and body, only if it's been edited
                calls.push($.ajax({
                    type: "POST",
                    url: "<?=site_url()?>article/edit/<?=$article->id?>",
                    data: ajaxrequest,
                    success: function(result){
                        if(result=="Refreshing...") { refresh = true; }
                        statusMessage += result;
                        $("#savenotify").html(result);
                        if(window.published) {
                            $("#articletitle").removeClass("draft");
                            $("#publisharticle").hide();
                            $("#unpublish").show();
                        } else {
                            $("#articletitle").addClass("draft");
                            $("#publisharticle").show();
                            $("#unpublish").hide();
                        }
                            
                        titleedited=false;
                        subtitleedited=false;
                        bodyedited=false;
                        photocreditedited=false;
                        photocaptionedited=false;
                        window.onbeforeunload = null; // remove message blocking navigation away from page
                        $('#articletitle, #articlesubtitle, #articlebody, #photocreditbonus, #photocaptionbonus').css("color", "inherit");
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown){
                        $("#savenotify").html("There was an unknown error. The site could not be reached. "+errorThrown+" "+textStatus);
                    }
                }));
          
                $.when.apply($, calls).then(function() {
                    $("#savenotify").html(statusMessage);
                    if(window.publish) {
                        window.location = "<?=site_url()?>"; 
                    }
                    if(refresh) { 
                        window.location.reload(); 
                    }
                });  
            });
        
            $("#deletearticle").click(function(event) {
                event.preventDefault()
          
                if(confirm("Are you sure you want to delete this article? (If this article has already been published, it's probs not kosher to delete it.)")) {
                    $.ajax({
                        type: "POST",
                        url: "<?=site_url()?>article/ajax_delete_article/<?=$article->id?>",
                        data: "remove=true",
                        success: function(result){
                            if(result=="Article deleted.") {
                                //return home
                                window.location = "<?=site_url()?>";
                            }
                            //show alert
                            $("#savenotify").html(result);
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown){
                            $("#savenotify").html("There was an unknown error. The site could not be reached. "+errorThrown+" "+textStatus);
                        }
                    });
                }
            });
        


            $(".authortile .delete").click(function(event) {
                event.preventDefault();
                var articleAuthorId = event.target.id.replace("deleteAuthor","");
              
                $.ajax({
                    type: "POST",
                    url: "<?=site_url()?>article/ajax_remove_article_author/"+articleAuthorId,
                    data: "remove=true",
                    success: function(result){
                        if(result=="Author removed.") {
                            $("#author"+articleAuthorId).hide("fast");
                        }
                        //show alert
                        $("#savenotify").html(result);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown){
                      $("#savenotify").html("There was an unknown error. The site could not be reached. "+errorThrown+" "+textStatus);
                    }
                });
            });
        


            $(".articlemedia .deletePhoto").click(function(event) {
                var photoId = event.target.id.replace("deletePhoto","");
              
                $.ajax({
                    type: "POST",
                    url: "<?=site_url()?>article/ajax_delete_photo/"+photoId,
                    data: "remove=true",
                    success: function(result){
                        if(result=="Photo deleted.") {
                            $("#photo"+photoId).hide("fast");
                        }

                        //show alert
                        $("#savenotify").html(result);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown){
                        $("#savenotify").html("There was an unknown error. The site could not be reached. "+errorThrown+" "+textStatus);
                    }
                });
            });
        


            $(".articlemedia .bigPhotoToggle").click(function(event) {
                var photoId = $("#"+event.target.id).data("photo-id");
                var toggle = $("#"+event.target.id).data("toggle");
              
                $.ajax({
                    type: "POST",
                    url: "<?=site_url()?>article/ajax_bigphoto/"+<?=$article->id?>,
                    data: "bigphoto="+toggle,
                    success: function(result){
                        if(result=="Bigphoto enabled.") {
                            $(".photo-wrapper").addClass("bigphoto");
                            $("#bigPhotoEnable"+photoId).hide();
                            $("#bigPhotoDisable"+photoId).show();
                        } else if(result=="Bigphoto disabled.") {
                            $(".photo-wrapper").removeClass("bigphoto");
                            $("#bigPhotoEnable"+photoId).show();
                            $("#bigPhotoDisable"+photoId).hide();
                        }
                    
                        //show alert
                        $("#savenotify").html(result);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown){
                        $("#savenotify").html("There was an unknown error. The site could not be reached. "+errorThrown+" "+textStatus);
                    }
                });
            });
        
            $("#insert-code").click(function(event) {
                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "<?=site_url()?>article/ajax_add_attachment/<?=$article->id?>",
                    data: {
                        type: "html",
                        content1: urlencode($("input[name=html-code]").val())
                    },
                    dataType: 'json',
                    success: function(result){
                        console.log(result);
                        $("#savenotify").html(result.status);
                        if(result.success) {
                            $("#article-attachments").append(result.view);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown){
                        $("#savenotify").html("There was an unknown error. The site could not be reached. "+errorThrown+" "+textStatus);
                    }
                });
            });

            $("#attach-video").click(function(event) {
                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "<?=site_url()?>article/ajax_add_attachment/<?=$article->id?>",
                    data: {
                        type:       "video",
                        content1:   urlencode($('input[name=video-url]').val())
                    },
                    dataType: 'json',
                    success: function(result){
                        console.log(result);
                        $("#savenotify").html(result.status);
                        if(result.success) {
                            //if it's a youtube video and there's an existing youtube video on the page...
                            if(result.type == 'youtube' && $('.articlemedia.video-wrapper.youtube').length>0) {
                                console.log("Appending to YouTube playlist.");
                                
                                //just add this new video to the playlist
                                $('.articlemedia.video-wrapper.youtube iframe').attr('src', $('.articlemedia.video-wrapper.youtube iframe').attr('src')+result.content1+',');
                                $('.articlemedia.video-wrapper.youtube iframe').addClass('playlist');
                            } else {
                                $("#article-attachments").append(result.view);
                            }
                        
                            // clear the video URL input from the attachment form
                            $('input[name=video-url]').val('');
                        } 
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown){
                        $("#savenotify").html("There was an unknown error. The site could not be reached. "+errorThrown+" "+textStatus);
                    }
                });
            });


        
            $(".articlemedia .deleteAttachment").live("click", function(event) {
                var attachmentId = event.target.id.replace("deleteAttachment","");
              
                ajaxrequest = {
                    remove: true,
                    article_id: $("#mainstory").data('article-id')
                };

                if($("#attachment"+attachmentId).data('playlist') && $("#attachment"+attachmentId).data('playlist').length > 0) {
                    ajaxrequest.playlist = true;
                }
              
                $.ajax({
                    type: "POST",
                    url: "<?=site_url()?>article/ajax_delete_attachment/"+attachmentId,
                    data: ajaxrequest,
                    dataType: 'json',
                    success: function(result){
                        //show alert
                        $("#savenotify").html(result.status);
                        if(result.success) {
                            $("#attachment"+attachmentId).hide("fast");
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown){
                        $("#savenotify").html("There was an unknown error. The site could not be reached. "+errorThrown+" "+textStatus);
                    }
                });
            });

        
            $(".articlemedia .bigAttachmentToggle").live("click", function(event) {
                var attachmentId = $("#"+event.target.id).data("attachment-id");
                var toggle = $("#"+event.target.id).data("toggle");
              
                $.ajax({
                    type: "POST",
                    url: "<?=site_url()?>article/ajax_attachment_big/"+attachmentId,
                    data: "big="+toggle,
                    success: function(result){
                        if(result=="Big enabled.") {
                            $("#attachment"+attachmentId).addClass("bigphoto");
                            $("#bigEnable"+attachmentId).hide();
                            $("#bigDisable"+attachmentId).show();
                        } else if(result=="Big disabled.") {
                            $("#attachment"+attachmentId).removeClass("bigphoto");
                            $("#bigEnable"+attachmentId).show();
                            $("#bigDisable"+attachmentId).hide();
                        }
                        //show alert
                        $("#savenotify").html(result);
                    }
                });
            });
        });

        // ugh, i forget what this is even for.
        // i think to help autocomplete work on contenteditable?
        (function ($) {
            var original = $.fn.val;
            $.fn.val = function() {
                if ($(this).is('*[contenteditable=true]')) {
                    return $.fn.html.apply(this, arguments);
                };
                return original.apply(this, arguments);
            };
        })(jQuery);

        $(function() {
            $( "#addauthor" ).autocomplete({
                source: "<?=site_url()?>article/ajax_suggest/author/name"
            });
        });

        $(function() {
            $( "#addauthorjob" ).autocomplete({
                source: "<?=site_url()?>article/ajax_suggest/job/name"
            });
        });


        $(function() {
            $( "#photocreditbonus" ).autocomplete({
                source: "<?=site_url()?>article/ajax_suggest/author/name"
            });
        });

        <? if(!empty($photos)): ?>
            <? foreach($photos as $photo): ?>
                $(function() {
                    $( "#photocredit<?=$photo->photo_id?>" ).autocomplete({
                        source: "<?=site_url()?>article/ajax_suggest/author/name"
                    });
                });
            <? endforeach; ?>
        <? endif; ?>
      
        <? if(!empty($attachments)): ?>
            <? foreach($attachments as $attachment): ?>
                $(function() {
                    $( "#attachmentcredit<?=$attachment->id?>" ).autocomplete({
                      source: "<?=site_url()?>article/ajax_suggest/author/name"
                    });
                });
            <? endforeach; ?>
        <? endif; ?>
      
        $(function() {
            $( "#series" ).autocomplete({
                source: "<?=site_url()?>article/ajax_suggest/series/name"
            });
        });

        </script>

        <!-- CK Editor -->
        <script>

        CKEDITOR.on( 'instanceCreated', function( event ) {
            var editor = event.editor,
            element = editor.element;

            // Customize editors for headers and tag list.
            // These editors don't need features like smileys, templates, iframes etc.
            if ( element.is( 'div' ) || element.getAttribute( 'id' ) == 'taglist' ) {
                // Customize the editor configurations on "configLoaded" event,
                // which is fired after the configuration file loading and
                // execution. This makes it possible to change the
                // configurations before the editor initialization takes place.
                editor.on( 'configLoaded', function() {

                    // Remove unnecessary plugins to make the editor simpler.
                    editor.config.removePlugins = 'colorbutton,find,flash,font,forms,iframe,image,newpage,scayt,smiley,specialchar,stylescombo,templates,wsc,contextmenu,liststyle,tabletools';

                    // Rearrange the layout of the toolbar.
                    editor.config.toolbarGroups = [
                        { name: 'editing', groups: [ 'basicstyles', 'links' ], items: ['Format', 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight'] },
                        { name: 'undo' },
                        { name: 'clipboard', groups: [ 'clipboard' ], items: ['RemoveFormat'] },
                        { name: 'showblocks', items: ['ShowBlocks']}
                    ];
                });
            }
        });

      
        // We need to turn off the automatic editor creation first.
        CKEDITOR.disableAutoInline = true;
        var editor = CKEDITOR.inline( 'articlebody' );
      
        editor.on('paste', function(evt) {
            // Update the text
            // evt.editor.setData(evt.editor.getData() + ' your additional comments.');
            bodyedited=true;
            window.onbeforeunload = "You have unsaved changes.";
            
            window.onbeforeunload = function(e) {
              return "You have unsaved changes.";
            };
            
            $('#articlebody').css("color", "darkred");

        }, editor.element.$);
          
        </script>

        <!-- image upload, drag-and-drop or file upload input -->
        <!-- from @rem's http://html5demos.com/file-api-simple -->
        <!--  and @rem's http://html5demos.com/file-api -->
        <script>
        var upload = document.getElementById('imageupload'),
        holder = document.getElementById('dnd-holder');

        // upload.onchange = function (e) {
        //     e.preventDefault();

        //     var file = upload.files[0],
        //     reader = new FileReader();
        //     reader.onload = function (event) {
        //         imageLoad(event);
        //     };
            
        //     reader.readAsDataURL(file);

        //     return false;
        // };

        // drag-and-drop image
        if(holder) {
            holder.ondragover = function () { this.className = 'hover'; return false; };
            holder.ondragend = function () { this.className = ''; return false; };
            holder.ondrop = function (e) {
                this.className = '';
                e.preventDefault();
          
                var file = e.dataTransfer.files[0],
                reader = new FileReader();
                
                reader.onload = function (event) {
                    imageLoad(event);
                };
                
                reader.readAsDataURL(file);
      
                return false;
            };
        };
      
        // for when a photo is added
        function imageLoad(event) {
            photoadded=true;
            window.onbeforeunload = "You have unsaved changes.";
            window.onbeforeunload = function(e) {
                return "You have unsaved changes.";
            };
            holder.style.background = 'url(' + event.target.result + ')';
            holder.style.borderColor = 'darkred';
            holder.className += "backgrounded";
            $('#dnd-instructions').remove();
            $('#imageupload').remove();
            $('figcaption.bonus').show();
            $('figure').removeClass('mini');
        }

        function isScrolledTo(elem) {
            var docViewTop = $(window).scrollTop(); //num of pixels hidden above current screen
            var docViewBottom = docViewTop + $(window).height();
            var elemTop = $(elem).offset().top - 100; //num of pixels above the elem
            var elemBottom = elemTop + $(elem).height();
            return ((elemTop <= docViewTop));
        }

        // set up the table of contents navigation stickiness
        var catcher = $('.sidebar-shim');
        var sticky = $('.sidebar');

        $(window).scroll(function() {
            if(isScrolledTo(sticky)) {
                sticky.css('position','fixed');
                sticky.css('top','100px');

                $("#bonus-bar").css("right", 0);
                $("#bonus-bar").css("left", "");
            }
            
            var topStop = catcher.offset().top + catcher.height() - 200;
            
            if ( topStop > sticky.offset().top - 200) {
                sticky.css('position','absolute');
                sticky.css('top','0');
                
                $offset = $("#mainstory").width() + 0.5 * ($("#container").width() - $("#mainstory").width());
                $(".sidebar#bonus-bar").css("left", $offset - 0.5 * $(".sidebar#bonus-bar").width());
            }
        });
        </script>
      
    <? endif; ?>
</body>
</html>