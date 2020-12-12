<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
ini_set('max_execution_time', 0);
ini_set('memory_limit','2048M');
ini_set('error_reporting', 'E_ALL & ~E_NOTICE');


class Services extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }

        $this->load->library('session');

        $this->store_salt = $this->config->item('store_salt', 'ion_auth');
        $this->salt_length = $this->config->item('salt_length', 'ion_auth');
        // initialize hash method options (Bcrypt)
        $this->hash_method = $this->config->item('hash_method', 'ion_auth');
        $this->default_rounds = $this->config->item('default_rounds', 'ion_auth');
        $this->random_rounds = $this->config->item('random_rounds', 'ion_auth');
        $this->min_rounds = $this->config->item('min_rounds', 'ion_auth');
        $this->max_rounds = $this->config->item('max_rounds', 'ion_auth');
        if ($this->hash_method == 'bcrypt') {
            if ($this->random_rounds) {
                $rand = rand($this->min_rounds, $this->max_rounds);
                $params = array('rounds' => $rand);
            } else {
                $params = array('rounds' => $this->default_rounds);
            }

            $params['salt_prefix'] = $this->config->item('salt_prefix', 'ion_auth');
            $this->load->library('bcrypt', $params);
        }

        $this->load->model('Jobs_Model');
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function authenticateForgotUser($token)
    {
        $ret = $this->Home_Model->selectWhere('users', ['forgotten_password_code' => $token]);
        if ($ret == false):
            //return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
            return false;
        else:
            return true;
        endif;
    }

    public function authenticateForgotTech($token)
    {
        $ret = $this->Home_Model->selectWhere('technicians', ['forgotten_password_code' => $token]);
        if ($ret == false):
            //return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
            return false;
        else:
            return true;
        endif;
    }

    public function authenticateAccessUser($token)
    {
        $ret = $this->Home_Model->selectWhere('users', ['accessToken' => $token]);
        if ($ret == false):
            //return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
            return false;
        else:
            return true;
        endif;
    }

    public function authenticateUser($userId, $token)
    {
        $ret = $this->Home_Model->selectWhere('users', ['id' => $userId, 'accessToken' => $token]);
        if ($ret == false):
            //return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
            return false;
        else:
            return true;
        endif;
    }

    public function authenticate($userId, $token, $table)
    {
        $ret = $this->Home_Model->selectWhere($table, ['id' => $userId, 'accessToken' => $token]);
        if ($ret == false):
            //return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
            return false;
        else:
            return true;
        endif;
    }

    public function hash_password_db($id, $password, $table, $use_sha1_override = FALSE)
    {
        if (empty($id) || empty($password)) {
            return FALSE;
        }


        $query = $this->db->select('password, salt')
            ->where('id', $id)
            ->limit(1)
            ->order_by('id', 'desc')
            ->get($table);

        $hash_password_db = $query->row();

        if ($query->num_rows() !== 1) {
            return FALSE;
        }

        // bcrypt
        if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
            if ($this->bcrypt->verify($password, $hash_password_db->password)) {

                return TRUE;
            }

            return FALSE;
        }

        // sha1
        if ($this->store_salt) {
            $db_password = sha1($password . $hash_password_db->salt);
        } else {
            $salt = substr($hash_password_db->password, 0, $this->salt_length);

            $db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }

        if ($db_password == $hash_password_db->password) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkPhoneNumber(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('country_code', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('Country Code required!'));
            } elseif (!array_key_exists('phone_number', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('Phone Number required!'));
            } elseif (!array_key_exists('app_type', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('app_type required!'));
            } else {
                $table = "";
                if ($postdata['app_type'] == 0) {
                    $table = 'users';
                }else {
                    $table = 'technicians';
                }
                $phone_number = $postdata['country_code'].$postdata['phone_number'];
                $ret = $this->Home_Model->selectWhere($table, array('phone' => $phone_number));
                if ($ret != false) {
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(402)
                        ->set_output(json_encode('Phone Number is already registered!'));
                }else {
                    //"api_key": "ioTGgPM2TBtd30eZB1eK3vVTySK8auJm",
                    $opts = array('http' =>
                        array(
                            'method'  => 'POST',
                            'header'=>"Content-Type:application/json\r\n",
                            'content' => '{
                              "api_key": "mX6QlVdByYBh2ekjKYl3WMD3LQ6ZTKxo",
                              "via": "sms",
                              "country_code": "'.$postdata['country_code'].'",
                              "phone_number": "'.$postdata['phone_number'].'"
                            }'
                        )
                    );
                    $context = stream_context_create($opts);
                    $result = file_get_contents('https://api.authy.com/protected/json/phones/verification/start', false, $context);
                    if($result){
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output($result);
                    }else{
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)
                            ->set_output(json_encode('Invalid number.'));
                    }
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function verificationCode(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('country_code', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('Country Code required!'));
            } elseif (!array_key_exists('phone_number', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('Phone Number required!'));
            }
            elseif (!array_key_exists('code', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('Verification Code required!'));
            }elseif (!array_key_exists('app_type', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('app_type required!'));
            } else {

                $opts = array('http' =>
                    array(
                        'method'  => 'GET'
                    )
                );
                $context = stream_context_create($opts);
                $result = json_decode(file_get_contents('https://api.authy.com/protected/json/phones/verification/check?api_key=mX6QlVdByYBh2ekjKYl3WMD3LQ6ZTKxo&country_code='.$postdata['country_code'].'&phone_number='.$postdata['phone_number'].'&verification_code='.$postdata['code'], false, $context));

                if($result){
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($result));
                }else {
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(404)
                        ->set_output("Invalid Code");
                }


            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function register()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('first_name', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('first_name required!'));
            } elseif (!array_key_exists('last_name', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('last_name required!'));
            } elseif (!array_key_exists('email', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('email required!'));
            } elseif (!array_key_exists('password', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('password required!'));
            } elseif (!array_key_exists('dateofbirth', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('dateofbirth required!'));
            } elseif (!array_key_exists('gender', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('gender required!'));
            } elseif (!array_key_exists('phone', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('phone required!'));
            } elseif (!array_key_exists('biodata', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('biodata required!'));
            } elseif (!array_key_exists('app_type', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('app_type required!'));
            } else {
                $usertype = false;
                if ($postdata['app_type'] == 0) {
                    if (!array_key_exists('usertype', $postdata)) {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)
                            ->set_output(json_encode('usertype required!'));
                    } else {
                        $table = 'users';
                        $ret = $this->Home_Model->selectWhere($table, array('email' => $postdata['email']));
                        if ($ret != false) {
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(404)
                                ->set_output(json_encode('Email ID is already registered!'));
                        } else {
                            $usertype = $postdata['usertype'];
                        }
                    }
                } else {
                    if (!array_key_exists('image', $postdata) || $postdata['image'] == "") {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)
                            ->set_output(json_encode('image required!'));
                    } else {
                        $table = 'technicians';
                        $ret = $this->Home_Model->selectWhere($table, array('email' => $postdata['email']));
                        if ($ret != false) {
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(404)
                                ->set_output(json_encode('Email ID is already registered!'));
                        }
                    }

                }

                $ret = $this->Home_Model->selectWhere($table, array('phone' => $postdata['phone']));
                if ($ret != false) {
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(402)
                        ->set_output(json_encode('Phone Number is already registered!'));
                }


                $salt = $this->ion_auth->salt();
                $password = $this->ion_auth->hash_password($postdata['password'], $salt);
                $token = $this->generateRandomString(15);
                $data = array(
                    'username' => $postdata['email'],
                    'password' => $password,
                    'email' => $postdata['email'],
                    'created_on' => time(),
                    'first_name' => $postdata['first_name'],
                    'last_name' => $postdata['last_name'],
                    'phone' => $postdata['phone'],
                    'phone_verified' => 1,
                    'gender' => $postdata['gender'],
                    'biodata' => $postdata['biodata'],
                    'dateofbirth' => $postdata['dateofbirth'],
                    'step' => 1,
                    'accessToken' => $token
                );

                if ($table == 'technicians') {
                    $data['nickname'] = $postdata['nickname'];
                    $data['sin'] = $postdata['sin'];
                }

                if ($usertype != false) {
                    $data['user_type'] = $usertype;
                }

                if ($table == 'users' && $usertype != false && $usertype == 1) {
                    $data['business_name'] = $postdata['business_name'];
                }


                if ($postdata['image'] != "") {
                    $main_image_name = md5(date('Y-m-d H:i:s:u')) . rand(10, 99);
                    file_put_contents('./uploads/' . $main_image_name . '.jpg', base64_decode($postdata['image']));
                    $data['profileImage'] = $main_image_name . '.jpg';
                }

                $this->Home_Model->insertDB($table, $data);


                $message = '
                <div align="center">
							<table width="600" cellspacing="5" cellpadding="5" border="0" style="color:#666 !important;background:none repeat scroll 0% 0% rgb(255,255,255);border-radius:3px;border:1px solid rgb(236,233,233)">
								<tbody>
								    <tr><td style="text-align:center"><img src="' . base_url() . 'include-assets/app-assets/images/'.(($table=='users')?'user':'tech').'-logo.png" width="250" align="center" class="CToWUd a6T" tabindex="0"></td></tr>
								    <tr>
                                        <td valign="top" style="text-align:left;color:#666;font-weight:600">
                                            <span style="color:#666;padding-bottom:10px;font-weight:300;display:block">
                                                <b>Welcome to Eze Tech</b><br/>
                                                It\'s great to have you on board.
                                            </span>
                                        </td>
									</tr>
									<tr>
									    <th style="background-color:#52944c;color:white">Dear ' . $postdata['first_name'] . '</th>
									</tr>
									<tr>
                                        <td valign="top" style="text-align:left;color:#666;font-weight:600"><span style="color:#666;padding-bottom:10px;font-weight:300;display:block">
                                            Your account has been created on EZE-TECH, Please find your account details below:
                                            <br>
                                            <br>
                                            <b>Access Your EZE Tech Account</b>
                                            <br>
                                            <br>
                                            <b>Your Email</b>: ' . $postdata['email'] . ' <br> <b>Your Password</b>: ' . $postdata['password'] . (($table == 'users')? (($table == 'users' && $usertype == 1)?'<br /><b>Customer Type</b>: Commercial ('.$postdata['business_name'].')':'Residential'): '').'
                                            <br><br>									    
                                            </span>
                                        </td>
									</tr>
									<tr><td><br>Regards,<br><b>EZE-TECH</b></td></td></tr>
								</tbody>
							</table>
						</div>
                ';
                $emailsend = sendEmail(['fromEmail' => 'do-not-reply@eze-tech.com', 'toEmail' => $postdata['email'], 'subject' => 'Welcome to Eze Tech', 'message' => $message]);

                $ret = $this->Home_Model->selectWhere($table, array('email' => $postdata['email']));
                return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function registerAddress()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('apt', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('address_1 required!'));
            } elseif (!array_key_exists('street', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('address_2 required!'));
            } elseif (!array_key_exists('city', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('city required!'));
            } elseif (!array_key_exists('state', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('state required!'));
            } elseif (!array_key_exists('country', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('country required!'));
            } elseif (!array_key_exists('gaddress', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('gaddress required!'));
            } elseif (!array_key_exists('latitude', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('latitude required!'));
            } elseif (!array_key_exists('longitude', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('longitude required!'));
            } elseif (!array_key_exists('app_type', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('app_type required!'));
            } else {
                if ($postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $data = array(
                        'address_1' => $postdata['apt'],
                        'address_2' => $postdata['street'],
                        'gaddress' => $postdata['gaddress'],
                        'city' => $postdata['city'],
                        'state' => $postdata['state'],
                        'country' => $postdata['country'],
                        'postcode' => $postdata['postcode'],
                        'latitude' => $postdata['latitude'],
                        'longitude' => $postdata['longitude'],
                        'step' => 2
                    );
                    $ret = $this->Home_Model->selectWhere($table, ['id' => $postdata['id']]);

                    if ($table == "users") {
                        $message = '
                         <div align="center">
							<table width="600" cellspacing="5" cellpadding="5" border="0" style="color:#666 !important;background:none repeat scroll 0% 0% rgb(255,255,255);border-radius:3px;border:1px solid rgb(236,233,233)">
								<tbody>
								    <tr><td style="text-align:center"><img src="' . base_url() . 'include-assets/app-assets/images/user-logo.png" width="250px" align="center" class="CToWUd a6T" tabindex="0"></td></tr>
								    <tr>
									    <th style="background-color:#52944c;color:white">Dear ' . $ret['first_name'] . '</th>
									</tr>
									<tr>
                                        <td valign="top" style="text-align:left;color:#666;font-weight:600"><span style="color:#666;padding-bottom:10px;font-weight:300;display:block">
                                        <br><br>
                                            Welcome to Eze Tech! You\'re now part of a community of hundreds of thousands of Service providers and seekers using our platform to build better technological Eco system.                                            									    
                                            </span>
                                        </td>
									</tr>
									<tr><td><br>Regards,<br><b>EZE-TECH</b></td></td></tr>
								</tbody>
							</table>
						</div>
                        ';
                        $emailsend = sendEmail(['fromEmail' => 'do-not-reply@eze-tech.com', 'toEmail' => $ret['email'], 'subject' => 'Congratulations', 'message' => $message]);
                    }


                    $this->Home_Model->updateDB($table, ['id' => $postdata['id']], $data);
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Address Updated Successfully"));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function getCats($id = 0,$app_type=0, $jobId=false, $userId = false)
    {
        $settings = $this->Home_Model->selectWhere('settings',['id'=> "1"]);
//        print_r($settings);
        if ($id == 0) {

            if($app_type == 0){
                $condition1 = ['parent_id' => $id, 'active' => "1",'techCountInCat >='=> $settings['min_categories']];

                $this->db->select('categories.*');
                $this->db->select('IFNULL(COUNT(technicians.`id`), 0) AS techCount');
                $this->db->join('technician_cat_link', 'technician_cat_link.catId = categories.id', 'left');
                $this->db->join('technicians', 'technicians.id = technician_cat_link.techId AND technicians.`active` = "1"', 'left');
                $this->db->where('categories.active', '1');
                $this->db->having('techCount >=', $settings['min_categories']);
                $this->db->where('categories.parent_id', $id);
                $this->db->where('categories.is_delete', 0);
                $this->db->group_by('categories.id');
                $this->db->order_by('sorting_id asc');
            }else {
                $condition1 = ['parent_id' => $id, 'active' => "1"];

                $this->db->select('categories.*');
                $this->db->select('IFNULL(COUNT(technicians.`id`), 0) AS techCount');
                $this->db->join('technician_cat_link', 'technician_cat_link.catId = categories.id', 'left');
                $this->db->join('technicians', 'technicians.id = technician_cat_link.techId AND technicians.`active` = "1"', 'left');
                $this->db->where('categories.active', '1');
                $this->db->where('categories.parent_id', $id);
                $this->db->where('categories.is_delete', 0);
                $this->db->group_by('categories.id');
                $this->db->order_by('sorting_id asc');
            }
            //$return = $this->Home_Model->selectWhereResult('categories', $condition1, 'sorting_id', 'asc');
            /*SELECT
            categories.*,
              IFNULL(COUNT(technicians.`id`), 0) AS techCount
            FROM
              categories
              LEFT JOIN `technician_cat_link`
                ON technician_cat_link.`catId` = categories.`id`
              LEFT JOIN technicians
                ON technicians.`id` = technician_cat_link.`techId`
                        AND technicians.`active` = '1'
            WHERE categories.`active` = '1'
            GROUP BY categories.id
            HAVING techCount >= 5
            ORDER BY sorting_id*/
            $return = $this->db->get('categories')->result_array();

        } else {
            if($app_type == 0){
                $condition2 = ['id' => $id, 'active' => "1",'techCountInCat >='=> $settings['min_categories']];
            }else {
                $condition2 = ['id' => $id, 'active' => "1"];
            }
            $return = $this->Home_Model->selectWhereResult('categories', $condition2, 'sorting_id', 'asc');

        }
        //$return['others'] = false;
        if($jobId && $userId) {
            $draftMeta = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $jobId]);
        }

        //echo "<pre>";print_r($return);die;
        if ($return) {
            foreach ($return as $key => $returns) {
                $return[$key]['image'] = base_url() . "uploads/" . $returns['image'];
                $return[$key]['selection'] = false;
                /*if($key == 0){
                    if(@$draftMeta){
                        $draftMetaValue = json_decode($draftMeta['draft_value'], true);
                        if($draftMetaValue['others'] == 'true'){
                            $return[$key]['others'] = true;
                        }
                    }
                }*/
                if($jobId && $userId){
                    //echo "<pre>";print_r($draftMeta);die;
                    if(@$draftMeta){
                        $draftMetaValue = json_decode($draftMeta['draft_value'], true);
                        //echo $returns['id'];die;
                        if($draftMetaValue['maincat'] == $returns['id']){
                            $return[$key]['selection'] = true;
                        }
                    }
                }

                if($app_type == 0){
                    $condition3 = ['parent_id' => $returns['id'], 'active' => "1",'techCountInCat >='=> $settings['min_categories']];

                    $this->db->select('categories.*');
                    $this->db->select('IFNULL(COUNT(technicians.`id`), 0) AS techCount');
                    $this->db->join('technician_cat_link', 'technician_cat_link.catId = categories.id', 'left');
                    $this->db->join('technicians', 'technicians.id = technician_cat_link.techId AND technicians.`active` = "1"', 'left');
                    $this->db->where('categories.active', '1');
                    $this->db->having('techCount >=', $settings['min_categories']);
                    $this->db->where('categories.parent_id', $returns['id']);
                    $this->db->where('categories.is_delete', 0);
                    $this->db->group_by('categories.id');
                    $this->db->order_by('sorting_id asc');
                }else {
                    $condition3 = ['parent_id' => $returns['id'], 'active' => "1"];

                    $this->db->select('categories.*');
                    $this->db->select('IFNULL(COUNT(technicians.`id`), 0) AS techCount');
                    $this->db->join('technician_cat_link', 'technician_cat_link.catId = categories.id', 'left');
                    $this->db->join('technicians', 'technicians.id = technician_cat_link.techId AND technicians.`active` = "1"', 'left');
                    $this->db->where('categories.active', '1');
                    $this->db->where('categories.parent_id', $returns['id']);
                    $this->db->where('categories.is_delete', 0);
                    $this->db->group_by('categories.id');
                    $this->db->order_by('sorting_id asc');

                }
                $returnSub = $this->db->get('categories')->result_array();
                //$returnSub = $this->Home_Model->selectWhereResult('categories', $condition3, 'sorting_id', 'asc');
                if ($returnSub) {
                    //echo "<pre>";print_r($returnSub);die;
                    foreach ($returnSub as $key2 => $returnsSub) {
                        $returnSub[$key2]['image'] = base_url() . "uploads/" . $returnsSub['image'];

                        $returnSub[$key2]['selection'] = false;
                        if(@$draftMeta){
                            $draftMetaValue = json_decode($draftMeta['draft_value'], true);
                            //echo $returns['id'];die;
                            if($draftMetaValue['subcat'] == $returnSub[$key2]['id']){
                                $returnSub[$key2]['selection'] = true;
                            }
                        }


                        if($app_type == 0){
                            $condition4 = ['parent_id' => $returnSub[$key2]['id'], 'active' => "1",'techCountInCat >='=> $settings['min_categories']];

                            $this->db->select('categories.*');
                            $this->db->select('IFNULL(COUNT(technicians.`id`), 0) AS techCount');
                            $this->db->join('technician_cat_link', 'technician_cat_link.catId = categories.id', 'left');
                            $this->db->join('technicians', 'technicians.id = technician_cat_link.techId AND technicians.`active` = "1"', 'left');
                            $this->db->where('categories.active', '1');
                            $this->db->having('techCount >=', $settings['min_categories']);
                            $this->db->where('categories.parent_id', $returnSub[$key2]['id']);
                            $this->db->where('categories.is_delete', 0);
                            $this->db->group_by('categories.id');
                            $this->db->order_by('sorting_id asc');

                        }else {
                            $condition4 = ['parent_id' => $returnSub[$key2]['id'], 'active' => "1"];

                            $this->db->select('categories.*');
                            $this->db->select('IFNULL(COUNT(technicians.`id`), 0) AS techCount');
                            $this->db->join('technician_cat_link', 'technician_cat_link.catId = categories.id', 'left');
                            $this->db->join('technicians', 'technicians.id = technician_cat_link.techId AND technicians.`active` = "1"', 'left');
                            $this->db->where('categories.active', '1');
                            $this->db->where('categories.parent_id', $returnSub[$key2]['id']);
                            $this->db->where('categories.is_delete', 0);
                            $this->db->group_by('categories.id');
                            $this->db->order_by('sorting_id asc');

                        }
                        //$returnSubSub = $this->Home_Model->selectWhereResult('categories', $condition4);
                        $returnSubSub = $this->db->get('categories')->result_array();
                        if ($returnSubSub) {
                            foreach ($returnSubSub as $key3 => $returnsSubSub) {
                                $returnSubSub[$key3]['image'] = base_url() . "uploads/" . $returnsSubSub['image'];
                                $returnSubSub[$key3]['selection'] = false;
                                if(@$draftMeta){
                                    $draftMetaValue = json_decode($draftMeta['draft_value'], true);
                                    //echo $returns['id'];die;
                                    if($draftMetaValue['subsubcat'] == $returnSubSub[$key3]['id']){
                                        $returnSubSub[$key3]['selection'] = true;
                                    }
                                }
                                $returnIssue = $this->Home_Model->selectWhereResult('issues', ['catId' => $returnSubSub[$key3]['id']]);
                                foreach ($returnIssue as $issueKey => $returnsIssue) {
                                    $returnIssue[$issueKey]['selection'] = false;
                                    if(@$draftMeta){
                                        $draftMetaValue = json_decode($draftMeta['draft_value'], true);
                                        //echo $returns['id'];die;
                                        if(in_array($returnSubSub[$key3]['id'].'_'.$returnIssue[$issueKey]['id'], $draftMetaValue['issues'])){
                                            $returnIssue[$issueKey]['selection']['selection'] = true;
                                        }
                                    }
                                }
                                $returnSubSub[$key3]['issues'] = $returnIssue;
                            }
                            $returnSub[$key2]['sub'] = $returnSubSub;
                        } else {
                            $returnIssue = $this->Home_Model->selectWhereResult('issues', ['catId' => $returnSub[$key2]['id']]);
                            foreach ($returnIssue as $issueKey => $returnsIssue) {
                                $returnIssue[$issueKey]['selection'] = false;
                            }
                            $returnSub[$key2]['issues'] = $returnIssue;
                        }
                    }
                    $return[$key]['sub'] = $returnSub;
                } else {
                    $returnIssue = $this->Home_Model->selectWhereResult('issues', ['catId' => $returns['id']]);
                    foreach ($returnIssue as $issueKey => $returnsIssue) {
                        $returnIssue[$issueKey]['selection'] = false;
                    }
                    $return[$key]['issues'] = $returnIssue;
                }
            }

            if($app_type == 0){
                if($jobId && $userId){
                    $data['breadcrumb'] = $this->loadBreadCrumb($userId, $jobId);
                    $draftMetaValue = json_decode($draftMeta['draft_value'], true);
                    $data['others'] = $draftMetaValue['others'];

                }
                /*if(@$draftMeta){
                    $draftMetaValue = json_decode($draftMeta['draft_value'], true);
                    if($draftMetaValue['others'] == 'true'){
                        $data['others'] = true;
                    }
                }*/
                $data['cats'] = $return;
            }else{
                $data = $return;
            }




            return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($data));
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('No Categories.'));
        }

    }

    public function postCat()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('categories', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('categories required!'));
            } elseif (!array_key_exists('issues', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('issues required!'));
            } elseif (!array_key_exists('selectedCats', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('selectedCats required!'));
            } elseif (!array_key_exists('step', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('step required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $cats = $postdata['categories'];
                    if(empty($cats)){
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('No Categories Selected'));
                    }
                    $issues = $postdata['issues'];
                    if(empty($issues)){
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('No Isssues Selected'));
                    }

                    if ($postdata['step']) {
                        $data = array(
                            'step' => 3
                        );
                        $this->Home_Model->updateDB('technicians', ['id' => $postdata['id']], $data);
                    } else {
                        $data = array();
                    }

                    foreach ($cats as $cat) {
                        $add = array('techId' => $postdata['id'], 'catId' => $cat);
                        $check = $this->Home_Model->selectWhere('technician_cat_link',$add);
                        if(!$check){
                            $category = $this->Home_Model->selectWhere('categories',['id' => $cat]);
                            $techCountInCat = $category['techCountInCat'] + 1;
                            $this->Home_Model->updateDB('categories',['id'=>$cat],['techCountInCat'=>$techCountInCat]);
                            $this->db->insert('technician_cat_link', $add);
                        }
                    }

                    $old_cats = $this->Home_Model->selectWhereResult('technician_cat_link',['techId' => $postdata['id']]);
                    $old_categories = [];
                    foreach($old_cats as $old_cat) {
                        array_push($old_categories,$old_cat['catId']);
                    }

                    $diff_array = array_diff($old_categories,$cats);
                    foreach ($diff_array as $newarraytoRemove) {
                        $category = $this->Home_Model->selectWhere('categories',['id' => $newarraytoRemove]);
                        $techCountInCat = $category['techCountInCat'] - 1;
                        $this->Home_Model->updateDB('categories',['id'=>$newarraytoRemove],['techCountInCat'=>$techCountInCat]);
                    }

                    $this->db->delete('technician_cat_link', array('techId' => $postdata['id']));
                    $this->db->delete('technician_issue_link', array('techId' => $postdata['id']));
                    $this->db->delete('technician_selectedCats_link', array('techId' => $postdata['id']));

                    $cats = $postdata['categories'];
                    $issues = $postdata['issues'];

                    foreach ($cats as $cat) {
                        $add = array('techId' => $postdata['id'], 'catId' => $cat, 'techSkills' => "");
                        $this->db->insert('technician_cat_link', $add);
                    }

                    $add = array('techId' => $postdata['id'], 'selectedCats' => json_encode($postdata['selectedCats']));
                    $this->db->insert('technician_selectedCats_link', $add);

                    foreach ($issues as $issue) {
                        $issue = explode("_", $issue);
                        $issueId = $issue[1];
                        $catId = $issue[0];

                        $add = array('techId' => $postdata['id'], 'catId' => $catId, 'issueId' => $issueId);
                        $this->db->insert('technician_issue_link', $add);
                    }

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Categories Updated Successfully"));
                }
            }
        }else{
            return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode("Something went wrong."));
        }
    }

    public function transport($update = "")
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('mode_transport', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('mode_transport required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    if ($postdata['mode_transport'] == "Walk" || $postdata['mode_transport'] == "Bus" || $postdata['mode_transport'] == "Train" || $postdata['mode_transport'] == "Bicycle") {
                        $data = array(
                            'mode_transport' => $postdata['mode_transport'],
                        );
                    } else {
                        if (!array_key_exists('transport_year', $postdata)) {
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(404)
                                ->set_output(json_encode('transport year required!'));
                        } elseif (!array_key_exists('transport_make', $postdata)) {
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(404)
                                ->set_output(json_encode('transport_make required!'));
                        } elseif (!array_key_exists('transport_reg_number', $postdata)) {
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(404)
                                ->set_output(json_encode('transport_reg_number required!'));
                        } elseif (!array_key_exists('transport_color', $postdata)) {
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(404)
                                ->set_output(json_encode('transport_color required!'));
                        } else {
                            $data = array(
                                'mode_transport' => $postdata['mode_transport'],
                                'transport_year' => $postdata['transport_year'],
                                'transport_make' => $postdata['transport_make'],
                                'transport_reg_number' => $postdata['transport_reg_number'],
                                'transport_color' => $postdata['transport_color'],

                            );
                        }

                    }

                    if ($update == "") {
                        $data['step'] = 4;
                    }


                    $this->Home_Model->updateDB('technicians', ['id' => $postdata['id']], $data);
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Transport Updated Successfully"));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function licence($update = "")
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('driving_licenceFront', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('driving_licenceFront required!'));
            } elseif (!array_key_exists('driving_licenceBack', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('driving_licenceBack required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    if ($update == "") {
                        $data = array(
                            'step' => 5
                        );
                    } else {
                        $data = array();
                    }


                    if ($postdata['driving_licenceFront'] != "") {
                        $driving_licenceFront = md5(date('Y-m-d H:i:s:u')) . rand(10, 99);
                        file_put_contents('./uploads/' . $driving_licenceFront . '.jpg', base64_decode($postdata['driving_licenceFront']));
                        $data['driving_licenceFront'] = $driving_licenceFront . '.jpg';
                    }

                    if ($postdata['driving_licenceBack'] != "") {
                        $driving_licenceBack = md5(date('Y-m-d H:i:s:u')) . rand(10, 99);
                        file_put_contents('./uploads/' . $driving_licenceBack . '.jpg', base64_decode($postdata['driving_licenceBack']));
                        $data['driving_licenceBack'] = $driving_licenceBack . '.jpg';
                    }

                    $this->Home_Model->updateDB('technicians', ['id' => $postdata['id']], $data);
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Licence Updated Successfully"));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function police($update = "")
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('police_document', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('police_document Front required!'));
            } elseif (!array_key_exists('police_document_back', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('police_document_back required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    if ($update == "") {
                        $data = array(
                            'step' => 6
                        );
                    } else {
                        $data = array();
                    }

                    if ($postdata['police_document'] != "") {
                        $police_document = md5(date('Y-m-d H:i:s:u')) . rand(10, 99);
                        file_put_contents('./uploads/' . $police_document . '.jpg', base64_decode($postdata['police_document']));
                        $data['police_document'] = $police_document . '.jpg';
                    }

                    if ($postdata['police_document_back'] != "") {
                        $police_document_back = md5(date('Y-m-d H:i:s:u')) . rand(10, 99);
                        file_put_contents('./uploads/' . $police_document_back . '.jpg', base64_decode($postdata['police_document_back']));
                        $data['police_document_back'] = $police_document_back . '.jpg';
                    }

                    $this->Home_Model->updateDB('technicians', ['id' => $postdata['id']], $data);
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Police Docs Updated Successfully"));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function image_upload($name, $types)
    {
        $config['upload_path'] = './uploads';
        $config['allowed_types'] = $types;

        $this->load->library('upload', $config);


        if ($this->upload->do_upload($name)) {
            $imgData = $this->upload->data();
//            $path3Thumb = $config['upload_path'].'/73x81';
//            $this->resize_image($config['upload_path'].'/' . $imgData['file_name'], $path3Thumb . '/'.$imgData['file_name'],$path3Thumb,'73','81', $imgData);
            return $imgData['file_name'];
        } else {

        }
    }

    public function uploadCV($update = "")
    {
        if (isset($_POST) && !empty($_POST)) {
            if (empty($this->input->post('id'))) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!isset($_FILES['upload_cv']) && $_FILES['upload_cv']['tmp_name'] == "") {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no file'));
            } else {

//                print_r($_FILES);

                $target_dir = "./uploads/";
                $upload_cv = md5(date('Y-m-d H:i:s:u')) . rand(10, 99);
                $explode = explode(".", basename($_FILES["upload_cv"]["name"]));
                $filename = $explode[0];

                $target_file = $target_dir . basename($_FILES["upload_cv"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $file = $target_dir . $upload_cv . "." . $imageFileType;
                if (file_exists($file)) {
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(404)
                        ->set_output(json_encode("Sorry, file already exists."));
                    $uploadOk = 0;
                }
// Check file size
                if ($_FILES["upload_cv"]["size"] > 5000000) {
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(404)
                        ->set_output(json_encode("Sorry, your file is too large."));

                    $uploadOk = 0;
                }
                if ($uploadOk == 0) {
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(404)
                        ->set_output(json_encode("Sorry, there was an error uploading your file."));
                } else {
                    if (move_uploaded_file($_FILES["upload_cv"]["tmp_name"], $file)) {
                        if ($update == "") {
                            $data = array(
                                'step' => 7
                            );
                        } else {
                            $data = array();
                        }

                        $data['upload_cv'] = basename($file);

                        $id = $this->input->post('id');
                        $this->Home_Model->updateDB('technicians', ['id' => $id], $data);
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("CV Updated Successfully"));
                    } else {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)
                            ->set_output(json_encode("Sorry, there was an error uploading your file."));
                    }
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function bankdetails()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);

        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }

            elseif (!array_key_exists('accountType', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('accountType required!'));
            }

            elseif (!array_key_exists('accountHolder', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('accountHolder required!'));
            }

            elseif (!array_key_exists('transitNumber', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('transitNumber required!'));
            }

            elseif (!array_key_exists('InstituteName', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('InstituteName required!'));
            }

            elseif (!array_key_exists('AccountNumber', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('AccountNumber required!'));
            }

            else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $data = array(
                        'accountType' => $postdata['accountType'],
                        'accountHolder' => $postdata['accountHolder'],
                        'transitNumber' => $postdata['transitNumber'],
                        'InstituteName' => $postdata['InstituteName'],
                        'AccountNumber' => $postdata['AccountNumber'],
                        'step' => 8,
                        'active' => 0
                    );
//                    print_r($data);

                    $this->Home_Model->updateDB('technicians', ['id' => $postdata['id']], $data);

                    $ret = $this->Home_Model->selectWhere('technicians', ['id' => $postdata['id']]);
                    $message = '
                         <div align="center">
							<table width="600" cellspacing="5" cellpadding="5" border="0" style="color:#666 !important;background:none repeat scroll 0% 0% rgb(255,255,255);border-radius:3px;border:1px solid rgb(236,233,233)">
								<tbody>
								    <tr><td style="text-align:center"><img src="' . base_url() . 'include-assets/app-assets/images/tech-logo.png" width="250px" align="center" class="CToWUd a6T" tabindex="0"></td></tr>
								    <tr>
									    <th style="background-color:#52944c;color:white">Dear ' . $ret['first_name'] . '</th>
									</tr>
									<tr>
                                        <td valign="top" style="text-align:left;color:#666;font-weight:600"><span style="color:#666;padding-bottom:10px;font-weight:300;display:block"><br><br>
                                            Your application has been received and in reviewing process, we\'ll come back to you shortly                                            									    
                                            </span>
                                        </td>
									</tr>
									<tr><td><br>Regards,<br><b>EZE-TECH</b></td></td></tr>
								</tbody>
							</table>
						</div>
                        ';
                    $emailsend = sendEmail(['fromEmail' => 'do-not-reply@eze-tech.com', 'toEmail' => $ret['email'], 'subject' => 'Congratulations', 'message' => $message]);

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Bank Details Updated Successfully"));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function profile()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    if(!empty($postdata['did']) && !empty($postdata['dtype'])){
                        $this->Home_Model->updateDB('users', ['id' => $postdata['id']], ['deviceId' => $postdata['did'], 'deviceType' =>  $postdata['dtype'] ]);
                    }
                    $ret = $this->Home_Model->selectWhere('users', array('id' => $postdata['id']));

                    $date = new DateTime($ret['dateofbirth']);
                    $now = new DateTime();
                    $interval = $now->diff($date);
                    $ret['age'] = $interval->y;

                    $posted_jobs = $this->Home_Model->countResult('jobs',['userId'=>$postdata['id'],'techId !='=>'0', 'jobStatus'=>'1']);
                    $ret['posted_jobs'] = ($posted_jobs) ? $posted_jobs : 0;
                    $ret['pending_jobs'] = $this->Home_Model->countResult('jobs',['userId'=>$postdata['id'],'techId !='=>'0', 'jobStatus'=>'2'])+$this->Home_Model->countResult('jobs',['userId'=>$postdata['id'],'techId !='=>'0', 'jobStatus'=>'3'])+$this->Home_Model->countResult('jobs',['userId'=>$postdata['id'],'techId !='=>'0', 'jobStatus'=>'4'])+$this->Home_Model->countResult('jobs',['userId'=>$postdata['id'],'techId !='=>'0', 'jobStatus'=>'5']);


                    $rate = $this->db->query('SELECT ROUND(AVG(reviewed_rate)) as reviewed_rate FROM reviews WHERE reviewed_to = "'.$postdata['id'].'" AND app_type = "1"')->row_array();
                    $ret['rating'] = (($rate['reviewed_rate'])?$rate['reviewed_rate']:0);

                    $testimonails = $this->db->query('SELECT *,( SELECT CONCAT(`first_name`," ",last_name) FROM `technicians` WHERE id = reviews.`reviewed_by` ) AS reviewerName, (SELECT nickname FROM `technicians` WHERE id = reviews.`reviewed_by` ) AS nickname FROM reviews WHERE reviewed_to = "'.$postdata['id'].'" AND app_type = "1"')->result_array();
                    $ret['testimonals'] = ($testimonails) ? $testimonails : [];

                    $ret['profileImage'] = base_url() . "uploads/" . $ret['profileImage'];

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
                }
            }
        }
    }

    public function login()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if ($this->ion_auth->login($postdata['email'], $postdata['password'])) {
                $output['message'] = $this->ion_auth->messages();
                $token = $this->generateRandomString(15);
                $this->Home_Model->updateDB('users', ['email' => $postdata['email']], ['AccessToken' => $token, 'IsLogin' => '1', "deviceId" => "", "deviceType" => ""]);
                $ret = $this->Home_Model->selectWhere('users', array('email' => $postdata['email']));
                if($ret['step'] == 8 && $ret['active'] == 0 ){
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode("We have received your profile registration and is in review we will get back to you soon."));
                }else if($ret['active'] == 3 ){
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode("Your Account is Disabled"));
                }
                $ret['profileImage'] = base_url() . "uploads/" . $ret['profileImage'];

                $page = $this->Home_Model->selectWhere('pages',['id'=>'15']);
                $ret['coming_soon'] = $page;

                return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
                // redirect('auth', 'refresh');
            } else {
                return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode("Invalid credentials"));

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function logout()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no id!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if ($this->authenticateUser($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION']) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $this->Home_Model->updateDB('users', ['id' => $postdata['id']], ['IsLogin' => '0', 'accessToken' => '', 'deviceId' => '' , 'deviceType' => '']);
                    $logout = $this->ion_auth->logout();
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode('Logout Successfully!'));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function forgot_password()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $identity = $this->ion_auth->where($identity_column, $postdata['email'])->users()->row();

            if (empty($identity)) {

                if ($this->config->item('identity', 'ion_auth') != 'email') {
                    $this->ion_auth->set_error('forgot_password_identity_not_found');
                } else {
                    $this->ion_auth->set_error('forgot_password_email_not_found');
                }

                $output['message'] = $this->ion_auth->errors();
                return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode($output));
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten) {
                // if there were no errors
                $output['message'] = $this->ion_auth->messages();
                return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($output));
            } else {
                $output['message'] = $this->ion_auth->errors();
                return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode($output));
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function forgotYourPass()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            $userEmail = $postdata['email'];
            if (empty($userEmail)):
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('please provide email address!'));
            else:
                $token = $this->generateRandomString(6);
                $r = $this->Home_Model->updateDB('users', ['email' => $userEmail], ['forgotten_password_code' => $token]);
                if ($r) {
//                    $message = '';
//                    $message = 'Dear User,<br />. Your Token is :<br />'. $token . ' <br /><br />Regards';

                    $message = '
                         <div align="center">
                            <table width="600" cellspacing="5" cellpadding="5" border="0" style="color:#666 !important;background:none repeat scroll 0% 0% rgb(255,255,255);border-radius:3px;border:1px solid rgb(236,233,233)">
                                <tbody>
                                    <tr><td style="text-align:center"><img src="'.base_url().'include-assets/app-assets/images/user-logo.png" width="250px" align="center" class="CToWUd a6T" tabindex="0"></td></tr>
                                    <tr>
                                        <th style="background-color:#52944c;color:white">Dear User</th>
                                    </tr>
                                    <tr>
                                        <td valign="top" style="text-align:center;color:#666;font-weight:600"><span style="color:#666;padding-bottom:10px;font-weight:300;display:block">
                                            Please enter the following code on the application screen to change your password.
                                        <br><br>
                                            <b>Your Code is</b> :  ' . $token . '                                      									    
                                            </span>
                                        </td>
                                    </tr>
                                    <tr><td style=""><br>Regards ,<br>Team <b>EZE-TECH</b></td></td></tr>
                                </tbody>
                            </table>
                        </div>  
                        ';
                    $emailsend = sendEmail(['fromEmail' => 'do-not-reply@eze-tech.com', 'toEmail' => $userEmail, 'subject' => 'Reset Password', 'message' => $message]);
                    if ($emailsend) {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)->set_output(json_encode('email sent'));
                    } else {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)->set_output(json_encode('email not sent'));
                    }
                } else {
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(404)
                        ->set_output(json_encode('email address not found!'));
                }


            endif;
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function verifyToken()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            $usertoken = $postdata['security_code'];
            if (!array_key_exists('security_code', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no security_code!'));
            } else {
                if ($this->authenticateForgotUser($postdata['security_code']) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('Security code is Invalid'));
                } else {
                    $ret = $this->Home_Model->selectWhere('users', ['forgotten_password_code' => $usertoken]);
                    $token = $this->generateRandomString(15);
                    $this->Home_Model->updateDB('users', array('id' => $ret['id']),
                        array(
                            'forgotten_password_code' => '', 'accessToken' => $token, 'IsLogin' => '1',
                        )
                    );
                    // $ret['id'] = $postdata['id'];
                    $return['accessToken'] = $token;
                    $return['id'] = $ret['id'];
                    $return['message'] = "Security code is valid";
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)->set_output(json_encode($return));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function updatePassword()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no id!'));
            } elseif (!array_key_exists('password', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no password!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if ($this->authenticateUser($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION']) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    if (isset($postdata['password']) && !empty($postdata['password'])) {
                        $salt = $this->ion_auth->salt();
                        $data['password'] = $this->ion_auth->hash_password($postdata['password'], $salt);
                        $data['forgotten_password_code'] = "0";
                    }

                    $this->Home_Model->updateDB('users', ['id' => $postdata['id']], $data);
                    $ret = $this->Home_Model->selectWhere('users', ['id' => $postdata['id']]);
                    // $ret['uProfilePic'] = base_url() . 'uploads/' . $ret['uProfilePic'];
                    // $ret['uUniqueName'] = base_url('registration.html/') . encodeData($ret['uUniqueName']);
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }

    }

    public function t_profile()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    if(!empty($postdata['did']) && !empty($postdata['dtype'])){
                        $this->Home_Model->updateDB('technicians', ['id' => $postdata['id']], ['deviceId' => $postdata['did'], 'deviceType' =>  $postdata['dtype'] ]);
                    }
                    $ret = $this->Home_Model->selectWhere('technicians', array('id' => $postdata['id']));
                    $date = new DateTime($ret['dateofbirth']);
                    $now = new DateTime();
                    $interval = $now->diff($date);
                    $ret['age'] = $interval->y;
                    $ret['completed_jobs'] = $this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'6'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'7']);
                    $ret['pending_jobs'] = $this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'2'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'3'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'4'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'5']);


                    $rate = $this->db->query('SELECT ROUND(AVG(reviewed_rate)) as reviewed_rate FROM reviews WHERE reviewed_to = "'.$postdata['id'].'" AND app_type = "0"')->row_array();
                    $ret['rating'] = (($rate['reviewed_rate'])?$rate['reviewed_rate']:0);

                    $testimonails = $this->db->query('SELECT *,( SELECT CONCAT(`first_name`," ",last_name) FROM `users` WHERE id = reviews.`reviewed_by` ) AS reviewerName, (SELECT nickname FROM `technicians` WHERE id = reviews.`reviewed_by` ) AS nickname FROM reviews WHERE reviewed_to = "'.$postdata['id'].'" AND app_type = "0"')->result_array();
                    $ret['testimonals'] = ($testimonails) ? $testimonails : [];

                    $ret['profileImage'] = base_url() . "uploads/" . $ret['profileImage'];
                    $ret['driving_licenceFront'] = base_url() . "uploads/" . $ret['driving_licenceFront'];
                    $ret['driving_licenceBack'] = base_url() . "uploads/" . $ret['driving_licenceBack'];
                    $ret['police_document'] = base_url() . "uploads/" . $ret['police_document'];
                    $ret['police_document_back'] = base_url() . "uploads/" . $ret['police_document_back'];

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
                }
            }
        }
    }

    public function t_login()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('email', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no email!'));
            } elseif (!array_key_exists('password', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no password!'));
            } else {
                $retLogin = $this->Home_Model->selectWhere('technicians', array('email' => $postdata['email']));
                if($retLogin['step'] == 8 && $retLogin['active'] == 0 ){
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode("We have received your profile registration and is in review we will get back to you soon."));
                }else if($retLogin['active'] == 3 ){
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode("Your Account is Disabled"));
                }
                if ($this->hash_password_db($retLogin['id'], $postdata['password'], 'technicians')) {
                    $token = $this->generateRandomString(15);
                    $this->Home_Model->updateDB('technicians', ['email' => $postdata['email']], ['AccessToken' => $token, 'IsLogin' => '1', 'availabilityStatus'=>'1', "deviceId" => "", "deviceType" => "" ]);
                    $ret = $this->Home_Model->selectWhere('technicians', array('id'=>$retLogin['id'], 'email' => $postdata['email']));
                    $ret['profileImage'] = base_url() . "uploads/" . $ret['profileImage'];
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
                } else {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode("Invalid credentials"));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function t_logout()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no id!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $this->Home_Model->updateDB('technicians', ['id' => $postdata['id']], ['IsLogin' => '0', 'accessToken' => '', 'availabilityStatus'=>'0', 'deviceId' => '' , 'deviceType' => '']);
//                    $logout = $this->ion_auth->logout();
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode('Logout Successfully!'));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function t_forgot_password()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $identity = $this->ion_auth->where($identity_column, $postdata['email'])->users()->row();

            if (empty($identity)) {

                if ($this->config->item('identity', 'ion_auth') != 'email') {
                    $this->ion_auth->set_error('forgot_password_identity_not_found');
                } else {
                    $this->ion_auth->set_error('forgot_password_email_not_found');
                }

                $output['message'] = $this->ion_auth->errors();
                return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode($output));
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten) {
                // if there were no errors
                $output['message'] = $this->ion_auth->messages();
                return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($output));
            } else {
                $output['message'] = $this->ion_auth->errors();
                return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode($output));
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function t_forgotYourPass()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            $userEmail = $postdata['email'];
            if (empty($userEmail)):
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('please provide email address!'));
            else:
                $token = $this->generateRandomString(6);
                $r = $this->Home_Model->updateDB('technicians', ['email' => $userEmail], ['forgotten_password_code' => $token]);
                if ($r) {
//                    $message = '';
//                    $message = 'Dear User,<br />. Your Token is :<br />'. $token . ' <br /><br />Regards';
                    $message = '
                         <div align="center">
                            <table width="600" cellspacing="5" cellpadding="5" border="0" style="color:#666 !important;background:none repeat scroll 0% 0% rgb(255,255,255);border-radius:3px;border:1px solid rgb(236,233,233)">
                                <tbody>
                                    <tr><td style="text-align:center"><img src="'.base_url().'include-assets/app-assets/images/tech-logo.png" width="250px" align="center" class="CToWUd a6T" tabindex="0"></td></tr>
                                    <tr>
                                        <th style="background-color:#52944c;color:white">Dear User</th>
                                    </tr>
                                    <tr>
                                        <td valign="top" style="text-align:center;color:#666;font-weight:600"><span style="color:#666;padding-bottom:10px;font-weight:300;display:block">
                                            Please enter the following code on the application screen to change your password.
                                        <br><br>
                                            <b>Your Code is</b> :  ' . $token . '                                      									    
                                            </span>
                                        </td>
                                    </tr>
                                    <tr><td style=""><br>Regards ,<br>Team <b>EZE-TECH</b></td></td></tr>
                                </tbody>
                            </table>
                        </div>  
                        ';
                    $emailsend = sendEmail(['fromEmail' => 'do-not-reply@eze-tech.com', 'toEmail' => $userEmail, 'subject' => 'Reset Password', 'message' => $message]);
                    if ($emailsend) {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)->set_output(json_encode('email sent'));
                    } else {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)->set_output(json_encode('email not sent'));
                    }
                } else {
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(404)
                        ->set_output(json_encode('email address not found!'));
                }


            endif;
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function t_verifyToken()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            $usertoken = $postdata['security_code'];
            if (!array_key_exists('security_code', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no security_code!'));
            } else {
                if ($this->authenticateForgotTech($postdata['security_code']) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('Security code is Invalid'));
                } else {
                    $ret = $this->Home_Model->selectWhere('technicians', ['forgotten_password_code' => $usertoken]);
                    $token = $this->generateRandomString(15);
                    $this->Home_Model->updateDB('technicians', array('id' => $ret['id']),
                        array(
                            'forgotten_password_code' => '', 'accessToken' => $token, 'IsLogin' => '1'
                        )
                    );
                    // $ret['id'] = $postdata['id'];
                    $return['accessToken'] = $token;
                    $return['id'] = $ret['id'];
                    $return['message'] = "Security code is valid";
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)->set_output(json_encode($return));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function t_updatePassword()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no id!'));
            } elseif (!array_key_exists('password', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no password!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    if (isset($postdata['password']) && !empty($postdata['password'])) {
                        $salt = $this->ion_auth->salt();
                        $data['password'] = $this->ion_auth->hash_password($postdata['password'], $salt);
                        $data['forgotten_password_code'] = "0";
                    }

                    $this->Home_Model->updateDB('technicians', ['id' => $postdata['id']], $data);
                    $ret = $this->Home_Model->selectWhere('technicians', ['id' => $postdata['id']]);
                    // $ret['uProfilePic'] = base_url() . 'uploads/' . $ret['uProfilePic'];
                    // $ret['uUniqueName'] = base_url('registration.html/') . encodeData($ret['uUniqueName']);
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }

    }

    public function contactUs()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no id!'));
            } elseif (!array_key_exists('companyname', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no companyname!'));
            } elseif (!array_key_exists('contactname', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no contactname!'));
            } elseif (!array_key_exists('email', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no email!'));
            } elseif (!array_key_exists('msg', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no msg !'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $email = $postdata['email'];
                    $contactname = $postdata['contactname'];
                    $companyname = $postdata['companyname'];
                    $msg = $postdata['msg'];
                    $message = 'Dear,<br /><br />. Received Contact from Name :' . $contactname . ' <br /> Email : ' . $email . ' Company Name : ' . $companyname . ' with message: <br> ' . $msg . ' <br />Regards';
                    sendEmail(['fromEmail' => $email, 'toEmail' => ADMIN_EMAIL, 'subject' => 'Contact Form', 'message' => $message]);

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Email Sent Successfully!"));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function getBankInfo()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('app_type', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('app_type required!'));
            } else {
                if ($postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $ret = $this->Home_Model->selectWhere($table, array('id' => $postdata['id']));
                    $date = new DateTime($ret['dateofbirth']);
                    $now = new DateTime();
                    $interval = $now->diff($date);
                    $ret['age'] = $interval->y;
                    $ret['profileImage'] = base_url() . "uploads/" . $ret['profileImage'];
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
                }
            }
        }
    }

    public function updateBankInfo()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('bankname', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('bankname required!'));
            } elseif (!array_key_exists('accountNo', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('accountNo required!'));
            } elseif (!array_key_exists('accountTitle', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('accountTitle required!'));
            } elseif (!array_key_exists('swiftCode', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('swiftCode required!'));
            } elseif (!array_key_exists('iban', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('iban required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $data = array(
                        'bankname' => $postdata['bankname'],
                        'accountNo' => $postdata['accountNo'],
                        'iban' => $postdata['iban'],
                        'accountTitle' => $postdata['accountTitle'],
                        'swiftCode' => $postdata['swiftCode'],
                    );

                    $this->Home_Model->updateDB('technicians', ['id' => $postdata['id']], $data);
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Bank Details Updated"));
                }
            }
        }
    }

    public function updateProfilePic()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if (array_key_exists('app_type', $postdata) && $postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }

                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    if (!array_key_exists('image', $postdata) && $postdata['image'] == "") {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)
                            ->set_output(json_encode('image required!'));
                    }
                    $data = array();
                    if ($postdata['image'] != "") {
                        $main_image_name = md5(date('Y-m-d H:i:s:u')) . rand(10, 99);
                        $result = file_put_contents('./uploads/' . $main_image_name . '.jpg', base64_decode($postdata['image']));
                        $data['profileImage'] = $main_image_name . '.jpg';
                    }


                    $this->Home_Model->updateDB($table, ['id' => $postdata['id']], $data);
                    $ret = $this->Home_Model->selectWhere($table, array('id' => $postdata['id']));
                    $ret['profileImage'] = base_url() . "uploads/" . $ret['profileImage'];
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
                }
            }
        }
    }

    public function updateBasicInfo()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('first_name', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('first_name required!'));
            } elseif (!array_key_exists('last_name', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('last_name required!'));
            } elseif (!array_key_exists('dateofbirth', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('dateofbirth required!'));
            } elseif (!array_key_exists('gender', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('gender required!'));
            } elseif (!array_key_exists('biodata', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('biodata required!'));
            } else {
                if (array_key_exists('app_type', $postdata) && $postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }

                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $data = array(
                        'first_name' => $postdata['first_name'],
                        'last_name' => $postdata['last_name'],
                        'gender' => $postdata['gender'],
                        'biodata' => $postdata['biodata'],
                        'dateofbirth' => $postdata['dateofbirth'],
                    );

                    if ($table == 'technicians') {
                        $data['nickname'] = $postdata['nickname'];
                        $data['sin'] = $postdata['sin'];
                    }
                    if ($table == 'users') {
                        $data['business_name'] = $postdata['business_name'];
                    }




                    $this->Home_Model->updateDB($table, ['id' => $postdata['id']], $data);
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Basic Info Updated"));
                }
            }
        }
    }

    public function updateAddress()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('apt', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('address_1 required!'));
            } elseif (!array_key_exists('street', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('address_2 required!'));
            } elseif (!array_key_exists('city', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('city required!'));
            } elseif (!array_key_exists('state', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('state required!'));
            } elseif (!array_key_exists('country', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('country required!'));
            } elseif (!array_key_exists('gaddress', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('gaddress required!'));
            } elseif (!array_key_exists('latitude', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('latitude required!'));
            } elseif (!array_key_exists('longitude', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('longitude required!'));
            } elseif (!array_key_exists('app_type', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('app_type required!'));
            } else {
                if (array_key_exists('app_type', $postdata) && $postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }

                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $data = array(
                        'address_1' => $postdata['apt'],
                        'address_2' => $postdata['street'],
                        'gaddress' => $postdata['gaddress'],
                        'city' => $postdata['city'],
                        'state' => $postdata['state'],
                        'country' => $postdata['country'],
                        'postcode' => $postdata['postcode'],
                        'latitude' => $postdata['latitude'],
                        'longitude' => $postdata['longitude'],
                    );
                    $ret = $this->Home_Model->selectWhere($table, ['id' => $postdata['id']]);
                    $this->Home_Model->updateDB($table, ['id' => $postdata['id']], $data);
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Address Updated Successfully"));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function ChangePassword()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('oldpass', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('oldpass required!'));
            } elseif (!array_key_exists('newpass', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('newpass required!'));
            } else {

                if (array_key_exists('app_type', $postdata) && $postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }

                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $old_password = $postdata['oldpass'];
                    $user_id = $postdata['id'];

                    if ($this->hash_password_db($user_id, $old_password, $table)) {
                        $salt = $this->ion_auth->salt();
                        $password = $this->ion_auth->hash_password($postdata['newpass'], $salt);

                        $data = array(
                            'password' => $password,
                        );

                        $this->Home_Model->updateDB($table, ['id' => $postdata['id']], $data);
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Password Updated Successfully"));
                    } else {
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode("Old Password miss match"));
                    }

                }
            }
        }
    }

    public function ChangePhoneNo()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('oldpass', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('oldpass required!'));
            } elseif (!array_key_exists('phone', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('phone required!'));
            } else {
                if (array_key_exists('app_type', $postdata) && $postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }

                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $old_password = $postdata['oldpass'];
                    $user_id = $postdata['id'];

                    if ($this->hash_password_db($user_id, $old_password, $table)) {

                        $ret = $this->Home_Model->selectWhere($table, array('phone' => $postdata['phone']));
                        if ($ret != false) {
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(402)
                                ->set_output(json_encode('Phone Number is already registered!'));
                        }

                        $data = array(
                            'phone' => $postdata['phone'],
                        );

                        $this->Home_Model->updateDB($table, ['id' => $postdata['id']], $data);
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Phone Number Updated Successfully"));
                    } else {
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode("Old Password miss match"));
                    }

                }
            }
        }
    }

    public function updateAvailabilityStatus()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('availability', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('availability required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $data = array(
                        'availabilityStatus' => $postdata['availability']
                    );


                    $this->Home_Model->updateDB('technicians', ['id' => $postdata['id']], $data);
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Status Updated"));
                }
            }
        }
    }

    public function getSelectedCategories()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
//                    $ret = $this->Home_Model->selectWhere('technician_selectedCats_link', array('techId' => $postdata['id']));
//                    $ret['selectedCats'] = json_decode($ret['selectedCats']);
//                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
//
//                    die();

                    $retResult = array();
                    $selectedCats = [];
                    $ret = $this->Home_Model->selectWhereResult('technician_cat_link', array('techId' => $postdata['id']), false , "catId","asc");
                    if(!$ret){
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('Pleaes Add New Category to proceed'));
                    }
                    $retIssues = $this->Home_Model->selectWhereResult('technician_issue_link', array('techId' => $postdata['id']));
                    if(!$retIssues){
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('Pleaes Add New Category to proceed'));
                    }
                    $cat_ids = [];
                    foreach ($ret as $catId) {
                        $cat_ids[] = $catId['catId'];
                    }
//                    print_r($cat_ids);
                    foreach ($retIssues as $issuesId) {
                        $issueIds[] = $issuesId['issueId'];
                    }
//                        print_r($cat_ids);
                    foreach ($cat_ids as $cat_id) {
                        $category = $this->Home_Model->selectWhere('categories', array('id' => $cat_id , 'parent_id'=> 0, "active"=> "1", "is_delete"=>0 ));
                        if($category){
                            $category['image'] = base_url() . "uploads/" . $category['image'];
                            $category['selection'] = true;
                            $allSubCategories = $this->Home_Model->selectWhereResult('categories', array('parent_id' => $category['id'], "active"=> "1", "is_delete"=>0 ));
//                            print_r($allSubCategories);
//                            die();
                            if($allSubCategories){
                                foreach ($allSubCategories as $key => $allSubCategory) {
                                    if (in_array($allSubCategory['id'], $cat_ids)) {
                                        $allSubCategories[$key] ['selection'] = true;
                                    }else {
                                        $allSubCategories[$key]['selection'] = false;
                                    }
                                    $allSubCategory['image'] = base_url() . "uploads/" . $allSubCategories[$key]['image'];
                                    $allSubSubCategories = $this->Home_Model->selectWhereResult('categories', array('parent_id' => $allSubCategory['id'], "active"=> "1" ), false , "id","asc");
//                                        print_r($allSubSubCategories);
                                    if($allSubSubCategories){
                                        foreach ($allSubSubCategories as $key2 => $allSubSubCategory) {
                                            if (in_array($allSubSubCategory['id'], $cat_ids)) {
                                                $allSubSubCategory['selection'] = true;
                                            } else {
                                                $allSubSubCategory['selection'] = false;
                                            }
                                            $allSubSubCategory['image'] = base_url() . "uploads/" . $allSubSubCategory['image'];

                                            $issues = $this->Home_Model->selectWhereResult('issues', array('catId' => $allSubSubCategories[$key2]['id'] ));
                                            foreach ($issues as $key3 => $issue) {
                                                if (in_array($issue['id'], $issueIds)) {
                                                    $issues[$key3]['selection'] = true;
                                                }else {
                                                    $issues[$key3]['selection'] = false;
                                                }
                                            }
                                            $allSubSubCategory['issues'] = $issues;
//                                                echo "Category ".$allSubSubCategory['id'].": ";
//                                                print_r($allSubSubCategory);
//                                                echo "<br>";
                                            $allSubCategories[$key]['sub'][] = $allSubSubCategory;
                                        }
//        print_r($allSubCategory);
//                                            $allSubCategory['sub'] = $allSubSubCategory;
                                    }else {
                                        $allSubCategories[$key]['issuesNhn'] = "a rahe".$allSubCategory['id'];
                                        $issues = $this->Home_Model->selectWhereResult('issues', array('catId' => $allSubCategory['id'] ));
                                        foreach ($issues as $keyIssue => $issue) {
                                            if (in_array($issue['id'], $issueIds)) {
                                                $issues[$keyIssue]['selection'] = true;
                                            }else {
                                                $issues[$keyIssue]['selection'] = false;
                                            }
                                        }
                                        $allSubCategories[$key]['issues'] = $issues;
                                    }
//                                    $allSubCategories[$key] = $allSubCategory;
//                                        print_r($allSubCategories);

                                }
                                $category['sub'] = $allSubCategories;
                            }else {
                                $issues = $this->Home_Model->selectWhereResult('issues', array('catId' => $category['id'] ));
                                foreach ($issues as $key => $issue) {
                                    if (in_array($issue['id'], $issueIds)) {
                                        $issues[$key]['selection'] = true;
                                    }else {
                                        $issues[$key]['selection'] = false;
                                    }
                                }
                                $category['issues'] = $issues;
                            }

                            $selectedCats[] = $category;

                        }

                    }

                    $retResult['techId'] = $postdata['id'];
                    $retResult['selectedCats'] = $selectedCats;

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($retResult));

//                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($selectedCats));
//                    die();


                }
            }
        }
    }

    public function postUsersCat()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('issues', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('issues required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $issues = $postdata['issues'];
                    $sols = array();
                    foreach ($issues as $issue) {
                        $issue = explode("_", $issue);
                        $issueId = $issue[1];
                        $catId = $issue[0];

                        $sols['issues'][] = $this->Home_Model->selectWhere('issues', ['id' => $issueId, 'catId' => $catId]);
                    }

                    if($postdata['maincat'] != ""){
                        $sols['maincat'] = $this->Home_Model->selectWhere('categories', ['id' => $postdata['maincat'] ]);
                    }

                    if($postdata['subcat'] != ""){
                        $sols['subcat'] = $this->Home_Model->selectWhere('categories', ['id' => $postdata['subcat'] ]);
                    }

                    if($postdata['subsubcat'] != ""){
                        $sols['subsubcat'] = $this->Home_Model->selectWhere('categories', ['id' => $postdata['subsubcat'] ]);
                    }


                    $settings = $this->Home_Model->selectWhere('settings',['id'=>'1']);
                    $sols['tax_cost'] = $settings['tax_rate'];
                    $sols['commission_cost'] = $settings['commission_rate'];


                    //MZ
                    $job = array(
                        'userId' => $postdata['id'],
                        'saveStatus' => 'draft',
                        'jobStatus' => 0,
                        'stepCount' => 1,
                    );
                    if(empty($postdata['jobId'])){
                        $this->db->insert('jobs', $job);
                        $jobId = $this->db->insert_id();
                        $sols['jobId'] = $jobId;
                        //DELETE TECH META
                        $this->deleteDraftTech($jobId, $postdata['id']);
                    }else{
                        $this->db->where('id', $postdata['jobId']);
                        $this->db->update('jobs', $job);
                        $sols['jobId'] = $postdata['jobId'];
                        //DELETE TECH META
                        $this->deleteDraftTech($postdata['jobId'], $postdata['id']);

                    }



                    $jobDraftValue = [
                        'id' => $postdata['id'],
                        'maincat' => $postdata['maincat'],
                        'others' => $postdata['others'],
                        'issues' => $postdata['issues'],
                        'subcat' => $postdata['subcat'],
                        'subsubcat' => $postdata['subsubcat']
                    ];
                    $jobDraft = [
                        'draft_meta'=>'step1',
                        'draft_value'=>json_encode($jobDraftValue),
                        'fk_userId'=> $postdata['id'],
                    ];
                    if(empty($postdata['jobId'])){
                        $jobDraft['fk_jobId'] = $jobId;
                        $this->db->insert('job_draft', $jobDraft);
                        $jobDraftId = $this->db->insert_id();
                        $sols['breadcrumb'] = $this->loadBreadCrumb($postdata['id'], $jobId);
                    }else{
                        $this->db->where('fk_userId', $postdata['id']);
                        $this->db->where('draft_meta', 'step1');
                        $this->db->update('job_draft', $jobDraft);
                        $sols['jobId'] = $postdata['jobId'];
                        $sols['breadcrumb'] = $this->loadBreadCrumb($postdata['id'], $postdata['jobId']);
                    }
                    //MZ

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($sols));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function postJOB()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('job_title', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('job_title required!'));
            }
            elseif (!array_key_exists('job_desc', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('job_desc required!'));
            }
            elseif (!array_key_exists('job_date', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('job_date required!'));
            }
            elseif (!array_key_exists('job_time', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('job_time required!'));
            }
            elseif (!array_key_exists('apt', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('apt required!'));
            }
            elseif (!array_key_exists('street', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('street required!'));
            }
            elseif (!array_key_exists('city', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('city required!'));
            }
            elseif (!array_key_exists('state', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('state required!'));
            }
            elseif (!array_key_exists('country', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('country required!'));
            }
            elseif (!array_key_exists('postcode', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('postcode required!'));
            }

            elseif (!array_key_exists('latitude', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('latitude required!'));
            }
            elseif (!array_key_exists('longitude', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('longitude required!'));
            }
            elseif (!array_key_exists('gaddress', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('gaddress required!'));
            }

            elseif (!array_key_exists('additionalnotes', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('additionalnotes required!'));
            }

            elseif (!array_key_exists('additionalPic', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('image required!'));
            }

            elseif (!array_key_exists('notes', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('notes required!'));
            }
            elseif (!array_key_exists('totalCost', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('totalCost required!'));
            }
            else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                }else {

                    $data['userId'] = $postdata['id'];

                    if ($postdata['additionalPic'] != "") {
                        $additionalPic = md5(date('Y-m-d H:i:s:u')) . rand(10, 99);
                        file_put_contents('./uploads/' . $additionalPic . '.jpg', base64_decode($postdata['additionalPic']));
                        $data['additionalPic'] = $additionalPic . '.jpg';
                    }

                    if($postdata['maincat'] != ""){
                        $data['category'] = $postdata['maincat'];
                    }

                    if($postdata['subcat'] != ""){
                        $data['subcat'] = $postdata['subcat'];
                    }

                    if($postdata['subsubcat'] != ""){
                        $data['subsubcat'] = $postdata['subsubcat'];
                    }



                    $data['additionalInfo'] = $postdata['additionalnotes'];
                    $data['address_1'] = $postdata['apt'];
                    $data['address_2'] = $postdata['street'];
                    $data['city'] = $postdata['city'];
                    $data['state'] = $postdata['state'];
                    $data['country'] = $postdata['country'];
                    $data['postcode'] = $postdata['postcode'];
                    $data['gaddress'] = $postdata['gaddress'];
                    $data['latitude'] = $postdata['latitude'];
                    $data['longitude'] = $postdata['longitude'];
                    $data['additionalNotes'] = $postdata['notes'];
                    $data['jobTitle'] = $postdata['job_title'];
                    $data['jobDate'] = $postdata['job_date'];
                    $data['jobTime'] = $postdata['job_time'];
                    $data['jobDescription'] = $postdata['job_desc'];
                    $data['totalCost'] = $postdata['totalCost'];

                    $return['job_id'] = $job_id = $this->Home_Model->insertDB('jobs', $data);


                    if($data['category']){
                        $selectCategory = $this->Home_Model->selectWhereWithFields('categories',['id'=>$data['category']], 'id, name,slug, image, parent_id, active,sorting_id,hours,cost,  min_categories, commercial_cost, priceTier_res, priceTier_com, isFree' );
                        $selectCategory['jobId'] = $job_id;
                        if($selectCategory){ $this->insertCategory($selectCategory); }
                    }

                    if($postdata['subcat']){
                        $selectCategory = $this->Home_Model->selectWhereWithFields('categories',['id'=>$data['subcat']], 'id, name,slug, image, parent_id, active,sorting_id,hours,cost,  min_categories, commercial_cost, priceTier_res, priceTier_com, isFree' );
                        $selectCategory['jobId'] = $job_id;
                        if($selectCategory){ $this->insertCategory($selectCategory); }
                    }

                    if($postdata['subsubcat']){
                        $selectCategory = $this->Home_Model->selectWhereWithFields('categories',['id'=>$data['subsubcat']], 'id, name,slug, image, parent_id, active,sorting_id,hours,cost,  min_categories, commercial_cost, priceTier_res, priceTier_com, isFree' );
                        $selectCategory['jobId'] = $job_id;
                        if($selectCategory){ $this->insertCategory($selectCategory); }
                    }





                    $issues = $postdata['issues'];
                    foreach ($issues as $issue) {
                        $issue = explode("_", $issue);
                        $issueId = $issue[1];
                        $catId = $issue[0];

                        $add = array('job_id' => $job_id, 'cat_id' => $catId, 'issue_id' => $issueId);
                        $this->db->insert('job_issues_link', $add);
                    }

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($return));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    function insertCategory($data){
//        print_r($data);
        return $insertCategory = $this->Home_Model->insertDB('job_categories',$data);
    }

    function array_multi_subsort($array, $subkey)
    {
        $b = array(); $c = array();

        foreach ($array as $k => $v)
        {
            $b[$k] = strtolower($v->$subkey);
        }

        asort($b);
        foreach ($b as $key => $val)
        {
            $c[] = $array[$key];
        }

        return $c;
    }

    //SINGLE FETCH
    /*public function getTechListing()
    {

        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('jobid', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobid required!'));
            } elseif (!array_key_exists('factor', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('factor required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $data = array();
                    $settings = $this->Home_Model->selectWhere('settings',['id'=>'1']);
                    $factor_count = $postdata['factor'];
                    $technicians = [];
                    $job_id = $postdata['jobid'];
                    $techLatLongs = array();

                    $step1 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $job_id, 'draft_meta' => 'step1']);
                    $step1Value = json_decode($step1['draft_value'], true);
                    //echo "<pre>";print_r($step1Value);die;
                    $step5 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $job_id, 'draft_meta' => 'step5']);
                    $step5Value = json_decode($step5['draft_value'], true);

                    $issues = [];
                    foreach ($step1Value['issues'] as $issue) {
                        $issue = explode("_", $issue);
                        $issues[] = $issue[1];
                    }

                    $issues = implode(",", $issues);

                    $query = "SELECT  tcl1.techId,(SELECT COUNT(linkId) FROM technician_issue_link til2 WHERE ";
                    if(!empty($issues)){
                        $query .=  "til2.`issueId` IN (".$issues.") AND";
                    }
                    $query .= " t.`id` = til2.`techId`) AS score , CONCAT(t.first_name,' ',t.last_name) AS techname, FLOOR(DATEDIFF(CURDATE(),t.dateofbirth) / 365) AS techage, t.* FROM `technicians` t ";

                    if($step1Value['maincat'] != ""){
                        $query .= "INNER JOIN `technician_cat_link` tcl1  ON (t.`id` = tcl1.`techId` AND tcl1.`catId` = '".$step1Value['maincat']."') ";
                    }
                    if($step1Value['subcat'] != ""){
                        $query .= "INNER JOIN `technician_cat_link` tcl2  ON (t.`id` = tcl2.`techId` AND tcl2.`catId` = '".$step1Value['subcat']."') ";
                    }
                    if($step1Value['subsubcat'] != ""){
                        $query .= "INNER JOIN `technician_cat_link` tcl3  ON (t.`id` = tcl3.`techId` AND tcl3.`catId` = '".$step1Value['subsubcat']."') ";
                    }
                    $query .= " WHERE t.`availabilityStatus` = '1' and active='1' ORDER BY score DESC";

//                    $data['query'] = $query;
                    $ret = $this->Home_Model->queryDB($query);
                    //echo $this->db->last_query();die;



                    foreach ($ret as $tech) {
                        $techLatLng = $tech['latitude'].",".$tech['longitude'];
                        array_push($techLatLongs,$techLatLng);
                    }


                    $apiKey = GOOGLE_API_KEY;
                    $origins = $step5Value['latitude'].",".$step5Value['longitude'];

                    $destinations = implode("|", $techLatLongs);
                    //echo "<pre>";print_r($destinations);die;
                    //echo $destinations;die;
                    $distanceMatrixApi = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$origins.'&destinations='.$destinations.'&key='.$apiKey;
                    $apiReturn = json_decode( file_get_contents($distanceMatrixApi) ,true);
                    $apiResults = $apiReturn['rows'][0]['elements'];
                    //echo "<pre>";print_r($apiReturn);die;


                    for ($i=0; $i<=$factor_count; $i++){
                        $getTechnicians = $this->sortTech($i, $postdata['jobid'], $postdata['id'], $settings, $ret, $apiResults);
                        if($getTechnicians){
                            foreach ($getTechnicians as $getTechnician){
                                $technicians[] = $getTechnician;
                            }
                        }
                    }

                    $data['technicians'] = $technicians ;
                    if (array_key_exists('jobid', $postdata)) {
                        $data['breadcrumb'] = $this->loadBreadCrumb($postdata['id'], $postdata['jobid']);
                    }
                    $data['next_factor_applied_cost'] = "Additional charges of " . $settings['factor_rate'] * ($factor_count+1) ."$ will be added to your cart";

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($data));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    //SINGLE FETCH
    public function sortTech($factor_count, $jobId, $userId, $settings, $ret, $apiResults){
        $factor_count_after = intval($factor_count) + 1;
        $factor_distance = floatval($settings['factor_distance'])  * intval($factor_count);
        $factor_distance_after = floatval($settings['factor_distance']) * intval($factor_count_after);

        $technicians = array();
        $data = array();

        $job_id = $jobId;

        foreach ($ret as $index => $tech) {
            if($apiResults[$index]['status'] == 'OK'){
                $date = new DateTime($tech['dateofbirth']);
                $now = new DateTime();
                $interval = $now->diff($date);
                $ret[$index]['age'] = $interval->y;
                $ret[$index]['completed_jobs'] = $this->Home_Model->countResult('jobs',['techId'=>$tech['id'], 'jobStatus'=>'6'])+$this->Home_Model->countResult('jobs',['techId'=>$tech['id'], 'jobStatus'=>'7']);
                $ret[$index]['pending_jobs'] = $this->Home_Model->countResult('jobs',['techId'=>$tech['id'], 'jobStatus'=>'2'])+$this->Home_Model->countResult('jobs',['techId'=>$tech['id'], 'jobStatus'=>'3'])+$this->Home_Model->countResult('jobs',['techId'=>$tech['id'], 'jobStatus'=>'4'])+$this->Home_Model->countResult('jobs',['techId'=>$tech['id'], 'jobStatus'=>'5']);
                $ret[$index]['profileImage'] = base_url() . "uploads/" . $tech['profileImage'];
                $ret[$index]['driving_licenceFront'] = base_url() . "uploads/" . $tech['driving_licenceFront'];
                $ret[$index]['driving_licenceBack'] = base_url() . "uploads/" . $tech['driving_licenceBack'];
                $ret[$index]['police_document'] = base_url() . "uploads/" . $tech['police_document'];
                $ret[$index]['police_document_back'] = base_url() . "uploads/" . $tech['police_document_back'];
                $ret[$index]['upload_cv'] = base_url() . "uploads/" . $tech['upload_cv'];
                $ret[$index]['factor_count'] = $factor_count;
                $ret[$index]['factor_applied_cost'] = $settings['factor_rate'] * $factor_count;
                $rate = $this->db->query('SELECT ROUND(AVG(reviewed_rate)) as reviewed_rate FROM reviews WHERE reviewed_to = "'.$tech['id'].'"')->row_array();
                $ret[$index]['rating'] = (($rate['reviewed_rate'])?$rate['reviewed_rate']:0);
                $testimonails = $this->db->query('SELECT *,( SELECT CONCAT(`first_name`," ",last_name) FROM `users` WHERE id = reviews.`reviewed_by` ) AS reviewerName, (SELECT nickname FROM `technicians` WHERE id = reviews.`reviewed_by` ) AS nickname FROM reviews WHERE reviewed_to = "'.$tech['id'].'" AND app_type="0"')->result_array();
                $ret[$index]['testimonals'] = ($testimonails) ? $testimonails : [];

                $distance = $apiResults[$index]['distance']['value'] / 1000;
                $ret[$index]['techmiles'] = number_format($distance, 1);
                $ret[$index]['techduration'] = $durInMin = $apiResults[$index]['duration']['text'];
                $ret[$index]['factor_distance'] = $factor_distance;
                $ret[$index]['factor_distance_after'] = $factor_distance_after;

                if($distance >= $factor_distance && $distance <= $factor_distance_after){
                    $technicians[] = $ret[$index];
                }
            }
        }
        array_sort_by_column($technicians,"techmiles");


        return $technicians;


    }*/

    public function getTechListing()
    {

        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('jobid', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobid required!'));
            } elseif (!array_key_exists('factor', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('factor required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $data = array();
                    $settings = $this->Home_Model->selectWhere('settings',['id'=>'1']);
                    $factor_count = $postdata['factor'];
                    $technicians = [];


                    for ($i=0; $i<=$factor_count; $i++){
                        $getTechnicians = $this->sortTech($i, $postdata['jobid'], $postdata['id'], $settings);
                        if($getTechnicians){
                            foreach ($getTechnicians as $getTechnician){
                                $technicians[] = $getTechnician;
                            }
                            $data['technicians_length'] = count($getTechnicians);
                        }else{
                            $data['technicians_length'] = 0;
                        }
                    }

                    $tempArr = array_unique(array_column($technicians, 'id'));
                    $technicians = array_intersect_key($technicians, $tempArr);


                    $data['technicians'] = $technicians ;
                    if (array_key_exists('jobid', $postdata)) {
                        $data['breadcrumb'] = $this->loadBreadCrumb($postdata['id'], $postdata['jobid']);
                    }
                    $data['next_factor_applied_cost'] = "Additional charges of " . $settings['factor_rate'] * ($factor_count+1) ."$ will be added to your cart";

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($data));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function sortTech($factor_count, $jobId, $userId, $settings){
        $factor_count_after = intval($factor_count) + 1;
        $factor_distance = floatval($settings['factor_distance'])  * intval($factor_count);
        $factor_distance_after = floatval($settings['factor_distance']) * intval($factor_count_after);

        $technicians = array();
        $data = array();
        $myTechArray = array();

        $job_id = $jobId;


        $step1 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $job_id, 'draft_meta' => 'step1']);
        $step1Value = json_decode($step1['draft_value'], true);
        $step5 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $job_id, 'draft_meta' => 'step5']);
        $step5Value = json_decode($step5['draft_value'], true);
        $issues = [];
        foreach ($step1Value['issues'] as $issue) {
            $issue = explode("_", $issue);
            $issues[] = $issue[1];
        }

        $issues = implode(",", $issues);

        $query = "SELECT  tcl1.techId,(SELECT COUNT(linkId) FROM technician_issue_link til2 WHERE ";
        if(!empty($issues)){
            $query .=  "til2.`issueId` IN (".$issues.") AND";
        }
        $query .= " t.`id` = til2.`techId`) AS score , CONCAT(t.first_name,' ',t.last_name) AS techname, FLOOR(DATEDIFF(CURDATE(),t.dateofbirth) / 365) AS techage, t.* FROM `technicians` t ";

        if($step1Value['maincat'] != ""){
            $query .= "INNER JOIN `technician_cat_link` tcl1  ON (t.`id` = tcl1.`techId` AND tcl1.`catId` = '".$step1Value['maincat']."') ";
        }
        if($step1Value['subcat'] != ""){
            $query .= "INNER JOIN `technician_cat_link` tcl2  ON (t.`id` = tcl2.`techId` AND tcl2.`catId` = '".$step1Value['subcat']."') ";
        }
        if($step1Value['subsubcat'] != ""){
            $query .= "INNER JOIN `technician_cat_link` tcl3  ON (t.`id` = tcl3.`techId` AND tcl3.`catId` = '".$step1Value['subsubcat']."') ";
        }
        $query .= " WHERE t.`availabilityStatus` = '1' and active='1' ORDER BY score DESC";

        $ret = $this->Home_Model->queryDB($query);

        $apiKey = GOOGLE_API_KEY;

        $client = new Predis\Client();
        foreach ($ret as $tech) {
            $techGps = $client->get('tech_'.$tech['id']);
            if($techGps){
                $techGps = json_decode($techGps, true);
                $tech['currentLatitude'] =  $techGps['latitude'];
                $tech['currentLongitude'] =  $techGps['longitude'];
                $techLatLng = $techGps['latitude'].','.$techGps['longitude'];
                //array_push($techLatLongs,$techLatLng);
                array_push($myTechArray,$tech);
            }

        }
        $techChunk = array_chunk($myTechArray, 1);
        $origins = $step5Value['latitude'].','.$step5Value['longitude'];
        foreach ($techChunk as $techKey => $techValue){
            $techLatLongs = array();
            foreach ($techValue as $singleTech){
                $techLatLng = $singleTech['currentLatitude'].','.$singleTech['currentLongitude'];
                array_push($techLatLongs,$techLatLng);
            }
            //echo "<pre>";print_r($techValue);
            $destinations = implode("|", $techLatLongs);
            $distanceMatrixApi = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$origins.'&destinations='.$destinations.'&key='.$apiKey;
            $apiReturn = json_decode( file_get_contents($distanceMatrixApi) ,true);
            $apiResults = $apiReturn['rows'][0]['elements'];
            foreach ($techValue as $index => $tech) {

                if ($apiResults[$index]['status'] == 'OK') {
                    $date = new DateTime($tech['dateofbirth']);
                    $now = new DateTime();
                    $interval = $now->diff($date);
                    $techValue[$index]['age'] = $interval->y;
                    $techValue[$index]['completed_jobs'] = $this->Home_Model->countResult('jobs', ['techId' => $tech['id'], 'jobStatus' => '6']) + $this->Home_Model->countResult('jobs', ['techId' => $tech['id'], 'jobStatus' => '7']);
                    $techValue[$index]['pending_jobs'] = $this->Home_Model->countResult('jobs', ['techId' => $tech['id'], 'jobStatus' => '2']) + $this->Home_Model->countResult('jobs', ['techId' => $tech['id'], 'jobStatus' => '3']) + $this->Home_Model->countResult('jobs', ['techId' => $tech['id'], 'jobStatus' => '4']) + $this->Home_Model->countResult('jobs', ['techId' => $tech['id'], 'jobStatus' => '5']);
                    $techValue[$index]['profileImage'] = base_url() . "uploads/" . $tech['profileImage'];
                    $techValue[$index]['driving_licenceFront'] = base_url() . "uploads/" . $tech['driving_licenceFront'];
                    $techValue[$index]['driving_licenceBack'] = base_url() . "uploads/" . $tech['driving_licenceBack'];
                    $techValue[$index]['police_document'] = base_url() . "uploads/" . $tech['police_document'];
                    $techValue[$index]['police_document_back'] = base_url() . "uploads/" . $tech['police_document_back'];
                    $techValue[$index]['upload_cv'] = base_url() . "uploads/" . $tech['upload_cv'];
                    $techValue[$index]['factor_count'] = $factor_count;
                    $techValue[$index]['factor_applied_cost'] = $settings['factor_rate'] * $factor_count;
                    $rate = $this->db->query('SELECT ROUND(AVG(reviewed_rate)) as reviewed_rate FROM reviews WHERE reviewed_to = "' . $tech['id'] . '"')->row_array();
                    $techValue[$index]['rating'] = (($rate['reviewed_rate']) ? $rate['reviewed_rate'] : 0);
                    $testimonails = $this->db->query('SELECT *,( SELECT CONCAT(`first_name`," ",last_name) FROM `users` WHERE id = reviews.`reviewed_by` ) AS reviewerName, (SELECT nickname FROM `technicians` WHERE id = reviews.`reviewed_by` ) AS nickname FROM reviews WHERE reviewed_to = "' . $tech['id'] . '" AND app_type="0"')->result_array();
                    $techValue[$index]['testimonals'] = ($testimonails) ? $testimonails : [];


                    $distance = $apiResults[$index]['distance']['value'] / 1000;
                    $techValue[$index]['techmiles'] = number_format($distance, 1);
                    $techValue[$index]['techduration'] = $durInMin = $apiResults[$index]['duration']['text'];
                    $techValue[$index]['techduration2'] = $apiResults[$index]['distance']['value'];
                    $techValue[$index]['factor_distance'] = $factor_distance;
                    $techValue[$index]['factor_distance_after'] = $factor_distance_after;
                    if ($distance >= $factor_distance && $distance <= $factor_distance_after) {
                        $technicians[] = $techValue[$index];
                    }

                }
            }
        }
        array_sort_by_column($technicians,"techmiles");
        return $technicians;
    }

    public function getTechDetails()
    {

        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('techid', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('techid required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $ret = $this->Home_Model->selectWhere('technicians', array('id' => $postdata['techid']));
                    if($ret){
                        $date = new DateTime($ret['dateofbirth']);
                        $now = new DateTime();
                        $interval = $now->diff($date);
                        $ret['age'] = $interval->y;
                        $ret['completed_jobs'] = $this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'6'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'7']);
                        $ret['pending_jobs'] = $this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'2'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'3'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'4'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['id'], 'jobStatus'=>'5']);

                        $ret['profileImage'] = ($ret['profileImage'] != "") ? base_url() . "uploads/" . $ret['profileImage'] : base_url().'/uploads/noimage.png';
                        $ret['driving_licenceFront'] = ($ret['driving_licenceFront'] != "") ? base_url() . "uploads/" . $ret['driving_licenceFront'] : base_url().'/uploads/noimage.png';
                        $ret['driving_licenceBack'] = ($ret['driving_licenceBack'] != "") ? base_url() . "uploads/" . $ret['driving_licenceBack'] : base_url().'/uploads/noimage.png';
                        $ret['police_document'] = ($ret['police_document'] != "") ? base_url() . "uploads/" . $ret['police_document'] : base_url().'/uploads/noimage.png';
                        $ret['police_document_back'] = ($ret['police_document_back'] != "") ? base_url() . "uploads/" . $ret['police_document_back'] : base_url().'/uploads/noimage.png';
                        $ret['upload_cv'] = ($ret['upload_cv'] != "") ? base_url() . "uploads/" . $ret['upload_cv'] : base_url().'/uploads/noimage.png';

                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
                    }else {
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("No Tech Found"));
                    }

                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function assignJob(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('techid', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('techid required!'));
            } elseif (!array_key_exists('jobid', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobid required!'));
            } elseif (!array_key_exists('totalCost', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('totalCost required!'));
            } elseif (!array_key_exists('factor_count', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('factor_count required!'));
            } elseif (!array_key_exists('factor_applied_cost', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('factor_applied_cost required!'));
            } elseif (!array_key_exists('tax_cost', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('tax_cost required!'));
            } elseif (!array_key_exists('commission_cost', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('commission_cost required!'));
            } elseif (!array_key_exists('totalCostWithoutTax', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('Total Cost Without Tax required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $data['userId'] = $postdata['id'];
                    $data['techId'] = $postdata['techid'];
                    $data['totalCost'] = $postdata['totalCost'];
                    $data['totalCostWithoutTax'] = $postdata['totalCostWithoutTax'];
                    $data['factor_count'] = $postdata['factor_count'];
                    $data['factor_applied_cost'] = $postdata['factor_applied_cost'];
                    $data['tax_cost'] = $postdata['tax_cost'];
                    $data['commission_cost'] = $postdata['commission_cost'];
                    $data['jobStatus'] = 1;


                    $this->Home_Model->updateDB('jobs', ['id' => $postdata['jobid']], $data);

                    $this->destroyFactor();
                    $techDetails = $this->Home_Model->selectWhere('technicians', ['id' => $postdata['techid']]);

                    $jobs = $this->Home_Model->selectWhereWithFields('jobs', ['id' => $postdata['jobid']], '*, ROUND((`totalCostWithoutTax`-(`totalCostWithoutTax`*(`commission_cost`)/100)),2) AS totalCostforTech, IF((SELECT COUNT(review_id) FROM reviews WHERE reviewed_jobid = '. $postdata['jobid'].' ) > 0, 1, 0)  AS isReviewed');

                    $status = "";
                    if($jobs['jobStatus'] == 0){ $status = "Cancelled"; }
                    else if($jobs['jobStatus'] == 1){ $status = "Pending"; }
                    else if($jobs['jobStatus'] == 2){ $status = "Selected"; }
                    else if($jobs['jobStatus'] == 3){ $status = "Started"; }
                    else if($jobs['jobStatus'] == 4){ $status = "Arrived"; }
                    else if($jobs['jobStatus'] == 5){ $status = "Pending Completion"; }
                    else if($jobs['jobStatus'] == 6){ $status = "Completed"; }
                    else if($jobs['jobStatus'] == 7){ $status = "Paid"; }

                    $jobs['jobDate'] = date("d-M-Y", strtotime($jobs['jobDate']));
                    $jobs['jobTime'] = date("h:i A", strtotime($jobs['jobTime']));

                    $jobs['jobStatus'] = $status;

                    $jobs['issues'] = $this->Jobs_Model->getJobIssues($jobs['id']);


                    $category = $this->Home_Model->selectWhere('job_categories',['id'=>$jobs['category'], 'jobId'=>$jobs['id']]);
                    $subcat = $this->Home_Model->selectWhere('job_categories',['id'=>$jobs['subcat'], 'jobId'=>$jobs['id']]);
                    $subsubcat = $this->Home_Model->selectWhere('job_categories',['id'=>$jobs['subsubcat'], 'jobId'=>$jobs['id']]);
                    $jobs['category'] = ($category) ? $category : [];
                    $jobs['subcat'] = ($subcat) ? $subcat : [];
                    $jobs['subsubcat'] = ($subsubcat) ? $subsubcat : [];

                    $jobs['jobStatus'] = $status;

                    $pushArray = [
                        'id' => $jobs['id'],
                        'jobStatus' => $jobs['jobStatus'],
                        'isReviewed' => $jobs['isReviewed']
                    ];

                    $postString = array();
                    $postString['registration_ids'][] = $techDetails['deviceId'];
                    $title = "EZE-TECH NOTIFICATION";
                    $body  = "You have received a hire request. Please review and respond";
//                    $postString['notification'] = [ "title"=>$title,"body"=>"Please click to view this appointment","badge"=>1,"sound"=>"default","icon"=>"http://dev20.onlinetestingserver.com/trava-lab-up/assets/images/fav_icon.png" ];
                    if($techDetails['deviceType'] == "ios"){
                        $postString['notification'] = [ "title"=>$title,"body"=> $body];
                        $postString['data'] = [ 'job'=>$pushArray];
                    }else {
                        $postString['data'] = [ 'job'=> $pushArray, "title"=>$title,"body"=> $body];
                    }

                    $postString['priority'] = 'high';

//                    echo json_encode($postString);
//
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL,            "https://fcm.googleapis.com/fcm/send" );
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
                    curl_setopt($ch, CURLOPT_POST,           1 );
                    curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($postString) );
                    curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization:key=AIzaSyDs93AGHXUn8Eh09ByKqnG9OJY6TJNPffI','Content-Type:application/json'));

                    $result=curl_exec ($ch);

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Job Created Successfully"));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function destroyFactor(){
        $this->session->unset_userdata('factor_count');
    }

    public function getMyJobs()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('status', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('status required!'));
            }  else {

                if (!isset($postdata['app_type']) || $postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $$techValueArray = false;
                    $userId = $postdata['id'];
                    if (!isset($postdata['app_type']) || $postdata['app_type'] == 0 && $postdata['status'] == '-1') {
                        $where = ['userId ' => $userId, 'saveStatus' => 'draft'];
                        $loadDraftArray = true;
                    } else if(!isset($postdata['app_type']) || $postdata['app_type'] == 0) {
                        $where = ['userId' => $userId , 'techId !=' => "0", 'jobStatus' => $postdata['status']];
                    }else{
                        $where = ['techId ' => $userId, 'jobStatus' => $postdata['status']];
                    }

                    if($loadDraftArray == true){
                        $custom = array();
                        $getJobs = $this->Home_Model->selectWhereResult('jobs', $where, 'id', 'desc');

                        foreach ($getJobs as $index => $job) {
                            $step3 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $job['id'], 'draft_meta' => 'step3']);
                            $step3Value = json_decode($step3['draft_value'], true);

                            $step4 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $job['id'], 'draft_meta' => 'step4']);
                            $step4Value = json_decode($step4['draft_value'], true);

                            $step7 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $job['id'], 'draft_meta' => 'step7']);
                            $step7Value = json_decode($step7['draft_value'], true);


                            $techId = (($step7)?$step7Value['techId']:false);
                            $jobDate = (($step4)?$step4Value['jobDate']:false);
                            $jobTime = (($step4)?$step4Value['jobTime']:false);
                            $jobTitle = (($step4)?$step4Value['title']:false);
                            $jobDescription = (($step4)?$step4Value['desc']:false);

                            $totalCost = (($step7)?$step7Value['totalCost']:(($step3)?$step3Value['totalCost']:false));


                            $custom[$index]['id'] = $job['id'];
                            $custom[$index]['userId'] = $job['userId'];
                            $custom[$index]['jobStatus'] = 'Drafted';
                            $custom[$index]['stepCount'] = (($job['stepCount'] > 6) ? "6" : $job['stepCount']);
                            $custom[$index]['saveStatus'] = $job['saveStatus'];

                            $custom[$index]['isReviewed'] = 0;
                            $custom[$index]['techId'] = (($techId)?$techId:'');
                            $custom[$index]['jobDate'] = (($jobDate)?date("d-M-Y", strtotime($jobDate)):'TO BE FILLED');
                            $custom[$index]['jobTime'] = (($jobTime)?date("h:i A", strtotime($jobTime)):'TO BE FILLED');


                            $custom[$index]['jobTitle'] = (($jobTitle)?$jobTitle:'TO BE FILLED');
                            $custom[$index]['jobDescription'] = (($jobDescription)?$jobDescription:'TO BE FILLED');

                            $custom[$index]['totalCost'] = (($totalCost)?$totalCost:'TO BE FILLED');

                            $custom[$index]['duration'] = 'TO BE FILLED';
                        }

                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($custom));
                    }else{
                        $jobs = array();
                        $jobs = $this->Home_Model->selectWhereResultWithFields('jobs', $where , 'id,jobStatus,isExpired,jobEta,expiredate,saveStatus,paymentStatus,totalCost,tax_cost,commission_cost,totalCostWithoutTax, ((`totalCostWithoutTax`-`commission_cost`)) AS totalCostforTech,jobTitle,jobDate,jobTime,jobDescription, techId,userId,category,subcat,subsubcat,additionalInfo, IF((SELECT COUNT(review_id) FROM reviews WHERE reviewed_jobid = id AND reviewed_by = '.$postdata['id'].' AND app_type= '.$postdata['app_type'].' ) > 0, 1, 0)  AS isReviewed','id','desc');//echo $this->db->last_query();die;
                        if($jobs){
                            foreach ($jobs as $index => $job) {
                                $status = "";
                                if($job['jobStatus'] == 0){ $status = "Cancelled"; }
                                else if($job['jobStatus'] == 1){ $status = "Pending"; }
                                else if($job['jobStatus'] == 2){ $status = "Selected"; }
                                else if($job['jobStatus'] == 3){ $status = "Started"; }
                                else if($job['jobStatus'] == 4){ $status = "Arrived"; }
                                else if($job['jobStatus'] == 5){ $status = "Pending Completion"; }
                                else if($job['jobStatus'] == 6){ $status = "Completed"; }
                                else if($job['jobStatus'] == 7){ $status = "Paid"; }


                                $jobs[$index]['jobDate'] = date("d-M-Y", strtotime($job['jobDate']));
                                $jobs[$index]['jobTime'] = date("h:i A", strtotime($job['jobTime']));

                                $category = $this->Home_Model->selectWhere('categories',['id'=>$job['category']]);
                                $subcat = $this->Home_Model->selectWhere('categories',['id'=>$job['subcat']]);
                                $subsubcat = $this->Home_Model->selectWhere('categories',['id'=>$job['subsubcat']]);
                                $jobs[$index]['category'] = ($category) ? $category : [];
                                $jobs[$index]['subcat'] = ($subcat) ? $subcat : [];
                                $jobs[$index]['subsubcat'] = ($subsubcat) ? $subsubcat : [];
                                $jobs[$index]['expireIn'] = getDateDiff($job['expiredate']);
                                $jobs[$index]['isExpired'] = (($job['isExpired'] == 1) ? true : false);

                                $jobs[$index]['issues'] = $this->Jobs_Model->getJobIssues($job['id']);

                                $jobs[$index]['jobStatus'] = $status;
                                $jobs[$index]['duration'] = $job['jobEta'];

                                $jobs[$index]['totalCost'] = number_format($jobs[$index]['totalCost'], 2);
                                $jobs[$index]['totalCostforTech'] = number_format($jobs[$index]['totalCostforTech'], 2);
                            }
//

                        }
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($jobs));
                    }


                }

            }

        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function getFullJob()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('jobid', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobid required!'));
            } else {
                if (!isset($postdata['app_type']) || $postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $userId = $postdata['id'];
                    $jobid = $postdata['jobid'];

                    $getJob = $this->Home_Model->selectWhere('jobs', ['id' => $jobid, 'userId'=>$userId]);
                    if($getJob && $getJob['saveStatus']=='draft'){
                        $step1 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $jobid, 'draft_meta' => 'step1']);
                        $step1Value = json_decode($step1['draft_value'], true);

                        $step2 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $jobid, 'draft_meta' => 'step2']);
                        $step2Value = json_decode($step2['draft_value'], true);

                        $step3 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $jobid, 'draft_meta' => 'step3']);
                        $step3Value = json_decode($step3['draft_value'], true);

                        $step4 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $jobid, 'draft_meta' => 'step4']);
                        $step4Value = json_decode($step4['draft_value'], true);

                        $step5 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $jobid, 'draft_meta' => 'step5']);
                        $step5Value = json_decode($step5['draft_value'], true);

                        $step7 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $jobid, 'draft_meta' => 'step7']);
                        $step7Value = json_decode($step7['draft_value'], true);


                        $techId = (($step7)?$step7Value['techId']:false);
                        $jobDate = (($step4)?$step4Value['jobDate']:false);
                        $jobTime = (($step4)?$step4Value['jobTime']:false);
                        $jobTitle = (($step4)?$step4Value['title']:false);
                        $jobDescription = (($step4)?$step4Value['desc']:false);

                        $totalCost = (($step7)?$step7Value['totalCost']:(($step3)?$step3Value['totalCost']:false));
                        $gaddress = (($step5)?$step5Value['gaddress']:false);
                        $notes = (($step5)?$step5Value['notes']:false);

                        $additionalInfo = (($step2)?$step2Value['othersAddnotes']:false);
                        $additionalPic = (($step2)?$step2Value['othersAddpic']:false);

                        $issues = (($step2)?json_decode($step2Value['unres_issues'],true):false);

                        $maincatId = (($step1)?$step1Value['maincat']:false);
                        $subcatId = (($step1)?$step1Value['subcat']:false);
                        $subsubcatId = (($step1)?$step1Value['subsubcat']:false);


                        $custom['id'] =  $jobid;
                        $custom['userId'] = $userId;
                        $custom['jobStatus'] = 'Drafted';
                        $custom['stepCount'] = (($getJob['stepCount'] > 6) ? "6" : $getJob['stepCount']);
                        $custom['isReviewed'] = 0;
                        $custom['jobDate'] = (($jobDate)?date("d-M-Y", strtotime($jobDate)):'TO BE FILLED');
                        $custom['jobTime'] = (($jobTime)?date("h:i A", strtotime($jobTime)):'TO BE FILLED');
                        $custom['jobTitle'] = (($jobTitle)?$jobTitle:'TO BE FILLED');
                        $custom['jobDescription'] = (($jobDescription)?$jobDescription:'TO BE FILLED');
                        $custom['totalCost'] = (($totalCost)?$totalCost:'TO BE FILLED');
                        $custom['gaddress'] = (($gaddress)?$gaddress:'TO BE FILLED');

                        $custom['additionalInfo'] = (($additionalInfo)?$additionalInfo:'');
                        $custom['additionalPic'] = (($additionalPic)?base_url() . "/uploads/".$additionalPic:'');
                        $custom['additionalNotes'] = (($notes)?$notes:'TO BE FILLED');


                        $getUser = $this->Home_Model->selectWhere('users', ['id' => $userId]);
                        $custom['userInfo']['id'] = $getUser['id'];
                        $custom['userInfo']['profileImage'] = ($getUser['profileImage'] != "") ? base_url() . "uploads/" . $getUser['profileImage'] : base_url().'/uploads/noimage.png';
                        $custom['userInfo']['first_name'] = $getUser['first_name'];
                        $custom['userInfo']['last_name'] = $getUser['last_name'];
                        $custom['userInfo']['gender'] = $getUser['gender'];

                        if($techId){
                            $techInfo = $this->Home_Model->selectWhere('technicians', ['id' => $techId]);
                            $custom['techInfo']['id'] = $techInfo['id'];
                            $custom['techInfo']['profileImage'] = ($techInfo['profileImage'] != "") ? base_url() . "uploads/" . $techInfo['profileImage'] : base_url().'/uploads/noimage.png';
                            $custom['techInfo']['first_name'] = $techInfo['first_name'];
                            $custom['techInfo']['last_name'] = $techInfo['last_name'];
                            $custom['techInfo']['nickname'] = $techInfo['nickname'];
                            $custom['techInfo']['gender'] = $techInfo['gender'];
                            $custom['techInfo']['phone'] = $techInfo['phone'];
                        }else{
                            $custom['techInfo']['id'] ='TO BE FILLED';
                            $custom['techInfo']['profileImage'] =  base_url().'/uploads/noimage.png';
                            $custom['techInfo']['first_name'] = 'TO BE FILLED';
                            $custom['techInfo']['last_name'] = 'TO BE FILLED';
                            $custom['techInfo']['nickname'] = 'TO BE FILLED';
                            $custom['techInfo']['gender'] = 'TO BE FILLED';
                            $custom['techInfo']['phone'] = 'TO BE FILLED';
                        }

                        if($issues){
                            $isssue_array = [];
                            foreach ($issues as $issue){
                                $this->db->select('*, "'.base_url().'/uploads/noimage.png" as issueImage ');
                                $this->db->from('issues');
                                $this->db->where("id", $issue['id']);
                                $isssue_array[] = $this->db->get()->row_array();
                            }
                        }

                        $custom['issues'] = (($issues)?$isssue_array:[]);


                        $category = $this->Home_Model->selectWhere('job_categories',['id'=>$maincatId]);
                        $subcat = $this->Home_Model->selectWhere('job_categories',['id'=>$subcatId]);
                        $subsubcat = $this->Home_Model->selectWhere('job_categories',['id'=>$subsubcatId]);


                        $custom['category'] = (($maincatId && $category)?$category:[]);
                        $custom['subcat'] = (($subcatId && $subcat)?$subcat:[]);
                        $custom['subsubcat'] = (($subsubcatId && $subsubcat)?$subsubcat:[]);

                        $custom['techId'] = (($techId)?$techId:'');
                        $custom['duration'] = 'TO BE FILLED';

                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($custom));

                    }else{
                        if($table == 'technicians'){
                            $jobs = $this->Home_Model->selectWhereWithFields('jobs', ['id' => $jobid, 'techId'=>$userId, 'isExpired'=>0], '*, ((`totalCostWithoutTax`-`commission_cost`)) AS totalCostforTech, IF((SELECT COUNT(review_id) FROM reviews WHERE reviewed_jobid = id AND reviewed_by = '.$postdata['id'].' AND app_type= '.$postdata['app_type'].' ) > 0, 1, 0)  AS isReviewed');
                        }else{
                            $jobs = $this->Home_Model->selectWhereWithFields('jobs', ['id' => $jobid], '*, ((`totalCostWithoutTax`-`commission_cost`)) AS totalCostforTech, IF((SELECT COUNT(review_id) FROM reviews WHERE reviewed_jobid = id AND reviewed_by = '.$postdata['id'].' AND app_type= '.$postdata['app_type'].' ) > 0, 1, 0)  AS isReviewed');
                        }


                        if($jobs){

                            $jobs['techInfo'] = $this->Home_Model->selectWhere('technicians', ['id' => $jobs['techId']]);
                            $jobs['userInfo'] = $this->Home_Model->selectWhere('users', ['id' => $jobs['userId']]);

                            $jobs['userInfo']['profileImage'] = ($jobs['userInfo']['profileImage'] != "") ? base_url() . "uploads/" . $jobs['userInfo']['profileImage'] : base_url().'/uploads/noimage.png';

                            $date = new DateTime($jobs['userInfo']['dateofbirth']);
                            $now = new DateTime();
                            $interval = $now->diff($date);
                            $jobs['userInfo']['age'] = $interval->y;

                            $date = new DateTime($jobs['techInfo']['dateofbirth']);
                            $now = new DateTime();
                            $interval = $now->diff($date);
                            $jobs['techInfo']['age'] = $interval->y;


                            $jobs['additionalPic'] = ($jobs['additionalPic'] != "") ? base_url() . "uploads/" . $jobs['additionalPic'] : base_url().'/uploads/noimage.png';

                            $jobs['techInfo']['profileImage'] = ($jobs['additionalPic'] != "") ? base_url() . "uploads/" . $jobs['techInfo']['profileImage'] : base_url().'/uploads/noimage.png' ;
                            $jobs['techInfo']['driving_licenceFront'] = ($jobs['techInfo']['driving_licenceFront'] != "") ? base_url() . "uploads/" . $jobs['techInfo']['driving_licenceFront'] : base_url().'/uploads/noimage.png';
                            $jobs['techInfo']['driving_licenceBack'] = ($jobs['techInfo']['driving_licenceBack'] != "") ? base_url() . "uploads/" . $jobs['techInfo']['driving_licenceBack'] : base_url().'/uploads/noimage.png';
                            $jobs['techInfo']['police_document'] = ($jobs['techInfo']['police_document'] != "") ? base_url() . "uploads/" . $jobs['techInfo']['police_document'] : base_url().'/uploads/noimage.png';
                            $jobs['techInfo']['police_document_back'] = ($jobs['techInfo']['police_document_back'] != "") ? base_url() . "uploads/" . $jobs['techInfo']['police_document_back'] : base_url().'/uploads/noimage.png';
                            $jobs['techInfo']['upload_cv'] = ($jobs['techInfo']['upload_cv'] != "") ?   base_url() . "uploads/" . $jobs['techInfo']['upload_cv'] : base_url().'/uploads/noimage.png';



                            $status = "";
                            if($jobs['jobStatus'] == 0){ $status = "Cancelled"; }
                            else if($jobs['jobStatus'] == 1){ $status = "Pending"; }
                            else if($jobs['jobStatus'] == 2){ $status = "Selected"; }
                            else if($jobs['jobStatus'] == 3){ $status = "Started"; }
                            else if($jobs['jobStatus'] == 4){ $status = "Arrived"; }
                            else if($jobs['jobStatus'] == 5){ $status = "Pending Completion"; }
                            else if($jobs['jobStatus'] == 6){ $status = "Completed"; }
                            else if($jobs['jobStatus'] == 7){ $status = "Paid"; }

                            $jobs['jobDate'] = date("d-M-Y", strtotime($jobs['jobDate']));
                            $jobs['jobTime'] = date("h:i A", strtotime($jobs['jobTime']));

                            $jobs['jobStatus'] = $status;
                            $jobs['expireIn'] = getDateDiff($jobs['expiredate']);
                            $jobs['isExpired'] = (($jobs['isExpired'] == 1) ? true : false);

                            $jobs['issues'] = $this->Jobs_Model->getJobIssues($jobs['id']);
                            $jobs['totalCost'] = number_format($jobs['totalCost'], 2);
                            $jobs['totalCostforTech'] = number_format($jobs['totalCostforTech'], 2);

                            /*$category = $this->Home_Model->selectWhere('job_categories',['id'=>$jobs['category'], 'jobId'=>$jobs['id']]);
                            $subcat = $this->Home_Model->selectWhere('job_categories',['id'=>$jobs['subcat'], 'jobId'=>$jobs['id']]);
                            $subsubcat = $this->Home_Model->selectWhere('job_categories',['id'=>$jobs['subsubcat'], 'jobId'=>$jobs['id']]);*/

                            if($jobs['categoryName'] != NULL){
                                $category = ['id'=>$jobs['category'], 'name'=>$jobs['categoryName'], 'isFree'=>$jobs['categoryIsFree']];
                            }

                            if($jobs['subcatName'] != NULL){
                                $subcat = ['id'=>$jobs['subcat'], 'name'=>$jobs['subcatName'], 'isFree'=>$jobs['subcatIsFree']];
                            }

                            if($jobs['subsubcatName'] != NULL){
                                $subsubcat = ['id'=>$jobs['subsubcat'], 'name'=>$jobs['subsubcatName'], 'isFree'=>$jobs['subsubcatIsFree']];
                            }

                            $jobs['category'] = (isset($category)) ? $category : [];
                            $jobs['subcat'] = (isset($subcat)) ? $subcat : [];
                            $jobs['subsubcat'] = (isset($subsubcat)) ? $subsubcat : [];
                            $jobs['duration'] = $jobs['jobEta'];

                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($jobs));
                        }else{
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(['invalid'=>true]));
                        }
                    }
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function changeJobStatus(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('newstatus', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('new status required!'));
            }  elseif (!array_key_exists('jobid', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobid required!'));
            } else {
                if (!isset($postdata['app_type']) || $postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $array = ['jobStatus' => $postdata['newstatus']];
                    if ($table == 'technicians') {
                        $array['techCompleteMessage'] = $postdata['techCompleteMessage'];
                    }
                    if ($this->Home_Model->updateDB('jobs', ['id' => $postdata['jobid']], $array)) {

                        $job = $this->Home_Model->selectWhereWithFields('jobs', ['id' => $postdata['jobid']], '*, IF((SELECT COUNT(review_id) FROM reviews WHERE reviewed_by = ' . $postdata['id'] . ' ) > 0, 1, 0)  AS isReviewed');

                        $status = "";
                        if ($job['jobStatus'] == 0) {
                            $status = "Cancelled";
                        } else if ($job['jobStatus'] == 1) {
                            $status = "Pending";
                        } else if ($job['jobStatus'] == 2) {
                            $status = "Selected";
                        } else if ($job['jobStatus'] == 3) {
                            $status = "Started";
                        } else if ($job['jobStatus'] == 4) {
                            $status = "Arrived";
                        } else if ($job['jobStatus'] == 5) {
                            $status = "Pending Completion";
                        } else if ($job['jobStatus'] == 6) {
                            $status = "Completed";
                        } else if ($job['jobStatus'] == 7) {
                            $status = "Paid";
                        }

                        $job['jobDate'] = date("d-M-Y", strtotime($job['jobDate']));
                        $job['jobTime'] = date("h:i A", strtotime($job['jobTime']));

                        $job['jobStatus'] = $status;

                        $job['issues'] = $this->Jobs_Model->getJobIssues($job['id']);


                        $category = $this->Home_Model->selectWhere('job_categories', ['id' => $job['category'], 'jobId' => $job['id']]);
                        $subcat = $this->Home_Model->selectWhere('job_categories', ['id' => $job['subcat'], 'jobId' => $job['id']]);
                        $subsubcat = $this->Home_Model->selectWhere('job_categories', ['id' => $job['subsubcat'], 'jobId' => $job['id']]);
                        $job['category'] = ($category) ? $category : [];
                        $job['subcat'] = ($subcat) ? $subcat : [];
                        $job['subsubcat'] = ($subsubcat) ? $subsubcat : [];

                        $job['jobStatus'] = $status;

                        if ($postdata['app_type'] == "0") {
                            // Inverse Tables Are Used Here
                            $table = "technicians";
                            $tableApp = "users";
                            $where = ['id' => $job['techId']];
                        } else {
                            // Inverse Tables Are Used Here
                            $table = "users";
                            $tableApp = "technicians";
                            $where = ['id' => $job['userId']];
                        }

                        $userDetails = $this->Home_Model->selectWhere($table, $where);


                        $postString = array();
                        $postString['registration_ids'][] = $userDetails['deviceId'];
                        $title = "EZE-TECH NOTIFICATION";
                        $body = "Please click to view the job";

                        if ($postdata['newstatus'] == 0) {
                            if ($postdata['app_type'] == "0") {
                                $body = "The job has been cancelled by the user.";
                            } else {
                                $body = "Technician Canceled your job offer after Review, due to complexity in the description, Please hit Try again on your app screen and select another technician, Sorry for the inconvenience";
                            }
                        } else if ($postdata['newstatus'] == 2) {
                            $body = "Your Job was reviewed and accepted by our technician and tech on his way, technician will be there in ".$job['jobEta']." you will be notified upon arrival";
                        } else if ($postdata['newstatus'] == 3) {
                            $body = "Tech on the way started the trip";
                        } else if ($postdata['newstatus'] == 4) {
                            $body = "Your technician has arrived";
                        } else if ($postdata['newstatus'] == 5) {
                            $body = "Please review the completion request by the technician";
                        } else if ($postdata['newstatus'] == 6) {
                            $body = "The job is complete.";
                        }
                        //                        else if($postdata['newstatus'] == 7){
                        ////                            $appUser = $this->Home_Model->selectWhere($tableApp, ['id' => $postdata['id']] );
                        //                            $body = "The client has paid for your services";
                        //                        }

                        //                    $postString['notification'] = [ "title"=>$title,"body"=>"Please click to view this appointment","badge"=>1,"sound"=>"default","icon"=>"http://dev20.onlinetestingserver.com/trava-lab-up/assets/images/fav_icon.png" ];

                        $pushArray = [
                            'id' => $job['id'],
                            'jobStatus' => $job['jobStatus'],
                            'isReviewed' => $job['isReviewed']
                        ];
                        if ($userDetails['deviceType'] == "ios") {
                            $postString['notification'] = ["title" => $title, "body" => $body];
                            $postString['data'] = ['job' => $pushArray];
                        } else {
                            $postString['data'] = ['job' => $pushArray, "title" => $title, "body" => $body];
                        }


                        //                    echo json_encode($postString);
                        //

                        $postString['priority'] = 'high';
                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postString));
                        if ($postdata['app_type'] == "0") {
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:key=AIzaSyDs93AGHXUn8Eh09ByKqnG9OJY6TJNPffI', 'Content-Type:application/json'));
                        } else {
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:key=AIzaSyDcXX4jV35Q0do8cmnSdu4Rwg1DtqUCsYI', 'Content-Type:application/json'));
                        }

                        $result = curl_exec($ch);
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode('Status Updated Successfully.'));
                    } else {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode('Status Not Updated, Something going wrong!.'));
                    }

                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function reportJob(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('jobid', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobid required!'));
            } elseif (!array_key_exists('reporter_type', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('reporter_type required!'));
            } elseif (!array_key_exists('reason', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('reason required!'));
            } else {
                if (!isset($postdata['reporter_type']) || $postdata['reporter_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $data = [
                        'reported_job' => $postdata['jobid'],
                        'reported_by' => $postdata['id'],
                        'reason' => $postdata['reason'],
                        'reporter_type' => $postdata['reporter_type']
                    ];
                    if( $this->Home_Model->insertDB('report_requests', $data) ){

                        if($postdata['reporter_type'] == "0"){
                            $reportedUser = $this->Home_Model->selectWhere('users', ['id' => $postdata['id'] ]);
                        }else {
                            $reportedUser = $this->Home_Model->selectWhere('technicians', ['id' => $postdata['id'] ]);
                        }

                        $message = '
                         <div align="center">
							<table width="600" cellspacing="5" cellpadding="5" border="0" style="color:#666 !important;background:none repeat scroll 0% 0% rgb(255,255,255);border-radius:3px;border:1px solid rgb(236,233,233)">
								<tbody>
								    <tr><td style="text-align:center"><img src="' . base_url() . 'include-assets/app-assets/images/'.(($table=='users')?'user':'tech').'-logo.png" width="250" align="center" class="CToWUd a6T" tabindex="0"></td></tr>
								    <tr>
									    <th style="background-color:#52944c;color:white">Dear ' . $reportedUser['first_name'].' '.$reportedUser['last_name']. '</th>
									</tr>
									<tr>
                                        <td valign="top" style="text-align:left;color:#666;font-weight:600">
                                            <span style="color:#666;padding-bottom:10px;font-weight:300;display:block"><br><br>
                                                We have received your report, we will get back to you soon,                                                                                            									    
                                            </span>
                                        </td>
									</tr>
									<tr><td><br>Thanks,<br><b>Teeam EZE-TECH</b></td></td></tr>
								</tbody>
							</table>
						</div>
                        ';
                        $emailsend = sendEmail(['fromEmail' => 'do-not-reply@eze-tech.com', 'toEmail' => $reportedUser['email'], 'subject' => 'Thanks For Your Report', 'message' => $message]);

                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode('Report Submitted'));
                    }else {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode('Report Not Submitted, Something going wrong!.'));
                    }
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function reviewJob(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('jobid', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobid required!'));
            } elseif (!array_key_exists('stars', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('stars required!'));
            } elseif (!array_key_exists('reviewed_to', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('Reviewed To required!'));
            } elseif (!array_key_exists('review', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('review required!'));
            } else {
                if (!isset($postdata['app_type']) || $postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {


                    $data = [
                        'reviewed_to' => $postdata['reviewed_to'],
                        'reviewed_by' => $postdata['id'],
                        'reviewed_jobid' => $postdata['jobid'],
                        'reviewed_rate' => $postdata['stars'],
                        'reviewed_message' => $postdata['review'],
                        'app_type' => $postdata['app_type'],
                    ];

                    $select = $this->Home_Model->selectWhere('reviews',$data);
                    if(!$select){
                        if( $this->Home_Model->insertDB('reviews', $data) ){
                            $job = $this->Home_Model->selectWhereWithFields('jobs', ['id' => $postdata['jobid']] , '*, IF((SELECT COUNT(review_id) FROM reviews WHERE reviewed_to = '. $postdata['id'].' and reviewed_jobid = '.$postdata['jobid'].' ) > 0, 1, 0)  AS isReviewed');

                            $status = "";
                            if($job['jobStatus'] == 0){ $status = "Cancelled"; }
                            else if($job['jobStatus'] == 1){ $status = "Pending"; }
                            else if($job['jobStatus'] == 2){ $status = "Selected"; }
                            else if($job['jobStatus'] == 3){ $status = "Started"; }
                            else if($job['jobStatus'] == 4){ $status = "Arrived"; }
                            else if($job['jobStatus'] == 5){ $status = "Pending Completion"; }
                            else if($job['jobStatus'] == 6){ $status = "Completed"; }
                            else if($job['jobStatus'] == 7){ $status = "Paid"; }

                            $job['jobDate'] = date("d-M-Y", strtotime($job['jobDate']));
                            $job['jobTime'] = date("h:i A", strtotime($job['jobTime']));

                            $job['jobStatus'] = $status;

                            $job['issues'] = $this->Jobs_Model->getJobIssues($job['id']);


                            $category = $this->Home_Model->selectWhere('job_categories',['id'=>$job['category'], 'jobId'=>$job['id']]);
                            $subcat = $this->Home_Model->selectWhere('job_categories',['id'=>$job['subcat'], 'jobId'=>$job['id']]);
                            $subsubcat = $this->Home_Model->selectWhere('job_categories',['id'=>$job['subsubcat'], 'jobId'=>$job['id']]);
                            $job['category'] = ($category) ? $category : [];
                            $job['subcat'] = ($subcat) ? $subcat : [];
                            $job['subsubcat'] = ($subsubcat) ? $subsubcat : [];

                            $job['jobStatus'] = $status;

                            $pushArray = [
                                'id' => $job['id'],
                                'jobStatus' => $job['jobStatus'],
                                'isReviewed' => $job['isReviewed']
                            ];

                            if( $postdata['app_type'] == "0"){
                                // Inverse Tables Are Used Here
                                $table = "technicians";
                                $where = ['id' => $postdata['reviewed_to']];
                            }else {
                                // Inverse Tables Are Used Here
                                $table = "users";
                                $where = ['id' => $postdata['reviewed_to']];
                            }

                            $userDetails = $this->Home_Model->selectWhere($table,$where );


                            $postString = array();
                            $postString['registration_ids'][] = $userDetails['deviceId'];
                            $title = "EZE-TECH NOTIFICATION";
                            $body = "Please click to view the job";


                            if( $postdata['app_type'] == "0"){
                                $body = "Review Received";
                            }else {
                                $body = "Review Received";
                            }

                            if($userDetails['deviceType'] == "ios"){
                                $postString['notification'] = [ "title"=>$title,"body"=> $body];
                                $postString['data'] = [ 'job'=>$pushArray];
                            }else {
                                $postString['data'] = [ 'job'=> $pushArray, "title"=>$title,"body"=>$body];
                            }



//                    echo json_encode($postString);
//

                            $postString['priority'] = 'high';
                            $ch = curl_init();

                            curl_setopt($ch, CURLOPT_URL,            "https://fcm.googleapis.com/fcm/send" );
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
                            curl_setopt($ch, CURLOPT_POST,           1 );
                            curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($postString) );
                            if( $postdata['app_type'] == "0"){
                                curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization:key=AIzaSyDs93AGHXUn8Eh09ByKqnG9OJY6TJNPffI','Content-Type:application/json'));
                            }else {
                                curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization:key=AIzaSyDcXX4jV35Q0do8cmnSdu4Rwg1DtqUCsYI','Content-Type:application/json'));
                            }

                            $result=curl_exec ($ch);


                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode('Review Submitted'));
                        }else {
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode('Review Not Submitted, Something going wrong!.'));
                        }
                    }else {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode('Review Already Submitted'));
                    }


                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function JobTransaction(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('jobid', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobid required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    /*$job = $this->Home_Model->selectWhereWithFields('jobs',['id' => $postdata['jobid']],"category,subcat,subsubcat,totalCost, factor_count,factor_applied_cost,tax_cost,commission_cost, ROUND((`totalCostWithoutTax`-(`totalCostWithoutTax`*(`commission_cost`)/100)),2) AS totalCostforTech");
                    $job['category'] = $this->Home_Model->selectWhere('job_categories',['id' => $job['category'], 'jobId'=>$postdata['jobid']]);
                    $subcat = $this->Home_Model->selectWhere('job_categories',['id' => $job['subcat'], 'jobId'=>$postdata['jobid']]);
                    $job['subcat'] = ($subcat) ? $subcat : [];
                    $subsubcat = $this->Home_Model->selectWhere('job_categories',['id' => $job['subsubcat'], 'jobId'=>$postdata['jobid']]);
                    $job['subsubcat'] = ($subsubcat) ? $subsubcat : [];


                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($job));*/

                    $result = array();
                    $totalCost = 0;
                    $user = $this->Home_Model->selectWhere('users', ['id' => $postdata['id']]);
                    $job = $this->Home_Model->selectWhere('jobs', ['id' => $postdata['jobid']]);

                    /*$mainCatId = $job['category'];
                    $subCatId = $job['subcat'];
                    $subSubCatId = $job['subsubcat'];

                    $mainCat = $this->Home_Model->selectWhere('categories', ['id' => $mainCatId]);
                    $subCat = $this->Home_Model->selectWhere('categories', ['id' => $subCatId]);
                    $subSubCat = $this->Home_Model->selectWhere('categories', ['id' => $subSubCatId]);

                    if($mainCat && $mainCat['isFree'] == 0){
                        $result['mainCat']['name'] = $mainCat['name'];
                        $result['mainCat']['cost'] = number_format(($user['user_type'] == 0)?$mainCat['cost']:$mainCat['commercial_cost'], 2);
                        $totalCost += $result['mainCat']['cost'];
                    }

                    if($subCat && $subCat['isFree'] == 0){
                        $result['subCat']['name'] = $subCat['name'];
                        $result['subCat']['cost'] = number_format(($user['user_type'] == 0)?$subCat['cost']:$subCat['commercial_cost'], 2);
                        $totalCost += $result['subCat']['cost'];
                    }

                    if($subSubCat && $subSubCat['isFree'] == 0){
                        $result['subSubCat']['name'] = $subSubCat['name'];
                        $result['subSubCat']['cost'] = number_format(($user['user_type'] == 0)?$subSubCat['cost']:$subSubCat['commercial_cost'], 2);
                        $totalCost += $result['subSubCat']['cost'];
                    }*/

                    if($job['categoryName'] != NULL && $job['categoryIsFree'] == 0){
                        $result['mainCat']['name'] = $job['categoryName'];
                        $result['mainCat']['cost'] = number_format($job['categoryPrice'],2);
                    }

                    if($job['subcatName'] != NULL && $job['subcatIsFree'] == 0){
                        $result['subCat']['name'] = $job['subcatName'];
                        $result['subCat']['cost'] = number_format($job['subcatPrice'],2);
                    }

                    if($job['subsubcatName'] != NULL && $job['subsubcatIsFree'] == 0){
                        $result['subSubCat']['name'] = $job['subsubcatName'];
                        $result['subSubCat']['cost'] = number_format($job['subsubcatPrice'],2);
                    }


                    $settings = $this->Home_Model->selectWhere('settings', ['id' => 1]);

                    //$getTaxColum = (($currentState == STATE_ONE_FOR_TAX)? $settings[STATE_ONE_COLUMN_FOR_TAX]: (($currentState == STATE_TWO_FOR_TAX) ? $settings[STATE_TWO_COLUMN_FOR_TAX] : $settings['tax_rate']));
                    $stateOne = explode("||", STATE_ONE_FOR_TAX);
                    $stateTwo = explode("||", STATE_TWO_FOR_TAX);
                    $getHstText = ((in_array($job['state'], $stateOne))? 'Q.S.T': ((in_array($job['state'], $stateTwo)) ? 'H.S.T' : 'TAX'));
                    $tax = $job['tax_cost'];
                    $gst = $job['gst_cost'];
                    //$tax = $getTaxColum;
                    $totalCostWithTaxAndFactor = $job['totalCost'];
                    $totalCostWithoutTax = $job['totalCostWithoutTax'];
                    $factorAppliedCost = $job['factor_applied_cost'];
                    $result['tax'] = number_format($tax,2);
                    $result['totalCostWithTaxAndFactor'] = number_format($totalCostWithTaxAndFactor,2);
                    $result['factorAppliedCost'] = number_format($factorAppliedCost,2);
                    $result['totalCostWithoutTax'] = number_format($totalCostWithoutTax,2);
                    $result['gst'] = number_format($gst,2);
                    $result['gst_text'] = "G.S.T";
                    $result['hst_text'] = $getHstText;
                    $result['factor_text'] = "Surge Price";
                    $result['subtotal_text'] = "Sub Total";
                    $result['total_text'] = "Total";
                    $result['tax_percentage'] = number_format($tax/$totalCostWithoutTax*100,2).'%';
                    $result['gst_percentage'] = number_format($gst/$totalCostWithoutTax*100,2).'%';
                    $result['tax_message'] = "";

                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($result));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function viewProfile()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if (!isset($postdata['app_type']) || $postdata['app_type'] == 0) {
                    $table = 'users';
                } else {
                    $table = 'technicians';
                }
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $table) == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    if (!isset($postdata['viewType']) || $postdata['viewType'] == 0) {
                        $table = 'users';
                    } else {
                        $table = 'technicians';
                    }
                    $ret = $this->Home_Model->selectWhere($table, array('id' => $postdata['viewId']));
                    if($ret){
                        $date = new DateTime($ret['dateofbirth']);
                        $now = new DateTime();
                        $interval = $now->diff($date);
                        $ret['age'] = $interval->y;

                        $ret['profileImage'] = base_url() . "uploads/" . $ret['profileImage'];

                        if (isset($postdata['viewType']) && $postdata['viewType'] == 1) {
                            $ret['completed_jobs'] = $this->Home_Model->countResult('jobs',['techId'=>$postdata['viewId'], 'jobStatus'=>'6'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['viewId'], 'jobStatus'=>'7']);
                            $ret['pending_jobs'] = $this->Home_Model->countResult('jobs',['techId'=>$postdata['viewId'], 'jobStatus'=>'2'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['viewId'], 'jobStatus'=>'3'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['viewId'], 'jobStatus'=>'4'])+$this->Home_Model->countResult('jobs',['techId'=>$postdata['viewId'], 'jobStatus'=>'5']);

                            $rate = $this->db->query('SELECT ROUND(AVG(reviewed_rate)) as reviewed_rate FROM reviews WHERE reviewed_to = "'.$postdata['viewId'].'"')->row_array();
                            $ret['rating'] = (($rate['reviewed_rate'])?$rate['reviewed_rate']:0);

                            $testimonails = $this->db->query('SELECT *,( SELECT CONCAT(`first_name`," ",last_name) FROM `users` WHERE id = reviews.`reviewed_by` ) AS reviewerName, (SELECT nickname FROM `technicians` WHERE id = reviews.`reviewed_by` ) AS nickname FROM reviews WHERE reviewed_to = "'.$postdata['viewId'].'" AND app_type= "'.$postdata['viewId'].'"')->result_array();
                            $ret['testimonals'] = ($testimonails) ? $testimonails : [];

                            $ret['driving_licenceFront'] = base_url() . "uploads/" . $ret['driving_licenceFront'];
                            $ret['driving_licenceBack'] = base_url() . "uploads/" . $ret['driving_licenceBack'];
                            $ret['police_document'] = base_url() . "uploads/" . $ret['police_document'];
                            $ret['police_document_back'] = base_url() . "uploads/" . $ret['police_document_back'];
                        }else {
                            $posted_job = $this->Home_Model->countResult('jobs',['userId'=>$postdata['viewId'],'techId !=' => "0", 'jobStatus'=>'1']);
                            $ret['posted_jobs'] = ($posted_job) ? $posted_job : 0;
                            $ret['pending_jobs'] = $this->Home_Model->countResult('jobs',['userId'=>$postdata['viewId'], 'jobStatus'=>'2'])+$this->Home_Model->countResult('jobs',['userId'=>$postdata['viewId'], 'jobStatus'=>'3'])+$this->Home_Model->countResult('jobs',['userId'=>$postdata['viewId'], 'jobStatus'=>'4'])+$this->Home_Model->countResult('jobs',['userId'=>$postdata['viewId'], 'jobStatus'=>'5']);
                            $rate = $this->db->query('SELECT ROUND(AVG(reviewed_rate)) as reviewed_rate FROM reviews WHERE reviewed_to = "'.$postdata['viewId'].'"')->row_array();
                            $ret['rating'] = (($rate['reviewed_rate'])?$rate['reviewed_rate']:0);

                            $testimonails = $this->db->query('SELECT *,( SELECT CONCAT(`first_name`," ",last_name) FROM `technicians` WHERE id = reviews.`reviewed_by` ) AS reviewerName, (SELECT nickname FROM `technicians` WHERE id = reviews.`reviewed_by` ) AS nickname FROM reviews WHERE reviewed_to = "'.$postdata['viewId'].'"')->result_array();
                            $ret['testimonals'] = ($testimonails) ? $testimonails : [];
                        }
                    }else {
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode("No Data Found"));
                    }

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
                }
            }
        }
    }

    public function paymentHistoy()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
//                    echo $postdata['id'];
                    $data['unpaidJobs'] = $this->Home_Model->selectWhereResultWithFields('jobs',['userId'=>$postdata['id'], 'jobStatus'=> "6"], "id,jobTitle,DATE_FORMAT(jobDate,'%d-%b-%Y') AS jobDate,DATE_FORMAT(jobTime,'%h:%i %p') AS jobTime,jobStatus,totalCost, factor_count,factor_applied_cost,tax_cost,commission_cost, ROUND((`totalCostWithoutTax`-(`totalCostWithoutTax`*(`commission_cost`)/100)),2) AS totalCostforTech", 'id', 'desc');

                    $data['paidJobs'] = $this->Home_Model->selectWhereResultWithFields('jobs',['userId'=>$postdata['id'], 'jobStatus'=> "7"],"id,jobTitle,DATE_FORMAT(jobDate,'%d-%b-%Y') AS jobDate,DATE_FORMAT(jobTime,'%h:%i %p') AS jobTime,jobStatus,totalCost, factor_count,factor_applied_cost,tax_cost,commission_cost, ROUND((`totalCostWithoutTax`-(`totalCostWithoutTax`*(`commission_cost`)/100)),2) AS totalCostforTech", 'id', 'desc');
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($data));

                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function myTransactions()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('paymentStatus', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobStatus required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
//                    echo $postdata['id'];
                    $data['jobs'] = $this->Home_Model->selectWhereResultWithFields('jobs',['techId'=>$postdata['id'], 'paymentStatus'=> $postdata["paymentStatus"], 'saveStatus'=>'Completed' ], "id,jobTitle,DATE_FORMAT(jobDate,'%d-%b-%Y') AS jobDate,DATE_FORMAT(jobTime,'%h:%i %p') AS jobTime,jobStatus,totalCost, factor_count,factor_applied_cost,tax_cost, gst_cost,commission_cost,  ROUND((`totalCostWithoutTax`-(`totalCostWithoutTax`*(`commission_cost`)/100)),2) AS totalCostforTech", 'id', 'desc');
                    $sum = 0;

                    foreach ($data['jobs'] as $index => $job) {
                        $sum += $job['totalCostforTech'];
                    }

                    $data['totalCost'] = number_format($sum,2);

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($data));

                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function payNow(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } elseif (!array_key_exists('token', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('token required!'));
            } elseif (!array_key_exists('jobid', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobid required!'));
            } elseif (!array_key_exists('name', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('name required!'));
            } elseif (!array_key_exists('email', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('Email required!'));
            } elseif (!array_key_exists('totalCost', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('totalCost required!'));
            } elseif (!array_key_exists('country', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('country required!'));
            } elseif (!array_key_exists('number', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('number required!'));
            } elseif (!array_key_exists('expMonth', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('expMonth required!'));
            } elseif (!array_key_exists('expYear', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('expYear required!'));
            } elseif (!array_key_exists('cvc', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('cvc required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    require_once APPPATH."third_party/stripe/init.php";

                    try{
                        if(STRIPE_MODE == 'live'){
                            $SSK = STRIPE_LIVE_SK;
                        }else{
                            $SSK = STRIPE_TEST_SK;
                        }


                        $test ='';
                        \Stripe\Stripe::setVerifySslCerts(false);
                        \Stripe\Stripe::setApiKey($SSK);


                        $token = \Stripe\Token::create([
                            'card' => [
                                'number' => $postdata['number'],
                                'exp_month' => $postdata['expMonth'],
                                'exp_year' => $postdata['expYear'],
                                'cvc' => $postdata['cvc']
                            ]
                        ]);

                        $customer = \Stripe\Customer::create(array(
                            'email' => $postdata['email'],
                            'source' => $token
                        ));
                        $charge = \Stripe\Charge::create(array(
                            'customer' => $customer->id,
                            'amount' => $postdata['totalCost']*100,
                            'currency' => 'CAD',
                            'description'=>'EZE-TECH User Payment by User: '.$postdata['name']."and Email: ".$postdata['email']
                        ));

                        if($charge['paid']){
                            $this->load->library('encryption');
                            $card = array(
                                'cardHolderName' => $postdata['name'],
                                'cardNumber' => $this->encryption->encrypt($postdata['number']),
                                'cardExpiryMonth' => $this->encryption->encrypt($postdata['expMonth']),
                                'cardExpiryYear' => $this->encryption->encrypt($postdata['expYear']),
                                'cardCvc' => $this->encryption->encrypt($postdata['cvc']),
                                'cardCountry' => $postdata['country'],
                                'updatedate' => CURRENT_DATETIME,
                            );

                            $update = $this->Home_Model->updateDB('user_creditcards', ['fk_uid'=>$postdata['id']], $card);

                            $data = array();
                            $data['userId'] = $postdata['id'];
                            $data['userName'] = $postdata['name'];
                            $data['UserEmail'] = $postdata['email'];
                            $data['PaidAmount'] = $postdata['totalCost'];
                            $data['tokenID'] = $postdata['token'];
                            $data['customerIDStripe'] = $customer->id;
                            $data['JobId'] = $postdata['jobid'];
                            $data['country'] = $postdata['country'];
                            $data['response'] = $charge;

                            $this->Home_Model->insertDB('payments',$data);

                            $this->Home_Model->updateDB('jobs',['id'=> $postdata['jobid'] ],['jobStatus'=> '7','paymentStatus'=> '0' ]);

                            //INVOICE EMAIL
                            $getJob = $this->Home_Model->selectWhere('jobs', ['id'=> $postdata['jobid']]);
                            $getUser = $this->Home_Model->selectWhere('users', ['id'=> $postdata['id']]);
                            $result =[];

                            if($getJob['categoryName'] != NULL && $getJob['categoryIsFree'] == 0){
                                $result['mainCat']['name'] = $getJob['categoryName'];
                                $result['mainCat']['cost'] = number_format($getJob['categoryPrice'],2);
                            }

                            if($getJob['subcatName'] != NULL && $getJob['subcatIsFree'] == 0){
                                $result['subCat']['name'] = $getJob['subcatName'];
                                $result['subCat']['cost'] = number_format($getJob['subcatPrice'],2);
                            }

                            if($getJob['subsubcatName'] != NULL && $getJob['subsubcatIsFree'] == 0){
                                $result['subSubCat']['name'] = $getJob['subsubcatName'];
                                $result['subSubCat']['cost'] = number_format($getJob['subsubcatPrice'],2);
                            }

                            $result['tax'] = number_format($getJob['tax_cost'],2);
                            $result['gst'] = number_format($getJob['gst_cost'],2);
                            $result['factor_applied_cost'] = number_format($getJob['factor_applied_cost'],2);
                            $result['totalCostWithOutTax'] = number_format($getJob['totalCostWithoutTax'],2);
                            $result['totalCost'] = number_format($getJob['totalCost'],2);


                            $stateOne = explode("||", STATE_ONE_FOR_TAX);
                            $stateTwo = explode("||", STATE_TWO_FOR_TAX);
                            $getHstText = ((in_array($getJob['state'], $stateOne))? 'Q.S.T': ((in_array($getJob['state'], $stateTwo)) ? 'H.S.T' : 'TAX'));

                            $message="<p>We have received your payment, please find your job details below:</p> 
                              <table class=\"purchase\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                <tr>
                                     <td colspan=\"2\">
                                        <strong>Work order # ".sprintf("%07d", $getJob['id'])."</strong>
                                    </td>
                                </tr>
                                <tr>
                                  <td colspan=\"2\">
                                    <table class=\"purchase_content\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                      <tr>
                                        <th class=\"purchase_heading\">
                                          <p style='font-size:16px;font-weight:bold;'>Service Name</p>
                                        </th>
                                        <th class=\"purchase_heading\">
                                          <p class=\"align-right\" style='font-size:16px;font-weight:bold;'>Amount</p>
                                        </th>
                                      </tr>";
                            if(isset($result['mainCat']['name'])) {
                                $message .= "<tr>
                                        <td width=\"80%\" class=\"purchase_item\" style='font-size:16px;font-weight:bold;'>" . $result['mainCat']['name'] . "</td>
                                        <td class=\"align-right\" width=\"20%\" class=\"purchase_item\">&#36;" . number_format($result['mainCat']['cost'],2) . "</td>
                                      </tr>";
                            }
                            if(isset($result['subCat']['name'])) {
                                $message .= "<tr>
                                                <td width=\"80%\" class=\"purchase_item\" style='font-size:16px;font-weight:bold;'>" . $result['subCat']['name'] . "</td>
                                                <td class=\"align-right\" width=\"20%\" class=\"purchase_item\">&#36;" . number_format($result['subCat']['cost'],2) . "</td>
                                              </tr>";
                            }
                            if(isset($result['subSubCat']['name'])) {
                                $message .= "<tr>
                                                <td width=\"80%\" class=\"purchase_item\" style='font-size:16px;font-weight:bold;'>" . $result['subSubCat']['name'] . "</td>
                                                <td class=\"align-right\" width=\"20%\" class=\"purchase_item\">&#36;" . number_format($result['subSubCat']['cost'],2) . "</td>
                                              </tr>";
                            }

                            if($result['factor_applied_cost'] != '0') {
                                $message .= "<tr>
                                                <td width=\"80%\" class=\"purchase_item\" style='font-size:16px;font-weight:bold;'>Surge Price</td>
                                                <td class=\"align-right\" width=\"20%\" class=\"purchase_item\">&#36;" . number_format($result['factor_applied_cost'],2) . "</td>
                                              </tr>";
                            }
                            if($getJob['techCompleteMessage']) {
                                $message .= "<tr>
                                                        <td  colspan='2' width=\"100%\" class=\"purchase_item\"><span style='font-size:16px;font-weight:bold;'>Tech notes:<span><br /><span style='font-size:14px;font-weight:300;'>".$getJob['techCompleteMessage']."</span></td>
                                                      </tr>";
                            }
                            $message .= "<tr>
                                                    <td width=\"80%\" class=\"purchase_item\" style='font-size:16px;font-weight:bold;'>Sub Total</td>
                                                    <td class=\"align-right\" width=\"20%\" class=\"purchase_item\">&#36;" . number_format($result['totalCostWithOutTax'],2) . "</td>
                                                  </tr>";

                            if($result['tax'] != '0.00') {
                                $message .= "<tr>
                                                    <td width=\"80%\" class=\"purchase_item\" style='font-size:16px;font-weight:bold;'>".$getHstText."</td>
                                                    <td class=\"align-right\" width=\"20%\" class=\"purchase_item\">&#36;" . number_format($result['tax'],2) . "</td>
                                                  </tr>";
                            }
                            if($result['gst'] != '0.00') {
                                $message .= "<tr>
                                                            <td width=\"80%\" class=\"purchase_item\" style='font-size:16px;font-weight:bold;'>G.S.T</td>
                                                            <td class=\"align-right\" width=\"20%\" class=\"purchase_item\">&#36;" . number_format($result['gst'],2) . "</td>
                                                          </tr>";
                            }
                            $message .="<tr>
                                        <td width=\"80%\" class=\"purchase_footer\" valign=\"middle\">
                                          <p class=\"purchase_total purchase_total--label\">Total</p>
                                        </td>
                                        <td width=\"20%\" class=\"purchase_footer\" valign=\"middle\">
                                          <p class=\"purchase_total\">&#36;".number_format($result['totalCost'],2)."</p>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                                <tr>
                              </table>";


                            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
                            $m = '<!doctype html>
							<html>
							<head>
							<meta charset="utf-8">
							<meta name="viewport" content="width=device-width, initial-scale=1.0">
							<title>Eze Tech</title>
								<Style>
									*{
										margin:0;
										padding: 0;
									}
								</Style>
							</head>

							<body bgcolor="#FFFFFF" style="width: 1200px; text-align: center; margin: 0 auto; font-family: Arial, Helvetica, sans-serif;" width="1200">
								
								<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="margin: 0 auto; font-family: Arial, Helvetica, sans-serif;">
							  <tbody>
								<tr>
									<td width="10">&nbsp;</td>
								  <td>&nbsp;</td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
									<td width="10">&nbsp;</td>
								  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tbody>
								<tr>
								  <td valign="middle" width="50%" align="left"><img src="Group 1.png" width="120"></td>
								  <td  width="50%" valign="top"><table width="100%" border="1" cellspacing="0" cellpadding="2">
							  <tbody>
								<tr>
								  <td width="65%" align="right" style="padding:5px; font-weight: bold; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">Date</td>
								  <td width="33%" align="left" style="padding:5px; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">'.date('d-m-Y').'</td>
								</tr>
							
								  <tr>
								  <td width="65%" align="right" style="padding:5px; font-weight: bold; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">Work Order #</td>
								  <td width="33%" align="left" style="padding:5px; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">'.sprintf("%07d", $getJob['id']).'</td>
								</tr>
								  
							   
							  </tbody>
							</table>
							</td>
								</tr>
							  </tbody>
							</table></td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
								 <td width="10">&nbsp;</td>
								  <td>&nbsp;</td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
								 <td width="10">&nbsp;</td>
								  <td style="font-family: Arial, Helvetica, sans-serif; font-size: 22px; font-weight: bold;" align="right">INVOICE</td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
								 <td width="10">&nbsp;</td>
								  <td>&nbsp;</td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
								 <td width="10">&nbsp;</td>
								  <td><table width="100%"  cellspacing="0" cellpadding="0">
							  <tbody>
								<tr>
								  <td width="49%"><table width="100%" cellspacing="0" cellpadding="0">
							  <tbody>
								<tr>
								  <td align="left" style="font-size: 18px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">Technician Details</td>
								</tr>
								  <tr>
								  <td>&nbsp;</td>
								</tr>
								<tr>
								  <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Name:</strong></td>
								</tr>
								<tr>
								  <td style="font-size: 8px;">&nbsp;</td>
								</tr>
								
								<tr>
								  <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Phone Number:</strong></td>
								</tr>
								  <tr>
								  <td style="font-size: 8px;">&nbsp;</td>
								</tr>
								<tr>
								  <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Address:</strong> </td>
								</tr>
							  </tbody>
							</table>
							</td>
									<td width="2%">&nbsp;</td>
								  <td width="49%"><table width="100%" cellspacing="0" cellpadding="0">
							  <tbody>
								<tr>
								  <td align="left" style="font-size: 18px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">User Details</td>
								</tr>
								  <tr>
								  <td>&nbsp;</td>
								</tr>
								<tr>
								  <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Name:</strong></td>
								</tr>
								<tr>
								  <td style="font-size: 8px;">&nbsp;</td>
								</tr>
								
								<tr>
								  <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Phone Number:</strong></td>
								</tr>
								  <tr>
								  <td style="font-size: 8px;">&nbsp;</td>
								</tr>
								<tr>
								  <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Address:</strong> </td>
								</tr>
							  </tbody>
							</table></td>
								</tr>
							  </tbody>
							</table>
							</td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
								 <td width="10">&nbsp;</td>
								  <td >&nbsp;</td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
								 <td width="10">&nbsp;</td>
								  <td><table width="100%" border="1" cellspacing="0" cellpadding="0">
							  <tbody>';
							  $m .='<tr>
								  <th style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="left" width="10%">S#:</th>
								  <th style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="left" width="60%">Description</th>
								  <th style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;"  align="left" width="15%">Amount</th>
								</tr>';
								
								$m .='<tr>
								  <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;"  align="left" width="10%">1</td>
								  <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="left" width="60%">Unmountable boot volume</td>
								  <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="right" width="15%">$ 89.00</td>
								</tr>';
								$m .='<tr>
								  <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>
								  <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>
								  <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>
								</tr>
								<tr>
								  <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>
								  <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>

								  <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>
								</tr>
								</tr>
							  </tbody>
							</table>
							</td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
								 <td width="10">&nbsp;</td>
								  <td>&nbsp;</td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
								 <td width="10">&nbsp;</td>
								  <td><table width="100%" border="1" cellspacing="0" cellpadding="0">
							  <tbody>
								<tr>
								  <td width="85%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold;" align="right">T.V.Q</td>
								  <td width="15%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="right">$ 8.81</td>
								</tr>
								<tr>
								   <td width="85%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold;" align="right">T.P.S</td>
								  <td width="15%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="right">$ 4.45</td>
								</tr>
								<tr>
								   <td width="85%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold;" align="right">Total Due</td>
								  <td width="15%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold;" align="right">$ 102.26</td>
								</tr>
							  </tbody>
							</table>
							</td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
								 <td width="10">&nbsp;</td>
								  <td>&nbsp;</td>
									<td width="10">&nbsp;</td>
								</tr>

								  <tr>
								 <td width="10">&nbsp;</td>
								  <td>&nbsp;</td>
									<td width="10">&nbsp;</td>
								</tr>
								   <tr>
								 <td width="10">&nbsp;</td>
								  <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 18px;" align="left"><strong>Thanks</strong><br><br>EZE-TECH Support Team
							</td>
									<td width="10">&nbsp;</td>
								</tr>
							  </tbody>
							</table>


							</body>
							</html>
							';
                            $mpdf->WriteHTML($m);

                            $mpdf->SetTitle('PDF');
                            $filename="/var/www/html/uploads/invoices/test1.pdf";
                            $mpdf->Output($filename, 'F');

                            $mail = $this->sendHtmlEmail(['fromEmail'=>'do-not-reply@eze-tech.com','toEmail'=>$getUser['email'],'subject'=>'Payment Received' ,'message'=>$message, 'name'=>$getUser["first_name"] . ' ' . $getUser["last_name"], 'filePath'=>'/var/www/html/uploads/invoices/test1.pdf', 'fileName'=>'test1.pdf']);

                            //INVOICE EMAIL

                            $ret = $this->Home_Model->selectWhere('users', ['id' => $postdata['id']]);
//                            $message = '
//                             <div align="center">
//                                <tab    le width="600" cellspacing="5" cellpadding="5" border="0" style="color:#666 !important;background:none repeat scroll 0% 0% rgb(255,255,255);border-radius:3px;border:1px solid rgb(236,233,233)">
//                                    <tbody>
//                                        <tr><td style="text-align:center"><img src="' . base_url() . 'include-assets/app-assets/images/emaillogo.png" height="100px" align="center" class="CToWUd a6T" tabindex="0"></td></tr>
//                                        <tr>
//                                            <th style="background-color:#52944c;color:white">Dear ' . $ret['first_name'] . '</th>
//                                        </tr>
//                                        <tr>
//                                            <td valign="top" style="text-align:left;color:#666;font-weight:600"><span style="color:#666;padding-bottom:10px;font-weight:300;display:block"><br><br>
//                                                Your application has been received and in reviewing process, we\'ll come back to you shortly
//                                                </span>
//                                            </td>
//                                        </tr>
//                                        <tr><td><br>Regards,<br><b>EZE-TECH</b></td></td></tr>
//                                    </tbody>
//                                </table>
//                            </div>
//                            ';
//                            $emailsend = sendEmail(['fromEmail' => 'do-not-reply@eze-tech.com', 'toEmail' => $ret['email'], 'subject' => 'Congratulations', 'message' => $message]);

                            $job = $this->Home_Model->selectWhereWithFields('jobs', ['id' => $postdata['jobid']] , 'id,techId,userId,jobStatus, IF((SELECT COUNT(review_id) FROM reviews WHERE reviewed_by = '. $postdata['id'].' ) > 0, 1, 0)  AS isReviewed');

                            $where = ['id' => $job['techId'] ];
                            $userDetails = $this->Home_Model->selectWhere('technicians',$where );

                            $status = "";
                            if($job['jobStatus'] == 0){ $status = "Cancelled"; }
                            else if($job['jobStatus'] == 1){ $status = "Pending"; }
                            else if($job['jobStatus'] == 2){ $status = "Selected"; }
                            else if($job['jobStatus'] == 3){ $status = "Started"; }
                            else if($job['jobStatus'] == 4){ $status = "Arrived"; }
                            else if($job['jobStatus'] == 5){ $status = "Pending Completion"; }
                            else if($job['jobStatus'] == 6){ $status = "Completed"; }
                            else if($job['jobStatus'] == 7){ $status = "Paid"; }

                            $job['jobStatus'] = $status;

                            $pushArray = [
                                'id' => $job['id'],
                                'jobStatus' => $job['jobStatus'],
                                'isReviewed' => $job['isReviewed']
                            ];

                            $postString = array();
                            $postString['registration_ids'][] = $userDetails['deviceId'];
                            $title = "EZE-TECH JOB NOTIFICATION";
//                            $body = "User paid for your job, please review job";
                            $body ="The client ".$ret['first_name']." ".$ret['last_name']." has paid for your services";

//                    $postString['notification'] = [ "title"=>$title,"body"=>"Please click to view this appointment","badge"=>1,"sound"=>"default","icon"=>"http://dev20.onlinetestingserver.com/trava-lab-up/assets/images/fav_icon.png" ];

                            if($userDetails['deviceType'] == "ios"){
                                $postString['notification'] = [ "title"=>$title,"body"=> $body];
                                $postString['data'] = [ 'job'=>$pushArray];
                            }else {
                                $postString['data'] = [ 'job'=> $pushArray, "title"=>$title,"body"=>$body];
                            }



//                    echo json_encode($postString);
//

                            $postString['priority'] = 'high';
                            $ch = curl_init();

                            curl_setopt($ch, CURLOPT_URL,            "https://fcm.googleapis.com/fcm/send" );
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
                            curl_setopt($ch, CURLOPT_POST,           1 );
                            curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($postString) );
                            curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization:key=AIzaSyDs93AGHXUn8Eh09ByKqnG9OJY6TJNPffI','Content-Type:application/json'));

                            $result=curl_exec($ch);

                            return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($charge['outcome']['seller_message']));
                        }else {
                            return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode($charge['outcome']['seller_message']));
                        }

                    }catch(Exception $e){
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode($e->jsonBody['error']['message']));
                    }
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function viewPage($id){
        $page = $this->Home_Model->selectWhere('pages',['id'=>$id]);
        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($page));
    }

    //MZ

    public function getCreditCard(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $this->load->library('encryption');
                    $ret = $this->Home_Model->selectWhere('user_creditcards', array('fk_uid' => $postdata['id']));
                    if($ret){
                        $ret['status'] = true;
                        unset($ret['validateResponse']);
                        $ret['cardNumber'] = $this->encryption->decrypt($ret['cardNumber']);
                        $ret['cardExpiryMonth'] = $this->encryption->decrypt($ret['cardExpiryMonth']);
                        $ret['cardExpiryYear'] = $this->encryption->decrypt($ret['cardExpiryYear']);
                        $ret['cardCvc'] = $this->encryption->decrypt($ret['cardCvc']);
                        if (array_key_exists('jobId', $postdata)) {
                            $ret['breadcrumb'] = $this->loadBreadCrumb($postdata['id'], $postdata['jobId']);
                        }
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($ret));
                    }else {
                        return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('"No Credit Card found!"'));
                    }

                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function updateCreditCard(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('cardHolderName', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('cardHolderName required!'));
            }else if (!array_key_exists('cardNumber', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('cardNumber required!'));
            }else if (!array_key_exists('cardExpiryMonth', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('cardExpiryMonth required!'));
            }else if (!array_key_exists('cardExpiryYear', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('cardExpiryYear required!'));
            }else if (!array_key_exists('cardCvc', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('cardCvc required!'));
            }else if (!array_key_exists('cardCountry', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('cardCountry required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $this->deleteDraftTech($postdata['jobId'], $postdata['id']);
                    $ret = $this->Home_Model->selectWhere('user_creditcards', array('fk_uid' => $postdata['id']));
                    require_once APPPATH."third_party/stripe/init.php";
                    $this->load->library('encryption');
                    if($ret){
                        try{

                            if(STRIPE_MODE == 'live'){
                                $SSK = STRIPE_LIVE_SK;
                            }else{
                                $SSK = STRIPE_TEST_SK;
                            }

                            \Stripe\Stripe::setApiKey($SSK);

                            $token = \Stripe\Token::create(array(
                                "card" => array(
                                    "number" => $postdata['cardNumber'],
                                    "exp_month" => $postdata['cardExpiryMonth'],
                                    "exp_year" => $postdata['cardExpiryYear'],
                                    "cvc" => $postdata['cardCvc']
                                )
                            ));

                            $data = array(
                                'cardHolderName' => $postdata['cardHolderName'],
                                'cardNumber' => $this->encryption->encrypt($postdata['cardNumber']),
                                'cardExpiryMonth' => $this->encryption->encrypt($postdata['cardExpiryMonth']),
                                'cardExpiryYear' => $this->encryption->encrypt($postdata['cardExpiryYear']),
                                'cardCvc' => $this->encryption->encrypt($postdata['cardCvc']),
                                'cardCountry' => $postdata['cardCountry'],
                                'validateResponse' => json_encode($token),
                                'updatedate' => CURRENT_DATETIME,
                            );


                            $update = $this->Home_Model->updateDB('user_creditcards', ['id'=>$ret['id']], $data);
                            if($update){
                                $data['status']= true;
                                $data['cardNumber'] = $this->encryption->decrypt($data['cardNumber']);
                                $data['cardExpiryMonth'] = $this->encryption->decrypt($data['cardExpiryMonth']);
                                $data['cardExpiryYear'] = $this->encryption->decrypt($data['cardExpiryYear']);
                                $data['cardCvc'] = $this->encryption->decrypt($data['cardCvc']);
                                $data['id'] = $ret['id'];
                                $data['fk_uid'] = $ret['fk_uid'];
                                unset($data['validateResponse']);



                                //DRAFT STEP 6
                                $jobDraft = [
                                    'draft_meta'=>'step6',
                                    'draft_value'=>json_encode(['status'=>true]),
                                    'fk_userId'=> $postdata['id'],
                                    'fk_jobId'=> $postdata['jobId'],
                                ];
                                $ret = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step6']);
                                if($ret){
                                    $this->db->where('fk_userId', $postdata['id']);
                                    $this->db->where('draft_meta', 'step6');
                                    $this->db->where('fk_jobId', $postdata['jobId']);
                                    $this->db->update('job_draft', $jobDraft);
                                }else{
                                    $this->db->insert('job_draft', $jobDraft);
                                    $jobId = $this->db->insert_id();
                                }
                                $this->db->where('userId', $postdata['id']);
                                $this->db->where('id', $postdata['jobId']);
                                $this->db->update('jobs', ['stepCount'=>6]);
                                //DRAFT STEP 6



                                return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($data));
                            }

                        }catch(Exception $e){
                            return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode($e->jsonBody['error']['message']));
                        }

                    }else {
                        try{
                            if(STRIPE_MODE == 'live'){
                                $SSK = STRIPE_LIVE_SK;
                            }else{
                                $SSK = STRIPE_TEST_SK;
                            }

                            \Stripe\Stripe::setApiKey($SSK);

                            $token = \Stripe\Token::create(array(
                                "card" => array(
                                    "number" => $postdata['cardNumber'],
                                    "exp_month" => $postdata['cardExpiryMonth'],
                                    "exp_year" => $postdata['cardExpiryYear'],
                                    "cvc" => $postdata['cardCvc']
                                )
                            ));

                            $data = array(
                                'cardHolderName' => $postdata['cardHolderName'],
                                'cardNumber' => $this->encryption->encrypt($postdata['cardNumber']),
                                'cardExpiryMonth' => $this->encryption->encrypt($postdata['cardExpiryMonth']),
                                'cardExpiryYear' => $this->encryption->encrypt($postdata['cardExpiryYear']),
                                'cardCvc' => $this->encryption->encrypt($postdata['cardCvc']),
                                'cardCountry' => $postdata['cardCountry'],
                                'fk_uid' => $postdata['id'],
                                'validateResponse' => json_encode($token),
                                'createdate' => CURRENT_DATETIME,
                            );



                            $insert = $this->Home_Model->insertDB('user_creditcards', $data);
                            if($insert){
                                $data['id']= $insert;
                                unset($data['validateResponse']);
                                $data['status']= true;
                                $data['cardNumber'] = $this->encryption->decrypt($data['cardNumber']);
                                $data['cardExpiryMonth'] = $this->encryption->decrypt($data['cardExpiryMonth']);
                                $data['cardExpiryYear'] = $this->encryption->decrypt($data['cardExpiryYear']);
                                $data['cardCvc'] = $this->encryption->decrypt($data['cardCvc']);


                                //DRAFT STEP 6
                                $jobDraft = [
                                    'draft_meta'=>'step6',
                                    'draft_value'=>json_encode(['status'=>true]),
                                    'fk_userId'=> $postdata['id'],
                                    'fk_jobId'=> $postdata['jobId'],
                                ];
                                $ret = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step6']);
                                if($ret){
                                    $this->db->where('fk_userId', $postdata['id']);
                                    $this->db->where('draft_meta', 'step6');
                                    $this->db->update('job_draft', $jobDraft);
                                }else{
                                    $this->db->insert('job_draft', $jobDraft);
                                    $jobId = $this->db->insert_id();
                                }
                                $this->db->where('userId', $postdata['id']);
                                $this->db->where('id', $postdata['jobId']);
                                $this->db->update('jobs', ['stepCount'=>6]);
                                //DRAFT STEP 6


                                return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($data));
                            }
                        }catch(Exception $e){
                            return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode($e->jsonBody['error']['message']));
                        }

                    }

                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    //Load Draft 1 From function getCats($id = 0,$app_type=0, $jobId=false, $userId = false) - Save Draft 1 From function postUsersCat()

    public function loadDrafTwo()
    {
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $step1 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step1']);
                    $step2 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step2']);
                    $step1Value = json_decode($step1['draft_value'], true);
                    $step2Value = json_decode($step2['draft_value'], true);
                    $issues = $step1Value['issues'];

                    $sols = array();
                    foreach ($issues as $issue) {
                        $issue = explode("_", $issue);
                        $issueId = $issue[1];
                        $catId = $issue[0];

                        $sols['issues'][] = $this->Home_Model->selectWhere('issues', ['id' => $issueId, 'catId' => $catId]);
                    }

                    $sols['breadcrumb'] = $this->loadBreadCrumb($postdata['id'], $postdata['jobId']);

                    $sols['others'] = $step1Value['others'];
                    $sols['othersAddpic'] = (($step2Value['othersAddpic']) ? base_url() . $step2Value['othersAddpic'] : '');
                    $sols['othersAddnotes'] = (($step2Value['othersAddnotes']) ? $step2Value['othersAddnotes'] : '');
                    /*echo "<pre>";print_r($step2Value['othersAddnotes']);die;*/
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($sols));
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function saveDraftJob2(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            }else if (!array_key_exists('unres_issues', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('unres_issues required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    //DELETE TECH META
                    $this->deleteDraftTech($postdata['jobId'], $postdata['id']);

                    $jobDraftValue = [
                        'unres_issues' => $postdata['unres_issues'],
                        'othersAddpic' => '',
                        'othersAddnotes' => ((@$postdata['othersAddnotes'] != '')?@$postdata['othersAddnotes']:'')
                    ];
                    if (@$postdata['othersAddpic'] != "") {
                        $additionalPic = md5(date('Y-m-d H:i:s:u')) . rand(10, 99);
                        file_put_contents('./uploads/' . $additionalPic . '.jpg', base64_decode(@$postdata['othersAddpic']));
                        $jobDraftValue['othersAddpic'] = $additionalPic . '.jpg';
                    }
                    $jobDraft = [
                        'draft_meta'=>'step2',
                        'draft_value'=>json_encode($jobDraftValue),
                        'fk_userId'=> $postdata['id'],
                        'fk_jobId'=> $postdata['jobId'],
                    ];
                    $ret = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step2']);
                    if($ret){
                        $this->db->where('fk_userId', $postdata['id']);
                        $this->db->where('fk_jobId', $postdata['jobId']);
                        $this->db->where('draft_meta', 'step2');
                        $this->db->update('job_draft', $jobDraft);
                    }else{
                        $this->db->insert('job_draft', $jobDraft);
                        $jobId = $this->db->insert_id();
                    }

                    $this->db->where('userId', $postdata['id']);
                    $this->db->where('id', $postdata['jobId']);
                    $this->db->update('jobs', ['stepCount'=>2]);
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(json_encode($jobDraftValue)));
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function loadDraftJob3(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $result = array();
                    $totalCost = 0;
                    $user = $this->Home_Model->selectWhere('users', ['id' => $postdata['id']]);
                    $step1 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step1']);
                    $step1Value = json_decode($step1['draft_value'], true);
                    $mainCatId = $step1Value['maincat'];
                    $subCatId = $step1Value['subcat'];
                    $subSubCatId = $step1Value['subsubcat'];

                    //print_r($step1Value);die;

                    $step2 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step2']);
                    $step2Value = json_decode($step2['draft_value'], true);
                    $unresolveIssues = json_decode($step2Value['unres_issues'], true);


                    $mainCat = $this->Home_Model->selectWhere('categories', ['id' => $mainCatId]);
                    $subCat = $this->Home_Model->selectWhere('categories', ['id' => $subCatId]);
                    $subSubCat = $this->Home_Model->selectWhere('categories', ['id' => $subSubCatId]);

                    if($mainCat && $mainCat['isFree'] == 0){
                        $result['mainCat']['name'] = $mainCat['name'];
                        $result['mainCat']['cost'] = number_format(($user['user_type'] == 0)?$mainCat['cost']:$mainCat['commercial_cost'], 2);
                        $totalCost += $result['mainCat']['cost'];
                    }

                    if($subCat && $subCat['isFree'] == 0){
                        $result['subCat']['name'] = $subCat['name'];
                        $result['subCat']['cost'] = number_format(($user['user_type'] == 0)?$subCat['cost']:$subCat['commercial_cost'], 2);
                        $totalCost += $result['subCat']['cost'];
                    }

                    if($subSubCat && $subSubCat['isFree'] == 0){
                        $result['subSubCat']['name'] = $subSubCat['name'];
                        $result['subSubCat']['cost'] = number_format(($user['user_type'] == 0)?$subSubCat['cost']:$subSubCat['commercial_cost'], 2);
                        $totalCost += $result['subSubCat']['cost'];
                    }



                    $result['unresolvedIssues'] = array();
                    $pushUnResolvedIssues = array();
                    foreach ($unresolveIssues as $urIssue){
                        $pushUnResolvedIssues[] = $urIssue['issue_detail'];
                    }

                    $result['unresolvedIssues'] = $pushUnResolvedIssues;
                    $result['others'] = $step2Value['othersAddnotes'];



                    $settings = $this->Home_Model->selectWhere('settings', ['id' => 1]);
                    $result['tax'] = number_format($totalCost * ($settings['tax_rate'] / 100),2);
                    $result['totalCostWithOutTax'] = number_format($totalCost,2);
                    $result['totalCost'] = number_format($totalCost,2);
                    $result['breadcrumb'] = $this->loadBreadCrumb($postdata['id'], $postdata['jobId']);
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($result));
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function saveDraftJob3(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    //DELETE TECH META
                    $this->deleteDraftTech($postdata['jobId'], $postdata['id']);
                    $result = array();
                    $totalCost = 0;
                    $user = $this->Home_Model->selectWhere('users', ['id' => $postdata['id']]);
                    $step1 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step1']);
                    $step1Value = json_decode($step1['draft_value'], true);
                    $mainCatId = $step1Value['maincat'];
                    $subCatId = $step1Value['subcat'];
                    $subSubCatId = $step1Value['subsubcat'];

                    $step2 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step2']);
                    $step2Value = json_decode($step2['draft_value'], true);
                    $unresolveIssues = json_decode($step2Value['unres_issues'], true);


                    $mainCat = $this->Home_Model->selectWhere('categories', ['id' => $mainCatId]);
                    $subCat = $this->Home_Model->selectWhere('categories', ['id' => $subCatId]);
                    $subSubCat = $this->Home_Model->selectWhere('categories', ['id' => $subSubCatId]);

                    if($mainCat && $mainCat['isFree'] == 0){
                        $result['mainCat']['name'] = $mainCat['name'];
                        $result['mainCat']['cost'] = number_format(($user['user_type'] == 0)?$mainCat['cost']:$mainCat['commercial_cost'], 2);
                        $totalCost += $result['mainCat']['cost'];
                    }

                    if($subCat &&  $subCat['isFree'] == 0){
                        $result['subCat']['name'] = $subCat['name'];
                        $result['subCat']['cost'] = number_format(($user['user_type'] == 0)?$subCat['cost']:$subCat['commercial_cost'], 2);
                        $totalCost += $result['subCat']['cost'];
                    }

                    if($subSubCat && $subSubCat['isFree'] == 0){
                        $result['subSubCat']['name'] = $subSubCat['name'];
                        $result['subSubCat']['cost'] = number_format(($user['user_type'] == 0)?$subSubCat['cost']:$subSubCat['commercial_cost'], 2);
                        $totalCost += $result['subSubCat']['cost'];
                    }


                    $result['unresolvedIssues'] = array();
                    $pushUnResolvedIssues = array();
                    foreach ($unresolveIssues as $urIssue){
                        $pushUnResolvedIssues[] = $urIssue['issue_detail'];
                    }

                    $result['unresolvedIssues'] = $pushUnResolvedIssues;
                    $result['others'] = $step2Value['othersAddnotes'];



                    $settings = $this->Home_Model->selectWhere('settings', ['id' => 1]);
                    //$result['tax'] = number_format($totalCost * ($settings['tax_rate'] / 100),2);
                    $result['totalCostWithOutTax'] = number_format($totalCost,2);
                    //$result['totalCost'] = number_format($totalCost+$result['tax'],2);
                    $result['totalCost'] = number_format($totalCost,2);
                    $result['commissionCost'] = number_format($totalCost * ($settings['commission_rate'] / 100),2);
                    //echo "<pre>";print_r($result);die;
                    $jobDraft = [
                        'draft_meta'=>'step3',
                        'draft_value'=>json_encode($result),
                        'fk_userId'=> $postdata['id'],
                        'fk_jobId'=> $postdata['jobId'],
                    ];
                    $ret = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step3']);
                    if($ret){
                        $this->db->where('fk_userId', $postdata['id']);
                        $this->db->where('fk_jobId', $postdata['jobId']);
                        $this->db->where('draft_meta', 'step3');
                        $this->db->update('job_draft', $jobDraft);
                    }else{
                        $this->db->insert('job_draft', $jobDraft);
                        $jobId = $this->db->insert_id();
                    }

                    $this->db->where('userId', $postdata['id']);
                    $this->db->where('id', $postdata['jobId']);
                    $this->db->update('jobs', ['stepCount'=>3]);
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode('Updated'));
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function saveDraftJob4(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));

            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));

            }else if (!array_key_exists('title', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('title required!'));

            }else if (!array_key_exists('desc', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('desc required!'));

            }else if (!array_key_exists('bookForLater', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('bookForLater required!'));

            }else if (!array_key_exists('jobDate', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobDate required!'));

            }else if (!array_key_exists('jobTime', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobTime required!'));

            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    //DELETE TECH META
                    $this->deleteDraftTech($postdata['jobId'], $postdata['id']);
                    $result = [
                        'title'=>$postdata['title'],
                        'desc'=>$postdata['desc'],
                        'bookForLater'=>$postdata['bookForLater'],
                        'jobDate'=>$postdata['jobDate'],
                        'jobTime'=>$postdata['jobTime'],
                    ];
                    //echo "<pre>";print_r($result);die;
                    $jobDraft = [
                        'draft_meta'=>'step4',
                        'draft_value'=>json_encode($result),
                        'fk_userId'=> $postdata['id'],
                        'fk_jobId'=> $postdata['jobId'],
                    ];
                    $ret = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step4']);
                    if($ret){
                        $this->db->where('fk_userId', $postdata['id']);
                        $this->db->where('fk_jobId', $postdata['jobId']);
                        $this->db->where('draft_meta', 'step4');
                        $this->db->update('job_draft', $jobDraft);
                    }else{
                        $this->db->insert('job_draft', $jobDraft);
                        $jobId = $this->db->insert_id();
                    }

                    $this->db->where('userId', $postdata['id']);
                    $this->db->where('id', $postdata['jobId']);
                    $this->db->update('jobs', ['stepCount'=>4]);
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode('Updated'));
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function loadDraftJob4(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $step4 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step4']);
                    if($step4){
                        $step4Value = json_decode($step4['draft_value'], true);
                        $result = $step4Value;
                    }else{
                        $result = [
                            "title"=> "",
                            "desc"=>"",
                            "bookForLater"=>false,
                            "jobDate"=>"",
                            "jobTime"=>""
                        ];
                    }

                    $result['breadcrumb'] = $this->loadBreadCrumb($postdata['id'], $postdata['jobId']);

                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($result));
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function saveDraftJob5(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));

            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));

            }else if (!array_key_exists('country', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('country required!'));

            }else if (!array_key_exists('state', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('state required!'));

            }else if (!array_key_exists('city', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('city required!'));

            }else if (!array_key_exists('latitude', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('latitude required!'));

            }else if (!array_key_exists('longitude', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('longitude required!'));

            }else if (!array_key_exists('street', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('street required!'));

            }else if (!array_key_exists('apt', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('apt required!'));

            }else if (!array_key_exists('postcode', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('postcode required!'));

            }else if (!array_key_exists('gaddress', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('gaddress required!'));

            }else if (!array_key_exists('notes', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('notes required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    //DELETE TECH META
                    $this->deleteDraftTech($postdata['jobId'], $postdata['id']);
                    $result = [
                        'country'=>$postdata['country'],
                        'state'=>$postdata['state'],
                        'city'=>$postdata['city'],
                        'latitude'=>$postdata['latitude'],
                        'longitude'=>$postdata['longitude'],
                        'street'=>$postdata['street'],
                        'apt'=>$postdata['apt'],
                        'postcode'=>$postdata['postcode'],
                        'gaddress'=>$postdata['gaddress'],
                        'notes'=>$postdata['notes'],
                    ];
                    //echo "<pre>";print_r($result);die;
                    $jobDraft = [
                        'draft_meta'=>'step5',
                        'draft_value'=>json_encode($result),
                        'fk_userId'=> $postdata['id'],
                        'fk_jobId'=> $postdata['jobId'],
                    ];
                    $ret = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step5']);
                    if($ret){
                        $this->db->where('fk_userId', $postdata['id']);
                        $this->db->where('fk_jobId', $postdata['jobId']);
                        $this->db->where('draft_meta', 'step5');
                        $this->db->update('job_draft', $jobDraft);
                    }else{
                        $this->db->insert('job_draft', $jobDraft);
                        $jobId = $this->db->insert_id();
                    }

                    $this->db->where('userId', $postdata['id']);
                    $this->db->where('id', $postdata['jobId']);
                    $this->db->update('jobs', ['stepCount'=>5]);
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode('Updated'));
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function loadDraftJob5(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $result = array();
                    $step5 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step5']);
                    if($step5){
                        $step5Value = json_decode($step5['draft_value'], true);
                        $result = $step5Value;
                    }else{
                        //DELETE TECH META
                        //$this->deleteDraftTech($postdata['jobId'], $postdata['id']);
                        $user = $this->Home_Model->selectWhere('users', ['id' => $postdata['id']]);
                        $result = [
                            'gaddress' => $user['gaddress'],
                            'country' => $user['country'],
                            'state' => $user['state'],
                            'city' => $user['city'],
                            'address_1' => $user['address_1'],
                            'address_2' => $user['address_2'],
                            'postcode' => $user['postcode'],
                            'latitude' => $user['latitude'],
                            'longitude' => $user['longitude'],
                            'notes' => ''
                        ];
                    }

                    $result['breadcrumb'] = $this->loadBreadCrumb($postdata['id'], $postdata['jobId']);

                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($result));
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    //Load Draft 6 From function getCreditCard() - Save Draft 6 From function updateCreditCard()

    public function saveDraftJob7(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));

            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));

            }else if (!array_key_exists('factorCount', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('factorCount required!'));

            }else if (!array_key_exists('currentLatitude', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('currentLatitude required!'));

            }else if (!array_key_exists('currentLongitude', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('currentLongitude required!'));

            }else if (!array_key_exists('techId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('techId required!'));

            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $settings = $this->Home_Model->selectWhere('settings',['id'=> "1"]);
                    $step3 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step3']);
                    $step3Value = json_decode($step3['draft_value'], true);

                    $step5 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step5']);
                    $step5Value = json_decode($step5['draft_value'], true);
                    $currentState = $step5Value['state'];

                    $stateOne = explode("||", STATE_ONE_FOR_TAX);
                    $stateTwo = explode("||", STATE_TWO_FOR_TAX);


                    $getTaxColum = ((in_array($currentState, $stateOne))? STATE_ONE_TAX_VALUE: ((in_array($currentState, $stateTwo)) ? STATE_TWO_TAX_VALUE : $settings['tax_rate']));
                    $getGstColumn = ((in_array($currentState, $stateOne))? $settings['gst']:null);

                    $factorCost = $postdata['factorCount']*$settings['factor_rate'];
                    $totalCostWithOutTax = $step3Value['totalCost']+$factorCost;
                    $tax = $totalCostWithOutTax * ( $getTaxColum / 100);
                    $gst = (($getGstColumn != null)?$totalCostWithOutTax * ( $getGstColumn / 100):null);

                    $totalCost = $totalCostWithOutTax+$tax+(($gst != null)?$gst:0);

                    $result = [
                        'id'=>$postdata['id'],
                        'jobId'=>$postdata['jobId'],
                        'factorCount'=>$postdata['factorCount'],
                        'techId'=>$postdata['techId'],
                        'factorAppliedCost'=>number_format($factorCost,2),
                        'totalCost'=>number_format($totalCost, 2),
                        'totalCostWithoutTax'=>number_format($totalCostWithOutTax,2),
                        'tax'=>number_format($tax,2),
                        'gst'=>number_format($gst,2),
                        'commissionCost'=> number_format($totalCostWithOutTax * ($settings['commission_rate'] / 100),2),
                        'techLatitude'=>$postdata['currentLatitude'],
                        'techLongitude'=>$postdata['currentLongitude']
                    ];
                    //echo "<pre>";print_r($result);die;
                    $jobDraft = [
                        'draft_meta'=>'step7',
                        'draft_value'=>json_encode($result),
                        'fk_userId'=> $postdata['id'],
                        'fk_jobId'=> $postdata['jobId'],
                    ];
                    $ret = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step7']);
                    if($ret){
                        $this->db->where('fk_userId', $postdata['id']);
                        $this->db->where('fk_jobId', $postdata['jobId']);
                        $this->db->where('draft_meta', 'step7');
                        $this->db->update('job_draft', $jobDraft);
                    }else{
                        $this->db->insert('job_draft', $jobDraft);
                        $jobId = $this->db->insert_id();
                    }

                    $this->db->where('userId', $postdata['id']);
                    $this->db->where('id', $postdata['jobId']);
                    $this->db->update('jobs', ['stepCount'=>7]);
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode('Updated'));
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function loadDraftJob8(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $result = array();
                    $totalCost = 0;
                    $user = $this->Home_Model->selectWhere('users', ['id' => $postdata['id']]);
                    $step1 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step1']);
                    $step1Value = json_decode($step1['draft_value'], true);
                    $mainCatId = $step1Value['maincat'];
                    $subCatId = $step1Value['subcat'];
                    $subSubCatId = $step1Value['subsubcat'];

                    $step2 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step2']);
                    $step2Value = json_decode($step2['draft_value'], true);
                    $unresolveIssues = json_decode($step2Value['unres_issues'], true);

                    $step3 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step3']);
                    $step3Value = json_decode($step3['draft_value'], true);

                    $step5 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step5']);
                    $step5Value = json_decode($step5['draft_value'], true);
                    $currentState = $step5Value['state'];

                    $step7 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step7']);
                    $step7Value = json_decode($step7['draft_value'], true);


                    /*$mainCat = $this->Home_Model->selectWhere('categories', ['id' => $mainCatId]);
                    $subCat = $this->Home_Model->selectWhere('categories', ['id' => $subCatId]);
                    $subSubCat = $this->Home_Model->selectWhere('categories', ['id' => $subSubCatId]);

                    if($mainCat && $mainCat['isFree'] == 0){
                        $result['mainCat']['name'] = $mainCat['name'];
                        $result['mainCat']['cost'] = number_format(($user['user_type'] == 0)?$mainCat['cost']:$mainCat['commercial_cost'], 2);
                        $totalCost += $result['mainCat']['cost'];
                    }

                    if($subCat && $subCat['isFree'] == 0){
                        $result['subCat']['name'] = $subCat['name'];
                        $result['subCat']['cost'] = number_format(($user['user_type'] == 0)?$subCat['cost']:$subCat['commercial_cost'], 2);
                        $totalCost += $result['subCat']['cost'];
                    }

                    if($subSubCat && $subSubCat['isFree'] == 0){
                        $result['subSubCat']['name'] = $subSubCat['name'];
                        $result['subSubCat']['cost'] = number_format(($user['user_type'] == 0)?$subSubCat['cost']:$subSubCat['commercial_cost'], 2);
                        $totalCost += $result['subSubCat']['cost'];
                    }*/

                    //echo "<pre>";print_r($step3Value);die;

                    if(isset($step3Value) && array_key_exists('mainCat', $step3Value)){
                        $result['mainCat']['name'] = $step3Value['mainCat']['name'];
                        $result['mainCat']['cost'] = number_format($step3Value['mainCat']['cost'], 2);
                        $totalCost += $step3Value['mainCat']['cost'];
                    }

                    if(isset($step3Value) && array_key_exists('subCat', $step3Value)){
                        $result['subCat']['name'] = $step3Value['subCat']['name'];
                        $result['subCat']['cost'] = number_format($step3Value['subCat']['cost'], 2);
                        $totalCost += $step3Value['subCat']['cost'];
                    }

                    if(isset($step3Value) && array_key_exists('subSubCat', $step3Value)){
                        $result['subSubCat']['name'] = $step3Value['subSubCat']['name'];
                        $result['subSubCat']['cost'] = number_format($step3Value['subSubCat']['cost'], 2);
                        $totalCost += $step3Value['subSubCat']['cost'];
                    }

                    $result['unresolvedIssues'] = array();
                    $pushUnResolvedIssues = array();
                    foreach ($unresolveIssues as $urIssue){
                        $pushUnResolvedIssues[] = $urIssue['issue_detail'];
                    }

                    $result['unresolvedIssues'] = $pushUnResolvedIssues;
                    $result['others'] = $step2Value['othersAddnotes'];


                    $settings = $this->Home_Model->selectWhere('settings', ['id' => 1]);

                    //$getTaxColum = (($currentState == STATE_ONE_FOR_TAX)? $settings[STATE_ONE_COLUMN_FOR_TAX]: (($currentState == STATE_TWO_FOR_TAX) ? $settings[STATE_TWO_COLUMN_FOR_TAX] : $settings['tax_rate']));
                    $stateOne = explode("||", STATE_ONE_FOR_TAX);
                    $stateTwo = explode("||", STATE_TWO_FOR_TAX);
                    $getHstText = ((in_array($currentState, $stateOne))? 'Q.S.T': ((in_array($currentState, $stateTwo)) ? 'H.S.T' : 'TAX'));


                    $tax = $step7Value['tax'];
                    //$tax = $getTaxColum;
                    $totalCostWithTaxAndFactor = $step7Value['totalCost'];
                    $commissionCost = $step7Value['commissionCost'];
                    $factorAppliedCost = $step7Value['factorAppliedCost'];
                    $totalCostWithoutTax = $step7Value['totalCostWithoutTax'];
                    $gst = $step7Value['gst'];

                    $result['gst'] = number_format($gst,2);
                    $result['tax'] = number_format($tax,2);
                    $result['totalCostWithTaxAndFactor'] = number_format($totalCostWithTaxAndFactor,2);
                    $result['commissionCost'] = number_format($commissionCost,2);
                    $result['factorAppliedCost'] = number_format($factorAppliedCost,2);
                    $result['totalCostWithoutTax'] = number_format($totalCostWithoutTax,2);
                    $result['gst_text'] = "G.S.T";
                    $result['factor_text'] = "Surge Price";
                    $result['hst_text'] = $getHstText;
                    $result['subtotal_text'] = "Sub Total";
                    $result['total_text'] = "Total";
                    $result['tax_percentage'] = number_format($tax/$totalCostWithoutTax*100,2).'%';
                    $result['gst_percentage'] = number_format($gst/$totalCostWithoutTax*100,2).'%';
                    $result['tax_message'] = "";

                    $result['breadcrumb'] = $this->loadBreadCrumb($postdata['id'], $postdata['jobId']);

                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($result));
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function saveFinalJob8(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));

            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));

            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $step1 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step1']);
                    $step1Value = json_decode($step1['draft_value'], true);

                    $step2 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step2']);
                    $step2Value = json_decode($step2['draft_value'], true);

                    $step3 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step3']);
                    $step3Value = json_decode($step3['draft_value'], true);

                    $step4 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step4']);
                    $step4Value = json_decode($step4['draft_value'], true);

                    $step5 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step5']);
                    $step5Value = json_decode($step5['draft_value'], true);

                    $step7 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'step7']);
                    $step7Value = json_decode($step7['draft_value'], true);

                    $copy_id = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta' => 'copy_id']);
                    $copy_id = $copy_id['draft_value'];



                    $userId = $postdata['id'];
                    $techId = $step7Value['techId'];
                    $jobId = $postdata['jobId'];
                    $user = $this->Home_Model->selectWhere('users', ['id' => $userId]);
                    //$data['userId'] = $postdata['id'];

                    $data['additionalInfo'] =  $step5Value['notes'];
                    $data['additionalPic'] = $step2Value['othersAddpic'];
                    $data['category'] = $step1Value['maincat'];
                    $data['subcat'] = $step1Value['subcat'];
                    $data['subsubcat'] = $step1Value['subsubcat'];



                    $mainCat = $this->Home_Model->selectWhere('categories', ['id' => $data['category']]);
                    $subCat = $this->Home_Model->selectWhere('categories', ['id' => $data['subcat']]);
                    $subSubCat = $this->Home_Model->selectWhere('categories', ['id' => $data['subsubcat']]);

                    if($mainCat){
                        $data['categoryIsFree'] = $mainCat['isFree'];
                        $data['categoryPrice'] = number_format(($user['user_type'] == 0)?$mainCat['cost']:$mainCat['commercial_cost'], 2);
                        $data['categoryName'] = $mainCat['name'];
                    }

                    if($subCat){
                        $data['subcatIsFree'] = $subCat['isFree'];
                        $data['subcatPrice'] = number_format(($user['user_type'] == 0)?$subCat['cost']:$subCat['commercial_cost'], 2);
                        $data['subcatName'] = $subCat['name'];
                    }

                    if($subSubCat){
                        $data['subsubcatIsFree'] = $subSubCat['isFree'];
                        $data['subsubcatPrice'] = number_format(($user['user_type'] == 0)?$subSubCat['cost']:$subSubCat['commercial_cost'], 2);
                        $data['subsubcatName'] = $subSubCat['name'];
                    }


                    $data['address_1'] = $step5Value['apt'];
                    $data['address_2'] = $step5Value['street'];
                    $data['city'] = $step5Value['city'];
                    $data['state'] = $step5Value['state'];
                    $data['country'] = $step5Value['country'];
                    $data['gaddress'] = $step5Value['gaddress'];
                    $data['postcode'] = $step5Value['postcode'];
                    $data['latitude'] = $step5Value['latitude'];
                    $data['longitude'] = $step5Value['longitude'];
                    $data['additionalNotes'] = $step2Value['othersAddnotes'];

                    $data['jobTitle'] = $step4Value['title'];
                    $data['jobDate'] = $step4Value['jobDate'];
                    $data['jobTime'] = $step4Value['jobTime'];
                    $data['jobDescription'] = $step4Value['desc'];

                    $data['totalCost'] = $step3Value['totalCostWithOutTax']+$step7Value['factorAppliedCost'];

                    $settings = $this->Home_Model->selectWhere('settings', ['id' => 1]);
                    //ASSIGN JOB
                    $data['techId'] = $techId;
                    //$data['totalCost'] = $step3Value['totalCost']+$step7Value['factorAppliedCost'];
                    $data['totalCostWithoutTax'] = $step7Value['totalCostWithoutTax'];
                    $data['factor_count'] = $step7Value['factorCount'];
                    $data['factor_applied_cost'] = $step7Value['factorAppliedCost'];
                    $data['tax_cost'] = $step7Value['tax'];
                    $data['totalCost'] = $step7Value['totalCost'];
                    $data['commission_cost'] = $step7Value['commissionCost'];
                    $data['gst_cost'] = $step7Value['gst'];
                    $data['jobStatus'] = 1;
                    //ASSIGN JOB

                    $tech = $this->Home_Model->selectWhere('technicians', ['id'=> $techId]);
                    $origins = $step5Value['latitude'].",".$step5Value['longitude'];
                    $destinations = $step7Value['techLatitude'].','.$step7Value['techLongitude'];
                    $distanceMatrixApi = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$origins.'&destinations='.$destinations.'&key='.GOOGLE_API_KEY;
                    $apiReturn = json_decode( file_get_contents($distanceMatrixApi) ,true);
                    $apiResults = $apiReturn['rows'][0]['elements'];
                    if($apiResults[0]['status'] == 'OK'){
                        $disInKM = $apiResults[0]['distance']['text'];
                        $durInMin = $apiResults[0]['duration']['text'];
                        $data['jobDistance'] = $disInKM;
                        $data['jobEta'] = $durInMin;
                    }
                    $data['tech_latitude'] = $step7Value['techLatitude'];
                    $data['tech_longitude'] = $step7Value['techLongitude'];

                    $updateJob = $this->Home_Model->updateDB('jobs', ['id'=>$jobId, 'userId'=>$userId], $data);


                    if ($data['category']) {
                        $selectCategory = $this->Home_Model->selectWhereWithFields('categories', ['id' => $data['category']], 'id, name,slug, image, parent_id, active,sorting_id,hours,cost,  min_categories, commercial_cost, priceTier_res, priceTier_com, isFree');
                        $selectCategory['jobId'] = $jobId;
                        if ($selectCategory) {
                            $this->insertCategory($selectCategory);
                        }
                    }

                    if ($postdata['subcat']) {
                        $selectCategory = $this->Home_Model->selectWhereWithFields('categories', ['id' => $data['subcat']], 'id, name,slug, image, parent_id, active,sorting_id,hours,cost,  min_categories, commercial_cost, priceTier_res, priceTier_com, isFree');
                        $selectCategory['jobId'] = $jobId;
                        if ($selectCategory) {
                            $this->insertCategory($selectCategory);
                        }
                    }

                    if ($postdata['subsubcat']) {
                        $selectCategory = $this->Home_Model->selectWhereWithFields('categories', ['id' => $data['subsubcat']], 'id, name,slug, image, parent_id, active,sorting_id,hours,cost,  min_categories, commercial_cost, priceTier_res, priceTier_com, isFree');
                        $selectCategory['jobId'] = $jobId;
                        if ($selectCategory) {
                            $this->insertCategory($selectCategory);
                        }
                    }


                    $issues = $step1Value['issues'];
                    foreach ($issues as $issue) {
                        $issue = explode("_", $issue);
                        $issueId = $issue[1];
                        $catId = $issue[0];

                        $checkIssues = $this->Home_Model->countResult('job_issues_link', array('job_id' => $jobId, 'cat_id' => $catId, 'issue_id' => $issueId));
                        if($checkIssues == 0){
                            $add = array('job_id' => $jobId, 'cat_id' => $catId, 'issue_id' => $issueId);
                            $this->db->insert('job_issues_link', $add);
                        }

                    }


                    $techDetails = $this->Home_Model->selectWhere('technicians', ['id' => $step7Value['techId']]);

                    $jobs = $this->Home_Model->selectWhereWithFields('jobs', ['id' => $jobId], '*, ((`totalCostWithoutTax`-`commission_cost`)) AS totalCostforTech, IF((SELECT COUNT(review_id) FROM reviews WHERE reviewed_jobid = '. $jobId.' ) > 0, 1, 0)  AS isReviewed');

                    $status = "";
                    if($jobs['jobStatus'] == 0){ $status = "Cancelled"; }
                    else if($jobs['jobStatus'] == 1){ $status = "Pending"; }
                    else if($jobs['jobStatus'] == 2){ $status = "Selected"; }
                    else if($jobs['jobStatus'] == 3){ $status = "Started"; }
                    else if($jobs['jobStatus'] == 4){ $status = "Arrived"; }
                    else if($jobs['jobStatus'] == 5){ $status = "Pending Completion"; }
                    else if($jobs['jobStatus'] == 6){ $status = "Completed"; }
                    else if($jobs['jobStatus'] == 7){ $status = "Paid"; }

                    $jobs['jobDate'] = date("d-M-Y", strtotime($jobs['jobDate']));
                    $jobs['jobTime'] = date("h:i A", strtotime($jobs['jobTime']));

                    $jobs['jobStatus'] = $status;

                    $jobs['issues'] = $this->Jobs_Model->getJobIssues($jobs['id']);


                    $category = $this->Home_Model->selectWhere('job_categories',['id'=>$jobs['category'], 'jobId'=>$jobs['id']]);
                    $subcat = $this->Home_Model->selectWhere('job_categories',['id'=>$jobs['subcat'], 'jobId'=>$jobs['id']]);
                    $subsubcat = $this->Home_Model->selectWhere('job_categories',['id'=>$jobs['subsubcat'], 'jobId'=>$jobs['id']]);
                    $jobs['category'] = ($category) ? $category : [];
                    $jobs['subcat'] = ($subcat) ? $subcat : [];
                    $jobs['subsubcat'] = ($subsubcat) ? $subsubcat : [];

                    $jobs['jobStatus'] = $status;

                    $pushArray = [
                        'id' => $jobs['id'],
                        'jobStatus' => $jobs['jobStatus'],
                        'isReviewed' => $jobs['isReviewed']
                    ];

                    $postString = array();
                    $postString['registration_ids'][] = $techDetails['deviceId'];
                    $title = "EZE-TECH NOTIFICATION";
                    $body  = "You have received a hire request. Please review and respond";
                    if($techDetails['deviceType'] == "ios"){
                        $postString['notification'] = [ "title"=>$title,"body"=> $body];
                        $postString['data'] = [ 'job'=>$pushArray];
                    }else {
                        $postString['data'] = [ 'job'=> $pushArray, "title"=>$title,"body"=> $body];
                    }

                    $postString['priority'] = 'high';


                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL,            "https://fcm.googleapis.com/fcm/send" );
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
                    curl_setopt($ch, CURLOPT_POST,           1 );
                    curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($postString) );
                    curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization:key=AIzaSyDs93AGHXUn8Eh09ByKqnG9OJY6TJNPffI','Content-Type:application/json'));

                    $result=curl_exec($ch);


                    //$this->Home_Model->deleteWhere('job_draft', ['fk_jobId'=>$jobId, 'fk_userId'=>$userId]);

                    $this->db->where('userId', $userId);
                    $this->db->where('id', $jobId);
                    $this->db->update('jobs', ['saveStatus'=>'Completed', 'expiredate'=>date("Y-m-d H:i:s", time() + EXPIRE_IN), 'isExpired'=>0]);

                    /*if($copy_id){
                        $delete = $this->Home_Model->deleteWhere('jobs', ['id'=>$copy_id, 'userId'=>$userId]);
                        $deleteMeta = $this->Home_Model->deleteWhere('job_draft', ['fk_userId' => $userId, 'fk_jobId' => $copy_id]);
                    }*/

                    //CALL ME HERE.

                    makeCall($jobId, $techDetails['phone']);
                    sendSuspendRequest($jobId);

                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Job Created Successfully"));
                    //return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode($result));
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function deleteDraftTech($jobId, $userId){
        //DELETE TECH META
        $this->Home_Model->deleteWhere('job_draft', ['fk_jobId' => $jobId , 'fk_userId' => $userId, 'draft_meta'=>'step7']);
    }

    public function deleteDraftJob(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));

            } else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));

            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $this->Home_Model->deleteWhere('jobs', ['id' => $postdata['jobId'], 'userId' => $postdata['id'], 'saveStatus'=>'draft']);
                    $this->Home_Model->deleteWhere('job_draft', ['fk_jobId' => $postdata['jobId'], 'fk_userId' => $postdata['id']]);
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(200)->set_output(json_encode("Job Successfully deleted"));
                }
            }
        }else{
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function stripKey(){
        //DELETE TECH META
        if(STRIPE_MODE == 'live'){
            $SPK = STRIPE_LIVE_PK;
        }else{
            $SPK = STRIPE_TEST_PK;
        }
        $result["publishable_key"] = $SPK;
        return $this->output
            ->set_content_type('Content-Type: application/json')
            ->set_status_header(200)
            ->set_output(json_encode($result));
    }

    public function loadBreadCrumb($uId, $jobId){
        //$job = $this->Home_Model->selectWhere('jobs', ['saveStatus' => 'draft', 'fk_jobId' => $jobId,'userId'=> $uId]);

        $step1 = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $uId, 'fk_jobId' => $jobId, 'draft_meta' => 'step1']);
        $step1Value = json_decode($step1['draft_value'], true);

        $mainCatId = $step1Value['maincat'];
        $subCatId = $step1Value['subcat'];
        $subSubCatId = $step1Value['subsubcat'];
        $mainCat = $this->Home_Model->selectWhere('categories', ['id' => $mainCatId]);
        $subCat = $this->Home_Model->selectWhere('categories', ['id' => $subCatId]);
        $subSubCat = $this->Home_Model->selectWhere('categories', ['id' => $subSubCatId]);

        $data['mainCatName'] = $mainCat['name'];
        $data['subCatName'] = $subCat['name'];
        $data['subSubCatName'] = $subSubCat['name'];

        return $data;


    }

    public function forwardGeoCode(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if (!array_key_exists('address', $postdata)) {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('address required!'));
        }else{
            $output = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($postdata['address']).'&key=AIzaSyDq-_u-zSxKxlZY6U9hPJrt5oCXalqpnhg&result_type=locality'));
            if($output->status == "OK"){
                for($j=0;$j<count($output->results[0]->address_components);$j++){
                    $cn=array($output->results[0]->address_components[$j]->types[0]);
                    $cn1=array($output->results[0]->address_components[$j]->types[1]);
                    $cn2=array($output->results[0]->address_components[$j]->types[2]);
                    if(in_array("locality", $cn)){
                        $city= $output->results[0]->address_components[$j]->long_name;
                    }

                    if(in_array("country", $cn)){
                        $country= $output->results[0]->address_components[$j]->long_name;
                    }

                    if(in_array("administrative_area_level_1", $cn)){
                        $state= $output->results[0]->address_components[$j]->long_name;
                    }

                    if(in_array("postal_code", $cn)){
                        $postalCode = $output->results[0]->address_components[$j]->long_name;
                    }

                    if(in_array("street_number", $cn)){
                        $street_number = $output->results[0]->address_components[$j]->long_name;
                    }

                    if(in_array("route", $cn)){
                        $street = $output->results[0]->address_components[$j]->long_name;
                    }

                    if(in_array("political", $cn) && in_array("sublocality", $cn1) && in_array("sublocality_level_2", $cn2)){
                        $app_unit = $output->results[0]->address_components[$j]->long_name;
                    }
                }
                $lat = $output->results[0]->geometry->location->lat;
                $lng = $output->results[0]->geometry->location->lng;
                $formatted_address = $output->results[0]->formatted_address;
                $result = [
                    'country'=>(($country)?$country:''),
                    'city'=>(($city)?$city:''),
                    'state'=>(($state)?$state:''),
                    'lat'=>(($lat)?$lat:''),
                    'lng'=>(($lng)?$lng:''),
                    'formatted_address'=>(($formatted_address)?$formatted_address:''),
                    'zipCode'=>(($postalCode)?$postalCode:''),
                    'street'=>(($street)?$street:''),
                    'app_unit'=>(($app_unit)?$app_unit:'')
                ];
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));
            }else{
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('No Location found!.'));
            }
        }

    }

    public function applyAgain(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    //GET Cancelled JOB
                    $job = $this->Home_Model->selectWhere('jobs', ['userId' => $postdata['id'], 'id' => $postdata['jobId'], 'jobStatus' => 0]);
                    // GET DRAFT OF THAT JOB
                    //echo "<pre>";print_r($job);die;

                    if($job['saveStatus'] == 'Completed'){

                        $datetime = strtotime($job['jobDate'] . ' ' . $job['jobTime']);

                        //if($datetime > strtotime(date('Y-m-d H:i:s'))){
                        $result = [
                            'status'=>true,
                            'stepCount'=>6,
                            'jobStatus'=>'Drafted',
                            'jobId'=>$postdata['jobId'],
                            'id'=>$postdata['id']
                        ]; //OUTPUT ARRAY.
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($result));
                        /*}else{
                            $result = [
                                'status'=>false,
                                'message'=>'Job date time expired',
                                'jobId'=>$postdata['jobId'],
                                'id'=>$postdata['id']
                            ]; //OUTPUT ARRAY.
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($result));
                        }*/
                    }else{
                        $jobMetas = $this->Home_Model->selectWhereResult('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId']]);
                        $step4meta = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta'=>'step4']);
                        $step4value = json_decode($step4meta['draft_value'], true);
                        $datetime = strtotime($step4value['jobDate'] . ' ' . $step4value['jobTime']);
                        //if($datetime > strtotime(date('Y-m-d H:i:s'))){
                        $result = [
                            'status'=>true,
                            'stepCount'=>6,
                            'jobStatus'=>'Drafted',
                            'jobId'=>$postdata['jobId'],
                            'id'=>$postdata['id']
                        ]; //OUTPUT ARRAY.
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($result));
                        /*}else{
                            $result = [
                                'status'=>false,
                                'message'=>'Job date time expired',
                                'jobId'=>$postdata['jobId'],
                                'id'=>$postdata['id']
                            ]; //OUTPUT ARRAY.
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($result));
                        }*/

                    }

                    /*
                    //COPY ARRAY
                    $copyData = array(
                        'userId' => $job['userId'],
                        'saveStatus' => 'temp-draft',
                        'jobStatus' => 0,
                        'stepCount' => 6,
                        'createddate' => CURRENT_DATETIME
                    );
                    $this->db->insert('jobs', $copyData);
                    $newJobId = $this->db->insert_id(); //INSERT AND GET NEW JOB ID


                    //$checkCopy = $this->Home_Model->selectWhere('job_draft', ['draft_meta' => 'copy_id', 'fk_userId' => $job['id'], 'fk_userId'=> $job['userId']]);
                    $metaData = [
                        'draft_meta' => 'copy_id',
                        'draft_value' => $job['id'],
                        'fk_userId' => $job['userId'],
                        'fk_jobId' => $newJobId
                    ];
                    $this->db->insert('job_draft', $metaData); //SET COPY_ID OF NEW JOB ID




                    foreach ($jobMetas as $meta){
                        if($meta['draft_meta'] != 'step7' && $meta['draft_meta'] != 'copy_id'){
                            $metaData = [
                                'draft_meta' => $meta['draft_meta'],
                                'draft_value' => $meta['draft_value'],
                                'fk_userId' => $job['userId'],
                                'fk_jobId' => $newJobId
                            ];
                            $this->db->insert('job_draft', $metaData); //CREATE META OF NEW JOB ID
                        }
                    }*/
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function draftContinue(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    //GET Cancelled JOB
                    $job = $this->Home_Model->selectWhere('jobs', ['userId' => $postdata['id'], 'id' => $postdata['jobId'], 'jobStatus' => 'draft']);
                    if($job){
                        $step4meta = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta'=>'step4']);
                        if($step4meta){
                            $step4value = json_decode($step4meta['draft_value'], true);
                            $datetime = strtotime($step4value['jobDate'] . ' ' . $step4value['jobTime']);
                            //if($datetime > strtotime(date('Y-m-d H:i:s'))){
                            $result = [
                                'status'=>true,
                                'jobId'=>$postdata['jobId'],
                                'id'=>$postdata['id'],
                                'stepCount'=>(($job['stepCount'] > 6)?"6":$job['stepCount']),
                            ]; //OUTPUT ARRAY.
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($result));
                            /*}else{
                                $result = [
                                    'status'=>false,
                                    'message'=>'Job date time expired',
                                    'jobId'=>$postdata['jobId'],
                                    'id'=>$postdata['id'],
                                    'stepCount'=>(($job['stepCount'] > 6)?"6":$job['stepCount']),
                                ]; //OUTPUT ARRAY.
                                return $this->output
                                    ->set_content_type('Content-Type: application/json')
                                    ->set_status_header(200)
                                    ->set_output(json_encode($result));
                            }*/
                        }else {
                            $result = [
                                'status'=>true,
                                'jobId'=>$postdata['jobId'],
                                'id'=>$postdata['id'],
                                'stepCount'=>(($job['stepCount'] > 6)?"6":$job['stepCount']),
                            ]; //OUTPUT ARRAY.
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($result));
                        }

                    }else {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)
                            ->set_output(json_encode('Your job not in draft!.'));
                    }
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function updateDateTime(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            }else if (!array_key_exists('jobDate', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobDate required!'));
            }else if (!array_key_exists('jobTime', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobTime required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $datetime = strtotime($postdata['jobDate'] . ' ' . $postdata['jobTime']);
                    //if($datetime > strtotime(date('Y-m-d H:i:s'))){
                    //GET Cancelled JOB
                    $job = $this->Home_Model->selectWhere('jobs', ['userId' => $postdata['id'], 'id' => $postdata['jobId'], 'jobStatus' => 0]);
                    if($job['saveStatus'] == 'Completed'){
                        $update = $this->Home_Model->updateDB('jobs', ['userId' => $postdata['id'], 'id' => $postdata['jobId']], ['jobTime'=>$postdata['jobTime'], 'jobDate'=>$postdata['jobDate']]);
                        if($update){

                            $step4meta = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta'=>'step4']);
                            $step4value = json_decode($step4meta['draft_value'], true);
                            $step4value['jobTime'] = $postdata['jobTime'];
                            $step4value['jobDate'] = $postdata['jobDate'];
                            $updateDraft = $this->Home_Model->updateDB('job_draft',['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta'=>'step4'], ['draft_value'=>json_encode($step4value)]);

                            $result = [
                                'status'=>true,
                                'message'=>'Job time updated succesfully!',
                                'stepCount'=>6,
                                'jobStatus'=>'Drafted',
                                'jobId'=>$postdata['jobId'],
                                'id'=>$postdata['id']
                            ]; //OUTPUT ARRAY.
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($result));
                        }else{
                            $result = [
                                'status'=>false,
                                'message'=>'something went wrong',
                                'stepCount'=>6,
                                'jobStatus'=>'Drafted',
                                'jobId'=>$postdata['jobId'],
                                'id'=>$postdata['id']
                            ]; //OUTPUT ARRAY.
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($result));
                        }
                    }else{
                        $step4meta = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta'=>'step4']);
                        $step4value = json_decode($step4meta['draft_value'], true);
                        $step4value['jobTime'] = $postdata['jobTime'];
                        $step4value['jobDate'] = $postdata['jobDate'];
                        $update = $this->Home_Model->updateDB('job_draft',['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta'=>'step4'], ['draft_value'=>json_encode($step4value)]);
                        if($update){
                            $result = [
                                'status'=>true,
                                'message'=>'Job time updated succesfully!',
                                'stepCount'=>6,
                                'jobStatus'=>'Drafted',
                                'jobId'=>$postdata['jobId'],
                                'id'=>$postdata['id']
                            ]; //OUTPUT ARRAY.
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($result));
                        }else{
                            $result = [
                                'status'=>false,
                                'message'=>'something went wrong',
                                'stepCount'=>6,
                                'jobStatus'=>'Drafted',
                                'jobId'=>$postdata['jobId'],
                                'id'=>$postdata['id']
                            ]; //OUTPUT ARRAY.
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($result));
                        }
                    }
                    /*}else{
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)
                            ->set_output(json_encode('Expired Datetime'));
                    }*/



                    /*
                    //COPY ARRAY
                    $copyData = array(
                        'userId' => $job['userId'],
                        'saveStatus' => 'temp-draft',
                        'jobStatus' => 0,
                        'stepCount' => 6,
                        'createddate' => CURRENT_DATETIME
                    );
                    $this->db->insert('jobs', $copyData);
                    $newJobId = $this->db->insert_id(); //INSERT AND GET NEW JOB ID


                    //$checkCopy = $this->Home_Model->selectWhere('job_draft', ['draft_meta' => 'copy_id', 'fk_userId' => $job['id'], 'fk_userId'=> $job['userId']]);
                    $metaData = [
                        'draft_meta' => 'copy_id',
                        'draft_value' => $job['id'],
                        'fk_userId' => $job['userId'],
                        'fk_jobId' => $newJobId
                    ];
                    $this->db->insert('job_draft', $metaData); //SET COPY_ID OF NEW JOB ID




                    foreach ($jobMetas as $meta){
                        if($meta['draft_meta'] != 'step7' && $meta['draft_meta'] != 'copy_id'){
                            $metaData = [
                                'draft_meta' => $meta['draft_meta'],
                                'draft_value' => $meta['draft_value'],
                                'fk_userId' => $job['userId'],
                                'fk_jobId' => $newJobId
                            ];
                            $this->db->insert('job_draft', $metaData); //CREATE META OF NEW JOB ID
                        }
                    }*/
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function updateDateTimeDraft(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            }else if (!array_key_exists('jobDate', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobDate required!'));
            }else if (!array_key_exists('jobTime', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobTime required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {

                    $datetime = strtotime($postdata['jobDate'] . ' ' . $postdata['jobTime']);
                    //if($datetime > strtotime(date('Y-m-d H:i:s'))){
                    //GET Cancelled JOB
                    $job = $this->Home_Model->selectWhere('jobs', ['userId' => $postdata['id'], 'id' => $postdata['jobId'], 'jobStatus' => 0]);
                    if($job){
                        $step4meta = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta'=>'step4']);
                        $step4value = json_decode($step4meta['draft_value'], true);
                        $step4value['jobTime'] = $postdata['jobTime'];
                        $step4value['jobDate'] = $postdata['jobDate'];
                        $update = $this->Home_Model->updateDB('job_draft',['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta'=>'step4'], ['draft_value'=>json_encode($step4value)]);
                        if($update){
                            $result = [
                                'status'=>true,
                                'message'=>'Job time updated succesfully!',
                                'jobId'=>$postdata['jobId'],
                                'stepCount'=>($job['stepCount']>6?6:$job['stepCount']),
                                'jobStatus'=>"Drafted",
                                'id'=>$postdata['id']
                            ]; //OUTPUT ARRAY.
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($result));
                        }else{
                            $result = [
                                'status'=>false,
                                'message'=>'something went wrong',
                                'jobId'=>$postdata['jobId'],
                                'stepCount'=>($job['stepCount']>6?6:$job['stepCount']),
                                'jobStatus'=>"Drafted",
                                'id'=>$postdata['id']
                            ]; //OUTPUT ARRAY.
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($result));
                        }
                    }
                    /*}else{
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)
                            ->set_output(json_encode('Expired Datetime'));
                    }*/



                    /*
                    //COPY ARRAY
                    $copyData = array(
                        'userId' => $job['userId'],
                        'saveStatus' => 'temp-draft',
                        'jobStatus' => 0,
                        'stepCount' => 6,
                        'createddate' => CURRENT_DATETIME
                    );
                    $this->db->insert('jobs', $copyData);
                    $newJobId = $this->db->insert_id(); //INSERT AND GET NEW JOB ID


                    //$checkCopy = $this->Home_Model->selectWhere('job_draft', ['draft_meta' => 'copy_id', 'fk_userId' => $job['id'], 'fk_userId'=> $job['userId']]);
                    $metaData = [
                        'draft_meta' => 'copy_id',
                        'draft_value' => $job['id'],
                        'fk_userId' => $job['userId'],
                        'fk_jobId' => $newJobId
                    ];
                    $this->db->insert('job_draft', $metaData); //SET COPY_ID OF NEW JOB ID




                    foreach ($jobMetas as $meta){
                        if($meta['draft_meta'] != 'step7' && $meta['draft_meta'] != 'copy_id'){
                            $metaData = [
                                'draft_meta' => $meta['draft_meta'],
                                'draft_value' => $meta['draft_value'],
                                'fk_userId' => $job['userId'],
                                'fk_jobId' => $newJobId
                            ];
                            $this->db->insert('job_draft', $metaData); //CREATE META OF NEW JOB ID
                        }
                    }*/
                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function checkSuspend(){
        $jobs = $this->Home_Model->selectWhereResult('jobs', ['jobStatus' => 1, 'expiredate<=' => CURRENT_DATETIME, 'expiredate!=' => '0000-00-00 00:00:00', 'isExpired' => '0' ,'saveStatus'=>'Completed']);
        if($jobs){
            foreach ($jobs as $key => $job){
                $this->Home_Model->updateDB('jobs', ['id' => $job['id']], ['jobStatus'=>0, 'expiredate'=>'0000-00-00 00:00:00', 'isExpired'=> 1]);
                $status = "Cancelled";
                $job['jobStatus'] = $status;
                $table = "users";
                $where = ['id' => $job['userId']];
                $userDetails = $this->Home_Model->selectWhere($table, $where);
                $postString = array();
                $postString['registration_ids'][] = $userDetails['deviceId'];
                $title = "EZE-TECH NOTIFICATION";
                $body = "Please click to view the job";

                $body = "No Response from technician";

                $pushArray = [
                    'id' => $job['id'],
                    'jobStatus' => $job['jobStatus'],
                    'isReviewed' => ''
                ];

                if ($userDetails['deviceType'] == "ios") {
                    $postString['notification'] = ["title" => $title, "body" => $body];
                    $postString['data'] = ['job' => $pushArray];
                } else {
                    $postString['data'] = ['job' => $pushArray, "title" => $title, "body" => $body];
                }

                $postString['priority'] = 'high';
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postString));

                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:key=AIzaSyDcXX4jV35Q0do8cmnSdu4Rwg1DtqUCsYI', 'Content-Type:application/json'));
                $result = curl_exec($ch);

            }
        }


    }

    public function cancelToDraft(){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            }else if (!array_key_exists('step', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('step required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $this->db->where('userId', $postdata['id']);
                    $this->db->where('id', $postdata['jobId']);
                    $this->db->update('jobs', ['stepCount'=>$postdata['step'],'saveStatus'=>'draft', 'techId'=>0]);
                    $afftectedRows = $this->db->affected_rows();
                    if($afftectedRows > 0){
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode('Status changed to draft'));
                    }else{
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)
                            ->set_output(json_encode('Something going wrong!.'));
                    }

                }

            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }

    }

    public function suspendJob($jobId){
        $job = $this->Home_Model->selectWhere('jobs', ['id'=> $jobId, 'jobStatus' => 1, 'expiredate<=' => CURRENT_DATETIME, 'expiredate!=' => '0000-00-00 00:00:00', 'isExpired' => '0' ,'saveStatus'=>'Completed']);
        if($job){
            $this->Home_Model->updateDB('jobs', ['id' => $job['id']], ['jobStatus'=>0, 'expiredate'=>'0000-00-00 00:00:00', 'isExpired'=> 1]);
            $status = "Cancelled";
            $job['jobStatus'] = $status;
            $table = "users";
            $where = ['id' => $job['userId']];
            $userDetails = $this->Home_Model->selectWhere($table, $where);
            $postString = array();
            $postString['registration_ids'][] = $userDetails['deviceId'];
            $title = "EZE-TECH NOTIFICATION";
            $body = "Please click to view the job";

            $body = "No Response from technician";

            $pushArray = [
                'id' => $job['id'],
                'jobStatus' => $job['jobStatus'],
                'isReviewed' => ''
            ];

            if ($userDetails['deviceType'] == "ios") {
                $postString['notification'] = ["title" => $title, "body" => $body];
                $postString['data'] = ['job' => $pushArray];
            } else {
                $postString['data'] = ['job' => $pushArray, "title" => $title, "body" => $body];
            }

            $postString['priority'] = 'high';
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postString));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:key=AIzaSyDcXX4jV35Q0do8cmnSdu4Rwg1DtqUCsYI', 'Content-Type:application/json'));
            $result = curl_exec($ch);
            print_r($result);
        }
    }

    public function updateGps($techId){
        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('id required!'));
            } elseif (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('jobId', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('jobId required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'users') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    //GET Cancelled JOB
                    $job = $this->Home_Model->selectWhere('jobs', ['userId' => $postdata['id'], 'id' => $postdata['jobId'], 'jobStatus' => 'draft']);
                    if($job){
                        $step4meta = $this->Home_Model->selectWhere('job_draft', ['fk_userId' => $postdata['id'], 'fk_jobId' => $postdata['jobId'], 'draft_meta'=>'step4']);
                        if($step4meta){
                            $step4value = json_decode($step4meta['draft_value'], true);
                            $datetime = strtotime($step4value['jobDate'] . ' ' . $step4value['jobTime']);
                            if($datetime > strtotime(date('Y-m-d H:i:s'))){
                                $result = [
                                    'status'=>true,
                                    'jobId'=>$postdata['jobId'],
                                    'id'=>$postdata['id'],
                                    'stepCount'=>$job['stepCount'],
                                ]; //OUTPUT ARRAY.
                                return $this->output
                                    ->set_content_type('Content-Type: application/json')
                                    ->set_status_header(200)
                                    ->set_output(json_encode($result));
                            }else{
                                $result = [
                                    'status'=>false,
                                    'message'=>'Job date time expired',
                                    'jobId'=>$postdata['jobId'],
                                    'id'=>$postdata['id'],
                                    'stepCount'=>$job['stepCount'],
                                ]; //OUTPUT ARRAY.
                                return $this->output
                                    ->set_content_type('Content-Type: application/json')
                                    ->set_status_header(200)
                                    ->set_output(json_encode($result));
                            }
                        }else {
                            $result = [
                                'status'=>true,
                                'jobId'=>$postdata['jobId'],
                                'id'=>$postdata['id'],
                                'stepCount'=>$job['stepCount'],
                            ]; //OUTPUT ARRAY.
                            return $this->output
                                ->set_content_type('Content-Type: application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($result));
                        }

                    }else {
                        return $this->output
                            ->set_content_type('Content-Type: application/json')
                            ->set_status_header(404)
                            ->set_output(json_encode('Your job not in draft!.'));
                    }
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }
    }

    public function pushRedis(){

        $query = $this->Home_Model->selectWhereResult('technicians', ['longitude!='=>'', 'latitude!='=>'']);
        foreach ($query as $row){
            $data_json = json_encode(['techId'=>$row['id'], 'lng'=> $row['longitude'], 'lat'=>$row['latitude']]);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://localhost/predis/getLocation.php');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response  = curl_exec($ch);
            echo "<pre>";print_r($response);
            curl_close($ch);
        }

    }

    //FOR BACKGROUND UPDATE
    public function techGps(){

        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('tech id required!'));
            } else {
                if ($this->authenticate($_SERVER['REDIRECT_HTTP_ID'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    $postdata = $postdata[0];
                    //$postdata = $postdata;
                    //[{"provider":"gps","time":1546420790000,"latitude":50,"longitude":100,"accuracy":20,"altitude":3,"locationProvider":0}]
                    $latitude = $postdata['latitude'];
                    $longitude = $postdata['longitude'];
                    $mode = $postdata['mode'];
                    $time = substr($postdata['time'], 0, -3);
                    $techId = $_SERVER['REDIRECT_HTTP_ID'];
                    //$postdata = json_decode($postdata, true);
                    $client = new Predis\Client();
                    $client->set('tech_'.$techId, json_encode(['techId'=>$techId,'longitude'=>$longitude, 'latitude'=>$latitude, 'time'=>gmdate('Y-m-d H:i:s', $time)]));
                    //$client->expire('tech_'.$techId, REDIS_EXPIRE);
                    $value = $client->get('tech_'.$techId);
                    $data = [
                        'foo' => json_encode($postdata),
                        'userid' => $techId,
                        'mode' => $mode,
                        'createdate' => date('Y-m-d H:i:s')
                    ];
                    $this->Home_Model->insertDB('locations', $data);
                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output($value);
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }



    }

    //FOR FORGROUND UPDATE
    public function updateTechGps(){

        $postdata = json_decode(file_get_contents('php://input'), true);
        if ($postdata) {
            if (!array_key_exists('id', $postdata)) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('no authorization token!'));
            }else if (!array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) || empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                return $this->output
                    ->set_content_type('Content-Type: application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode('tech id required!'));
            } else {
                if ($this->authenticate($postdata['id'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'technicians') == false) {
                    return $this->output->set_content_type('Content-Type: application/json')->set_status_header(404)->set_output(json_encode('invalid token'));
                } else {
                    //$postdata = $postdata[0];
                    $postdata = $postdata;
                    //[{"provider":"gps","time":1546420790000,"latitude":50,"longitude":100,"accuracy":20,"altitude":3,"locationProvider":0}]
                    $latitude = $postdata['latitude'];
                    $longitude = $postdata['longitude'];
                    $mode = $postdata['mode'];
                    $time = substr($postdata['time'], 0, -3);
                    $techId = $postdata['id'];
                    //$postdata = json_decode($postdata, true);
                    $client = new Predis\Client();
                    $client->set('tech_'.$techId, json_encode(['techId'=>$techId,'longitude'=>$longitude, 'latitude'=>$latitude, 'time'=>gmdate('Y-m-d H:i:s', $time)]));
                    //$client->expire('tech_'.$techId, REDIS_EXPIRE);
                    $value = $client->get('tech_'.$techId);


                    $data = [
                        'foo' => json_encode($postdata),
                        'userid' => $techId,
                        'mode' => $mode,
                        'createdate' => date('Y-m-d H:i:s')
                    ];
                    $this->Home_Model->insertDB('locations', $data);

                    return $this->output
                        ->set_content_type('Content-Type: application/json')
                        ->set_status_header(200)
                        ->set_output($value);
                }
            }
        } else {
            return $this->output
                ->set_content_type('Content-Type: application/json')
                ->set_status_header(404)
                ->set_output(json_encode('Something going wrong!.'));
        }



    }

    public function getValue($id){
        $client = new Predis\Client();
        $value = $client->get('tech_'.$id);
        return $this->output
            ->set_content_type('Content-Type: application/json')
            ->set_status_header(404)
            ->set_output($value);
    }

    public function flushRedis(){
        $client = new Predis\Client();
        $client->flushdb();
        return $this->output
            ->set_content_type('Content-Type: application/json')
            ->set_status_header(404)
            ->set_output('Flush');
    }

    public function sendEmail(){
        $mail = $this->sendHtmlEmail(['fromEmail'=>'do-not-reply@eze-tech.com','toEmail'=>'johnnybravotest379@gmail.com'
            ,'subject'=>'test' ,'message'=>'hello', 'name'=>'Jhonny', 'filePath'=>'index.php', 'fileName'=>'abc.pdf']);
    }

    public function pdf(){
        error_reporting(E_ALL);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $m = '<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eze Tech</title>
	<Style>
		*{
			margin:0;
			padding: 0;
		}
	</Style>
</head>

<body bgcolor="#FFFFFF" style="width: 1200px; text-align: center; margin: 0 auto; font-family: Arial, Helvetica, sans-serif;" width="1200">
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="margin: 0 auto; font-family: Arial, Helvetica, sans-serif;">
  <tbody>
    <tr>
		<td width="10">&nbsp;</td>
      <td>&nbsp;</td>
		<td width="10">&nbsp;</td>
    </tr>
    <tr>
		<td width="10">&nbsp;</td>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td valign="middle" width="50%" align="left"><img src="Group 1.png" width="120"></td>
      <td  width="50%" valign="top"><table width="100%" border="1" cellspacing="0" cellpadding="2">
  <tbody>
    <tr>
      <td width="65%" align="right" style="padding:5px; font-weight: bold; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">Date</td>
      <td width="33%" align="left" style="padding:5px; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">27-May-2019</td>
    </tr>
	  <tr>
      <td width="65%" align="right" style="padding:5px; font-weight: bold; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">Invoive #</td>
      <td width="33%" align="left" style="padding:5px; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">123456</td>
    </tr>
	  <tr>
      <td width="65%" align="right" style="padding:5px; font-weight: bold; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">Order #</td>
      <td width="33%" align="left" style="padding:5px; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">123456</td>
    </tr>
	  
   
  </tbody>
</table>
</td>
    </tr>
  </tbody>
</table></td>
		<td width="10">&nbsp;</td>
    </tr>
    <tr>
     <td width="10">&nbsp;</td>
      <td>&nbsp;</td>
		<td width="10">&nbsp;</td>
    </tr>
    <tr>
     <td width="10">&nbsp;</td>
      <td style="font-family: Arial, Helvetica, sans-serif; font-size: 22px; font-weight: bold;" align="right">INVOICE</td>
		<td width="10">&nbsp;</td>
    </tr>
    <tr>
     <td width="10">&nbsp;</td>
      <td>&nbsp;</td>
		<td width="10">&nbsp;</td>
    </tr>
    <tr>
     <td width="10">&nbsp;</td>
      <td><table width="100%"  cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="49%"><table width="100%" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="left" style="font-size: 18px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">Technician Details</td>
    </tr>
	  <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Name:</strong></td>
    </tr>
	<tr>
      <td style="font-size: 8px;">&nbsp;</td>
    </tr>
    
    <tr>
      <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Phone Number:</strong></td>
    </tr>
	  <tr>
      <td style="font-size: 8px;">&nbsp;</td>
    </tr>
    <tr>
      <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Address:</strong> </td>
    </tr>
  </tbody>
</table>
</td>
		<td width="2%">&nbsp;</td>
      <td width="49%"><table width="100%" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="left" style="font-size: 18px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">User Details</td>
    </tr>
	  <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Name:</strong></td>
    </tr>
	<tr>
      <td style="font-size: 8px;">&nbsp;</td>
    </tr>
    
    <tr>
      <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Phone Number:</strong></td>
    </tr>
	  <tr>
      <td style="font-size: 8px;">&nbsp;</td>
    </tr>
    <tr>
      <td  align="left" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>Address:</strong> </td>
    </tr>
  </tbody>
</table></td>
    </tr>
  </tbody>
</table>
</td>
		<td width="10">&nbsp;</td>
    </tr>
    <tr>
     <td width="10">&nbsp;</td>
      <td >&nbsp;</td>
		<td width="10">&nbsp;</td>
    </tr>
    <tr>
     <td width="10">&nbsp;</td>
      <td><table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tbody>
	  <tr>
      <th style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="left" width="10%">S#:</th>
      <th style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="left" width="60%">Description</th>
      <th style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;"  align="left" width="15%">Amount</th>
    </tr>
    <tr>
      <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;"  align="left" width="10%">1</td>
      <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="left" width="60%">Unmountable boot volume</td>
      <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="right" width="15%">$ 89.00</td>
    </tr>
    <tr>
      <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>
      <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>
      <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>
    </tr>
    <tr>
      <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>
      <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>
     
      <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">&nbsp;</td>
    </tr>
    </tr>
  </tbody>
</table>
</td>
		<td width="10">&nbsp;</td>
    </tr>
    <tr>
     <td width="10">&nbsp;</td>
      <td>&nbsp;</td>
		<td width="10">&nbsp;</td>
    </tr>
    <tr>
     <td width="10">&nbsp;</td>
      <td><table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="85%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold;" align="right">T.V.Q</td>
      <td width="15%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="right">$ 8.81</td>
    </tr>
    <tr>
       <td width="85%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold;" align="right">T.P.S</td>
      <td width="15%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;" align="right">$ 4.45</td>
    </tr>
    <tr>
       <td width="85%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold;" align="right">Total Due</td>
      <td width="15%" style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold;" align="right">$ 102.26</td>
    </tr>
  </tbody>
</table>
</td>
		<td width="10">&nbsp;</td>
    </tr>
    <tr>
     <td width="10">&nbsp;</td>
      <td>&nbsp;</td>
		<td width="10">&nbsp;</td>
    </tr>
   
	  <tr>
     <td width="10">&nbsp;</td>
      <td>&nbsp;</td>
		<td width="10">&nbsp;</td>
    </tr>
	   <tr>
     <td width="10">&nbsp;</td>
      <td style="padding: 5px; font-family: Arial, Helvetica, sans-serif; font-size: 18px;" align="left"><strong>Thanks</strong><br><br>EZE-TECH Support Team 
</td>
		<td width="10">&nbsp;</td>
    </tr>
  </tbody>
</table>

	
</body>
</html>
';
        $mpdf->WriteHTML($m);

        $mpdf->SetTitle('PDF');
        $filename="/var/www/html/uploads/invoices/test.pdf";
        $mpdf->Output($filename, 'F');
    }

}