<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
		 <?php echo "SISCAL | ".$title; ?>		
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
  
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
	<div class="wrapper">
		<header class="main-header">
			<a href="index2.html" class="logo">     
				<span class="logo-mini"><b>SCS</b></span>      
				
			</a>   
			<nav class="navbar navbar-static-top">				
				<a class="navbar-brand" href="#"><b>SENTRAL KALIBRASI DASHBOARD</b></a>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">	
					  <li>
						<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
					  </li>
					</ul>
				</div>
			</nav>
		</header> 
		<aside class="main-sidebar">    
			<section class="sidebar">
				<ul class="sidebar-menu">
					<li class="header">MAIN NAVIGATION</li>
					<li>
						<a href="#"><i class="fa fa-dashboard"></i> <span>Dashboard</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
						<ul class="treeview-menu">
							<li>
								<a href="#"><i class="fa fa-calculator"></i> <span>Sales Report</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
								<ul class="treeview-menu">
									<li>
										<a href="<?php echo base_url().'index.php/dashboard';?>"><i class="fa fa-calculator"></i> <span>Quotation</span></a>
									</li>
									<li>
										<a href="<?php echo base_url().'index.php/dashboard_order';?>"><i class="fa fa-reply-all"></i> <span>Sales Order</span></a>
									</li>
								</ul>
							</li>
							<li>
								<a href="<?php echo base_url().'index.php/dashboard_process';?>"><i class="fa fa-clock-o"></i> <span>Calibration Process</span></a>
							</li>
							<li>
								<a href="<?php echo base_url().'index.php/dashboard_certificates';?>"><i class="fa fa-certificate"></i> <span>Certificate</span></a>
							</li>
							<li>
								<a href="<?php echo base_url().'index.php/dashboard_purchase';?>"><i class="fa fa-list"></i> <span>Incomplete PO</span></a>
							</li>
							<li>
								<a href="#"><i class="fa fa-credit-card"></i> <span>Invoice</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
								<ul class="treeview-menu">
									<li>
										<a href="<?php echo base_url().'index.php/dashboard_invoice';?>"><i class="fa fa-credit-card"></i> <span>Potensial Debt</span></a>
									</li>
									<li>
										<a href="<?php echo base_url().'index.php/dashboard_inv_bad';?>"><i class="fa fa-credit-card"></i> <span>Bad Debt</span></a>
									</li>
									<li>
										<a href="<?php echo base_url().'index.php/dashboard_inv_other';?>"><i class="fa fa-credit-card"></i> <span>Other</span></a>
									</li>
								</ul>
								
							</li>
						</ul>
						
					</li>
					
					<li class="treeview">
						<a href="#"><i class="fa fa-desktop"></i> <span>Laporan</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
						<ul class="treeview-menu">
							<li>
								<a href="<?php echo base_url().'index.php/laporan_uninvoice';?>"><i class="fa fa-money"></i> <span>SO Outs Invoice</span></a>
							</li>
							<li>
								<a href="<?php echo base_url().'index.php/laporan_inv_plan_payment';?>"><i class="fa fa-calendar"></i> <span>Plan Payment Inv</span></a>
							</li>
							<li>
								<a href="<?php echo base_url().'index.php/laporan_invoice';?>"><i class="fa fa-money"></i> <span>Lap Penjualan</span></a>
							</li>
							<li>
								<a href="<?php echo base_url().'index.php/Laporan_out_bast_certificate';?>"><i class="fa fa-certificate"></i> <span>Outs Bast Certificate</span></a>
							</li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#"><i class="fa fa-home"></i> <span>Warehouse</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
						<ul class="treeview-menu">
							<li>
								<a href="<?php echo base_url().'index.php/outstanding_receive';?>"><i class="fa fa-bell"></i> <span>Outstanding PO</span></a>
							</li>
							
						</ul>
					</li>
				</ul>
			</section>
		</aside>
		<div class="content-wrapper">    
			<section class="content-header">
				 <h1>					
					<?php echo ucwords(strtolower($title)); ?>
				 </h1>
				 <ol class="breadcrumb">
					<li><?php echo ucwords(str_replace('_',' ',strtolower($this->uri->segment(1)))); ?></a></li>
					<li class="active">
						<a href="<?php echo base_url().'index.php/'.strtolower($this->uri->segment(1).'/'.$action); ?>">
						<?php echo ucwords(strtolower($action)); ?>
						</a>
					</li>
				</ol>
				
			</section>
			<section class="content">     
				<div class="row">
					<div class="col-lg-12 col-xs-12">
												
						
