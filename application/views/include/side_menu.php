<!DOCTYPE html>

<html>

<head>
	<!-- <meta charset="utf-8"> -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>
		<?php echo "SISCAL | " . $title; ?>
	</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?php echo base_url('adminlte/bootstrap/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/ionicons/css/ionicons.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('adminlte/dist/css/AdminLTE.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('adminlte/dist/css/skins/_all-skins.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/iCheck/flat/blue.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/morris/morris.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/daterangepicker/daterangepicker.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('chosen/chosen.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('sweetalert/dist/sweetalert.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('jquery-ui/jquery-ui.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/apexcharts/dist/apexcharts.css') ?>">
	<script src="<?php echo base_url('adminlte/plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>
	<style rel="stylesheet">
		table.dataTable span.highlight {
			background-color: #FFFF88;
			border-radius: 0.28571429rem;
		}

		table.dataTable span.column_highlight {
			background-color: #ffcc99;
			border-radius: 0.28571429rem;
		}

		.chosen-search-input {
			color: #000;
		}

		.chosen-container {
			width: 100% !important;
		}

		/* Start Custom Boy */
		.navbar{
			border-radius: 0px 0px 30px 0px !important;
			background: rgba(255, 255, 255, 0.1) !important;
			backdrop-filter: blur(1rem);
    		-webkit-backdrop-filter: blur(1rem);
			box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1) !important;
		}
		.logo{
			background-color: #2F92E4 !important;
			color: white !important;
			border-radius: 0px 0px 17px 17px !important;
		}
		.btn-logout-cs{
			align: center !important;
			background-color: #d9534f !important;
			color: white !important;
			font-size: 13.3px !important;
			padding: 8px !important;
			margin-top: 6px !important;
			border-radius: 20px !important;
			width: 90px;
			border: none !important;
			box-shadow: 0 1px 2px rgba(0,0,0,0.07), 
					0 2px 4px rgba(0,0,0,0.07), 
					0 4px 8px rgba(0,0,0,0.07), 
					0 8px 16px rgba(0,0,0,0.07),
					0 16px 32px rgba(0,0,0,0.07), 
					0 32px 64px rgba(0,0,0,0.07);
		}
		a.btn-logout-cs:hover{
			color: white;
			transition: all 150ms linear;
			opacity: .85;
		}
		.sidebar-toggle{
			margin-left: 25px !important;
			color:black !important;
		}
		.sidebar-toggle:hover{
			background-color: rgba(255, 255, 255, 0.1) !important;
		}

		/* .foocus {
			background: #8BC2A1;
			border-radius: 7.5px;
			width: 168px;
			height: 38px;
			font-weight: 700 !important;
		} */
		
		.sidebar-menu li.active{
			border-right: 3.2px solid #2F92E4 !important;
			color: #000;
		}

		.menu-0 li.active {
			color: #000;
			border-right: 3.5px solid #2F92E4 !important;
		}

		.menu-1 li.active {
			color: #000;
			border-right: 4.5px solid #2F92E4 !important;
		}

		@media (max-width: 48em) {
			.logo{
				border-radius: 0px 0px 0px 0px !important;
			}
			.navbar{
				border-radius: 0px 0px 35px 35px !important;
			}
			.logo-lg{
				width: 30%;
			}
		}
		@media (max-width: 768px) {
			.logo-lg{
				width: 30%;
			}
		}
		@media only screen and (max-width: 600px) {
			.logo-lg{
				width: 40% !important;
			}
		}
		@media only screen and (max-width: 530px) {
			.logo-lg{
				width: 48% !important;
			}
		}
		@media only screen and (max-width: 360px) {
			.logo-lg{
				width: 60% !important;
			}
		}
		/* End Custom Boy */

	</style>
</head>

<body class="skin-blue-light fixed sidebar-mini sidebar-mini-expand-feature">
	<div class="wrapper">
		<header class="main-header">
			<a href="#" class="logo">
				<img src="<?php echo base_url('assets/img/sc_logo.png') ?>" class="logo-mini" alt="User Image" width="250%">
				<img src="<?php echo base_url('assets/img/logo_flat_white.png') ?>" class="logo-lg" alt="User Image" width="95%">
			</a>
			<nav class="navbar navbar-static-top">
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav" style="margin-right: 30px;">
						<li>
							<a href="<?php echo base_url(); ?>dashboard/logout" class="btn-logout-cs" data-role="qtip">
							<text style="margin-left:10px;"><i class="fa fa-power-off"></i> Logout</text>
							</a>
						</li>
						<!-- <li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="<?php echo base_url('assets/img/avatar.png') ?>" class="img-circle" alt="User Image" height="20px" width="20px">
								<span class="hidden-xs"> <text style="color:black;font-weight: bold;"><?php echo $this->session->userdata('siscal_username'); ?></text></span>
							</a>
						</li> -->
					</ul>
				</div>
			</nav>
		</header>
		<aside class="main-sidebar">
			<section class="sidebar">
				<div class="user-panel">
					<div class="pull-left image">
						<img src="<?php echo base_url('assets/img/avatar.png') ?>" class="img-circle" alt="User Image">
					</div>
					<div class="pull-left info">
						<p><?php echo $this->session->userdata('siscal_username'); ?></p>
						<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
					</div>
				</div>
				<?php
				$Menus	= group_menus_access();
				//echo"<pre>";print_r($Menus);
				render_left_menus($Menus);
				?>


				<script>
					// $(document).ready(function() {
					// 	$('.sidebar-menu > li').click(function() {
					// 		let a = localStorage.setItem("menu1", $(this).attr("id"));
					// 		$('li').removeClass("active");
					// 		$(this).addClass("active");
					// 		console.log('getMenu:' + $(this).attr("id"));
					// 	});

					// 	$('.menu-0 > li').click(function() {
					// 		let a = localStorage.setItem("menu2", $(this).attr("id"));
					// 		$('li').removeClass("active");
					// 		$(this).addClass("active");
					// 		console.log('getMenu2:' + $(this).attr("id"));
					// 	});

					// 	$('.menu-1 > li').click(function() {
					// 		let a = localStorage.setItem("menu3", $(this).attr("id"));
					// 		$('li').removeClass("active");
					// 		$(this).addClass("active");
					// 		console.log('getMenu3:' + $(this).attr("id"));
					// 	});
						
					// 	var ActiveSet = window.localStorage.getItem("menu1");
					// 		ActiveSet ? $("#" + ActiveSet).addClass("active") : $('#1-1').addClass("active");

					// 	var ActiveSet2 = window.localStorage.getItem("menu2");
					// 		ActiveSet2 ? $("#" + ActiveSet2).addClass("active") : $('#7-1').addClass("active");

					// 	var ActiveSet3 = window.localStorage.getItem("menu3");
					// 		ActiveSet3 ? $("#" + ActiveSet3).addClass("active") : $('#8-1').addClass("active");
					// });
				</script>



				<!--
				<ul class="sidebar-menu">
				
					<li class="header">MAIN NAVIGATION</li>

					<li class="treeview">
						<a href="#"><i class="fa fa-refresh"></i> <span>JARI Integrasi</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
						<ul class="treeview-menu">
							<li>
								<a href="<?php echo base_url() . 'index.php/jari_quot_incomplete'; ?>"><i class="fa fa-folder-open"></i> <span>Incomplete PO</span></a>
							</li>
							
							<li>
								<a href="<?php echo base_url() . 'index.php/jari_cust_bast'; ?>"><i class="fa fa-file"></i> <span>BAST Cust / Subcon</span></a>
							</li>
							<li>
								<a href="<?php echo base_url() . 'index.php/jari_insitu_bast'; ?>"><i class="fa fa-user"></i> <span>BAST Insitu</span></a>
							</li>
							<li>
								<a href="<?php echo base_url() . 'index.php/jari_certificate_bast'; ?>"><i class="fa fa-certificate"></i> <span>BAST Certificate</span></a>
							</li>
							<li>
								<a href="<?php echo base_url() . 'index.php/jari_invoice'; ?>"><i class="fa fa-money"></i> <span>Invoice Delivery</span></a>
							</li>
							
						</ul>
					</li>
				</ul>
				!-->
			</section>
		</aside>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>
					<?php echo ucwords(strtolower($title)); ?>
				</h1>
				<ol class="breadcrumb">
					<li><?php echo ucwords(str_replace('_', ' ', strtolower($this->uri->segment(1)))); ?></a></li>
					<li class="active">
						<a href="<?php echo base_url(). strtolower($this->uri->segment(1) . '/' . $action); ?>">
							<?php echo ucwords(strtolower($action)); ?>
						</a>
					</li>
				</ol>

			</section>
			<section class="content">
				<div class="row">
					<div class="col-lg-12 col-xs-12">
