<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Fonlineadmission extends Frontend_Controller
{
    public $load;
    public $session;
    public $lang;
    public $frontend_setting_m;
    public $form_validation;
    public $input;
    public $upload_data;
    public $data;
    public $onlineadmission_m;
    public $upload;
    public $uri;
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

    function __construct()
    {
        parent::__construct();
        $this->load->model("student_m");
        $this->load->model("section_m");
        $this->load->model("classes_m");
        $this->load->model("onlineadmission_m");
        $this->load->model('studentrelation_m');
        $this->load->model('studentgroup_m');
        $this->load->model('studentextend_m');
        $this->load->model('subject_m');
        $this->load->model('schoolyear_m');
        $this->load->model('usertype_m');
        $this->load->model('frontend_setting_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('onlineadmission', $language);
    }

    public function index()
    {
        redirect(base_url('frontend/home'));
    }

    protected function rules()
    {
        return array(
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|required|xss_clean|max_length[60]'
            ),
            array(
                'field' => 'dob',
                'label' => 'date of birth',
                'rules' => 'trim|required|xss_clean|max_length[10]'
            ),
            array(
                'field' => 'sex',
                'label' => 'gender',
                'rules' => 'trim|required|xss_clean|max_length[10]'
            ),
            array(
                'field' => 'religion',
                'label' => 'religion',
                'rules' => 'trim|required|xss_clean|max_length[25]'
            ),
            array(
                'field' => 'email',
                'label' => 'email',
                'rules' => 'trim|xss_clean|max_length[40]|valid_email'
            ),
            array(
                'field' => 'phone',
                'label' => 'phone',
                'rules' => 'trim|required|xss_clean|min_length[5]|max_length[25]'
            ),
            array(
                'field' => 'address',
                'label' => 'address',
                'rules' => 'trim|required|xss_clean|max_length[200]'
            ),
            array(
                'field' => 'country',
                'label' => 'country',
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_data'
            ),
            array(
                'field' => 'classesID',
                'label' => 'classes',
                'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_classesID'
            ),
             array(
                 'field' => 'photo',
                 'label' => 'photo',
                 'rules' => 'trim|xss_clean|max_length[200]|callback_photoupload'
             ),
            array(
                'field' => 'file',
                'label' => 'file',
                'rules' => 'trim|xss_clean|max_length[200]|callback_unique_document_upload'
            )
        );
    }

    protected function admission_rules()
    {
        return array(
            array(
                'field' => 'admissionID',
                'label' => 'Admission ID',
                'rules' => 'trim|xss_clean|numeric|callback_check_admissionID'
            ),
            array(
                'field' => 'phone',
                'label' => 'Phone',
                'rules' => 'trim|xss_clean|callback_check_phone'
            )
        );
    }

    public function add()
    {

        $frontendSetting = $this->frontend_setting_m->get_frontend_setting();
        $retArray['status'] = FALSE;
        if ($_POST !== []) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $retArray = $this->form_validation->error_array();
                $retArray['status'] = FALSE;
                echo json_encode($retArray);
                exit;
            } elseif ($frontendSetting->online_admission_status == 1) {
                $array = [
                    'name'          => $this->input->post('name'),
                    'dob'           => date("Y-m-d", strtotime((string) $this->input->post("dob"))),
                    'sex'           => $this->input->post('sex'),
                    'religion'      => $this->input->post('religion'),
                    'email'         => $this->input->post('email'),
                    'phone'         => $this->input->post('phone'),
                    'address'       => $this->input->post('address'),
                    'country'       => $this->input->post('country'),
                    'classesID'     => $this->input->post('classesID'),
                    'photo'         => $this->upload_data['photo']['file_name'],
                    'document'      => $this->upload_data['file']['file_name'],
                    'schoolyearID'  => $this->data['backend_setting']->school_year,
                    'create_date'   => date("Y-m-d H:i:s"),
                    'modify_date'   => date("Y-m-d H:i:s"),
                ];
                $admissionID             = $this->onlineadmission_m->insert_onlineadmission($array);
                $retArray['admissionID'] = $admissionID;
                $retArray['status']      = TRUE;
                echo json_encode($retArray);
            } else {
                $retArray['message'] = 'The admission has closed';
                echo json_encode($retArray);
            }
        } else {
            echo json_encode($retArray);
        }
    }

    public function getAdmission()
    {
        $retArray['status'] = FALSE;
        $retArray['message'] = "Invalid Information";
        if ($_POST !== []) {
            $rules = $this->admission_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $retArray = $this->form_validation->error_array();
                $retArray['status'] = FALSE;
                echo json_encode($retArray);
                exit;
            } else {
                $array                      = [];
                $array['onlineadmissionID'] = (int)$this->input->post('onlineadmissionID');
                $array['phone']             = $this->input->post('phone');
                $admission                  = $this->onlineadmission_m->get_single_onlineadmission($array);
                if (customCompute($admission)) {
                    $retArray['onlineadmissionID'] = $admission->onlineadmissionID;
                    $retArray['status'] = TRUE;
                    echo json_encode($retArray);
                } else {
                    echo json_encode($retArray);
                }
            }
        } else {
            echo json_encode($retArray);
        }
    }

    public function unique_document_upload()
    {
        $new_file = '';
        if ($_FILES["file"]['name'] != "") {
            $file_name = $_FILES["file"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random . (strtotime(date('Y-m-d H:i:s'))) . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', (string) $file_name);
            if (customCompute($explode) >= 2) {
                $new_file = $file_name_rename . '.' . end($explode);
                $config['upload_path'] = "./uploads/documents";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name'] = $new_file;
                $config['max_size'] = '5120';
                $config['max_width'] = '10000';
                $config['max_height'] = '10000';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload("file")) {
                    $this->form_validation->set_message("unique_document_upload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['file'] =  $this->upload->data();
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("unique_document_upload", "Invalid file");
                return FALSE;
            }
        } else {
            $this->form_validation->set_message("unique_document_upload", "The file is required.");
            return FALSE;
        }
    }

    public function unique_classesID()
    {
        if ($this->input->post('classesID') == 0) {
            $this->form_validation->set_message("unique_classesID", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_data($data)
    {
        if ($data != '') {
            if ($data == '0') {
                $this->form_validation->set_message('unique_data', 'The %s field is required.');
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }

    public function date_valid($date)
    {
        if ($date) {
            if (strlen((string) $date) < 10) {
                $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                return FALSE;
            } else {
                $arr = explode("-", (string) $date);
                $dd = $arr[0];
                $mm = $arr[1];
                $yyyy = $arr[2];
                if (checkdate($mm, $dd, $yyyy)) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    public function photoupload()
    {
        $id = htmlentities((string) escapeString($this->uri->segment(3)));
        $onlineadmission = array();
        if ((int)$id !== 0) {
            $onlineadmission = $this->onlineadmission_m->get_single_onlineadmission(array('onlineadmissionID' => $id));
        }

        $new_file = "default.png";
        if ($_FILES["photo"]['name'] != "") {
            $file_name = $_FILES["photo"]['name'];
            $random = rand(1, 1000000000);
            $makeRandom = hash('sha512', $random . $this->input->post('username') . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', (string) $file_name);
            if (customCompute($explode) >= 2) {
                $new_file = $file_name_rename . '.' . end($explode);
                $config['upload_path'] = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name'] = $new_file;
                $config['max_size'] = '1024';
                $config['max_width'] = '3000';
                $config['max_height'] = '3000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("photo")) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['photo'] =  $this->upload->data();
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return FALSE;
            }
        } elseif (customCompute($onlineadmission) && isset($onlineadmission->photo)) {
            $this->upload_data['photo'] = array('file_name' => $onlineadmission->photo);
            return TRUE;
        } else {
            $this->upload_data['photo'] = array('file_name' => $new_file);
            return TRUE;
        }
    }


    public function check_admissionID()
    {
        if ($this->input->post('admissionID') != '') {
            $admissions = $this->onlineadmission_m->get_single_onlineadmission(array('onlineadmissionID' => $this->input->post('admissionID')));
            if (customCompute($admissions)) {
                return TRUE;
            } else {
                $this->form_validation->set_message("check_admissionID", "The Admission ID not exits.");
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    public function check_phone()
    {
        if ($this->input->post('phone') != '') {
            $admissions = $this->onlineadmission_m->get_single_onlineadmission(array('phone' => $this->input->post('phone')));
            if (customCompute($admissions)) {
                return TRUE;
            } else {
                $this->form_validation->set_message("check_phone", "The phone number not exits.");
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }
}
