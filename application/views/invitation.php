<?php 
	include 'dbconnector.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Requests</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href= "<?php echo base_url();?>/css/invi.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/icon" href="<?php echo base_url(); ?>/icons/logo.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<?php 
		$details_query = "SELECT * FROM users WHERE id='$id_user'";
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
					<a class="nav-link ho" href="../home/mechdashboard" style="font-size: 18px; color: #59bac9;">Invitations</a>
				</li>
				<li class="nav-item active list">
					<a class="nav-link" href="../home/mechanicTask" style="font-size: 18px;">Accepted</a>
				</li>
				<li class="nav-item active list">
					<a class="nav-link" href="../home/mechanicCompleted" style="font-size: 18px;">Completed</a>
				</li>
				<li class="list">
					<select class="bg-dark" id="filter" onchange="filterLocation()">
					<option value="2">2 KM</option>
					<option value="5">5 KM</option>
					<option value="10">10 KM</option>
					<option value="25">25 KM</option>
					<option value="50">50 KM</option>
					<option value="100" selected="">100 KM</option>
				</select>
				</li>
				<li class="nav-item active list">
					<a class="nav-link" href="../home/logout" style="font-size: 18px;">
						<i class="fa fa-sign-out"></i>Logout</a>
				</li>
			</ul>
		</div>
	</nav>

	<input type="hidden" id="userid" value="<?php echo $id_user; ?>">
	<input type="hidden" id="mechid" value="<?php echo $id_user; ?>">
	<input type="hidden" id="locationid" value="<?php echo $details_attr["location"]; ?>">
	<div class="container py-5">
		<div class="row" id="data">
			<?php 

			$filter_distance = 100;
			$filter_place = $details_attr["location"];
			$mechanic_location_latitude;
			$mechanic_location_longitude;

			$mechanic_location_query = "SELECT * FROM location WHERE location='$filter_place'";
            $mechanic_location_result = mysqli_query($connection,$mechanic_location_query);
            
            $mechanic_location_attr = mysqli_fetch_assoc($mechanic_location_result);
            $mechanic_location_latitude = $mechanic_location_attr["lat"];
            $mechanic_location_longitude = $mechanic_location_attr["lon"];

            $user_location_latitude;
            $user_location_longitude;

            $user_select_query = "SELECT * FROM invitation WHERE mechid='$id_user'";
            $user_select_result = mysqli_query($connection,$user_select_query);

            while ($user_select_row = mysqli_fetch_array($user_select_result)) {
                $user_select_id = $user_select_row["userid"];

                $user_details_query = "SELECT * FROM user WHERE id='$user_select_id'";
                $user_details_result = mysqli_query($connection,$user_details_query);

                $user_details_attr = mysqli_fetch_assoc($user_details_result);

                $user_location_lontitude;
                $user_location_latitude;

                $user_location = $user_details_attr["location"];

                $load_location_query = "SELECT * FROM location WHERE location='$user_location'";
                $load_location_result = mysqli_query($connection,$load_location_query);

                $load_location_attr = mysqli_fetch_assoc($load_location_result);
                $user_location_latitude = $load_location_attr['lat'];
                $user_location_longitude = $load_location_attr['lon'];

                $theta = $mechanic_location_longitude - $user_location_longitude;
                $distance = sin(deg2rad($mechanic_location_latitude)) * sin(deg2rad($user_location_latitude)) + cos(deg2rad($mechanic_location_latitude)) * cos(deg2rad($user_location_latitude)) * cos(deg2rad($theta));
                $distance = acos($distance);
                $distance = rad2deg($distance);
                $distance = $distance * 60 * 1.1515;
                $distance = round($distance * 1.609344,2);
                if ($distance <= $filter_distance) {
                    ?>
                    	<div class="col-md-4 as">
                    		<div class="card whole">
                    			<div class="detail">
                    				<div class="left">
                    					<img src="<?php echo $user_details_attr['url']; ?>" class="img" alt="">
                    				</div>
                    				<div class="right">
                    					<div>
											<label class="set_title">Name</label>
											<div class="sub_title">
												<input style="border: none; width: 100%; outline: none;" type="text" value="<?php echo $user_details_attr["name"]; ?>" readonly>
											</div>
										</div>
											<div>
											<label class="set_title">Contact</label>
											<div class="sub_title">
												<input style="border: none; width: 100%; outline: none;" type="text" value="<?php echo $user_details_attr["contact"] ?>" readonly>
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
													<input style="border: none; width: 100%; outline: none;" type="text" value="<?php echo $load_location_attr["location"]; ?>" readonly>
												</div>
											</div>
                						</div>
                						<div class="body_right">
                							<div>
												<label class="set_title">Distance</label>
												<div class="sub_title">
													<input style="border: none; width: 100%; outline: none;" type="text" value="<?php echo $distance.' Km'; ?>" readonly>
												</div>
											</div>
                						</div>
										
										<div style="width: 100%;">
											<label class="set_title">Date</label>
											<input type="date" style="border: none; outline: none;" value="<?php echo $user_select_row["date"]; ?>" readonly>
										</div>
                						<div style="width: 100%;">
                							<label class="set_title">Purpose</label>
                							<div >
                								<textarea style="width: 100%; border: none; outline: none; resize: none;" readonly><?php echo $user_select_row["purpose"]; ?></textarea>
                							</div>
                						</div>
                						<div style="width: 100%;">
                							<div class="body_left">
                								<button class="closeb decline" id="<?php echo $user_details_attr["id"]; ?>">
                									<i class="fa fa-close"></i>	
                								Decline</button>
                							</div>
                							<div class="body_right">
                								<button class="confirmb confirm" id="<?php echo $user_details_attr["id"]; ?>">
                									<i class="fa fa-check"></i>
                								Confirm</button>
                							</div>
                						</div>
                					</div>
                				</div>
                    		</div>
                    	</div>
                    <?php
                }
            }
            
			?>
		</div>
	</div>
	<div class="container">
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div style="float: left; width: 80%;">
							<h4 id="pop_head" class="modal-title">Accept Job</h4>
						</div>
						<div style="float: right;width: 20%;">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<div>
							<div class="body_left">
								<div>
									<img src="" id="profile" style="width: 100%; border-radius: 50%;" alt="">
								</div>
								<div class="form-group">
									<div style="width: 100%; padding-left: 10px;">
										<button type="button" onclick="cancelTask()" class="closeb" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
							<div class="body_right">
								<div class="form-group">
									<label>Name</label>
									<input type="text" id="name" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label>Contact</label>
									<input type="text" id="contact" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label>Location</label>
									<input type="text" id="location" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label>Date</label>
									<input type="date" id="datepop" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label>Purpose</label>
									<input type="text" id="purpose" class="form-control" readonly>
								</div>
								<div style="width: 100%;" class="form-group">
									<button type="button" onclick="checkDate()" class="confirmb">Confirm</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="modal fade" id="deleteModal" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div style="float: left; width: 80%;">
							<h4 id="delete_head" class="modal-title">Accept Job</h4>
						</div>
						<div style="float: right;width: 20%;">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<div>
							<div class="body_left">
								<div>
									<img src="" id="profile_delete" style="width: 100%; border-radius: 50%;" alt="">
								</div>
								<div class="form-group">
									<div style="width: 100%; padding-left: 10px;">
										<button type="button" class="closeb" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
							<div class="body_right">
								<div class="form-group">
									<label>Name</label>
									<input type="text" id="deletename" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label>Contact</label>
									<input type="text" id="deletecontact" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label>Location</label>
									<input type="text" id="deletelocation" class="form-control" readonly>
								</div>
								<div style="width: 100%;" class="form-group">
									<button type="button" onclick="deleteTask()" class="confirmb">Confirm</button>
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
							<h4 class="modal-title" id="status_head">Decline Job/Task</h4>
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
	
	function executeOperation(id) {
		//window.alert(id);
		$.ajax({
			url:"<?php echo base_url(); ?>index.php/home/getInviter",
			method:"POST",
			data:{id:id},
			success:function(data) {
				var decodeJSON = JSON.parse(data);
				//alert(data);
				_("name").value = decodeJSON.name;
				_("deletename").value = decodeJSON.name;
				_("contact").value = decodeJSON.contact;
				_("deletecontact").value = decodeJSON.contact;
				_("location").value = decodeJSON.location;
				_("deletelocation").value = decodeJSON.location;
				$("#profile").attr("src",decodeJSON.url);
				$("#profile_delete").attr("src",decodeJSON.url);
			}
		});
		var mechid = _("mechid").value;
		$.ajax({
			url:"<?php echo base_url(); ?>index.php/home/getInvDate",
			method:"POST",
			data:{id:id,mechid:mechid},
			success:function(data) {
				var decodeJSON = JSON.parse(data);
				_("datepop").value = decodeJSON.date;
				_("purpose").value = decodeJSON.purpose;
			}
		});
	}

	$(document).on('click','.confirm',function() {
		var id = $(this).attr('id');
		_("userid").value = id;
		_("pop_head").innerHTML = "Accept Job";
		executeOperation(id);
		$("#myModal").modal('toggle');
	});

	$(document).on('click','.decline',function() {
		var id = $(this).attr('id');
		_("userid").value = id;
		_("delete_head").innerHTML = "Decline Request";
		executeOperation(id);
		$("#deleteModal").modal('toggle');
	});

	function confirmTask() {
		var mechid = _("mechid").value;
		var userid = _("userid").value;
		var date = _("datepop").value;
		//alert(userid + " " + mechid);
		$.ajax({
			url:"<?php echo base_url(); ?>index.php/home/uploadAccept",
			method:"POST",
			data:{userid:userid,mechid:mechid,date:date},
			success:function(data) {
				$("#myModal").modal('hide');
					if (data == "send") {
						$("#status_img").attr('src','https://pics.clipartpng.com/midle/Danger_Warning_Sign_PNG_Clipart-812.png');
						_("status_p").innerHTML = "Request Accepted Already";
						_("status_head").innerHTML = "Already Accepted";
						_("status_head").style.color = "#f2d00f";
					} else if(data == "ok") {
						$("#status_img").attr('src','https://www.bartsguild.org.uk/wp-content/uploads/2017/06/Green-tick-check-mark-tick-green-clipart-free-to-use-clip-art-resource-1024x1024.png');
						_("status_p").innerHTML = "Request Accepted Successfully";
						_("status_head").innerHTML = "Successfull";
						_("status_head").style.color = "#3dc938";
					} else if (data == "fail") {
						$("#status_img").attr('src','https://www.freeiconspng.com/uploads/x-png-33.png');
						_("status_p").innerHTML = "Failed to Accept Request";
						_("status_head").innerHTML = "Failed";
						_("status_head").style.color = "#db1818";
					}
					$("#status").modal('toggle');
			}
		});
	}

	function deleteTask() {
		var mechid = _("mechid").value;
		var userid = _("userid").value;
		//alert(mechid + " " + userid);
		$.ajax({
			url:"<?php echo base_url(); ?>index.php/home/deleteRequest",
			method:"POST",
			data:{userid:userid,mechid:mechid},
			success:function(data) {
				$("#deleteModal").modal('hide');
				if (data == "send") {
					$("#status_img").attr('src','https://pics.clipartpng.com/midle/Danger_Warning_Sign_PNG_Clipart-812.png');
					_("status_p").innerHTML = "Request Accepted Already, Decline the invitation from Tasks";
					_("status_head").innerHTML = "Already Accepted";
					_("status_head").style.color = "#f2d00f";
				} else if(data == "ok") {
					$("#status_img").attr('src','https://www.bartsguild.org.uk/wp-content/uploads/2017/06/Green-tick-check-mark-tick-green-clipart-free-to-use-clip-art-resource-1024x1024.png');
					_("status_p").innerHTML = "Request Declined Successfully";
					_("status_head").innerHTML = "Successfull";
					_("status_head").style.color = "#3dc938";
				} else if (data == "fail") {
					$("#status_img").attr('src','https://www.freeiconspng.com/uploads/x-png-33.png');
					_("status_p").innerHTML = "Failed to Decline Request";
					_("status_head").innerHTML = "Failed";
					_("status_head").style.color = "#db1818";
				} else {
					alert(data);
				}
				$("#status").modal('toggle');
			}
		});
	}

	function filterLocation() {
		var mechid = _("mechid").value;
		var userid = _("userid").value;
		var distance = _("filter").value;
		var location = _("locationid").value;
		$.ajax({
			url:"<?php echo base_url(); ?>index.php/home/filterMech",
			method:"POST",
			data:{mechid:mechid,userid:userid,distance:distance,location:location},
			success:function(data) {
				$('#data').html(data);
			}
		});
	}

	function checkDate() {
		var mechid = _("mechid").value;
		var userid = _("userid").value;
		var date = _("datepop").value;

		//alert(mechid + " " + userid);
		$.ajax({
			url:"<?php echo base_url(); ?>index.php/home/mydate",
			method:"POST",
			data:{mechid:mechid,userid:userid,date:date},
			success:function(data) {
				alert(data);
				if (data == "ok") {
					confirmTask();
				} else {
					if (confirm("You have a task fixed on particular date, Do you wish to Accept the request?")) {
						confirmTask();
					} else {
						if (confirm("Do you wish to delete this Request?")) {
							deleteTask();
						} else {
							$("#myModal").modal('hide');
						}
					}
				}
			}
		});
	}

</script>
</html>