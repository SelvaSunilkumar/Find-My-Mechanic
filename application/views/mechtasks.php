<?php 
    include 'dbconnector.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>My Requests</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href= "<?php echo base_url();?>/css/request.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/icon" href="<?php echo base_url(); ?>/icons/logo.png">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php 
            $details_query = "SELECT * FROM users WHERE id='$userid'";
            $details_result = mysqli_query($connection,$details_query);
            $details_attr = mysqli_fetch_assoc($details_result);
        ?>
        <nav  class="navbar navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand" href="#">
                <img class="dp" src="<?php echo $details_attr["url"]; ?>" alt="">
                <?php echo $details_attr["name"]; ?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div style="margin-left: 35%;">
                <img style="height: 50px;" src="<?php echo base_url(); ?>/icons/titleicon.png" alt="">
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active list">
                        <a class="nav-link ho" href="../home/mechdashboard" style="font-size: 18px;">Invitations</a>
                    </li>
                    <li class="nav-item active list">
                        <a class="nav-link" href="../home/mechanicTask" style="font-size: 18px; color: #59bac9;">Accepted</a>
                    </li>
                    <li class="nav-item active list">
                        <a class="nav-link" href="../home/mechanicCompleted" style="font-size: 18px;">Completed</a>
                    </li>
                    <li class="nav-item active list">
                        <a class="nav-link" href="../home/logout" style="font-size: 18px;">
                            <i class="fa fa-sign-out"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <input type="hidden" id="mechid" value="<?php echo $userid; ?>">
        <input type="hidden" id="userid">

        <?php 
        $select_accpeted_query = "SELECT * FROM accepted WHERE mechid='$userid'";
        $select_accpeted_result = mysqli_query($connection,$select_accpeted_query);

        while ($select_accpeted_row = mysqli_fetch_array($select_accpeted_result)) {
            $user_id = $select_accpeted_row["userid"];

            $get_user_details = "SELECT * FROM user WHERE id='$user_id'";
            $get_user_result = mysqli_query($connection,$get_user_details);

            $get_user_attr = mysqli_fetch_assoc($get_user_result);

            $select_purpose_query = "SELECT * FROM invitation WHERE userid='$user_id' AND mechid='$userid'";
            $select_purpose_result = mysqli_query($connection,$select_purpose_query);
            
            $select_purpose_attr = mysqli_fetch_assoc($select_purpose_result);

            $purpose = $select_purpose_attr["purpose"];
            //echo $purpose;
            ?> 
            <div class="container">
                <div class="card contain">
                    <div class="card-header" head>
                        <?php echo $purpose; ?>
                    </div>
                    <div class="card-body">
                        <div class="card-title"><?php echo $get_user_attr["name"]; ?></div>
                        <div class="whole">
                            <div class="left_body">
                                <div>
                                    <div class="cont">
                                        <label class="title">Location</label>
                                        <div>
                                            <input class="input" type="text" value="<?php echo $get_user_attr["location"]; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="right_body">
                                <div class="left_content">
                                    <div>
                                        <div class="cont">
                                            <label class="title">Contact No</label>
                                            <div>
                                                <input class="input" type="text" value="<?php echo $get_user_attr["contact"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="right_content">
                                    <img class="profile" src="<?php echo $get_user_attr["url"]; ?>">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div style="width: 80%;">
                                <button style="width: 100%;" class="decline decj" id="<?php echo $get_user_attr["id"]; ?>">Cancel JOB</button>
                            </div>
                        </div>
                    </div>     
                </div>
            </div>
            <?php
        }
        ?>

        <div class="container">
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div style="float: left; width: 80%;">
                                <h4 id="pop_head" class="modal-title">Delete Task</h4>
                            </div>
                            <div style="float: right; width: 20%;">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="body_left">
                                    <div>
                                        <img src="" id="profile" style="width: 100%; border-radius: 50%;" alt="display pic">
                                    </div>
                                    <div class="form-group">
                                        <div style="width: 100%; padding-left: 10px;">
                                            <button type="button" class="decline closeb" data-dismiss="modal">&times; Close</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="body_right">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" id="username" class="form-control" readonly="">
                                    </div>
                                    <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" id="location" class="form-control" readonly="">
                                    </div>
                                    <div class="form-group">
                                        <label>Purpose</label>
                                        <input type="text" id="purpose" class="form-control" readonly="">
                                    </div>
                                    <div class="form-group">
                                        <label>Reason of Cancellation</label>
                                        <input type="text" id="reason" class="form-control">
                                    </div>
                                    <div class="form-group" style="width: 100%;">
                                        <button type="button" class="done confirmb" onclick="sendDeleteRequest()">Confirm Deletion</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="modal fade" id="status" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div style="float: left; width: 80%">
                                <h4 class="modal-title" id="status_head">Confirm Mechanic</h4>
                            </div>
                            <div style="float: right; width: 20%">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div style="display: flex; justify-content: center;">
                                <img id="status_img" style="height: 50px; width: 50px;" src="https://www.bartsguild.org.uk/wp-content/uploads/2017/06/Green-tick-check-mark-tick-green-clipart-free-to-use-clip-art-resource-1024x1024.png">
                            </div>
                            <div style="width: 100%; text-align: center; padding-top: 15px;">
                                <p id="status_p"> Successfull</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript">

        function _(el) {
            return document.getElementById(el);
        }
        
        $(document).on('click','.decj',function() {
            //$("#myModal").modal('toggle');
            var userid = $(this).attr('id');
            var mechid = _("mechid").value;
            _("userid").value = userid;

            $.ajax({
                url:"<?php echo base_url(); ?>index.php/home/deleteAccepted",
                method:"POST",
                data:{mechid:mechid,userid:userid},
                success:function(data) {
                    var recieved = JSON.parse(data);
                    _("username").value = recieved.name;
                    _("location").value = recieved.location;
                    _("purpose").value = recieved.purpose;
                    $("#profile").attr('src',recieved.url);
                    $("#myModal").modal('toggle');
                }
            });
        });

        function sendDeleteRequest() {
            var reason = _("reason").value;

            if (reason == '') {
                alert("Please specify reason for Declining the request");
            } else {
                var mechid = _("mechid").value;
                var userid = _("userid").value;
                
                $.ajax({
                    url:"<?php echo base_url(); ?>index.php/home/deleteAcceptedRequest",
                    method:"POST",
                    data:{mechid:mechid,userid:userid,reason:reason},
                    success:function(data) {
                        $("#myModal").modal('hide');
                        //alert(data);
                        if (data == "send") {
                            $("#status_img").attr('src','https://pics.clipartpng.com/midle/Danger_Warning_Sign_PNG_Clipart-812.png');
                            _("status_p").innerHTML = "Invitation is Accepted";
                            _("status_head").innerHTML = "Already Accepted";
                            _("status_head").style.color = "#f2d00f";
                        } else if(data == "ok") {
                            $("#status_img").attr('src','https://www.bartsguild.org.uk/wp-content/uploads/2017/06/Green-tick-check-mark-tick-green-clipart-free-to-use-clip-art-resource-1024x1024.png');
                            _("status_p").innerHTML = "Invitation Deleted Successfully";
                            _("status_head").innerHTML = "Successfull";
                            _("status_head").style.color = "#3dc938";
                        } else if (data == "fail") {
                            $("#status_img").attr('src','https://www.freeiconspng.com/uploads/x-png-33.png');
                            _("status_p").innerHTML = "Invitation Delete Failed";
                            _("status_head").innerHTML = "Failed";
                            _("status_head").style.color = "#db1818";
                        }
                        $("#status").modal('toggle'); 
                    }
                });
            }
        }

    </script>
</html>