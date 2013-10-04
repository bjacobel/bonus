<?php
class Author extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/New_York');
		$this->load->model('author_model','',TRUE);
		$this->load->model('article_model', '', TRUE);
		$this->load->model('attachments_model', '', TRUE);
	}
	
	public function index($id = '')
	{
		if(!$id) 
		{
			$this->error();
		}
		else
		{
			$this->view($id);
		}
	}
	
	public function error($message = '')
	{
		$data->message = $message;
		$this->load->view('error', $data);
	}
		
	public function view($id)
	{
		$author = $this->author_model->get_author($id);
		
		if(!$author) 
		{
			$this->error("No such author exists.");
		}
		else
		{
			$data->footerdata->quote = $this->attachments_model->get_random_quote();
			$data->headerdata->date = date("Y-m-d");
			$data->author = $author;
			
			$author_collaborators = $this->author_model->get_author_collaborators($id);
			$photo_collaborators = $this->author_model->get_photographer_collaborators($id);
			if ($author_collaborators && $photo_collaborators)
			{
				$data->collaborators = (object) array_merge((array) $author_collaborators, (array) $photo_collaborators);
			}
			elseif ($author_collaborators)
			{
				$data->collaborators = $author_collaborators;
			}
			elseif ($photo_collaborators)
			{
				$data->collaborators = $photo_collaborators;
			}


			$data->articles = $this->article_model->get_articles_by_date(date("Y-m-d"), false, false, false, false, $id);
			$data->popular = $this->article_model->get_popular_articles_by_date(date("Y-m-d"), false, '5',   false, $id, false);
			$data->series = $this->author_model->get_author_series($id);
			$data->longreads = $this->author_model->get_author_longreads($id);
			$data->stats = $this->author_model->get_author_stats($id);
			$data->photos = $this->attachments_model->get_author_photos($id);
			
			// meta
			$data->page_title = $author->name." — The Bowdoin Orient";
			$data->page_description = htmlspecialchars(strip_tags($author->bio));
			$data->page_type = 'profile';
			if($author->photo) $data->page_image = base_url().'images/authors/'.$author->photo;
			
			$this->load->view('author', $data);
		}
	}

	public function ajax_add_photo($article_date, $article_id)
	{
		if(!bonus()) exit("Permission denied. Try refreshing and logging in again.");
		
		$this->load->helper('file');
		
		$css_offset = 4;
		$css_offset_tail = 1;
		$png_offset = 22;
		$jpg_offset = 23;
		$gif_offset = 22;
		
		$offset = $css_offset;
		$extension = "";
		
		if(strpos(substr($this->input->post("img"), $css_offset, 15),"image/jpeg"))
		{
			$offset += $jpg_offset;
			$extension = ".jpg";
		}
		elseif(strpos(substr($this->input->post("img"), $css_offset, 15),"image/png"))
		{
			$offset += $png_offset;
			$extension = ".png";
		}
		elseif(strpos(substr($this->input->post("img"), $css_offset, 15),"image/gif"))
		{
			$offset += $gif_offset;
			$extension = ".gif";
		}
		else
		{
			exit("Only JPG, PNG, and GIF images are supported.");
		}
		
		$offset_tail = $css_offset_tail;
		$strlen_offset = $offset + $offset_tail;
		
		$img = substr($this->input->post("img"), $offset, strlen($this->input->post("img"))-($strlen_offset));

		// bug: "When Base64 gets POSTed, all pluses are interpreted as spaces."
		// this corrects for it.
		$img_fixed = str_replace(' ','+',$img);
		
		// create directory for relevant date if necessary
		if(!is_dir('images/authors'))
		{
			mkdir('images/authors');
		}
		
		// so that you can upload multiple photos to an article and the filenames won't collide,
		// we write it $articleid."_1" for the first photo attached to an article, $articleid."_2", etc.
		$article_photo_number = $this->attachments_model->count_article_photos($article_id) + 1;
		
		// write full-size image
		$filename_root = $article_id.'_'.$article_photo_number;
		$filename_original = $filename_root.$extension;
		$write_result = write_file('images/authors/'.$filename_original, base64_decode($img_fixed));
		
		// resize to small 
		// (breaks animation on animated gifs)
		$filename_small = $filename_root.'_small'.$extension; //width: 400px
		$img_config['image_library']	= 'gd2';
		$img_config['source_image']		= 'images/'.$article_date.'/'.$filename_original;
		$img_config['new_image'] 		= $filename_small;
		$img_config['maintain_ratio']	= TRUE;
		$img_config['width'] 			= 400;
		$img_config['height']			= 400;
		$this->load->library('image_lib', $img_config);
		$this->image_lib->resize();
		
		else {
			// resizing breaks animation on animated gifs, which is the only reason to use gifs. 
			// so we leave the large version untouched. but we DO make a small unanimated version above, for home page and such,
			// because gifs can get big and leaving them big could really slow down homepage (if we ever got around to using gifs)
			// that said, it could be really cool having animated gifs on the home page. think about it. #todo
			// could detect only animated gifs, but probs not worth it: http://it.php.net/manual/en/function.imagecreatefromgif.php#59787
			$filename_large = $filename_original;
		}
		
		// add photo to database
		$this->attachments_model->add_photo($filename_small, $filename_large, $filename_original, $credit, $caption, $article_id, $article_photo_number);
		exit("Photo added.");
	}
	
}
?>