<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Posts extends Admin_Controller {
    public $load;
    public $session;
    public $lang;
    public $data;
    public $input;
    public $form_validation;
    public $posts_m;
    public $db;
    public $posts_category_m;
    public $uri;
    public $pages_m;
    public $upload;
    public $media_gallery_m;
    public $posts_categories_m;
    /*
    | -----------------------------------------------------
    | PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
    | -----------------------------------------------------
    | AUTHOR:			INILABS TEAM
    | -----------------------------------------------------
    | EMAIL:			info@inilabs.net
    | -----------------------------------------------------
    | COPYRIGHT:		RESERVED BY INILABS IT
    | -----------------------------------------------------
    | WEBSITE:			http://inilabs.net
    | -----------------------------------------------------
    */
    function __construct() {
        parent::__construct();
        $this->load->model("pages_m");
        $this->load->model("posts_m");
        $this->load->model("posts_categories_m");
        $this->load->model("posts_category_m");
        $this->load->model("media_gallery_m");
        $this->load->model("fmenu_relation_m");
        $this->load->helper("frontenddata");
        $language = $this->session->userdata('lang');
        $this->lang->load('posts', $language);
    }

    public function index() {
        $this->session->unset_userdata('media_gallery_stroge');
        $this->session->unset_userdata('sesPageReUrl');
        $this->session->unset_userdata('sesPageReUrlEditID');
        $this->data['posts_categorys'] = pluck_multi_array($this->posts_category_m->get_order_by_posts_category(), 'posts_categoriesID', 'postsID');
        
        $this->data['posts_categories'] = pluck($this->posts_categories_m->get_order_by_posts_categories(), 'posts_categories', 'posts_categoriesID');
        $this->data['posts'] = $this->posts_m->get_order_by_posts();
        $this->data["subview"] = "posts/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules($visibility) {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("posts_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'url',
                'label' => $this->lang->line("posts_url"),
                'rules' => 'trim|required|xss_clean|max_length[240]|callback_unique_url'
            ),
            array(
                'field' => 'featured_image',
                'label' => $this->lang->line("posts_featured_image"),
                'rules' => 'trim|xss_clean|max_length[11]|numeric'
            ),
            array(
                'field' => 'content',
                'label' => $this->lang->line("posts_content"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("posts_status"),
                'rules' => 'trim|required|xss_clean|max_length[10]'
            ), 
            array(
                'field' => 'visibility',
                'label' => $this->lang->line("posts_visibility"),
                'rules' => 'trim|required|xss_clean|max_length[40]'
            ), 
            array(
                'field' => 'publish_month',
                'label' => 'Month',
                'rules' => 'trim|required|xss_clean|max_length[2]|callback_validateDate'
            ),
            array(
                'field' => 'publish_day',
                'label' => 'Day',
                'rules' => 'trim|required|xss_clean|max_length[2]|callback_validateDate'
            ),
            array(
                'field' => 'publish_year',
                'label' => 'Year',
                'rules' => 'trim|required|xss_clean|max_length[4]|callback_validateDate'
            ),
            array(
                'field' => 'publish_hour',
                'label' => 'Hour',
                'rules' => 'trim|required|xss_clean|max_length[2]|callback_validateDate'
            ),
            array(
                'field' => 'publish_minute',
                'label' => 'Minute',
                'rules' => 'trim|required|xss_clean|max_length[2]|callback_validateDate'
            ),
        );

        if($visibility == 2) {
            $rules[] = array(
                'field' => 'protected_password',
                'label' => 'Protected Password',
                'rules' => 'trim|xss_clean|max_length[40]'
            );
        }

        return $rules;
    }    

    public function validateDate() {
        $status = FALSE;
        $error = 0;
        $month = $this->input->post('publish_month');
        $day = $this->input->post('publish_day');
        $year = $this->input->post('publish_year');
        $hour = $this->input->post('publish_hour');
        $minute = $this->input->post('publish_minute');

        if(empty($month) || empty($day) || empty($year) || empty($hour) || empty($minute)) {
            $error++;
        }

        if($error > 0) {
            $this->form_validation->set_message("validateDate", "The %s field is required.");
        } else {
            $format = "Y-m-d H:i:s";
            $date = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':'.'01';
            
            if(validateDate($date, $format)) { //From helper
                $status = TRUE;
            } else {
                $this->form_validation->set_message("validateDate", "The date & time is invalid.");
            }
        }

        return $status;
    }

    public function dateCompare($data = NULL) {
        if ($data == NULL) {
            $month = $this->input->post('publish_month');
            $day = $this->input->post('publish_day');
            $year = $this->input->post('publish_year');
            $hour = $this->input->post('publish_hour');
            $minute = $this->input->post('publish_minute');
            $date = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute;
            if(strtotime($date) === strtotime(date('Y-m-d H:i'))) {
                return 'same';
            } else {
                return date('M d, Y @ H:i', strtotime($date));
            }
        } elseif (strtotime((string) $data) === strtotime(date('Y-m-d H:i:s'))) {
            return 'same';
        } else {
            return date('M d, Y @ H:i', strtotime((string) $data));
        }
    }

    public function add() {
        $this->session->unset_userdata('media_gallery_stroge');

        if($this->session->flashdata('pageSubmitType')) { 
            $url = base_url('frontend/post/'.$this->session->flashdata('sesPageUrl'));
            redirect($url);
        }

        if($this->session->userdata('sesPageReUrl')) {
            redirect(base_url('posts/edit/'.$this->session->userdata('sesPageReUrlEditID')));
        }


        $this->data['headerassets'] = array(
            'css' => array(
                'assets/wp-style/assets/css/style.css',
                'assets/wp-style/assets/css/galleryforeditor.css',
                'assets/wp-style/assets/css/responsive.css',
                'assets/summernote/summernote.css',
                'assets/iniPlaylist/iniplaylist.css',
            )
        );

        $this->data['footerassets'] = array(
            'js' => array(
                'assets/summernote/summernote.min.js',
                'assets/wp-style/assets/js/wp.js',
            )
        );
        
        $this->data['posts_categories'] = $this->posts_categories_m->get_posts_categories();

        $this->data['media_gallerys_all'] = $this->media_gallery_m->get_media_gallery();
        
        $this->data['media_gallerys_images'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 1));

        $this->data['media_gallerys_audios'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 2));
        
        $this->data['media_gallerys_videos'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 3));



        if($_POST !== []) {

            $rules = $this->rules($this->input->post('visibility'));
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["send_status"] = $this->input->post('status');
                $this->data["send_visibility"] = $this->input->post('visibility');
                $this->data["send_publish"] = $this->validateDate();
                if($this->data["send_publish"]) {
                    $this->data['send_date_status'] = $this->dateCompare();
                }

                if(!empty($this->input->post('featured_image'))) {
                    $this->data['send_featured_image'] = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $this->input->post('featured_image')));
                } else {
                    $this->data['send_featured_image'] = array();
                }

                if(!empty($this->input->post('url'))) {
                    $this->data['send_url'] = array('status' => TRUE, 'url' => $this->input->post('url'));
                } else {
                    $this->data['send_url'] = array('status' => FALSE);
                }

                
                $this->data['send_category'] = $this->input->post('categories');
                


                $this->data["subview"] = "posts/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $date = $this->input->post('publish_year').'-'.$this->input->post('publish_month').'-'.$this->input->post('publish_day').' '.$this->input->post('publish_hour').':'.$this->input->post('publish_minute').':01';
                

                $status = $this->input->post('submit') == 'Publish' ? 1 : pageStatus($this->input->post('status'));

                $array = array(
                    'title' => ucwords((string) $this->input->post('title')),
                    'url' => $this->input->post('url'),
                    'content' =>  xssRemove(str_replace('<p><br></p>', '', (string) $_POST['content'])),
                    'status' => $status,
                    'visibility' => $this->input->post('visibility'),
                    'publish_date' => $date,
                    'parentID' => 0,
                    'postorder' => 0,
                    'featured_image' => $this->input->post('featured_image'),
                    'create_date' => date("Y-m-d h:i:s"),
                    'modify_date' => date("Y-m-d h:i:s"),
                    'create_userID' => $this->session->userdata('loginuserID'),
                    'create_username' => $this->session->userdata('username'),
                    'create_usertypeID' => $this->session->userdata('usertypeID'),
                );

                if($this->input->post('visibility') == 2) {
                    $array['password'] = $this->input->post('protected_password');
                }

                $this->posts_m->insert_posts($array);
                $lastID = $this->db->insert_id();

                if(customCompute($this->input->post('categories'))) {
                    $categories = $this->input->post('categories');
                    foreach ($categories as $categorie) {
                        $categoryArray = array(
                            'postsID' => $lastID,
                            'posts_categoriesID' => $categorie
                        );
                        $this->posts_category_m->insert_posts_category($categoryArray);
                    }
                }

                if($this->input->post('submit') == 'preview') {
                    $this->session->set_flashdata('pageSubmitType', 'preview');
                    $this->session->set_flashdata('sesPageUrl', $this->input->post('url'));
                    $this->session->set_userdata('sesPageReUrl', TRUE);
                    $this->session->set_userdata('sesPageReUrlEditID', $lastID);
                    redirect(base_url('posts/add'));

                } elseif($this->input->post('submit') == 'draft' || $this->input->post('submit') == 'review') {
                    redirect(base_url('posts/edit/'.$lastID));
                } else {
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("posts/index"));
                }
            }
        } else {
            $this->data["send_status"] = "draft";
            $this->data["send_visibility"] = 1;
            $this->data["send_publish"] = TRUE;
            $this->data["send_date_status"] = 'same';
            $this->data['send_featured_image'] = array();
            $this->data['send_slider_images'] = array();
            $this->data['send_url'] = array('status' => FALSE);
            $this->data['send_category'] = array();
            $this->data["subview"] = "posts/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->session->unset_userdata('media_gallery_stroge');
        $this->session->unset_userdata('sesPageReUrl');
        $this->session->unset_userdata('sesPageReUrlEditID');
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/wp-style/assets/css/style.css',
                'assets/wp-style/assets/css/galleryforeditor.css',
                'assets/wp-style/assets/css/responsive.css',
                'assets/summernote/summernote.css',
                'assets/iniPlaylist/iniplaylist.css',
            )
        );

        $this->data['footerassets'] = array(
            'js' => array(
                'assets/summernote/summernote.min.js',
                'assets/wp-style/assets/js/wp.js',
            )
        );
        
        $this->data['posts_categories'] = $this->posts_categories_m->get_posts_categories();

        $this->data['media_gallerys_all'] = $this->media_gallery_m->get_media_gallery();
        
        $this->data['media_gallerys_images'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 1));

        $this->data['media_gallerys_audios'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 2));
        
        $this->data['media_gallerys_videos'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 3));

        $id = htmlentities((string) escapeString($this->uri->segment(3)));
        if((int)$id !== 0) {
            $this->data['post'] = $this->posts_m->get_posts($id);
            if($this->data['post']) {
                $this->data['posts_category'] = $this->posts_category_m->get_order_by_posts_category(array('postsID' => $id));
                if($_POST !== []) {
                    $rules = $this->rules($this->input->post('visibility'));
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["send_status"] = $this->input->post('status');
                        $this->data["send_visibility"] = $this->input->post('visibility');
                        $this->data["send_publish"] = $this->validateDate();
                        if($this->data["send_publish"]) {
                            $this->data['send_date_status'] = $this->dateCompare();
                        }

                        if(!empty($this->input->post('featured_image'))) {
                            $this->data['send_featured_image'] = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $this->input->post('featured_image')));
                        } else {
                            $this->data['send_featured_image'] = array();
                        }

                        
                            
                        $this->data['send_hidden_slider_images'] = $this->input->post('hidden_slider_images');
           
                        

                        if(!empty($this->input->post('url'))) {
                            $this->data['send_url'] = array('status' => TRUE, 'url' => $this->input->post('url'));
                        } else {
                            $this->data['send_url'] = array('status' => FALSE);
                        }

                        $this->data['send_category'] = $this->input->post('categories');

                        $this->data["subview"] = "posts/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $date = $this->input->post('publish_year').'-'.$this->input->post('publish_month').'-'.$this->input->post('publish_day').' '.$this->input->post('publish_hour').':'.$this->input->post('publish_minute').':01';
                        
                        $status = pageStatus($this->input->post('status'));
                       
                        $array = array(
                            'title' => ucwords((string) $this->input->post('title')),
                            'url' => $this->input->post('url'),
                            'content' =>  xssRemove(str_replace('<p><br></p>', '', (string) $_POST['content'])),
                            'status' => $status,
                            'visibility' => $this->input->post('visibility'),
                            'publish_date' => $date,
                            'parentID' => 0,
                            'postorder' => 0,
                            'featured_image' => $this->input->post('featured_image'),
                            'modify_date' => date("Y-m-d h:i:s"),
                        );


                        if($this->input->post('visibility') == 2) {
                            $array['password'] = $this->input->post('protected_password');
                        }

                        if(customCompute($this->data['posts_category'])) {
                            foreach ($this->data['posts_category'] as $old_post_category) {
                                $this->posts_category_m->delete_posts_category_by_array(array('postsID' => $old_post_category->postsID));
                            }
                        }

                        if(customCompute($this->input->post('categories'))) {
                            $categories = $this->input->post('categories');
                            foreach ($categories as $categorie) {
                                $categoryArray = array(
                                    'postsID' => $id,
                                    'posts_categoriesID' => $categorie
                                );
                                $this->posts_category_m->insert_posts_category($categoryArray);
                            }
                        }

                        $this->posts_m->update_posts($array, $id);

                        if($this->input->post('submit') == 'preview') {
                            $this->session->set_flashdata('pageSubmitType', 'preview');
                            $this->session->set_flashdata('sesPageUrl', $this->input->post('url'));
                            redirect(base_url('posts/edit/'.$id));
                        } else {
                            redirect(base_url("posts/index"));  
                        }

                    }
                } else {

                    $this->data["send_status"] = pageStatus($this->data['post']->status, FALSE);
                    $this->data["send_visibility"] = $this->data['post']->visibility;
                    $this->data["send_publish"] = TRUE;
                    $this->data["send_date_status"] = $this->dateCompare($this->data['post']->publish_date);

                    $this->data['send_featured_image'] = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $this->data['post']->featured_image));
                    $this->data['send_hidden_slider_images'] = '';

                    $this->data['send_url'] = array('status' => TRUE, 'url' => $this->data['post']->url );
                    
                    $this->data['send_category'] = pluck($this->data['posts_category'], 'posts_categoriesID');

                    $this->data["subview"] = "posts/edit";
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function delete() {
        $id = htmlentities((string) escapeString($this->uri->segment(3)));
        if((int)$id !== 0) {
            $this->data['posts'] = $this->posts_m->get_single_posts(array('postsID' => $id));
            if($this->data['posts']) {
                $this->posts_m->delete_posts($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("posts/index"));
            } else {
                redirect(base_url("posts/index"));
            }
        } else {
            redirect(base_url("posts/index"));
        }
    }

    public function unique_url() {
        $id = htmlentities((string) escapeString($this->uri->segment(3)));
        if((int)$id !== 0) {
            $page = $this->pages_m->get_order_by_pages(array("url" => $this->input->post("url")));
            $post = $this->posts_m->get_order_by_posts(array("url" => $this->input->post("url"), 'postsID !=' => $id));

            if(customCompute($page)) {
                $this->form_validation->set_message("unique_url", "The %s is already exists.");
                return FALSE;
            }


            if(customCompute($post)) {
                $this->form_validation->set_message("unique_url", "The %s is already exists.");
                return FALSE;
            }

            return TRUE;
        } else {
            $page = $this->pages_m->get_order_by_pages(array("url" => $this->input->post("url")));
            $post = $this->posts_m->get_order_by_posts(array("url" => $this->input->post("url")));

            if(customCompute($page)) {
                $this->form_validation->set_message("unique_url", "The %s is already exists.");
                return FALSE;
            }

            if(customCompute($post)) {
                $this->form_validation->set_message("unique_url", "The %s is already exists.");
                return FALSE;
            }

            return TRUE;
        }
    }


    public function fileUpload() {
        $message                        = '';
        $insert_media_content           = '';
        $create_gallery_content         = '';
        $create_audio_playlist_content  = '';
        $create_video_playlist_content  = '';
        $featured_image_content         = '';
        $set_featured_image_content     = '';
        $set_slider_images_content      = '';
        $status                         = FALSE;
        $focus_id                       = $this->input->post('focus_id');
        $media_gallery_type             = $this->input->post('media_gallery_type');


        if($focus_id && $media_gallery_type) {
            if((int)$media_gallery_type !== 0) {
                if($media_gallery_type >= 1 && $media_gallery_type <= 4) {

                    if($media_gallery_type == 1) {
                        $fileType = "gif|jpg|png|mp3|pcm|wav|aac|ogg|wma|mkv|flv|vob|wmv|mpg|avi|webm|ogv|mp4|3gp";
                    } elseif($media_gallery_type == 2) {
                        $fileType = "gif|jpg|png";
                    } elseif($media_gallery_type == 3) {
                        $fileType = 'mp3|pcm|wav|aac|ogg|wma';
                    } elseif($media_gallery_type == 4) {
                        $fileType = 'mkv|flv|vob|wmv|mpg|avi|mp4|webm|ogv|mp4|3gp';
                    } else {
                        $fileType = 'none';
                    }

                    if($fileType != 'none') {
                        if($_FILES["file"]['name'] != "") {
                            $file_name          = $_FILES["file"]['name'];
                            $random             = random19();
                            $makeRandom         = hash('sha512', $random. $_FILES["file"]['name'] . config_item("encryption_key"));
                            $file_name_rename   = $makeRandom;
                            $explode = explode('.', (string) $file_name);
                            if(customCompute($explode) >= 2) {
                                $new_file = $file_name_rename.'.'.end($explode);
                                $config['upload_path']      = "./uploads/gallery";
                                $config['allowed_types']    = $fileType;
                                $config['file_name']        = $new_file;
                                $config['max_size']         = '819200';
                                $config['max_width']        = '5000';
                                $config['max_height']       = '5000';
                                $this->load->library('upload', $config);
                                if(!$this->upload->do_upload("file")) {
                                    $message = str_replace('</p>', '', str_replace('<p>', '', (string) $this->upload->display_errors()));
                                } else {
                                    $media_gallery_type = $this->checkFileType($_FILES['file']);
                                    $fileArray = array(
                                        'media_gallery_type' => $media_gallery_type,
                                        'file_type' => end($explode),
                                        'file_name' => $new_file,
                                        'file_original_name' => $file_name,
                                        'file_title' => pathinfo((string) $file_name, PATHINFO_FILENAME),
                                        'file_size' => formatSizeUnits($_FILES['file']['size']),
                                        'file_upload_date' => date("Y-m-d h:i:s"),
                                    );

                                    if($media_gallery_type == 1) {
                                        $image_info = getimagesize($_FILES["file"]["tmp_name"]);
                                        $image_width = $image_info[0];
                                        $image_height = $image_info[1];

                                        $fileArray['file_width_height'] = $image_width . ' x ' .$image_height;
                                    } elseif($media_gallery_type == 2) {
                                        // $fileArray['file_length'] = $_FILES['file']['hours'];
                                    } elseif($media_gallery_type == 3) {
                                        // $fileArray['file_length'] = $_FILES['file']['hours'];

                                        // $getID3 = new getID3;
                                        // $file = $getID3->analyze($_FILES["file"]['name']);
                                    }

                                    

                                    $this->media_gallery_m->insert_media_gallery($fileArray);
                                    $status = TRUE;
                                    $message = 'Success';

                                    $this->data['media_gallerys_all'] = $this->media_gallery_m->get_media_gallery();

                                    $this->data['media_gallerys_images'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 1));

                                    $this->data['media_gallerys_audios'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 2));
                                    
                                    $this->data['media_gallerys_videos'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 3));

                                    $insert_media_content = $this->load->view('posts/content/insert_media', $this->data, true);
                                    $create_gallery_content = $this->load->view('posts/content/create_gallery', $this->data, true);
                                    $create_audio_playlist_content = $this->load->view('posts/content/create_audio_playlist', $this->data, true);
                                    $create_video_playlist_content = $this->load->view('posts/content/create_video_playlist', $this->data, true);
                                    $featured_image_content = $this->load->view('posts/content/featured_image', $this->data, true);
                                    $set_featured_image_content = $this->load->view('posts/content/set_featured_image', $this->data, true);

                                    $set_slider_images_content = $this->load->view('posts/content/set_slider_images', $this->data, true);
                                }
                            } else {
                                $message = 'Invalid file';
                            }
                        } else {
                            $message = 'File does not found';
                        }
                    } else {
                        $message = 'File type is not match';
                    }
                } else {
                    $message = 'Media gallery type id is not match';
                }
            } else {
                $message = 'Media gallery type is not integer';
            }
        } else {
            $message = 'Focus and media gallery type empty';
        }

        $json = array(
            "message" => $message, 
            'focus_id' => $focus_id, 
            'insert_media_content' => $insert_media_content, 
            'create_gallery_content' => $create_gallery_content, 
            'create_audio_playlist_content' => $create_audio_playlist_content, 
            'create_video_playlist_content' => $create_video_playlist_content,
            'featured_image_content' => $featured_image_content, 
            'set_featured_image_content' => $set_featured_image_content, 
            'set_slider_images_content' => $set_slider_images_content,
            'status' => $status,
        );
        header("Content-Type: application/json", true);
        echo json_encode($json);
        exit;
    }
    
    public function getFileInfo() {
        $id =  htmlentities((string) escapeString($this->input->post('id')));
        $media_type =  htmlentities((string) escapeString($this->input->post('media_type')));
        $send_status =  htmlentities((string) escapeString($this->input->post('send_status')));
        $imageArray['file_status'] = false;
        $imageArray['content'] = '';

        if((int) $id && $media_type) {
            $this->data['image_info'] = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $id));

            if(customCompute($this->data['image_info'])) {
                $imageArray = (array) $this->data['image_info'];
                $imageArray['file_status'] = true;

                if($media_type == 1) {
                    $imageArray['content'] = $this->load->view('posts/content/callImageInfo', $this->data, true);
                } elseif($media_type == 2) {
                    $imageArray['content'] = $this->load->view('posts/content/callAudioInfo', $this->data, true);
                } elseif($media_type == 3) {
                    $imageArray['content'] = $this->load->view('posts/content/callVideoInfo', $this->data, true);
                }
            }
        }

        header("Content-Type: application/json", true);
        echo json_encode($imageArray);
        exit;
    }

    public function deleteFileInfo() {
        $id                             = $this->input->post('id');
        $message                        = '';
        $insert_media_content           = '';
        $create_gallery_content         = '';
        $create_audio_playlist_content  = '';
        $create_video_playlist_content  = '';
        $featured_image_content         = '';
        $set_featured_image_content     = '';
        $set_slider_images_content      = '';
        $status                         = false;
        if((int) $id !== 0) {
            $image_info = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $id));
            if(customCompute($image_info)) {

                if(config_item('demo') == FALSE && file_exists(FCPATH.'uploads/gallery/'.$image_info->file_name)) {
                    unlink(FCPATH.'uploads/gallery/'.$image_info->file_name);
                }

                $this->media_gallery_m->delete_media_gallery($id);
                $message = 'Success';
                $status = true;

                $this->data['media_gallerys_all'] = $this->media_gallery_m->get_media_gallery();

                $this->data['media_gallerys_images'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 1));

                $this->data['media_gallerys_audios'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 2));
                
                $this->data['media_gallerys_videos'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 3));

                $insert_media_content = $this->load->view('posts/content/insert_media', $this->data, true);
                $create_gallery_content = $this->load->view('posts/content/create_gallery', $this->data, true);
                $create_audio_playlist_content = $this->load->view('posts/content/create_audio_playlist', $this->data, true);
                $create_video_playlist_content = $this->load->view('posts/content/create_video_playlist', $this->data, true);
                $featured_image_content = $this->load->view('posts/content/featured_image', $this->data, true);
                $set_featured_image_content = $this->load->view('posts/content/set_featured_image', $this->data, true);
                $set_slider_images_content = $this->load->view('posts/content/set_slider_images', $this->data, true);
            } else {
                $message = 'File does not found';
            }
        } else {
            $message = 'File ID is not integer';
        }

        $json = array(
            "message" => $message,
            'insert_media_content' => $insert_media_content, 
            'create_gallery_content' => $create_gallery_content, 
            'create_audio_playlist_content' => $create_audio_playlist_content, 
            'create_video_playlist_content' => $create_video_playlist_content,
            'featured_image_content' => $featured_image_content, 
            'set_featured_image_content' => $set_featured_image_content, 
            'set_slider_images_content' => $set_slider_images_content,
            'status' => $status
        );

        header("Content-Type: application/json", true);
        echo json_encode($json);
        exit;
    }

    public function checkFileType($file) {
        $filetype = 'none';
        if(isset($file)) {
            $mime = $file['type'];
            if (strstr((string) $mime, "video/")) {
                $filetype = 3;
            } elseif (strstr((string) $mime, "image/")) {
                $filetype = 1;
            } elseif (strstr((string) $mime, "audio/")) {
                $filetype = 2;
            }
        }
        return $filetype;
    }

    public function setFileToEditor() {

        $insert_media_content           = '';
        $create_gallery_content         = '';
        $create_audio_playlist_content  = '';
        $create_video_playlist_content  = '';
        $featured_image_content         = '';
        $set_featured_image_content     = '';
        $set_slider_images_content      = '';

        $array = array();
        $i = 1;
        $audioStatus = FALSE;
        $videoStatus = FALSE;
        $imageStatus = FALSE;
        $header = '';
        $footer = '';
        $rand = '';
        $this->data['rand'] = '';
        $content = '';
        $warpContent = '';
        $message = '';
        $status = FALSE;
        $allID =  htmlentities((string) escapeString($this->input->post('allID')));
        $media_type =  htmlentities((string) escapeString($this->input->post('media_type')));
        $ulclass_type =  htmlentities((string) escapeString($this->input->post('ulclass_type')));

        if((int)$media_type && $allID && $ulclass_type) {
            $expID = explode(',', $allID);
            if (customCompute($expID) >= 2) {
                $sessionData = $this->session->userdata('media_gallery_stroge');
                $lastID = end($expID);
                foreach ($expID as $expKey => $expValue) {
                    if($expValue !== '') {
                        $get_media_gallery = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $expValue));

                        if(isset($sessionData[$expValue])) { 
                            $array = $sessionData[$expValue];
                            if(customCompute($get_media_gallery)) {
                                // $array['file_original_name'] = $sessionData[$expValue]['file_title'].'.'.$get_media_gallery->file_type;     
                                $this->media_gallery_m->update_media_gallery($array, $expValue);
                            }
                        } else {
                            $message = 'Some session unset';
                        }

                        $get_media_gallery = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $expValue));

                        if(customCompute($get_media_gallery)) {
                            if($get_media_gallery->media_gallery_type == 1) {
                                if($ulclass_type == 'set_slider_images_type') {
                                    $this->data['imageContent'] = $get_media_gallery;

                                    $imageContent = $this->load->view('posts/content/image/slider_full_content', $this->data, true);

                                    $warpContent .= $imageContent;
                                    if($lastID == $expValue) {
                                        $content .= $warpContent;
                                    }
                                } elseif($ulclass_type == 'create_gallery_type') {
                                    $this->data['imageContent'] = $get_media_gallery;


                                    if($imageStatus == FALSE) {
                                        $header = $this->load->view('posts/content/image/gallery_header', $this->data, true);
                                        $footer = $this->load->view('posts/content/image/gallery_footer', $this->data, true);
                                        $imageStatus = TRUE;
                                    }

                                    $imageContent = $this->load->view('posts/content/image/gallery_content', $this->data, true);

                                    $warpContent .= $imageContent;
                                    if($lastID == $expValue) {
                                        $content .= $header;
                                        $content .= $warpContent;
                                        $content .= $footer;

                                    }
                                } else {
                                    $warpContent = '<img src="'.base_url('uploads/gallery/'.$get_media_gallery->file_name).'" alt="'.$get_media_gallery->file_alt_text.'">';

                                    $content .= $warpContent;
                                }
                            } elseif($get_media_gallery->media_gallery_type == 2) {
                                $this->data['audioContent'] = $get_media_gallery;
                                if($audioStatus == FALSE) {
                                    $rand = random19();
                                    $this->data['rand'] = $rand;
                                    $header = $this->load->view('posts/content/audio/audio_header', $this->data, true);
                                    $footer = $this->load->view('posts/content/audio/audio_footer', $this->data, true);
                                }
                                $this->data['i'] = $i;
                                $this->data['audioStatus'] = $audioStatus;
                                $audioContent = $this->load->view('posts/content/audio/audio_content', $this->data, true);
                                $audioStatus = TRUE;
                                $i++;

                                $warpContent .= $audioContent;
                                if($lastID == $expValue) {
                                    $content .= $header;
                                    $content .= $warpContent;
                                    $content .= $footer;
                                }
                            } elseif($get_media_gallery->media_gallery_type == 3) {

                                $this->data['videoContent'] = $get_media_gallery;
                                if($videoStatus == FALSE) {
                                    $rand = random19();
                                    $this->data['rand'] = $rand;
                                    $header = $this->load->view('posts/content/video/video_header', $this->data, true);
                                    $footer = $this->load->view('posts/content/video/video_footer', $this->data, true);
                                }
                                $this->data['i'] = $i;
                                $this->data['videoStatus'] = $videoStatus;
                                $videoContent = $this->load->view('posts/content/video/video_content', $this->data, true);
                                $videoStatus = TRUE;
                                $i++;

                                $warpContent .= $videoContent;
                                if($lastID == $expValue) {
                                    $content .= $header;
                                    $content .= $warpContent;
                                    $content .= $footer;
                                }
                            }
                        }

                    } else {
                        $message = 'Empty each value';
                    }
                }
                $message = 'Success';
                $status = TRUE;
            } elseif ((int)$allID !== 0) {
                $sessionData = $this->session->userdata('media_gallery_stroge');
                if(isset($sessionData[$allID])) {
                    $array = $sessionData[$allID];
                    $this->media_gallery_m->update_media_gallery($array, $allID);
                } else {
                    $message = 'Some session unset';
                }
                $get_media_gallery = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $allID));
                if(customCompute($get_media_gallery)) {
                    if($get_media_gallery->media_gallery_type == 1) {
                        $content = '<img src="'.base_url('uploads/gallery/'.$get_media_gallery->file_name).'" alt="'.$get_media_gallery->file_alt_text.'">';
                    } elseif($get_media_gallery->media_gallery_type == 2) {
                        $rand = random19();
                        $this->data['rand'] = $rand;
                        $this->data['audioContent'] = $get_media_gallery;
                        $content = $this->load->view('posts/content/audio/audio_single', $this->data, true);
                    } elseif($get_media_gallery->media_gallery_type == 3) {
                        $rand = random19();
                        $this->data['rand'] = $rand;
                        $this->data['videoContent'] = $get_media_gallery;
                        $content = $this->load->view('posts/content/video/video_single', $this->data, true);
                    }
                }
                $message = 'Success';
                $status = TRUE;
            } else {
                $message = 'ID is not int';
            }
        } else {
            $message = 'Some poost data missing';
        }



        $this->data['media_gallerys_all'] = $this->media_gallery_m->get_media_gallery();

        $this->data['media_gallerys_images'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 1));

        $this->data['media_gallerys_audios'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 2));
        
        $this->data['media_gallerys_videos'] = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 3));

        $insert_media_content = $this->load->view('posts/content/insert_media', $this->data, true);
        $create_gallery_content = $this->load->view('posts/content/create_gallery', $this->data, true);
        $create_audio_playlist_content = $this->load->view('posts/content/create_audio_playlist', $this->data, true);
        $create_video_playlist_content = $this->load->view('posts/content/create_video_playlist', $this->data, true);
        $featured_image_content = $this->load->view('posts/content/featured_image', $this->data, true);
        $set_featured_image_content = $this->load->view('posts/content/set_featured_image', $this->data, true);
        $set_slider_images_content = $this->load->view('posts/content/set_slider_images', $this->data, true);


        header("Content-Type: application/json", true);
        echo json_encode(
            array(
                'message' => $message, 
                'status' => $status, 
                'content' => $content,
                'insert_media_content' => $insert_media_content, 
                'create_gallery_content' => $create_gallery_content, 
                'create_audio_playlist_content' => $create_audio_playlist_content, 
                'create_video_playlist_content' => $create_video_playlist_content,
                'featured_image_content' => $featured_image_content, 
                'set_featured_image_content' => $set_featured_image_content, 
                'set_slider_images_content' => $set_slider_images_content,
            )
        );
        exit;
    }

    public function setFileInfo() {
        $media_galleryID = xssRemove($this->input->post('hidden_id_field'));
        if(!empty($media_galleryID)) {
            $file_title = xssRemove(htmlentities((string) escapeString($this->input->post('file_title'))));
            $file_caption = xssRemove(htmlentities((string) escapeString($this->input->post('file_caption'))));
            $file_description = xssRemove(htmlentities((string) escapeString($this->input->post('file_description'))));
            $file_alt_text = xssRemove(htmlentities((string) escapeString($this->input->post('file_alt_text'))));
            $file_artist = xssRemove(htmlentities((string) escapeString($this->input->post('file_artist'))));
            $file_album = xssRemove(htmlentities((string) escapeString($this->input->post('file_album'))));

            if(isset($file_title) && isset($file_caption) && isset($file_description) && isset($file_alt_text) && isset($file_artist) && isset($file_album)) {
                $dData = $this->session->userdata('media_gallery_stroge');
                if($dData == '') {
                    $newdData = array(
                        $media_galleryID => array(
                            'file_title'        => $file_title,
                            'file_caption'      => $file_caption,
                            'file_description'  => $file_description,
                            'file_alt_text'     => $file_alt_text,
                            'file_artist'       => $file_artist,  
                            'file_album'        => $file_album,
                        )
                    );
                    $this->session->set_userdata('media_gallery_stroge', $newdData);
                } else {
                    $dData[$media_galleryID] = array(
                        'file_title'        => $file_title,
                        'file_caption'      => $file_caption,
                        'file_description'  => $file_description,
                        'file_alt_text'     => $file_alt_text,
                        'file_artist'       => $file_artist,  
                        'file_album'        => $file_album,  
                    );
                    $this->session->set_userdata('media_gallery_stroge', $dData);
                }
            }
        }
        $dData = $this->session->userdata('media_gallery_stroge');
        echo 'success';
    }

    public function addCategory() {
        $category =  htmlentities((string) xssRemove($this->input->post('category')));
        $message = '';
        $status = FALSE;
        $content = '';
        if($category !== '') {
            $array = array(
                'posts_categories' => $category, 
                'posts_slug' => '#',
                'posts_parent' => 0,
                'posts_description' => ''
            );

            $posts_categories = $this->posts_categories_m->get_single_posts_categories(array('posts_categories' => $category));

            if(!customCompute($posts_categories)) {
                $this->posts_categories_m->insert_posts_categories($array);
                $lastID = $this->db->insert_id();

                $status = TRUE;
                $content = '<div class="form-group ini-dis-flex">
                    <input id="'.$category.'" class="categories" name="categories[]" value="'.$lastID.'" type="checkbox" checked>
                    <label for="'.$category.'">'.$category.'</label>
                </div>';
                $message = 'Success';
            } else {
                $message = 'error';
            }
        }


        header("Content-Type: application/json", true);
        echo json_encode(array('message' => $message, 'status' => $status, 'content' => $content));
        exit;
    }   
}

?>