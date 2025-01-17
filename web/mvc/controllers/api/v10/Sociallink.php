<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') || exit('No direct script access allowed');

class sociallink extends Api_Controller 
{
    public $load;
    /**
     * @var array<string, mixed>
     */
    public $data;
    public $retdata;
    public $sociallink_m;
    public $student_m;
    public $systemadmin_m;
    public $teacher_m;
    public $parents_m;
    public $user_m;
    public function __construct() 
    {
        parent::__construct();
        $this->load->model("sociallink_m");
        $this->load->model("systemadmin_m");
        $this->load->model("teacher_m");
        $this->load->model("student_m");
        $this->load->model("parents_m");
        $this->load->model("user_m");
        $this->load->model("usertype_m");
        $this->load->helper("text");
    }

    public function index_get($id=null) 
    {
        $this->data['usertypes'] = $this->usertype_m->get_usertype();
        $this->data['roles'] = pluck($this->data['usertypes'],'usertype','usertypeID');
        $this->retdata['uriID'] = $id;

        if((int)$id !== 0) {
            $sociallinks = $this->sociallink_m->get_order_by_sociallink(array('usertypeID' => $id));
        } else {
            $sociallinks = $this->sociallink_m->get_sociallink();
        }

        $this->retdata['alluser'] = $this->userListName($sociallinks, $this->data['roles']);
        $this->retdata['sociallinks'] = $sociallinks;
        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    private function userListName($sociallinks, $roles) 
    {
        $returnArray = [];
        $studentIDArray = [];
        $student = [];
        if(customCompute($sociallinks)) {
            $i = 0;
            foreach ($sociallinks as $sociallink) {
                if($sociallink->usertypeID == 3) {
                    $studentIDArray[$i] = $sociallink->userID;
                    $i++;
                }
            }
        }
        if(customCompute($studentIDArray)) {
            $student = $this->student_m->general_get_where_in_student($studentIDArray);
        }

        $systemadmin = $this->systemadmin_m->get_systemadmin();
        if(customCompute($systemadmin)) {
            $returnArray[1]= $this->nameImage($systemadmin, 'systemadminID', $roles);
        }

        $teacher = $this->teacher_m->get_teacher();
        if(customCompute($teacher)) {
            $returnArray[2] = $this->nameImage($teacher, 'teacherID', $roles);
        }

        if(customCompute($student)) {
            $returnArray[3] = $this->nameImage($student, 'studentID', $roles);
        }

        $parent = $this->parents_m->get_parents();
        if(customCompute($parent)) {
            $returnArray[4] = $this->nameImage($parent, 'parentsID', $roles);
        }

        $users = $this->user_m->get_user();
        if(customCompute($users)) {
            foreach ($users as $user) {
                $role = 'none';
                if(isset($roles[$user->usertypeID])) {
                    $role = $roles[$user->usertypeID];
                }
                $returnArray[$user->usertypeID][$user->userID] = ['name' => $user->name, 'usertype' => $role, 'photo' => $user->photo];
            }
        }
        return $returnArray;
    }

    private function nameImage($arrays, $primaryKey, $roles = []) 
    {
        $retArray = [];
        if(customCompute($arrays)) {
            foreach ($arrays as $array) {
                $role = 'none';
                if(isset($roles[$array->usertypeID])) {
                    $role = $roles[$array->usertypeID];
                }

                $retArray[$array->$primaryKey] = ['name' => $array->name, 'usertype' => $role, 'photo' => $array->photo];
            }
        }
        return $retArray;
    }
}
