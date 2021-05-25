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
            $details_query = "SELECT * FROM user WHERE id='$user_id'";
            $details_result = mysqli_query($connection,$details_query);
            $details_attr = mysqli_fetch_assoc($details_result);
        ?>

        <nav  class="navbar navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand" href="#">
                <img style="width: 40px; height: 40px; border-radius: 50%;" src="<?php echo $details_attr["url"]; ?>" alt="">
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
                        <a class="nav-link ho" href="../home/welcome" style="font-size: 18px;">Mechanic Near Me</a>
                    </li>
                    <li class="nav-item active list">
                        <a class="nav-link" href="../home/myrequest" style="font-size: 18px; color: #59bac9;">My Requests</a>
                    </li>
                    <li class="nav-item active list">
                        <a class="nav-link ho" href="../home/usercompleted" style="font-size: 18px;">Completed</a>
                    </li>
                    <li class="nav-item active list">
                        <a class="nav-link" href="../home/logout" style="font-size: 18px;">
                            <i class="fa fa-sign-out"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    
        <input type="hidden" id="userid" value="<?php echo $user_id; ?>">
        <input type="hidden" id="mechid">
        <input type="hidden" id="purp">
        <?php
        //0111111
        $select_invitation_query = "SELECT * FROM invitation WHERE userid='$user_id'";
        $select_invitation_result = mysqli_query($connection,$select_invitation_query);

        while ($select_invitation_attr = mysqli_fetch_array($select_invitation_result)) {
            $mechanic_id = $select_invitation_attr["mechid"];

            $select_mechanic_query = "SELECT * FROM users WHERE id='$mechanic_id'";
            $select_mechanic_result = mysqli_query($connection,$select_mechanic_query);

            $select_mechanic_attr = mysqli_fetch_assoc($select_mechanic_result);

            ?> 
            <div class="container  all">
                <div class="card contain">
                    <div class="card-header head">
                        <?php echo $select_invitation_attr["purpose"]; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $select_mechanic_attr["occupation"]; ?></h5>
                        <div class="whole">
                            <div class="left_body">
                                <div>
                                    <div class="cont">
                                        <label class="title">Name</label>
                                        <div>
                                            <input type="text" value="<?php echo $select_mechanic_attr["name"]; ?>" class="input" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="cont">
                                        <label class="title">From</label>
                                        <div>
                                            <input type="text" value="<?php echo $select_mechanic_attr["location"]; ?>" class="input" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="right_body">
                                <div class="left_content">
                                    <div>
                                        <div class="cont">
                                            <label class="title">Average Amount</label>
                                            <div>
                                                <input type="text" value="<?php echo $select_mechanic_attr["amount"]; ?>" class="input" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="cont">
                                            <label class="title">Status</label>
                                            <?php 
                                            
                                            $is_accepted_query = "SELECT * FROM accepted WHERE userid='$user_id' AND mechid='$mechanic_id'";
                                            $is_accepted_result = mysqli_query($connection,$is_accepted_query);

                                            $is_accepted_assoc = mysqli_num_rows($is_accepted_result);

                                            if ($is_accepted_assoc > 0) {
                                                ?>
                                                <div>
                                                    <input type="text" value="Work Pending/ inProgress" class="input" readonly>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div>
                                                    <input type="text" value="Work Not Accepted" class="input" readonly>
                                                </div>
                                                <?php
                                            }
                                            
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="right_content">
                                    <img class="profile" src="<?php echo $select_mechanic_attr["url"]; ?>">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div>
                        <div class="leftb contb">
                            <button class="decline dec" id="<?php echo $mechanic_id; ?>">Decline</button>
                        </div>
                        <div class="rightb contb">
                            <button class="done comp" id="<?php echo $mechanic_id; ?>">Job Done</button>
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
                            <div style="float: left;width: 80%;">
                                <h4 class="modal-title">Decline Mechanic</h4>
                            </div>
                            <div style="float: right; width: 20%;">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="body_left">
                                    <div>
                                        <img id="profile" class="dp" style="width:100%; height:100%; border-radius: 50%;" src="https://www.clker.com/cliparts/4/4/9/5/15166963261736316638clipart-of-electrician.med.png" alt="dp">
                                    </div>
                                    <div class="form-group">
                                        <div style="width: 100%; padding-left: 10px;">
                                            <button type="button" class="decline declineb" data-dismiss="modal"> &times; Close</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="body_right">
                                    <div class="form-group">
                                    <label><b>Name</b></label>
                                    <input type="text" class="form-control" id="name" readonly>
                                </div>
                                <div class="form-group">
                                    <label><b>Location</b></label>
                                    <input type="text" class="form-control" id="location" readonly>
                                </div>
                                <div class="form-group">
                                    <label><b>Occupation</b></label>
                                    <input type="text" class="form-control" id="occupation" readonly>
                                </div>
                                <div class="form-group">
                                    <label><b>Average Amount</b></label>
                                    <input type="text" class="form-control" id="amount" readonly>
                                </div>
                                <div class="form-group">
                                    <label><b>Purpose/Work</b></label>
                                    <input type="text" class="form-control" id="purpose" readonly>
                                </div>
                                <div style="width: 100%;">
                                        <button class="done confirmb" onclick="deleteTask()">Confirm</button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="modal fade" id="task" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div style="float: left; width: 805;">
                                <h4>Task Completed</h4>
                            </div>
                            <div style="float: right; width: 20%;">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div style="display: flex; justify-content: center;">
                                <p id="purpose_dialog"></p>
                            </div>
                            <div class="form-group">
                                <label style="font-size: 18px;">Rating</label>
                                <select class="form-control" id="rating">
                                    <option value="" selected>-- Ratings --</option>
                                    <option value="1" >1</option>
                                    <option value="2" >2</option>
                                    <option value="3" >3</option>
                                    <option value="4" >4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div style="display: flex; justify-content: center;">
                                <p style="color: red;" id="warn"></p>
                            </div>
                            <div style="width: 100%;">
                                <div style="width: 50%; float: left; padding-right: 5px;">
                                    <button class="decline no" data-dismiss="modal">&times; No</button>
                                </div>
                                <div style="width: 50%; float: right; padding-left: 5px;">
                                    <button type="button" class="done yes" onclick="taskCompleted()">Completed</button>
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
    <script>

        function _(el) {
            return document.getElementById(el);
        }

        $(document).on('click','.dec',function() {
            var id = $(this).attr('id');
            var userid = _("userid").value;
            _("mechid").value = id;
            //alert(id);
            $.ajax({
                url:"<?php echo base_url(); ?>index.php/home/getAck",
                method:"POST",
                data:{id:id,userid:userid},
                success:function(data) {
                    var recieved = JSON.parse(data);
                    _("name").value = recieved.name;
                    _("location").value = recieved.location;
                    _("occupation").value = recieved.location;
                    _("amount").value = recieved.amount;
                    _("purpose").value = recieved.purpose;
                    $("#myModal").modal('toggle');
                }
            });
        });

        $(document).on('click','.comp',function() {
            var id = $(this).attr('id');
            _("mechid").value = id;
            var userid = _("userid").value;
            $.ajax({
                url:"<?php echo base_url(); ?>index.php/home/getAck",
                method:"POST",
                data:{id:id,userid:userid},
                success:function(data) {
                    var recieved = JSON.parse(data);
                    _("purp").value = recieved.purpose;
                    _("purpose_dialog").innerHTML = recieved.purpose;
                    $("#task").modal('toggle');
                }
            });
            
        });

        function deleteTask() {
            var mechid = _("mechid").value;
            var userid = _("userid").value;
            //alert(id);
            $.ajax({
                url:"<?php echo base_url(); ?>index.php/home/deleteTask",
                method:"POST",
                data:{mechid,mechid,userid:userid},
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

        function taskCompleted() {
            var rating = _("rating").value;
            if (rating == "") {
                //alert("Fail out");
                _("warn").innerHTML= "* Please Rate this Mechanic";
            } else {
                var userid = _("userid").value;
                var mechid = _("mechid").value;
                var purpose = _("purp").value;
                //alert(purpose);
                $.ajax({
                    url:"<?php echo base_url(); ?>index.php/home/finishTask",
                    method:"POST",
                    data:{userid:userid,mechid:mechid,purpose:purpose,rating:rating},
                    success:function(data) {
                        $("#task").modal('hide');
                        //alert(data);
                        if (data == "send") {
                            $("#status_img").attr('src','https://pics.clipartpng.com/midle/Danger_Warning_Sign_PNG_Clipart-812.png');
                            _("status_p").innerHTML = "Invitation is not Accepted, can't mark Job as Completed.";
                            _("status_head").innerHTML = "Invitation not Accepted";
                            _("status_head").style.color = "#f2d00f";
                        } else if(data == "ok") {
                            $("#status_img").attr('src','https://www.bartsguild.org.uk/wp-content/uploads/2017/06/Green-tick-check-mark-tick-green-clipart-free-to-use-clip-art-resource-1024x1024.png');
                            _("status_p").innerHTML = "Job marked as DONE Successfully";
                            _("status_head").innerHTML = "Successfull";
                            _("status_head").style.color = "#3dc938";
                        } else if (data == "fail") {
                            $("#status_img").attr('src','https://www.freeiconspng.com/uploads/x-png-33.png');
                            _("status_p").innerHTML = "Job marked as DONE Failed";
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