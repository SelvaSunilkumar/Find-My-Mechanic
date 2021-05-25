<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home extends CI_Controller {

    //$this->load->library('../controllers/User');

    public function index() {
        if ($this->session->userdata('id') != '') {
            redirect(base_url().'index.php/home/welcome');
        } else {
            $this->load->view('home');
        }
    }

    public function logout() {
        $this->session->unset_userdata('id');
        redirect(base_url().'index.php/home/index');
    }

    public function welcome() {
        if ($this->session->userdata('id') != '') {
            $id = $this->session->userdata('id');
            $data = array('id_user' => $id);
            $this->load->view("mecharme",$data);
        } else {
            redirect(base_url().'index.php/home/index');
        }
    }

    public function mechdashboard() {
        if ($this->session->userdata('id') != '') {
            $id = $this->session->userdata('id');
            $data = array('id_user' => $id);
            $this->load->view("invitation",$data);
        } else {
            redirect(base_url().'index.php/home/index');
        }
    }

    public function dashboard() {
        if ($this->session->userdata('id') != '') {
            redirect(base_url().'index.php/home/welcome');
        } else {
            redirect(base_url().'index.php/home/index');
        }
    }

    public function myrequest() {
        if ($this->session->userdata('id') != '') {
            $id = $this->session->userdata('id');
            $data = array('user_id' => $id);
            $this->load->view("requests",$data);
        } else {
            redirect(base_url().'index.php/home/index');
        }
    }

    public function usercompleted() {
        if ($this->session->userdata('id') != '') {
            $id = $this->session->userdata('id');
            $data = array('userid' => $id);
            $this->load->view("usercompleted",$data);
        } else {
            redirect(base_url().'index.php/home/index');
        }
    }

    public function mechanicCompleted() {
        if ($this->session->userdata('id') != '') {
            $id = $this->session->userdata('id');
            $data = array('userid' => $id);
            $this->load->view("mechcompleted",$data);
        } else {
            redirect(base_url().'index.php/home/index');
        }
    }

    public function mechanicTask() {
        if ($this->session->userdata('id') != '') {
            $id = $this->session->userdata('id');
            $data = array('userid' => $id);
            $this->load->view("mechtasks",$data);
        } else {
            redirect(base_url().'index.php/home/index');
        }
    }

    public function deletedTask() {
        if ($this->session->userdata('id') != '') {
            $id = $this->session->userdata('id');
            $data = array('userid' => $id);
            $this->load->view("userdeleted",$data);
        } else {
            redirect(base_url().'index.php/home/index');
        }
    }

    public function deletedMechTask() {
        if ($this->session->userdata('id') != '') {
            $id = $this->session->userdata('id');
            $data = array('userid' => $id);
            $this->load->view("mechdeleted",$data);
        } else {
            redirect(base_url().'index.php/home/index');
        }
    }

    public function auth() {
        //$this->load->view('mecharme');
        //redirect(base_url().'index.php/home/');
        //$this->go();
        include 'dbconn.php';

        $username = $this->input->post("username");
        $password = $this->input->post("password");
        
        $auth_mech_query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $auth_user_query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $auth_mech_result = mysqli_query($connection,$auth_mech_query);
        $auth_user_result = mysqli_query($connection,$auth_user_query);

        $auth_mech_count = mysqli_num_rows($auth_mech_result);
        $auth_user_count = mysqli_num_rows($auth_user_result);

        if ($auth_mech_count > 0) {
            //redirection to mechanic Page
            $auth_attr = mysqli_fetch_assoc($auth_mech_result);
            $session_data = array('id'=>$auth_attr["id"]);
            $this->session->set_userdata($session_data);
            redirect(base_url().'index.php/home/mechdashboard');
        } else if ($auth_user_count > 0) {
            $auth_attr = mysqli_fetch_assoc($auth_user_result);
            $session_data = array('id'=>$auth_attr['id']);
            $this->session->set_userdata($session_data);
            $data = array("id_user"=>$auth_attr['id']);
            redirect(base_url().'index.php/home/welcome');
        }
        else {
            $this->index();
        }
    }

    public function fetch() {
        include 'dbconn.php';
        $output = '';
       if ($this->input->post('filter')) {
           $filter = $this->input->post('filter');
           $place = $this->input->post('place');
           $id_user = $this->input->post('userid');
           if ($connection) {
               $user_location_latitude;
               $user_location_longitude;

               $user_location_query = "SELECT * FROM location where location='$place'";
               $user_location_result = mysqli_query($connection,$user_location_query);

               $user_location_row = mysqli_fetch_assoc($user_location_result);
               $user_location_longitude = $user_location_row["lon"];
               $user_location_latitude = $user_location_row["lat"];

               $mechanic_location_latitude;
               $mechanic_location_longitude;

               $mechanic_select_query = "SELECT * FROM users";
               $mechanic_select_result = mysqli_query($connection,$mechanic_select_query);

               while ($mechanic_select_row = mysqli_fetch_array($mechanic_select_result)) {

                //code for review count
                $get_id = $mechanic_select_row["id"];
                $get_review_query = "SELECT * FROM accomplished WHERE mechid='$get_id'";
                $get_review_result = mysqli_query($connection,$get_review_query);

                $get_review_count = mysqli_num_rows($get_review_result);
                $get_review_sum = 0;
                while ($get_review_attr = mysqli_fetch_array($get_review_result)) {
                    $rate = $get_review_attr["rate"];
                    $get_review_sum = $get_review_sum + $rate;
                }

                $mechanic_location = $mechanic_select_row["location"];

                $mechanic_location_query = "SELECT * FROM location WHERE location='$mechanic_location'";
                $mechanic_location_result = mysqli_query($connection,$mechanic_location_query);

                $mechanic_location_attr = mysqli_fetch_assoc($mechanic_location_result);
                $mechanic_location_longitude = $mechanic_location_attr["lon"];
                $mechanic_location_latitude = $mechanic_location_attr["lat"];

                //calculation for Distance
                $theta = $user_location_longitude - $mechanic_location_longitude;
                $distance = sin(deg2rad($user_location_latitude)) * sin(deg2rad($mechanic_location_latitude)) + cos(deg2rad($user_location_latitude)) * cos(deg2rad($mechanic_location_latitude)) * cos(deg2rad($theta));
                $distance = acos($distance);
                $distance = rad2deg($distance);
                $distance = $distance * 60 * 1.1515;
                $distance = round($distance * 1.609344,2);
                if ($distance <= $filter) {
                    $output .= '
                    <div class="col-md-4 as">
                    <div class="card whole">
                        <div class="details">
                            <div class="left">
                                <img src="'.$mechanic_select_row["url"].'" class="img" alt="">
                            </div>
                            <div class="right">
                                <div>
                                    <label class="set_title">Name</label>
                                    <div class="sub_title">
                                    <input style="border: none; width: 100%; outline: none;" type="text" value="'.$mechanic_select_row["name"].'" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="set_title">Occupation</label>
                                    <div class="sub">
                                    <input style="border: none; width: 100%; outline: none;" type="text" value="'.$mechanic_select_row["occupation"].'" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <div class="body_left">
                                    <div>
                                        <label class="set_title">From</label>
                                        <div>
                                        <input style="border: none; width: 100%; outline: none;" type="text" value="'.$mechanic_select_row["location"].'" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="body_right">
                                    <div>
                                        <label class="set_title">Average Cost</label>
                                        <div>
                                        <input style="border: none; width: 100%; outline: none;" type="text" value="'.$mechanic_select_row["amount"].'" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="set_title">Distance</label>
                                <input style="border: none; width: 50%; outline: none; float: right;" type="text" name="" value="'.$distance."KM".'" readonly>
                            </div>
                            <div>
                                <label class="set_title">Contact</label>
                                <input style="border: none; width: 50%; outline: none; float: right;" type="text" name="" value="'.$mechanic_select_row["contact"].'" readonly>
                            </div>
                            ';
                            if ($get_review_count == 0) {
                                $output .= '<div>
                                <label class="set_title">Rating</label>
                                <div style="border: none; width: 50%; outline: none; float: right;" >
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    ('.$get_review_count.')
                                </div>
                                </div>';
                            } else {
                                $rating = round(($get_review_sum / $get_review_count),1);
                                if ($rating <= 1.5) {
                                    $output .= '<div>
                                        <label class="set_title">Rating</label>
                                        <div style="border: none; width: 50%; outline: none; float: right;" >
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            ('.$get_review_count.')
                                        </div>
                                    </div>';
                                } else if ($rating > 1.5 && $rating <= 2.5) {
                                    $output .= '<div>
                                        <label class="set_title">Rating</label>
                                        <div style="border: none; width: 50%; outline: none; float: right;" >
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            ('.$get_review_count.')
                                        </div>
                                    </div>';
                                } else if ($rating > 2.5 && $rating <= 3.5) {
                                    $output .= '<div>
                                        <label class="set_title">Rating</label>
                                        <div style="border: none; width: 50%; outline: none; float: right;" >
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            ('.$get_review_count.')
                                        </div>
                                    </div>';
                                } else if ($rating > 3.5 && $rating <= 4.5) {
                                    $output .= '<div>
                                        <label class="set_title">Rating</label>
                                        <div style="border: none; width: 50%; outline: none; float: right;" >
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star"></span>
                                            ('.$get_review_count.')
                                        </div>
                                    </div>';
                                } else if ($rating > 4.5) {
                                    $output .= '<div>
                                        <label class="set_title">Rating</label>
                                        <div style="border: none; width: 50%; outline: none; float: right;" >
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            <span class="fa fa-star checked" style="color: orange;"></span>
                                            ('.$get_review_count.')
                                        </div>
                                    </div>';
                                }
                                /*$output .= '<div>
                                <label class="set_title">Rating</label>
                                <div style="border: none; width: 50%; outline: none; float: right;" >
                                <span class="fa fa-star checked" style="color: orange;"></span>
                                <span class="fa fa-star checked" style="color: orange;"></span>
                                <span class="fa fa-star checked" style="color: orange;"></span>
                                <span class="fa fa-star" style="color: orange;"></span>
                                <span class="fa fa-star"></span>
                                    ('.$get_review_count.')
                                </div>
                                </div>';*/
                            }
                            $invitation_check_query = "SELECT * FROM invitation WHERE userid='$id_user' AND mechid='$mechanic_select_row[id]'";
									$invitation_check_result = mysqli_query($connection,$invitation_check_query);
									$invitation_check_count = mysqli_num_rows($invitation_check_result);

									$accpted_invitation_query = "SELECT * FROM accepted WHERE userid='$id_user' AND mechid='$mechanic_select_row[id]'";
									$accpted_invitation_result = mysqli_query($connection,$accpted_invitation_query);
									$accpted_invitation_count = mysqli_num_rows($accpted_invitation_result);

									if ($accpted_invitation_count > 0) {
										
                                        $output .= '<div class="bottom">
                                            <button type="submit" style="background: #5ad44c;" class="select" id="'.$mechanic_select_row['id'].'">Work Pending</button>
                                        </div>';
									}
									else if ($invitation_check_count > 0) {
                                        $output .= '
											<div class="bottom">
												<button type="submit" style="background: #e0dd1f;" class="select" id="'.$mechanic_select_row['id'].'">Request Send</button>
											</div>
										';
									} else {
										$output .= '
											<div class="bottom">
												<button type="submit" class="select" id="'.$mechanic_select_row['id'].'">Send Request</button>
											</div>
										';
									}
                        $output .= '
                        </div>
                    </div>
                </div>';
                }
               }
           }
       }else {
           echo "<label>$filter</label>";
       }
       echo $output;
    }

    public function getId() {
        include 'dbconn.php';
        $output = '';
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
            //echo $id;}
            $user_query = "SELECT * FROM users WHERE id='$id'";
            $user_result = mysqli_query($connection,$user_query);
            if($user_result) {
                while ($row = mysqli_fetch_array($user_result)) {
                    $json = array(
                        "name"=>$row["name"],
                        "id"=>$row["id"],
                        "location"=>$row["location"],
                        "amount"=>$row["amount"],
                        "occupation"=>$row["occupation"],
                        "url"=>$row["url"]
                    );
                    $output .= json_encode($json);
                }
            } else {
                $output .= "Fail";
            }
            /*$user_select_attr = mysqli_fetch_assoc($usert_result);
            $output .= $user_select_attr["name"];*/
        } else {
            $output .= 'Not Data Send';
        }
        echo $output;
    }

    public function sendrequest() {
        include 'dbconn.php';
        $userid = $this->input->post('userid');
        $mechid = $this->input->post('mechid');
        $purpose = $this->input->post('purpose');
        $date = $this->input->post('date');
        $output = '';

        $check_invitaion_query = "SELECT * FROM invitation WHERE userid='$userid' AND mechid='$mechid'";
        $check_invitaion_result = mysqli_query($connection,$check_invitaion_query);
        
        $check_invitaion_count = mysqli_num_rows($check_invitaion_result);

        if ($check_invitaion_count > 0) {
            $output .= "send";
        } else {
            $send_invitation_query = "INSERT INTO `invitation`(`userid`, `mechid`, `purpose`, `date`) VALUES ('$userid','$mechid','$purpose','$date')";
            $send_invitation_result = mysqli_query($connection,$send_invitation_query);

            if ($send_invitation_result) {

                $user_details_query = "SELECT * FROM user WHERE id='$userid'";
                $user_details_result = mysqli_query($connection,$user_details_query);
                $user_details_attr = mysqli_fetch_assoc($user_details_result);

                $username = $user_details_attr["name"];
                $contact = $user_details_attr["contact"];
                $location = $user_details_attr["location"];
                
                $mechanic_details_query = "SELECT * FROM users WHERE id='$mechid'";
                $mechanic_details_result = mysqli_query($connection,$mechanic_details_query);
                $mechanic_details_attr = mysqli_fetch_assoc($mechanic_details_result);

                $toid = $mechanic_details_attr["username"];

                //$output .= "ok";
                //code to send mail
                $subject = "New Job Invitation has been Recieved";
                $from = "sunil";
                $to = 'sunil@student.tce.edu';
                //$to = "gowthama@student.tce.edu";
                //$to = "bindhuselva1976@gmail.com";

                $email_content = '<!DOCTYPE html>
                <html>
                <head>
                    <title>Profile</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                </head>
                <body>
                    <h2 style="text-align: center;">New Job Invitation</h2>
                
                    <div style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); max-width: 300px; margin: auto; text-align: center; font-family: arial; padding: 10px; background-color: #e8d27b; border-radius: 10px;">
                        <img src="https://firebasestorage.googleapis.com/v0/b/gallery-database-f63d5.appspot.com/o/titleicon.png?alt=media&token=081df965-9def-4d3c-b6a9-9707496cb7e1" alt="logo" style="width: 100%;">
                        <h1 style="color: #448dc2;">'.$purpose.'</h1>
                        <div style="padding-top: 10px;">
                            <label style="color: #787d79; font-size: 18px;"><b>Name</b></label>
                            <div>
                                <input type="text" value="'.$username.'" style="border: none; outline: none; align-content: center; text-align: center; background: transparent;font-size: 18px; color: #409fc2;">
                            </div>
                        </div>
                        <div style="padding-top: 10px;">
                            <label style="color: #787d79; font-size: 18px;"><b>Contact</b></label>
                            <div>
                                <input type="text" value="'.$contact.'" style="border: none; outline: none; align-content: center; text-align: center; background: transparent;font-size: 18px; color: #409fc2;">
                            </div>
                        </div>
                        <div style="padding-top: 10px;">
                            <label style="color: #787d79; font-size: 18px;"><b>Location</b></label>
                            <div>
                                <input type="text" value="'.$location.'" style="border: none; outline: none; align-content: center; text-align: center; background: transparent;font-size: 18px; color: #409fc2;">
                            </div>
                        </div>
                        <div style="padding-top: 10px;">
                            <label style="color: #787d79; font-size: 18px;"><b>Date</b></label>
                            <div>
                                <input type="text" value="'.$date.'" style="border: none; outline: none; align-content: center; text-align: center; background: transparent;font-size: 18px; color: #409fc2;">
                            </div>
                        </div>
                        <div style="width: 100%; padding-top: 10px;">
                            <a href="http://localhost/fstival/index.php/home" style="-webkit-appearance: button; -moz-appearance: button; appearance: button; text-decoration: none; color: initial; background: #409fc2; color: #fff; padding: 15px; border-radius: 10px;" > Open in Browser</a>
                        </div>
                    </div>
                </body>
                </html>';

                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'ssl://smtp.gmail.com';
                $config['smtp_port'] = '465';
                $config['smtp_timeout'] = '60';

                $config['smtp_user'] = 'sunilselva3335@gmail.com';
                $config['smtp_pass'] = 'sunilkumar001';

                $config['charset'] = 'utf-8';
                $config['newline'] = "\r\n";
                $config['mailtype'] = 'html';
                $config['validation'] = TRUE;

                $this->email->initialize($config);
                $this->email->set_mailtype("html");
                $this->email->from($from);
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($email_content);
                $this->email->send();

                $output .= "ok";

            } else {
                $output .= "fail";
            }
        }
        echo $output;
    }

    public function getInviter() {
        include 'dbconn.php';

        $userid = $this->input->post("id");

        $user_select_query = "SELECT * FROM user WHERE id='$userid'";
        $user_select_result = mysqli_query($connection,$user_select_query);

        $user_select_attr = mysqli_fetch_assoc($user_select_result);
        $user_json_encode = array(
            "name" => $user_select_attr["name"],
            "contact" => $user_select_attr["contact"],
            "location" => $user_select_attr["location"],
            "url" => $user_select_attr["url"]
        );

        echo json_encode($user_json_encode);
    }

    public function uploadAccept() {
        include 'dbconn.php';

        $user_id = $this->input->post("userid");
        $mech_id = $this->input->post("mechid");
        $mydate = $this->input->post("date");
        $output = '';

        $check_confirm_query = "SELECT * FROM accepted WHERE userid='$user_id' AND mechid='$mech_id'";
        $check_confirm_result = mysqli_query($connection,$check_confirm_query);
        $check_confirm_count = mysqli_num_rows($check_confirm_result);

        if ($check_confirm_count > 0) {
            $output .= 'send';
        } else {
            $accept_invitation_query = "INSERT INTO `accepted`(`userid`, `mechid`,`date`) VALUES ('$user_id','$mech_id','$mydate')";
            $accept_invitation_result = mysqli_query($connection,$accept_invitation_query);
            if ($accept_invitation_result) {
                //$output .= 'ok';

                $output .= "ok";

            } else {
                $output .= 'fail';
            }
        }
        echo $output;
    }

    public function deleteRequest() {
        include 'dbconn.php';

        $user_id = $this->input->post("userid");
        $mechid = $this->input->post("mechid");
        //$output = '';

        $check_confirm_query = "SELECT * FROM accepted WHERE userid='$user_id' and mechid='$mechid'";
        $check_confirm_result = mysqli_query($connection,$check_confirm_query);
        $check_confirm_count = mysqli_num_rows($check_confirm_result);
        //$output .= $check_confirm_count;
        if ($check_confirm_count > 0) {
            echo "send";
        } else {
            $delete_invitation_query = "DELETE FROM invitation WHERE userid='$user_id' AND mechid='$mechid'";
            $delete_invitation_result = mysqli_query($connection,$delete_invitation_query);
            
            if ($delete_invitation_result) {
                echo "ok";
            } else {
                echo "fail";
            }
        }
    }

    public function filterMech() {
        include 'dbconn.php';

        $mech_id = $this->input->post("mechid");
        $user_id = $this->input->post("userid");
        $filter_distance = $this->input->post("distance");
        $filter_location = $this->input->post("location");
        $output = '';

        $mechanic_location_latitude;
        $mechanic_location_longitude;
        $mechanic_location_query = "SELECT * FROM location WHERE location='$filter_location'";
        $mechanic_location_result = mysqli_query($connection,$mechanic_location_query);

        $mechanic_location_attr = mysqli_fetch_assoc($mechanic_location_result);
        $mechanic_location_latitude = $mechanic_location_attr["lat"];
        $mechanic_location_longitude = $mechanic_location_attr["lon"];

        $user_location_latitude;
        $user_location_longitude;
        
        $user_select_query = "SELECT * FROM invitation WHERE mechid='$mech_id'";
        $user_select_result = mysqli_query($connection,$user_select_query);
        while ($user_select_attr = mysqli_fetch_array($user_select_result)) {
            $user_select_id = $user_select_attr["userid"];
            #code to complete from line 83 in invitation.php

            $user_details_query = "SELECT * FROm user WHERE id='$user_select_id'";
            $user_details_result = mysqli_query($connection,$user_details_query);

            $user_details_assoc = mysqli_fetch_assoc($user_details_result);
            $user_location = $user_details_assoc["location"];

            $load_location_query = "SELECT * FROM location WHERE location='$user_location'";
            $load_location_result = mysqli_query($connection,$load_location_query);

            $load_location_attr = mysqli_fetch_assoc($load_location_result);
            $user_location_latitude = $load_location_attr["lat"];
            $user_location_longitude = $load_location_attr["lon"];

            $theta = $mechanic_location_longitude - $user_location_longitude;
            $distance = sin(deg2rad($mechanic_location_latitude)) * sin(deg2rad($user_location_latitude)) + cos(deg2rad($user_location_latitude)) * cos(deg2rad($mechanic_location_latitude)) * cos(deg2rad($theta));
            $distance = acos($distance);
            $distance = rad2deg($distance);
            $distance = $distance * 60 * 1.1515;
            $distance = round($distance * 1.609344,2);
            if ($distance <= $filter_distance) {
                $output .= '
                <div class="col-md-4 as">
                    <div class="card whole">
                        <div class="detail">
                            <div class="left">
                                <img src="'.$user_details_assoc['url'].'" class="img" alt="">
                            </div>
                            <div class="right">
                                <div>
                                    <label class="set_title">Name</label>
                                        <div class="sub_title">
                                            <input style="border: none; width: 100%; outline: none;" type="text" value="'. $user_details_assoc['name'].'" readonly>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="set_title">Contact</label>
                                        <div class="sub_title">
                                            <input style="border: none; width: 100%; outline: none;" type="text" value="'.$user_details_assoc['contact'].'" readonly>
                                        </div>
                                    </div>
                                
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <div class="body_left">
                                    <div>
                                        <label class="set_title">Location</label>
                                        <div class="sub_title">
                                            <input style="border: none; width: 100%; outline: none;" type="text" value="'.$load_location_attr["location"].'" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="body_right">
                                    <div>
                                        <label class="set_title">Distance</label>
                                        <div class="sub_title">
                                            <input style="border: none; width: 100%; outline: none;" type="text" value="'. $distance.' Km" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div style="width: 100%;">
                                    <label class="set_title">Purpose</label>
                                    <div >
                                        <textarea style="width: 100%; border: none; outline: none; resize: none;" readonly>'.$user_select_attr["purpose"].'</textarea>
                                    </div>
                                </div>
                                <div style="width: 100%;">
                                    <div class="body_left">
                                        <button class="closeb decline" id="'.$user_details_assoc["id"].'">
                                            <i class="fa fa-close"></i>	
                                        Decline</button>
                                    </div>
                                    <div class="body_right">
                                        <button class="confirmb confirm" id="'. $user_details_assoc["id"].'">
                                            <i class="fa fa-check"></i>
                                        Confirm</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }
        echo $output;
    }

    public function getAck() {
        include 'dbconn.php';

        $mech_id = $this->input->post("id");
        $user_id = $this->input->post("userid");

        $mechanic_details_query = "SELECT * FROM users WHERE id='$mech_id'";
        $mechanic_details_result = mysqli_query($connection,$mechanic_details_query);

        $mechanic_details_attr = mysqli_fetch_assoc($mechanic_details_result);
        
        $task_details_query = "SELECT * FROM invitation WHERE userid='$user_id' and mechid='$mech_id'";
        $task_details_result = mysqli_query($connection,$task_details_query);
        
        $task_details_assoc = mysqli_fetch_assoc($task_details_result);

        $details_json = array(
            "name" => $mechanic_details_attr["name"],
            "occupation" => $mechanic_details_attr["occupation"],
            "amount" => $mechanic_details_attr["amount"],
            "location" => $mechanic_details_attr["location"],
            "purpose" => $task_details_assoc["purpose"]
        );

        echo json_encode($details_json);
    }

    public function deleteTask() {
        include 'dbconn.php';

        $mechid = $this->input->post("mechid");
        $userid = $this->input->post("userid");
        $output = '';

        $delete_accepted_query = "DELETE FROM accepted WHERE userid='$userid' AND mechid='$mechid'";
        $delete_accepted_result = mysqli_query($connection,$delete_accepted_query);

        if ($delete_accepted_result) {
            $get_invitation_query = "SELECT * FROM invitation WHERE userid='$userid' AND mechid='$mechid'";
            $get_invitation_result = mysqli_query($connection,$get_invitation_query);

            $get_invitation_attr = mysqli_fetch_assoc($get_invitation_result);
            $date = $get_invitation_attr["date"];
            $purpose = $get_invitation_attr["purpose"];

            $delete_invitation_query = "DELETE FROM invitation WHERE userid='$userid' AND mechid='$mechid'";
            $delete_invitation_result = mysqli_query($connection,$delete_invitation_query);

            if ($delete_invitation_result) {
                //$output .= 'ok';
                $update_delete_query = "INSERT INTO `deletetask`(`mechid`, `userid`, `date`, `purpose`) VALUES ('$mechid','$userid','$date','$purpose')";
                $update_delete_result = mysqli_query($connection,$update_delete_query);

                if ($update_delete_result) {
                    $output .= 'ok';
                } else {
                    $output .= 'fail';
                }

            } else {
                $output .= 'fail';
            }
        } else {
            $output .= 'fail';
        }

        echo $output;

        //check if the requst is accepted
        /*$check_request_query = "SELECT * FROM accepted WHERE userid='$userid' AND mechid='$mechid'";
        $check_request_result = mysqli_query($connection,$check_request_query);

        $check_request_count = mysqli_num_rows($check_request_result);
        //echo $check_request_count;
        if ($check_request_count == 0) {
            //delete the send invitation to the mechanic
            $delete_invitation_query = "DELETE FROM invitation WHERE userid='$userid' AND mechid='$mechid'";
            $delete_invitation_result = mysqli_query($connection,$delete_invitation_query);

            if ($delete_invitation_result) {
                $output .= 'ok';
            } else {
                $output .= 'fail';
            }
        } else {
            $output .= 'send';
        }

        echo $output;*/
    }

    public function finishTask() {
        include 'dbconn.php';

        $userid = $this->input->post("userid");
        $mechid = $this->input->post("mechid");
        $purpose = $this->input->post("purpose");
        $rating = $this->input->post("rating");
        $output = '';

        $check_accepted_query = "SELECT * FROM accepted WHERE userid='$userid' AND mechid='$mechid'";
        $check_accepted_result = mysqli_query($connection,$check_accepted_query);

        $check_accepted_count = mysqli_num_rows($check_accepted_result); 
        if ($check_accepted_count > 0) {
            $complete_task_query = "INSERT INTO `accomplished`(`userid`, `mechid`, `purpose`,`rate`) VALUES ('$userid','$mechid','$purpose','$rating')";
            $complete_task_result = mysqli_query($connection,$complete_task_query);

            if ($complete_task_result) {
                $delete_accepted_query = "DELETE FROM accepted WHERE userid='$userid' AND mechid='$mechid'";
                $delete_accepted_result = mysqli_query($connection,$delete_accepted_query);

                if ($delete_accepted_result) {
                    $delete_invitation_query = "DELETE FROM invitation WHERE userid='$userid' AND mechid='$mechid'";
                    $delete_invitation_result = mysqli_query($connection,$delete_invitation_query);

                    if ($delete_invitation_result) {
                        $output .= 'ok';
                    } else {
                        $delete_complete_query = "DELETE FROM accomplished WHERE userid='$userid' AND mechid='$mechid'";
                        $delete_complete_result = mysqli_query($connection,$delete_complete_query);

                        if ($delete_complete_result) {
                            $output .= 'fail';
                        }

                        $insert_accept_query = "INSERT INTO accepted (userid,mechid) VALUES ('$userid','$mech_id')";
                        $insert_accept_result = mysqli_query($connection,$insert_accept_query);
                        
                    }
                } else {
                    $delete_complete_query = "DELETE FROM accomplished WHERE userid='$userid' AND mechid='$mechid'";
                    $delete_complete_result = mysqli_query($connection,$delete_complete_query);

                    if ($delete_complete_result) {
                        $output .= 'fail';
                    }
                }
            } else {
                $output .= 'fail';
            }
        } else {
            $output .= 'send';
        }
        echo $output;
    }

    public function deleteAccepted() {
        include 'dbconn.php';

        $userid = $this->input->post("userid");
        $mechid = $this->input->post("mechid");

        $user_details_query = "SELECT * FROM user WHERE id='$userid'";
        $user_details_result = mysqli_query($connection,$user_details_query);

        $user_details_attr = mysqli_fetch_assoc($user_details_result);

        $purpose_details_query = "SELECT * FROM invitation WHERE userid='$userid' AND mechid='$mechid'";
        $purpose_details_result = mysqli_query($connection,$purpose_details_query);

        $purpose_details_attr = mysqli_fetch_assoc($purpose_details_result);

        $delete_accepted = array(
            "name" => $user_details_attr["name"],
            "location" => $user_details_attr["location"],
            "purpose" => $purpose_details_attr["purpose"],
            "url" => $user_details_attr["url"]
        );

        echo json_encode($delete_accepted);
    }

    public function deleteAcceptedRequest() {
        include 'dbconn.php';

        $mechid = $this->input->post("mechid");
        $userid = $this->input->post("userid");
        $reason = $this->input->post("reason");
        $output = '';

        $delete_invitation_query = "DELETE FROM invitation WHERE userid='$userid' AND mechid='$mechid'";
        $delete_invitation_result = mysqli_query($connection,$delete_invitation_query);

        if ($delete_invitation_result) {
            $delete_accepted_query = "DELETE FROM accepted WHERE userid='$userid' AND mechid='$mechid'";
            $delete_accepted_result = mysqli_query($connection,$delete_accepted_query);

            if ($delete_accepted_result) {
                $insert_request_query = "INSERT INTO `deleted`(`userid`, `mechid`, `purpose`) VALUES ('$userid','$mechid','$reason')";
                $insert_request_result = mysqli_query($connection,$insert_request_query);

                if ($insert_request_result) {
                    $output = 'ok';
                } else {
                    $output = 'fail';
                }

            } else {
                $output = 'fail';
            }

        } else {
            $output = 'fail';
        }
        echo $output;
    }

    public function getInvDate() {
        include 'dbconn.php';

        $userid = $this->input->post("id");
        $mechid = $this->input->post("mechid");

        $select_invitation_query = "SELECT * FROM invitation WHERE userid='$userid' AND mechid='$mechid'";
        $select_invitation_result = mysqli_query($connection,$select_invitation_query);

        $select_invitation_attr = mysqli_fetch_assoc($select_invitation_result);
        $json = array(
            "date" => $select_invitation_attr["date"],
            "purpose" => $select_invitation_attr["purpose"]
        );
        echo json_encode($json);
    }

    public function mydate() {
        include 'dbconn.php';

        $mechid = $this->input->post("mechid");
        $userid = $this->input->post("userid");
        $mydate = $this->input->post("date");

        $check_commit_query = "SELECT * FROM accepted WHERE mechid='$mechid' AND date='$mydate'";
        $check_commit_result = mysqli_query($connection,$check_commit_query);

        $check_commit_count = mysqli_num_rows($check_commit_result);
        if ($check_commit_count == 0) {
            echo "ok";
        } else {
            echo "fail";
        }
    }
}