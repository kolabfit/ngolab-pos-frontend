<?php
require_once('./logic/loginvalidation.php');
Validation::validateLoginAdmin($_COOKIE['auth_token'], '/logic/login.php');

$url = 'https://ngolab.id/api/transactions/best-categories';

// Inisiasi cURL
$ch = curl_init($url);

// Set opsi cURL untuk mengirim request POST dengan JSON
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Set header untuk memberitahu bahwa kita mengirimkan JSON
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Content-Type: application/json',
	'Accept: application/json',
	'Authorization: ' . $_COOKIE['auth_token']
]);

// Eksekusi cURL dan ambil respons dari API
$response = curl_exec($ch);
// Decode response dari JSON ke array PHP
$kategoriTerlaris = json_decode($response, true);
curl_close($ch);

$url = 'https://ngolab.id/api/transactions/best-products';

// Inisiasi cURL
$ch = curl_init($url);

// Set opsi cURL untuk mengirim request POST dengan JSON
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Set header untuk memberitahu bahwa kita mengirimkan JSON
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Content-Type: application/json',
	'Accept: application/json',
	'Authorization: ' . $_COOKIE['auth_token']
]);

// Eksekusi cURL dan ambil respons dari API
$response = curl_exec($ch);
// Decode response dari JSON ke array PHP
$produkterlaris = json_decode($response, true);
curl_close($ch);

$url = 'https://ngolab.id/api/transactions/sales/day';

// Inisiasi cURL
$ch = curl_init($url);

// Set opsi cURL untuk mengirim request POST dengan JSON
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Set header untuk memberitahu bahwa kita mengirimkan JSON
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Content-Type: application/json',
	'Accept: application/json',
	'Authorization: ' . $_COOKIE['auth_token']
]);

// Eksekusi cURL dan ambil respons dari API
$response = curl_exec($ch);
// Decode response dari JSON ke array PHP
$salesInDay = json_decode($response, true);
curl_close($ch);

$url = 'https://ngolab.id/api/outlet/transactions/sales/day';

// Inisiasi cURL
$ch = curl_init($url);

// Set opsi cURL untuk mengirim request POST dengan JSON
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Set header untuk memberitahu bahwa kita mengirimkan JSON
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Content-Type: application/json',
	'Accept: application/json',
	'Authorization: ' . $_COOKIE['auth_token']
]);

// Eksekusi cURL dan ambil respons dari API
$response = curl_exec($ch);
// Decode response dari JSON ke array PHP
$salesInDayPerOutlet = json_decode($response, true);
curl_close($ch);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="KoLab">
	<meta name="robots" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">

	<!-- PAGE TITLE -->
	<title>Admin Dashboard</title>

	<!-- FAVICONS ICON -->
	<link rel="icon" type="image/png" href="/images/KoLab.png">

	<!-- Stylesheet -->
	<link rel="stylesheet" href="/vendor/jquery-nice-select/css/nice-select.css">
	<link rel="stylesheet" href="/vendor/owl-carousel/owl.carousel.css">
	<link rel="stylesheet" href="/vendor/chartist/css/chartist.min.css">
	<link rel="stylesheet" href="/vendor/nouislider/nouislider.min.css">

	<!-- Style css -->
	<link href="/css/style.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body data-page="index.php">

	<!--*******************
		Preloader start
	********************-->
	<div id="preloader">
		<div class="lds-ripple">
			<div></div>
			<div></div>
		</div>
	</div>
	<!--*******************
		Preloader end
	********************-->

	<!--**********************************
		Main wrapper start
	***********************************-->
	<div id="main-wrapper" class="show menu-toggle">

		<!--**********************************
			Nav header start
		***********************************-->
		<div class="nav-header">
			<a href="index.php" class="brand-logo">
				<img src="/images/KoLab.png" width="100vw" class="rounded-circle">
				<div class="brand-title">
					<h2 class="">Admin</h2>
				</div>
			</a>
			<div class="nav-control">
				<div class="hamburger" id="hamburger-btn">
					<span class="line"></span><span class="line"></span><span class="line"></span>
				</div>
			</div>
		</div>
		<!--**********************************
			Nav header end
		***********************************-->

		<!--**********************************
			Chat box start
		***********************************-->
		<div class="chatbox">
			<div class="chatbox-close"></div>
			<div class="custom-tab-1">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="/#notes">Notes</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="/#alerts">Alerts</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" data-bs-toggle="tab" href="/#chat">Chat</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade active show" id="chat" role="tabpanel">
						<div class="card mb-sm-3 mb-md-0 contacts_card dlab-chat-user-box">
							<div class="card-header chat-list-header text-center">
								<a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewbox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"></rect>
											<rect fill="#000000" opacity="0.3"
												transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) "
												x="4" y="11" width="16" height="2" rx="1"></rect>
										</g>
									</svg></a>
								<div>
									<h6 class="mb-1">Chat List</h6>
									<p class="mb-0">Show All</p>
								</div>
								<a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewbox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24"></rect>
											<circle fill="#000000" cx="5" cy="12" r="2"></circle>
											<circle fill="#000000" cx="12" cy="12" r="2"></circle>
											<circle fill="#000000" cx="19" cy="12" r="2"></circle>
										</g>
									</svg></a>
							</div>
							<div class="card-body contacts_body p-0 dlab-scroll  " id="DLAB_W_Contacts_Body">
								<ul class="contacts">
									<li class="name-first-letter">A</li>
									<li class="active dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/1.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>Archie Parker</span>
												<p>Kalid is online</p>
											</div>
										</div>
									</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/2.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Alfie Mason</span>
												<p>Taherah left 7 mins ago</p>
											</div>
										</div>
									</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/3.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>AharlieKane</span>
												<p>Sami is online</p>
											</div>
										</div>
									</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/4.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Athan Jacoby</span>
												<p>Nargis left 30 mins ago</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">B</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/5.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Bashid Samim</span>
												<p>Rashid left 50 mins ago</p>
											</div>
										</div>
									</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/1.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>Breddie Ronan</span>
												<p>Kalid is online</p>
											</div>
										</div>
									</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/2.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Ceorge Carson</span>
												<p>Taherah left 7 mins ago</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">D</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/3.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>Darry Parker</span>
												<p>Sami is online</p>
											</div>
										</div>
									</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/4.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Denry Hunter</span>
												<p>Nargis left 30 mins ago</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">J</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/5.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Jack Ronan</span>
												<p>Rashid left 50 mins ago</p>
											</div>
										</div>
									</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/1.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>Jacob Tucker</span>
												<p>Kalid is online</p>
											</div>
										</div>
									</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/2.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>James Logan</span>
												<p>Taherah left 7 mins ago</p>
											</div>
										</div>
									</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/3.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>Joshua Weston</span>
												<p>Sami is online</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">O</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/4.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Oliver Acker</span>
												<p>Nargis left 30 mins ago</p>
											</div>
										</div>
									</li>
									<li class="dlab-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="/images/avatar/5.jpg" class="rounded-circle user_img" alt="">
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Oscar Weston</span>
												<p>Rashid left 50 mins ago</p>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<div class="card chat dlab-chat-history-box d-none">
							<div class="card-header chat-list-header text-center">
								<a href="javascript:void(0);" class="dlab-chat-history-back">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
										width="18px" height="18px" viewbox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<polygon points="0 0 24 0 24 24 0 24"></polygon>
											<rect fill="#000000" opacity="0.3"
												transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000) "
												x="14" y="7" width="2" height="10" rx="1"></rect>
											<path
												d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z"
												fill="#000000" fill-rule="nonzero"
												transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) ">
											</path>
										</g>
									</svg>
								</a>
								<div>
									<h6 class="mb-1">Chat with Khelesh</h6>
									<p class="mb-0 text-success">Online</p>
								</div>
								<div class="dropdown">
									<a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false"><svg
											xmlns="http://www.w3.org/2000/svg"
											xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
											viewbox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24"></rect>
												<circle fill="#000000" cx="5" cy="12" r="2"></circle>
												<circle fill="#000000" cx="12" cy="12" r="2"></circle>
												<circle fill="#000000" cx="19" cy="12" r="2"></circle>
											</g>
										</svg></a>
									<ul class="dropdown-menu dropdown-menu-end">
										<li class="dropdown-item"><i class="fa fa-user-circle text-primary me-2"></i>
											View profile</li>
										<li class="dropdown-item"><i class="fa fa-users text-primary me-2"></i> Add to
											btn-close friends</li>
										<li class="dropdown-item"><i class="fa fa-plus text-primary me-2"></i> Add to
											group</li>
										<li class="dropdown-item"><i class="fa fa-ban text-primary me-2"></i> Block</li>
									</ul>
								</div>
							</div>
							<div class="card-body msg_card_body dlab-scroll" id="DLAB_W_Contacts_Body3">
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
									<div class="msg_cotainer">
										Hi, how are you samim?
										<span class="msg_time">8:40 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										Hi Khalid i am good tnx how about you?
										<span class="msg_time_send">8:55 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
									<div class="msg_cotainer">
										I am good too, thank you for your chat template
										<span class="msg_time">9:00 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										You are welcome
										<span class="msg_time_send">9:05 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
									<div class="msg_cotainer">
										I am looking for your next templates
										<span class="msg_time">9:07 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										Ok, thank you have a good day
										<span class="msg_time_send">9:10 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
									<div class="msg_cotainer">
										Bye, see you
										<span class="msg_time">9:12 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
									<div class="msg_cotainer">
										Hi, how are you samim?
										<span class="msg_time">8:40 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										Hi Khalid i am good tnx how about you?
										<span class="msg_time_send">8:55 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
									<div class="msg_cotainer">
										I am good too, thank you for your chat template
										<span class="msg_time">9:00 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										You are welcome
										<span class="msg_time_send">9:05 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
									<div class="msg_cotainer">
										I am looking for your next templates
										<span class="msg_time">9:07 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										Ok, thank you have a good day
										<span class="msg_time_send">9:10 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt="">
									</div>
									<div class="msg_cotainer">
										Bye, see you
										<span class="msg_time">9:12 AM, Today</span>
									</div>
								</div>
							</div>
							<div class="card-footer type_msg">
								<div class="input-group">
									<textarea class="form-control" placeholder="Type your message..."></textarea>
									<div class="input-group-append">
										<button type="button" class="btn btn-primary"><i
												class="fa fa-location-arrow"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="alerts" role="tabpanel">
						<div class="card mb-sm-3 mb-md-0 contacts_card">
							<div class="card-header chat-list-header text-center">
								<a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewbox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24"></rect>
											<circle fill="#000000" cx="5" cy="12" r="2"></circle>
											<circle fill="#000000" cx="12" cy="12" r="2"></circle>
											<circle fill="#000000" cx="19" cy="12" r="2"></circle>
										</g>
									</svg></a>
								<div>
									<h6 class="mb-1">Notications</h6>
									<p class="mb-0">Show All</p>
								</div>
								<a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewbox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24"></rect>
											<path
												d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
												fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
											<path
												d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
												fill="#000000" fill-rule="nonzero"></path>
										</g>
									</svg></a>
							</div>
							<div class="card-body contacts_body p-0 dlab-scroll" id="DLAB_W_Contacts_Body1">
								<ul class="contacts">
									<li class="name-first-letter">SEVER STATUS</li>
									<li class="active">
										<div class="d-flex bd-highlight">
											<div class="img_cont primary">KK</div>
											<div class="user_info">
												<span>David Nester Birthday</span>
												<p class="text-primary">Today</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">SOCIAL</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="img_cont success">RU</div>
											<div class="user_info">
												<span>Perfection Simplified</span>
												<p>Jame Smith commented on your status</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">SEVER STATUS</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="img_cont primary">AU</div>
											<div class="user_info">
												<span>AharlieKane</span>
												<p>Sami is online</p>
											</div>
										</div>
									</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="img_cont info">MO</div>
											<div class="user_info">
												<span>Athan Jacoby</span>
												<p>Nargis left 30 mins ago</p>
											</div>
										</div>
									</li>
								</ul>
							</div>
							<div class="card-footer"></div>
						</div>
					</div>
					<div class="tab-pane fade" id="notes">
						<div class="card mb-sm-3 mb-md-0 note_card">
							<div class="card-header chat-list-header text-center">
								<a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewbox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"></rect>
											<rect fill="#000000" opacity="0.3"
												transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) "
												x="4" y="11" width="16" height="2" rx="1"></rect>
										</g>
									</svg></a>
								<div>
									<h6 class="mb-1">Notes</h6>
									<p class="mb-0">Add New Nots</p>
								</div>
								<a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewbox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24"></rect>
											<path
												d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
												fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
											<path
												d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
												fill="#000000" fill-rule="nonzero"></path>
										</g>
									</svg></a>
							</div>
							<div class="card-body contacts_body p-0 dlab-scroll" id="DLAB_W_Contacts_Body2">
								<ul class="contacts">
									<li class="active">
										<div class="d-flex bd-highlight">
											<div class="user_info">
												<span>New order placed..</span>
												<p>10 Aug 2020</p>
											</div>
											<div class="ms-auto">
												<a href="javascript:void(0);"
													class="btn btn-primary btn-xs sharp me-1"><i
														class="fas fa-pencil-alt"></i></a>
												<a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i
														class="fa fa-trash"></i></a>
											</div>
										</div>
									</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="user_info">
												<span>Youtube, a video-sharing website..</span>
												<p>10 Aug 2020</p>
											</div>
											<div class="ms-auto">
												<a href="javascript:void(0);"
													class="btn btn-primary btn-xs sharp me-1"><i
														class="fas fa-pencil-alt"></i></a>
												<a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i
														class="fa fa-trash"></i></a>
											</div>
										</div>
									</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="user_info">
												<span>john just buy your product..</span>
												<p>10 Aug 2020</p>
											</div>
											<div class="ms-auto">
												<a href="javascript:void(0);"
													class="btn btn-primary btn-xs sharp me-1"><i
														class="fas fa-pencil-alt"></i></a>
												<a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i
														class="fa fa-trash"></i></a>
											</div>
										</div>
									</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="user_info">
												<span>Athan Jacoby</span>
												<p>10 Aug 2020</p>
											</div>
											<div class="ms-auto">
												<a href="javascript:void(0);"
													class="btn btn-primary btn-xs sharp me-1"><i
														class="fas fa-pencil-alt"></i></a>
												<a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i
														class="fa fa-trash"></i></a>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--**********************************
			Chat box End
		***********************************-->

		<!--**********************************
			Header start
		***********************************-->
		<div class="header border-bottom">
			<div class="header-content">
				<nav class="navbar navbar-expand">
					<div class="collapse navbar-collapse justify-content-between">
						<div class="header-left">
							<div class="dashboard_bar">
								Dashboard
							</div>
						</div>
						<ul class="navbar-nav header-right">
							<!-- <li class="nav-item d-flex align-items-center">
								<div class="input-group search-area">
									<input type="text" class="form-control" placeholder="Search here...">
									<span class="input-group-text"><a href="javascript:void(0)"><i
												class="flaticon-381-search-2"></i></a></span>
								</div>
							</li> -->
							<li class="nav-item dropdown notification_dropdown">
								<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
									<svg width="28" height="28" viewbox="0 0 28 28" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<path
											d="M23.3333 19.8333H23.1187C23.2568 19.4597 23.3295 19.065 23.3333 18.6666V12.8333C23.3294 10.7663 22.6402 8.75902 21.3735 7.12565C20.1068 5.49228 18.3343 4.32508 16.3333 3.80679V3.49996C16.3333 2.88112 16.0875 2.28763 15.6499 1.85004C15.2123 1.41246 14.6188 1.16663 14 1.16663C13.3812 1.16663 12.7877 1.41246 12.3501 1.85004C11.9125 2.28763 11.6667 2.88112 11.6667 3.49996V3.80679C9.66574 4.32508 7.89317 5.49228 6.6265 7.12565C5.35983 8.75902 4.67058 10.7663 4.66667 12.8333V18.6666C4.67053 19.065 4.74316 19.4597 4.88133 19.8333H4.66667C4.35725 19.8333 4.0605 19.9562 3.84171 20.175C3.62292 20.3938 3.5 20.6905 3.5 21C3.5 21.3094 3.62292 21.6061 3.84171 21.8249C4.0605 22.0437 4.35725 22.1666 4.66667 22.1666H23.3333C23.6428 22.1666 23.9395 22.0437 24.1583 21.8249C24.3771 21.6061 24.5 21.3094 24.5 21C24.5 20.6905 24.3771 20.3938 24.1583 20.175C23.9395 19.9562 23.6428 19.8333 23.3333 19.8333Z"
											fill="#717579"></path>
										<path
											d="M9.9819 24.5C10.3863 25.2088 10.971 25.7981 11.6766 26.2079C12.3823 26.6178 13.1838 26.8337 13.9999 26.8337C14.816 26.8337 15.6175 26.6178 16.3232 26.2079C17.0288 25.7981 17.6135 25.2088 18.0179 24.5H9.9819Z"
											fill="#717579"></path>
									</svg>
									<span class="badge light text-white bg-warning rounded-circle">12</span>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<div id="DZ_W_Notification1" class="widget-media dlab-scroll p-3"
										style="height:380px;">
										<ul class="timeline">
											<li>
												<div class="timeline-panel">
													<div class="media me-2">
														<img alt="image" width="50" src="/images/avatar/1.jpg">
													</div>
													<div class="media-body">
														<h6 class="mb-1">Dr sultads Send you Photo</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
											<li>
												<div class="timeline-panel">
													<div class="media me-2 media-info">
														KG
													</div>
													<div class="media-body">
														<h6 class="mb-1">Resport created successfully</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
											<li>
												<div class="timeline-panel">
													<div class="media me-2 media-success">
														<i class="fa fa-home"></i>
													</div>
													<div class="media-body">
														<h6 class="mb-1">Reminder : Treatment Time!</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
											<li>
												<div class="timeline-panel">
													<div class="media me-2">
														<img alt="image" width="50" src="/images/avatar/1.jpg">
													</div>
													<div class="media-body">
														<h6 class="mb-1">Dr sultads Send you Photo</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
											<li>
												<div class="timeline-panel">
													<div class="media me-2 media-danger">
														KG
													</div>
													<div class="media-body">
														<h6 class="mb-1">Resport created successfully</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
											<li>
												<div class="timeline-panel">
													<div class="media me-2 media-primary">
														<i class="fa fa-home"></i>
													</div>
													<div class="media-body">
														<h6 class="mb-1">Reminder : Treatment Time!</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
										</ul>
									</div>
									<a class="all-notification" href="javascript:void(0);">See all notifications <i
											class="ti-arrow-end"></i></a>
								</div>
							</li>

							<li class="nav-item dropdown notification_dropdown">
								<!-- <a class="nav-link " href="javascript:void(0);" data-bs-toggle="dropdown">
									<svg width="28" height="28" viewbox="0 0 28 28" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<path
											d="M22.1666 5.83331H20.9999V3.49998C20.9999 3.19056 20.877 2.89381 20.6582 2.67502C20.4394 2.45623 20.1427 2.33331 19.8333 2.33331C19.5238 2.33331 19.2271 2.45623 19.0083 2.67502C18.7895 2.89381 18.6666 3.19056 18.6666 3.49998V5.83331H9.33325V3.49998C9.33325 3.19056 9.21034 2.89381 8.99154 2.67502C8.77275 2.45623 8.47601 2.33331 8.16659 2.33331C7.85717 2.33331 7.56042 2.45623 7.34163 2.67502C7.12284 2.89381 6.99992 3.19056 6.99992 3.49998V5.83331H5.83325C4.90499 5.83331 4.01476 6.20206 3.35838 6.85844C2.702 7.51482 2.33325 8.40506 2.33325 9.33331V10.5H25.6666V9.33331C25.6666 8.40506 25.2978 7.51482 24.6415 6.85844C23.9851 6.20206 23.0948 5.83331 22.1666 5.83331Z"
											fill="#717579"></path>
										<path
											d="M2.33325 22.1666C2.33325 23.0949 2.702 23.9851 3.35838 24.6415C4.01476 25.2979 4.90499 25.6666 5.83325 25.6666H22.1666C23.0948 25.6666 23.9851 25.2979 24.6415 24.6415C25.2978 23.9851 25.6666 23.0949 25.6666 22.1666V12.8333H2.33325V22.1666Z"
											fill="#717579"></path>
									</svg>
									<span class="badge light text-white bg-success rounded-circle">!</span>
								</a> -->
								<div class="dropdown-menu dropdown-menu-end">
									<div id="DZ_W_TimeLine02"
										class="widget-timeline dlab-scroll style-1 ps ps--active-y p-3 height370">
										<ul class="timeline">
											<li>
												<div class="timeline-badge primary"></div>
												<a class="timeline-panel text-muted" href="javascript:void(0);">
													<span>10 minutes ago</span>
													<h6 class="mb-0">Youtube, a video-sharing website, goes live <strong
															class="text-primary">$500</strong>.</h6>
												</a>
											</li>
											<li>
												<div class="timeline-badge info">
												</div>
												<a class="timeline-panel text-muted" href="javascript:void(0);">
													<span>20 minutes ago</span>
													<h6 class="mb-0">New order placed <strong
															class="text-info">#XF-2356.</strong></h6>
													<p class="mb-0">Quisque a consequat ante Sit amet magna at
														volutapt...</p>
												</a>
											</li>
											<li>
												<div class="timeline-badge danger">
												</div>
												<a class="timeline-panel text-muted" href="javascript:void(0);">
													<span>30 minutes ago</span>
													<h6 class="mb-0">john just buy your product <strong
															class="text-warning">Sell $250</strong></h6>
												</a>
											</li>
											<li>
												<div class="timeline-badge success">
												</div>
												<a class="timeline-panel text-muted" href="javascript:void(0);">
													<span>15 minutes ago</span>
													<h6 class="mb-0">StumbleUpon is acquired by eBay. </h6>
												</a>
											</li>
											<li>
												<div class="timeline-badge warning">
												</div>
												<a class="timeline-panel text-muted" href="javascript:void(0);">
													<span>20 minutes ago</span>
													<h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
												</a>
											</li>
											<li>
												<div class="timeline-badge dark">
												</div>
												<a class="timeline-panel text-muted" href="javascript:void(0);">
													<span>20 minutes ago</span>
													<h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</li>

							<li class="nav-item dropdown  header-profile">
								<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
									<img src="/images/user.jpg" width="56" alt="">
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="/app-profile.html" class="dropdown-item ai-icon">
										<svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary"
											width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor"
											stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
											<circle cx="12" cy="7" r="4"></circle>
										</svg>
										<span class="ms-2">Profile </span>
									</a>
									<a href="/email-inbox.html" class="dropdown-item ai-icon">
										<svg id="icon-inbox" xmlns="http://www.w3.org/2000/svg" class="text-success"
											width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor"
											stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path
												d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
											</path>
											<polyline points="22,6 12,13 2,6"></polyline>
										</svg>
										<span class="ms-2">Inbox </span>
									</a>
									<a href="/logic/logout.php" class="dropdown-item ai-icon">
										<svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
											width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor"
											stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
											<polyline points="16 17 21 12 16 7"></polyline>
											<line x1="21" y1="12" x2="9" y2="12"></line>
										</svg>
										<span class="ms-2">Logout </span>
									</a>
								</div>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</div>
		<!--**********************************
			Header end
		***********************************-->



		<!--**********************************
			Sidebar start
		***********************************-->
		<div class="dlabnav">
			<div class="dlabnav-scroll">
				<ul class="metismenu text-start" id="sidebar-menu">
					<li>
						<a href="index.php">
							<img src="/images/icons/home.png" alt="Home Icon" width="24" height="24">
							<span class="menu-text">Home</span>
						</a>
					</li>
					<li>
						<a href="manageUserRole.php">
							<img src="/images/icons/user-gear.png" alt="Users Cog Icon" width="24" height="24">
							<span class="menu-text">Kelola User & Role</span>
						</a>
					</li>
					<li>
						<a href="manageProductCategory.php">
							<img src="/images/icons/folder-open.png" alt="Folder Icon" width="24" height="24">
							<span class="menu-text">Kelola Kategori Produk</span>
						</a>
					</li>
					<li>
						<a href="manageProduct.php">
							<img src="/images/icons/box-open.png" alt="Box Icon" width="24" height="24">
							<span class="menu-text">Kelola Produk</span>
						</a>
					</li>
					<li>
						<a href="manageVoucher.php">
							<img src="/images/icons/ticket.png" alt="Gift Icon" width="24" height="24">
							<span class="menu-text">Kelola Voucher</span>
						</a>
					</li>
					<li>
						<a href="manageTransaction.php">
							<img src="/images/icons/receipt.png" alt="Transaction Icon" width="24" height="24">
							<span class="menu-text">Kelola Transaksi</span>
						</a>
					</li>
					<li>
						<a href="manageOutlet.php">
							<img src="/images/icons/shop.png" alt="Outlet Icon" width="24" height="24">
							<span class="menu-text">Kelola Outlet</span>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<style>
			.menu-text {
				display: none;
			}

			.menu-expanded .menu-text {
				display: inline;
			}
		</style>

		<script>
			document.getElementById('hamburger-btn').addEventListener('click', function() {
				document.getElementById('sidebar-menu').classList.toggle('menu-expanded');
			});
		</script>

		<!--**********************************
			Sidebar end
		***********************************-->

		<!--**********************************
			Content body start
		***********************************-->
		<div class="content-body">
			<!-- row -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-xl-12">
						<div class="row">
							<div class="col-xl-12">
								<div class="row">
									<div class="col-xl-12">
										<div class="card">
											<div class="card-header border-0 flex-wrap">
												<h4 class="fs-20 font-w700 mb-2">Grafik Penjualan</h4>
												<div class="d-flex align-items-center project-tab mb-2">
													<div class="card-tabs mt-3 mt-sm-0 mb-3 ">
														<ul class="nav nav-tabs" role="tablist">
															<li class="nav-item">
																<a class="nav-link active" data-bs-toggle="tab"
																	href="/#weekly" role="tab">Weekly</a>
															</li>
															<li class="nav-item">
																<a class="nav-link" data-bs-toggle="tab"
																	href="/#monthly" role="tab">Monthly</a>
															</li>
														</ul>
													</div>
												</div>
											</div>
											<div class="card-body">
												<div
													class="d-flex justify-content-between align-items-center flex-wrap">
													<div class="d-flex">
														<div
															class="d-inline-block position-relative donut-chart-sale mb-3">
															<span class="donut1"
																data-peity='{ "fill": ["rgba(136,108,192,1)", "rgba(241, 234, 255, 1)"], "innerRadius": 20, "radius": 15}'>5/8</span>
														</div>
														<div class="ms-3">
															<h4 id="totalProjects" class="fs-24 font-w700 ">
																Rp.<?= number_format($salesInDay['data']['total_sales'], 0, ',', '.') ?>
															</h4>
															<span class="fs-16 font-w400 d-block">Total Sales
																Today</span>
														</div>
													</div>
													<?php
													$outletColors = ['#42FFFF', '#6E8E59', '#4B164C']; 
													$index = 0;

													?>
													<div class="d-flex">
														<?php foreach ($salesInDayPerOutlet['data'] as $sales): ?>
															<div class="d-flex me-5">
																<div class="mt-2">
																	<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<circle cx="6.5" cy="6.5" r="6.5" fill="<?= $outletColors[$index % count($outletColors)] ?>"></circle>
																	</svg>
																</div>
																<div class="ms-3">
																	<h4 id="kortailQuantity" class="fs-24 font-w700">
																		<?= number_format($sales['total_sales'], 0, ',', '.') ?>
																	</h4>
																	<span class="fs-16 font-w400 d-block"><?= $sales['outlet_name'] ?></span>
																</div>
															</div>
															<?php $index++; ?>
														<?php endforeach; ?>
													</div>

												</div>
												<div class="tab-content">
													<div class="tab-pane fade active show" id="weekly">
														<div id="chartBarWeekly" class="chartBar"></div>
													</div>

													<div class="tab-pane fade" id="monthly">
														<div id="chartBarMonthly" class="chartBar"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-6 col-lg-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">Total Overview</h4>
									</div>
									<div class="card-body">
										<div id="simple-line-chart" class="ct-chart ct-golden-section chartlist-chart">
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-6 col-lg-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">Income Overview</h4>
									</div>
									<div class="card-body">
										<div id="default-data" class="ct-chart ct-golden-section chartlist-chart"></div>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">Jam Penjualan</h4>
										<input type="checkbox" name="monthly" id="turnOnMonthly">
									</div>
									<div class="card-body">
										<div id="jam_penjualan"></div>
									</div>
								</div>
							</div>

							<div class="col-xl-12">
								<div class="row">
									<div class="col-xl-6 col-lg-6">
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-6">
												<div class="card">
													<div class="card-header border-0">
														<div>
															<h4 class="fs-20 font-w700">Kategori Terlaris</h4>
															<span class="fs-14 font-w400 d-block">Berikut merupakan
																penjualan kategori terlaris</span>
														</div>
													</div>
													<div class="card-body">
														<div id="kategoriterlarischart"> </div>
														<div class="mb-3 mt-4">
															<h4 class="fs-18 font-w600">Detail</h4>
														</div>
														<!-- Tambahkan container untuk legend dinamis -->
														<div id="legendContainer"></div>
													</div>
													<div class="card-footer border-0 pt-0">
														<a href="javascript:void(0);"
															class="btn btn-outline-primary d-block btn-rounded">Update
															Progress</a>

													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-6 col-lg-6">
										<div class="card">
											<div class="card-header border-0 pb-0">
												<div>
													<h4 class="fs-20 font-w700">Produk Populer</h4>
												</div>
											</div>
											<div class="card-body pb-0" id="productContainer">
												<?php foreach ($produkterlaris['data'] as $product): ?>
													<div class="project-details">
														<div class="d-flex align-items-center justify-content-between">
															<div class="d-flex align-items-center">
																<span class="big-wind"
																	style="overflow:hidden; box-sizing: border-box; margin:0;">
																	<img src="<?php echo $product['product']['image'] ?>"
																		alt="" style="width:100%; height:100%; "
																		class="img-fluid">
																</span>
																<div class="ms-3">
																	<h4><?php echo $product['product']['name']; ?></h4>
																	<span class="fs-14 font-w400">Total Terjual:
																		<strong><?php echo $product["total_quantity"]; ?>
																			<strong>Item</span>
																</div>
															</div>
															<div class="dropdown">
																<div class="btn-link" data-bs-toggle="dropdown">
																	<svg width="24" height="24" viewbox="0 0 24 24"
																		fill="none" xmlns="http://www.w3.org/2000/svg">
																		<circle cx="12.4999" cy="3.5" r="2.5"
																			fill="#A5A5A5"></circle>
																		<circle cx="12.4999" cy="11.5" r="2.5"
																			fill="#A5A5A5"></circle>
																		<circle cx="12.4999" cy="19.5" r="2.5"
																			fill="#A5A5A5"></circle>
																	</svg>
																</div>
																<div class="dropdown-menu dropdown-menu-right">
																	<a class="dropdown-item"
																		href="javascript:void(0)">Delete</a>
																	<a class="dropdown-item"
																		href="javascript:void(0)">Edit</a>
																</div>
															</div>
														</div>
													</div>
												<?php endforeach; ?>
											</div>
											<div class="card-footer pt-0 border-0">
												<a href="javascript:void(0);"
													class="btn btn-outline-primary d-block btn-rounded">Pin other
													projects</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--**********************************
			Content body end
		***********************************-->

		<!--**********************************
			Footer start
		***********************************-->
		<div class="footer">
			<div class="copyright">
				<p>Copyright © Designed &amp; Developed by DexignLab 2021</p>
			</div>
		</div>
		<!--**********************************
			Footer end
		***********************************-->

	</div>
	<!--**********************************
		Main wrapper end
	***********************************-->

	<!--**********************************
		Scripts
	***********************************-->

	<!-- Required vendors -->
	<script src="/js/navigation-gen.js"></script>
	<script src="/vendor/global/global.min.js"></script>
	<script src="/vendor/chart.js/Chart.bundle.min.js"></script>
	<script src="/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>
	<script src="/js/custom.min.js"></script>
	<script src="/js/dlabnav-init.js"></script>
	<script src="/js/demo.js"></script>
	<script src="/js/styleSwitcher.js"></script>

	<!-- Dashboard 1 -->
	<script src="/js/dashboard/dashboard-1.js"></script>
	<script src="/vendor/owl-carousel/owl.carousel.js"></script>

	<!-- Chart Morris plugin files -->
	<script src="/vendor/raphael/raphael.min.js"></script>
	<script src="/vendor/morris/morris.min.js"></script>
	<script src="/js/plugins-init/morris-init.js"></script>

	<!-- Apex Chart -->
	<script src="/vendor/apexchart/apexchart.js"></script>

	<!-- Chart piety plugin files -->
	<script src="/vendor/peity/jquery.peity.min.js"></script>

	<!-- Chart Chartist plugin files -->
	<script src="/vendor/chartist/js/chartist.min.js"></script>
	<script src="/vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js"></script>
	<script src="/js/plugins-init/chartist-init.js"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			async function fetchBestCategories() {
				try {
					// Panggil API dan parsing hasilnya sebagai JSON
					const response = await fetch('https://ngolab.id/api/transactions/best-categories');
					const result = await response.json();
					const data = result.data;

					// Validasi apakah `data` ada dan bukan array kosong
					if (!data || !Array.isArray(data) || data.length === 0) {
						console.error("Data kategori tidak ditemukan atau kosong.");
						return;
					}

					// Ambil nama kategori dan jumlah total dengan validasi
					const categories = [];
					const quantities = [];

					data.forEach(item => {
						if (item.category && item.category.name) {
							categories.push(item.category.name);
							quantities.push(parseInt(item.total_quantity) || 0);
						}
					});

					if (categories.length === 0 || quantities.length === 0) {
						console.error("Tidak ada kategori valid yang ditemukan dalam data API.");
						return;
					}

					// Warna untuk setiap kategori
					const colors = ['#886CC0', '#26E023', '#61CFF1', '#FFDA7C', '#FF86B1'];

					// Konfigurasi grafik dengan ApexCharts
					const options = {
						series: quantities,
						chart: {
							type: 'donut',
							height: 300
						},
						labels: categories,
						colors: colors,
						dataLabels: {
							enabled: false
						},
						stroke: {
							width: 0
						},
						legend: {
							show: false
						},
						responsive: [{
							breakpoint: 1800,
							options: {
								chart: {
									height: 200
								},
							}
						}]
					};

					// Render grafik di container dengan id #kategoriterlarischart
					const chartContainer = document.querySelector("#kategoriterlarischart");
					if (!chartContainer) {
						console.error("Elemen #kategoriterlarischart tidak ditemukan!");
						return;
					}

					const chart = new ApexCharts(chartContainer, options);
					chart.render();

					// Generate legend dinamis
					const legendContainer = document.getElementById("legendContainer");
					if (!legendContainer) {
						console.error("Elemen #legendContainer tidak ditemukan!");
						return;
					}

					legendContainer.innerHTML = ''; // Kosongkan legend sebelum mengisinya

					categories.forEach((category, index) => {
						// Buat elemen HTML untuk setiap kategori
						const legendItem = document.createElement("div");
						legendItem.classList.add("d-flex", "align-items-center", "justify-content-between", "mb-4");

						legendItem.innerHTML = `
                    <span class="fs-18 font-w500">
                        <svg class="me-3" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <rect width="20" height="20" rx="6" fill="${colors[index % colors.length]}"></rect>
                        </svg>
                        ${category} (${((quantities[index] / quantities.reduce((a, b) => a + b, 0)) * 100).toFixed(2)}%)
                    </span>
                    <span class="fs-18 font-w600">${quantities[index]}</span>
                `;

						legendContainer.appendChild(legendItem);
					});

				} catch (error) {
					console.error("Gagal mengambil data dari API:", error);
				}
			}

			// Panggil fungsi untuk mengambil data dan menampilkan grafik
			fetchBestCategories();
		});
	</script>
	</script>

	<script>
		// URL API Anda
		const apiUrl = 'https://ngolab.id/api/outlet/transactions/sales/weekly'; // Ganti dengan URL API Anda

		// Fungsi untuk mengambil data dari API dan memprosesnya
		async function fetchDataAndRenderChart() {
			try {
				const response = await fetch(apiUrl);

				if (!response.ok) {
					throw new Error(`HTTP error! Status: ${response.status}`);
				}

				const apiResponse = await response.json();
				const formattedData = processData(apiResponse.data);

				renderChart(formattedData);
			} catch (error) {
				console.error('Error fetching data:', error);
			}
		}

		// Fungsi untuk memproses data API
		const processData = (data) => {
			const outletsData = {};

			data.forEach(entry => {
				for (const outlet in entry) {
					if (outlet !== 'date') {
						if (!outletsData[outlet]) {
							outletsData[outlet] = [];
						}
						outletsData[outlet].push(parseInt(entry[outlet].total_sales, 10) || 0);
					}
				}
			});

			return Object.keys(outletsData).map(outlet => ({
				name: outlet,
				data: outletsData[outlet]
			}));
		};

		// Fungsi untuk menampilkan grafik dengan ApexCharts
		const renderChart = (formattedData) => {
			var options = {
				series: formattedData,
				chart: {
					type: 'bar',
					height: 400,
					toolbar: {
						show: false,
					},
				},
				plotOptions: {
					bar: {
						horizontal: false,
						columnWidth: '57%',
						endingShape: "rounded",
						borderRadius: 12,
					},
				},
				colors: ['#42FFFF', '#FFCF6D', '#FFA7D7'],
				dataLabels: {
					enabled: false,
				},
				legend: {
					show: false,
				},
				xaxis: {
					categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
					labels: {
						style: {
							colors: '#787878',
							fontSize: '13px',
							fontFamily: 'poppins',
						},
					},
				},
				yaxis: {
					labels: {
						style: {
							colors: '#787878',
							fontSize: '13px',
							fontFamily: 'poppins',
						},
					},
				},
				tooltip: {
					y: {
						formatter: function(val) {
							return "Rp. " + val.toLocaleString();
						}
					}
				},
			};

			var chartBar1 = new ApexCharts(document.querySelector("#chartBarWeekly"), options);
			chartBar1.render();
		};

		// Menjalankan fungsi untuk mengambil data dan menampilkan grafik saat halaman dimuat
		document.addEventListener("DOMContentLoaded", fetchDataAndRenderChart);
	</script>

	<script>
		// Fungsi untuk mendapatkan data dari API dan memperbarui elemen HTML
		async function fetchSalesData() {
			try {
				// Mem-fetch data dari API
				const response = await fetch('https://ngolab.id/api/transactions/sales/day');
				const data = await response.json();

				// Memastikan respon dari API memiliki data
				if (data && data.data && data.data.length > 0) {
					// Misalnya kita ambil data dari hari terakhir (data terbaru)
					const latestSalesData = data.data[data.data.length - 1];

					// Update nilai total quantity di elemen HTML
					document.getElementById('totalProjects').innerText = latestSalesData.total_quantity;
					document.getElementById('totalSales').innerText = latestSalesData.total_sales;
				}
			} catch (error) {
				console.error('Error fetching sales data:', error);
			}
		}

		// Panggil fungsi saat halaman dimuat
		window.onload = fetchSalesData;
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			fetchData('https://ngolab.id/api/outlet/transactions/sales/day');
		});

		// Fungsi untuk menampilkan chart Morris dengan data dari API
		function renderMorrisChart(data, outletNames) {
			setTimeout(() => {
				let chartContainer = document.getElementById('jam_penjualan');

				if (!chartContainer) {
					console.error("Elemen #jam_penjualan tidak ditemukan.");
					return;
				}

				chartContainer.innerHTML = ''; // Bersihkan sebelum menggambar ulang

				new Morris.line({
					element: 'jam_penjualan',
					data: data,
					xkey: 'outlet_name',
					ykeys: ['total_sales', 'total_discount', 'total_quantity'],
					labels: ['Total Sales', 'Total Discount', 'Total Quantity'],
					barColors: ['#3498db', '#e74c3c', '#2ecc71'],
					hideHover: 'auto',
					resize: true
				});
			}, 500);
		}
	</script>

	<script>
		function renderMorrisChart(data, outlet_name) {
			// Cari nilai maksimum dalam dataset
			let maxValue = 0;
			data.forEach(entry => {
				outlet_name.forEach(outlet => {
					if (entry[outlet] > maxValue) {
						maxValue = entry[outlet];
					}
				});
			});

			// Tambahkan margin atas (misalnya 20% lebih tinggi)
			let ymaxValue = Math.ceil(maxValue * 1.2);

			new Morris.Line({
				element: 'jam_penjualan',
				data: data, // Data dari API yang sudah diurutkan
				xkey: 'hour',
				ykeys: outlet_name, // Nama outlet
				labels: outlet_name, // Nama outlet untuk legend
				lineColors: ['#FFC107', '#0044CC', '#E63946'], // Warna garis per outlet
				pointSize: 3, // Tampilkan titik data lebih jelas
				lineWidth: 2, // Lebar garis agar lebih terlihat
				resize: true,
				hideHover: 'auto',
				gridTextColor: '#333',
				parseTime: false, // Hindari parsing otomatis ke format tanggal
				ymax: ymaxValue, // Tetapkan skala maksimum agar tidak mentok
				dateFormat: function(x) {
					return parseInt(x) + ":00"; // Pastikan format jam lebih rapi
				}
			});
		}

		// Fetch data dari API dan render ke Morris Chart
		async function fetchAndRender(url) {
			try {
				const response = await fetch(url);
				if (!response.ok) throw new Error("Gagal mengambil data dari API");

				const result = await response.json();
				const rawData = result.data;

				if (!rawData.transactions) throw new Error("Data transactions tidak ditemukan!");

				const morrisData = [];

				rawData.transactions.forEach(item => {
					let hourEntry = morrisData.find(entry => entry.hour === item.hour);

					if (!hourEntry) {
						hourEntry = {
							hour: item.hour
						};
						morrisData.push(hourEntry);
					}

					// Tambahkan data per outlet
					Object.keys(rawData.outlet_name).forEach(outlet => {
						hourEntry[outlet] = item[outlet] || 0; // Jika transaksi kosong, set ke 0
					});
				});

				// Urutkan data berdasarkan jam dari 1 hingga 23
				morrisData.sort((a, b) => parseInt(a.hour) - parseInt(b.hour));

				console.log("Morris Data (Sorted):", JSON.stringify(morrisData, null, 2));
				console.log("Outlet Names:", Object.keys(rawData.outlet_name));

				// Panggil render chart dengan array outlet_name
				renderMorrisChart(morrisData, Object.keys(rawData.outlet_name));

			} catch (error) {
				console.error("Error:", error);
			}
		}

		// Panggil fetchData untuk mengambil dan menampilkan data
		fetchAndRender('https://ngolab.id/api/transactions/total/hour');


		document.getElementById('turnOnMonthly').addEventListener('change', async function() {
			let url;
			if (this.checked) {
				url = `https://ngolab.id/api/transactions/total/hour`;
			} else {
				url = `https://ngolab.id/api/transactions/total/hour`
			}
			document.getElementById('jam_penjualan').innerHTML = '';

			fetchAndRender(url);
		});
	</script>
</body>

</html>