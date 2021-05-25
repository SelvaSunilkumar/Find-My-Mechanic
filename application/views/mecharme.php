<?php 
	include 'dbconnector.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Mechanic Near Me</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href= "<?php echo base_url(); ?>/css/mecharme.css">
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
		$details_query = "SELECT * FROM user WHERE id='$id_user'";
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
					<a class="nav-link ho" href="../home/mechdashboard" style="font-size: 18px; color: #59bac9;">Mechanic Near Me</a>
				</li>
				<li class="nav-item active list">
					<a class="nav-link" href="../home/myrequest" style="font-size: 18px;">My Requests</a>
				</li>
				<li class="nav-item active list">
					<a class="nav-link" href="../home/usercompleted" style="font-size: 18px;">Completed</a>
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
	<input type="hidden" id="locationid" value="<?php echo $details_attr["location"];?>">
 	<div class="container py-5">
		<div class="row" id="data">
            <?php 
            
                $filter_distance = 100;
				$filter_place;
				
				$filter_location_query = "SELECT * FROM user WHERE id='$id_user'";
				$filter_location_result = mysqli_query($connection,$filter_location_query);
				$filter_location_attr = mysqli_fetch_assoc($filter_location_result);
				$filter_place = $filter_location_attr["location"];

                $user_location_latitude;
                $user_location_longitude;

                $user_location_query = "SELECT * FROM location where location='$filter_place'";
                $user_location_result = mysqli_query($connection,$user_location_query);

                $user_location_row = mysqli_fetch_assoc($user_location_result);

                $user_location_latitude = $user_location_row["lat"];
                $user_location_longitude = $user_location_row["lon"];

                $mechanic_location_latitude;
                $mechanic_location_longitude;

				$mechanic_select_query = "SELECT * FROM users";
				$mechanic_select_result = mysqli_query($connection,$mechanic_select_query);
				while ($mechanic_select_row = mysqli_fetch_array($mechanic_select_result)) {

					//code for review
					$get_id = $mechanic_select_row["id"];
					$get_reviews_query = "SELECT * FROM accomplished WHERE mechid='$get_id'";
					$get_reviews_result = mysqli_query($connection,$get_reviews_query);

					$get_review_count = mysqli_num_rows($get_reviews_result);
					$get_review_sum = 0;
					while ($get_review_attr = mysqli_fetch_array($get_reviews_result)) {
						$rate = $get_review_attr["rate"];
						$get_review_sum = $get_review_sum + $rate;
					}

                $mechanic_location = $mechanic_select_row["location"];
                $mechanic_location_query = "SELECT * FROM location where location='$mechanic_location'";
                $mechanic_location_result = mysqli_query($connection,$mechanic_location_query);

                $mechanic_location_row = mysqli_fetch_assoc($mechanic_location_result);
                $mechanic_location_latitude = $mechanic_location_row["lat"];
                $mechanic_location_longitude = $mechanic_location_row["lon"];

                $theta = $user_location_longitude - $mechanic_location_longitude;
                $distance = sin(deg2rad($user_location_latitude)) * sin(deg2rad($mechanic_location_latitude)) + cos(deg2rad($user_location_latitude)) * cos(deg2rad($mechanic_location_latitude)) * cos(deg2rad($theta));
                $distance = acos($distance);
                $distance = rad2deg($distance);
                $distance = $distance * 60 * 1.1515;
                $distance = round($distance * 1.609344,2);
                if ($distance <= $filter_distance) {
					?>
					<div class="col-md-4 as">
						<div class="card whole">
							<div class="details">
								<div class="left">
									<img src="<?php echo $mechanic_select_row["url"]; ?>" class="img" alt="">
								</div>
								<div class="right">
									<div>
										<label class="set_title">Name</label>
										<div class="sub_title">
											<input style="border: none; width: 100%; outline: none;" type="text" value="<?php echo $mechanic_select_row["name"]; ?>" readonly>
										</div>
									</div>
									<div>
										<label class="set_title">Occupation</label>
										<div class="sub">
											<input type="text" style="border: none; width: 100%; outline: none;" name="" value="<?php echo $mechanic_select_row["occupation"]; ?>" readonly>
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
												<input style="border: none; width: 100%; outline: none;" type="text" name="" value="<?php echo $mechanic_select_row["location"]; ?>" readonly>
											</div>
										</div>
									</div>
									<div class="body_right">
										<div>
											<label class="set_title">Average Cost</label>
											<div>
												<input style="border: none; width: 100%; outline: none;" type="text" name="" value="<?php echo $mechanic_select_row["amount"]; ?>" readonly>
											</div>
										</div>
									</div>
								</div>
								<div>
									<label class="set_title">Distance</label>
									<input style="border: none; width: 50%; outline: none; float: right;" type="text" name="" value="<?php echo $distance.'KM'; ?>" readonly>
								</div>
								<div>
									<label class="set_title">Contact</label>
									<input style="border: none; width: 50%; outline: none; float: right;" type="text" name="" value="<?php echo $mechanic_select_row["contact"]; ?>" readonly>
								</div>
								<div>
									<label class="set_title">Rating</label>
									<div style="border: none; width: 50%; outline: none; float: right;" >
									<?php 
										if ($get_review_count == 0) {
											?> 
												<span class="fa fa-star checked"></span>
												<span class="fa fa-star checked"></span>
												<span class="fa fa-star checked"></span>
												<span class="fa fa-star"></span>
												<span class="fa fa-star"></span>
											<?php
										} else {
											$rating = round(($get_review_sum / $get_review_count),1);
											if ($rating <= 1.5) {
												?> 
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star checked"></span>
												<span class="fa fa-star checked"></span>
												<span class="fa fa-star"></span>
												<span class="fa fa-star"></span>
												<?php
											} else if ($rating > 1.5 && $rating <= 2.5) {
												?> 
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star checked"></span>
												<span class="fa fa-star"></span>
												<span class="fa fa-star"></span>
												<?php
											} else if ($rating > 2.5 && $rating <= 3.5) {
												?> 
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star"></span>
												<span class="fa fa-star"></span>
												<?php
											} else if ($rating > 3.5 && $rating <= 4.5) {
												?> 
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star" style="color: orange;"></span>
												<span class="fa fa-star"></span>
												<?php
											} else if ($rating > 4.5) {
												?> 
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star checked" style="color: orange;"></span>
												<span class="fa fa-star" style="color: orange;"></span>
												<span class="fa fa-star" style="color: orange;"></span>
												<?php
											}
										}
									?>
										
										(<?php echo $get_review_count; ?>)
									</div>
								</div>
								<?php
									$invitation_check_query = "SELECT * FROM invitation WHERE userid='$id_user' AND mechid='$mechanic_select_row[id]'";
									$invitation_check_result = mysqli_query($connection,$invitation_check_query);
									$invitation_check_count = mysqli_num_rows($invitation_check_result);

									$accpted_invitation_query = "SELECT * FROM accepted WHERE userid='$id_user' AND mechid='$mechanic_select_row[id]'";
									$accpted_invitation_result = mysqli_query($connection,$accpted_invitation_query);
									$accpted_invitation_count = mysqli_num_rows($accpted_invitation_result);

									if ($accpted_invitation_count > 0) {
										?>
											<div class="bottom">
												<button type="submit" style="background: #5ad44c;" class="select" id="<?php echo $mechanic_select_row['id']; ?>">Work Pending</button>
											</div>
										<?php
									}
									else if ($invitation_check_count > 0) {
										?>
											<div class="bottom">
												<button type="submit" style="background: #e0dd1f;" class="select" id="<?php echo $mechanic_select_row['id']; ?>">Request Send</button>
											</div>
										<?php
									} else {
										?>
											<div class="bottom">
												<button type="submit" class="select" id="<?php echo $mechanic_select_row['id']; ?>">Send Request</button>
											</div>
										<?php
									}
								?>
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
						<div style="float: left; width: 80%">
							<h4 class="modal-title">Confirm Mechanic</h4>
						</div>
						<div style="float: right; width: 20%">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<div>
							<div class="body_left">
								<div >
									<img id="profile" style="width: 100%; border-radius: 50%;" src="" alt="">
								</div>
								<div class="form-group">
									<div style=" width: 100%; padding-left: 10px;">
										<button type="button" class="closeb" data-dismiss="modal">Close</button>
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
									<label><b>Pick your Date</b></label>
									<input type="date" class="form-control" id="date">
								</div>
								<div class="form-group">
									<label><b>Purpose/Work</b></label>
									<input type="text" class="form-control" id="purpose">
								</div>
								<div style="width: 100%;">
										<button class="confirmb" onclick="sendInvitation()">Confirm</button>
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
<script>

	function _(el) {
		return document.getElementById(el);
	}

	function filterLocation() {
		var userid = _("userid").value;
		var distance = _("filter").value;
		var location = _("locationid").value;
		//alert(location);
		$.ajax({
			url:"<?php echo base_url();?>index.php/home/fetch",
			method:"POST",
			data:{filter:distance,place:location,userid:userid},
			success:function(data) {
				$('#data').html(data);
			}
		});
	}

	$(document).on('click','.select',function() {
		var id = $(this).attr('id');
		//alert(id);
		_("mechid").value = id;
		/*var userid = document.getElementById("userid").value;
		alert(userid);*/
		$.ajax({
			url:"<?php echo base_url(); ?>index.php/home/getId",
			method:"POST",
			data:{id:id},
			success:function(data) {
				var json = JSON.parse(data);
				//alert(json.name);
				_("name").value = json.name;
				_("location").value = json.location;
				_("occupation").value = json.occupation;
				_("amount").value = json.amount;
				$("#profile").attr('src',json.url);
			}
		});
		$("#myModal").modal('toggle');
	});

	function sendInvitation() {
		var userid = _("userid").value;
		var mechid = _("mechid").value;
		var purpose = _("purpose").value;
		var date = _("date").value;

		if (purpose == "") {
			alert("Please Specify the purpose of Request");
		} else {
			$.ajax({
				url:"<?php echo base_url(); ?>index.php/home/sendrequest",
				method:"POST",
				data:{userid:userid,mechid:mechid,purpose:purpose,date:date},
				success:function(data) {
					//alert(data);
					//_("myModal").close();
					$("#myModal").modal('hide');
					if (data == "send") {
						$("#status_img").attr('src','https://pics.clipartpng.com/midle/Danger_Warning_Sign_PNG_Clipart-812.png');
						_("status_p").innerHTML = "Invitation Send Already";
						_("status_head").innerHTML = "Already Send";
						_("status_head").style.color = "#f2d00f";
					} else if(data == "ok") {
						$("#status_img").attr('src','https://www.bartsguild.org.uk/wp-content/uploads/2017/06/Green-tick-check-mark-tick-green-clipart-free-to-use-clip-art-resource-1024x1024.png');
						_("status_p").innerHTML = "Invitation Send Successfully";
						_("status_head").innerHTML = "Successfull";
						_("status_head").style.color = "#3dc938";
					} else if (data == "fail") {
						$("#status_img").attr('src','https://www.freeiconspng.com/uploads/x-png-33.png');
						_("status_p").innerHTML = "Invitation Send Failed";
						_("status_head").innerHTML = "Failed";
						_("status_head").style.color = "#db1818";
					}
					$("#status").modal('toggle');
				}
			})
		}
	}

</script>
</html>