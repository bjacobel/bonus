<?php
class Browse extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/New_York');
        $this->load->model('issue_model', '', TRUE);
        $this->load->model('article_model', '', TRUE);
        $this->load->model('attachments_model', '', TRUE);
        $this->load->model('tools_model', '', TRUE);
        $this->load->model('ad_model', '', TRUE);
    }
    
    public function index()
    {
        $this->date();
    }
    
    public function error($message = '')
    {
        $data->message = $message;
        $this->load->view('error', $data);
    }
    
    public function date($date = '')
    {
        // just for testing
        //$this->output->enable_profiler(TRUE);
        
        // if no date specified, use current date
        if(!$date) $date = date("Y-m-d");
        $date_week_ago = date("Y-m-d", time()-(7*24*60*60));
        
        // get last updated date, PRIOR TO $date requested.
        $last_updated = $this->article_model->get_last_updated($date);
        $last_updated_week_ago = date("Y-m-d", strtotime($last_updated)-(7*24*60*60));
        $last_updated_fivemonths_ago = date("Y-m-d", strtotime($last_updated)-(5*4*7*24*60*60));
                
        // get latest issue <= date specified
        $issue = $this->issue_model->get_latest_issue($date);
                
        if(!$issue)
        {
            $this->error();
        }
        else
        {
            $volume = $issue->volume;
            $issue_number = $issue->issue_number;
            
            // get adjacent issues (for next/prev buttons)
            $nextissue = $this->issue_model->get_adjacent_issue($volume, $issue_number, 1);
            $previssue = $this->issue_model->get_adjacent_issue($volume, $issue_number, -1);
            
            // scribd
            try {
                $scribd_thumb_url = false;
                if($issue->scribd) {
                    $ch = curl_init();    
                    curl_setopt($ch, CURLOPT_URL, "http://api.scribd.com/api?method=thumbnail.get&api_key=34m5pzwzt3fqi0fod70cc&doc_id=".$issue->scribd);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); 
                    curl_setopt($ch, CURLOPT_TIMEOUT, 3); //timeout in seconds
                    $scribd_thumb_response = curl_exec($ch);
                    curl_close($ch);

                    if(!empty($scribd_thumb_response)){
                        libxml_use_internal_errors (TRUE);
                        $scribd_thumb = new SimpleXMLElement($scribd_thumb_response);
                        $scribd_thumb_url = $scribd_thumb->thumbnail_url;
                    }
                }
            } catch (Exception $e) {
                // scribd sucks
            }

            // featured articles for footer
            $featured = $this->article_model->get_articles_by_date($date, false, false, '5', true);
                        
            if(bonus()) {
                // latest articles
                $twenty_latest = $this->article_model->get_articles_by_date($date, false, false, '20');
                
                // popular articles
                $twenty_popular_this_week = $this->article_model->get_popular_articles_by_date($last_updated, $last_updated_week_ago, '20');
                $twenty_popular_this_semester = $this->article_model->get_popular_articles_by_date($last_updated, $last_updated_fivemonths_ago, '20');

                // popular articles with photos
                $twenty_popular_this_week_with_photo = $this->article_model->get_popular_articles_by_date($last_updated, $last_updated_week_ago, '20', false, false, false, true);
                $twenty_popular_this_semester_with_photo = $this->article_model->get_popular_articles_by_date($last_updated, $last_updated_fivemonths_ago, '20', false, false, false, true);
            } else {
                $atf = $this->tools_model->get_abovethefold();
                $leadstory = $this->article_model->get_article($atf[0]->article);
                $carousel = $this->article_model->get_article($atf[1]->article);
                $teaser1 = $this->article_model->get_article($atf[2]->article);
                $teaser2 = $this->article_model->get_article($atf[3]->article);
                $teaser3 = $this->article_model->get_article($atf[4]->article);
            }

            // php 5.4 STRONGLY objects if you don't do this and E_STRICT is turned on (which it is by default on OSX)
            $data = new stdClass();
            $data->headerdata = new stdClass();
            $data->footerdata = new stdClass();

            // get random quote
            $data->footerdata->quote = $this->attachments_model->get_random_quote();
            
            // get sections
            $sections = $this->issue_model->get_sections();
            
            foreach($sections as $section) {
                // get section articles
                $articles[$section->name] = $this->article_model->get_articles_by_date($date, $date_week_ago, $section->id);
                if(count($articles[$section->name]) < 10) {
                    $articles[$section->name] = $this->article_model->get_articles_by_date($date, false, $section->id, 10);
                }
            }
            
            // load data, view
            
            $data->headerdata->date = $date;
            $data->headerdata->volume = $volume;
            $data->headerdata->issue_number = $issue_number;
            $data->headerdata->issue = $issue;
            $data->headerdata->alerts = $this->tools_model->get_alerts();
            
            $data->date = $date;
            $data->issue = $issue;
            $data->scribd_thumb_url = $scribd_thumb_url;
            $data->nextissue = $nextissue;
            $data->previssue = $previssue;
            $data->featured = $featured;
            $data->sections = $sections;
            $data->articles = $articles;

            if(bonus()) {
                $data->latest = $twenty_latest;
                $data->popular_semester = $twenty_popular_this_semester;
                $data->popular_week = $twenty_popular_this_week;
                $data->popular_semester_photo = $twenty_popular_this_semester_with_photo;
                $data->popular_week_photo = $twenty_popular_this_week_with_photo;
            } else {
                $data->homepage = new stdClass();
                $data->homepage->leadstory = $leadstory;
                $data->homepage->carousel = $carousel;
                $data->homepage->carousel->photos = $this->attachments_model->get_photos($carousel->id);
                $data->homepage->teasers = array($teaser1, $teaser2, $teaser3);
            }

            
            // meta
            $data->page_title = '';
            $data->page_description = '';
            $data->page_type = '';

            $data->ad = $this->ad_model->get_ad();
            
            $this->load->view('browse', $data);
        }
    }
    
    public function issue($volume,$issue_number)
    {
        // get issue
        $issue = $this->issue_model->get_issue($volume, $issue_number);
        
        if(!$issue)
        {
            $this->error();
        }
        else
        {
            redirect('browse/'.$issue->issue_date, 'refresh');
        }
    }
}
?>