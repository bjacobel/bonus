<? $this->load->view('template/head'); ?>

<body>

<? $this->load->view('template/bodyheader', $headerdata); ?>

<div id="content">
    
    <article id="mainstory">
        
        <header>
            <hgroup>
                <!-- <h2 id="articletitle" class="articletitle">This survey's response period has closed.</h2> -->
            </hgroup>            
        </header>
        
        <div id="articlebody" class="articlebody">
            <!-- make a big div fill up some space -->
            <!-- <div style="display:block;height:500px;width:1px;"></div> -->
            
        <iframe src="https://docs.google.com/forms/d/13f9kG6Z-GrKuoxuxl-KN-vxZ-HVvwH4vIECFa1lhIHY/viewform?embedded=true" width="760" height="2200" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>                    
        </div>
      
    </article>

</div>

<? $this->load->view('template/bodyfooter', $footerdata); ?>

<? $this->load->view('bonus/bonusbar', TRUE); ?>

</body>

</html>