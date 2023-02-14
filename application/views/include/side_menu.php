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
	</style>
</head>

<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
	<div class="wrapper">
		<header class="main-header">
			<a href="#" class="logo">
				<span class="logo-mini"><b>SCS</b></span>

			</a>
			<nav class="navbar navbar-static-top">
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<a class="navbar-brand" href="#"><b>KALIBRASI DASHBOARD</b></a>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li class="dropdown tasks-menu">
							<a href="<?php echo base_url(); ?>index.php/dashboard/logout" data-role="qtip" title="Sign Out">
								<i class="fa fa-sign-out"></i>

							</a>
						</li>
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="<?php echo base_url('assets/img/avatar.png') ?>" class="img-circle" alt="User Image" height="20px" width="20px">
								<span class="hidden-xs"> <?php echo $this->session->userdata('siscal_username'); ?></span>
							</a>
						</li>
					</ul>
				</div>
			</nav>
		</header>
		<aside class="main-sidebar">
			<section class="sidebar">
				<?php
				$Menus	= group_menus_access();
				//echo"<pre>";print_r($Menus);
				render_left_menus($Menus);
				?>

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
						<a href="<?php echo base_url() . 'index.php/' . strtolower($this->uri->segment(1) . '/' . $action); ?>">
							<?php echo ucwords(strtolower($action)); ?>
						</a>
					</li>
				</ol>

			</section>
			<section class="content">
				<div class="row">
					<div class="col-lg-12 col-xs-12">