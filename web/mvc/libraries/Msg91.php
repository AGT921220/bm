<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

class Msg91
{
    public $ci;
    protected $authKey;
    protected $senderID;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->model('smssettings_m');

        $msg91_bind = [];
        $get_msg91s = $this->ci->smssettings_m->get_order_by_msg91();
        foreach ( $get_msg91s as $key => $get_msg91 ) {
            $msg91_bind[ $get_msg91->field_names ] = $get_msg91->field_values;
        }
        $this->authKey  = $msg91_bind['msg91_authKey'];
        $this->senderID = $msg91_bind['msg91_senderID'];
    }

    public function send( $to, $message )
    {
        //Your message to send, Add URL encoding here.
        $message = urlencode((string) $message);

        //Define route
        $route   = 4;
        $country = 0;
        //Prepare you post parameters
        $postData = [
            'mobiles' => $to,
            'message' => $message,
            'authkey' => $this->authKey,
            'sender'  => $this->senderID,
            'route'   => $route,
            'country' => $country
        ];

        $url = "http://api.msg91.com/api/sendhttp.php";

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ]);

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        //get response
        $output = curl_exec($ch);

        //Print error if any
        if ( curl_errno($ch) !== 0 ) {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);
        return (bool) $output;
    }

}