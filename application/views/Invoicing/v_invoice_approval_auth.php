<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SENTRAL CALIBRATION</title>
  <!-- Tell the browser to be responsive to screen width -->
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

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
</head>
<body>
	<?php
	if(empty($rows_header)){
		echo"<div class='row'>
			<div class='col-sm-12'>
				<div class='alert alert-warning alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<h4><i class='icon fa fa-warning'></i> Alert!</h4>
					NO RECORD WAS FOUND....
				  </div>
			</div>
		</div>";
	}else{
		$rows_User	= $this->db->get_where('users',array('id'=>$rows_header[0]->approve_by))->result();
		$Nama_User	= strtoupper($rows_User[0]->username);
		if($rows_User[0]->member_id){
			$rows_Member	= $this->db->get_where('members',array('id'=>$rows_User[0]->member_id))->result();
			$Nama_User		= strtoupper($rows_Member[0]->nama);
		}
		echo"
		<div class='form-box login text-center'> 
			<div class='header'>				
				<img src='".base_url('assets/img/logo.jpg')."' width='240px' height='140px' alt='Logo'>
				<h3 class='box-title bold-text'>Verifikasi Tanda Tangan Elektronik</h3>
			</div>
			<div class='body '>
				<div class='form-group'>
					<label class='control-label'>Nama Dokumen</label>
					<div>
						<h5 class='bold-text'>INVOICE ".$rows_header[0]->invoice_no." - ".strtoupper($rows_header[0]->customer_name)."</h5>
					</div>
				</div>
				<div class='form-group'>
					<label class='control-label'>Nomor Seri</label>
					<div>
						<h5 class='bold-text'>".$rows_header[0]->code_hash."</h5>
					</div>
				</div>
				<div class='form-group'>
					<br>
					<label class='control-label bold-text'>Telah ditandatangani secara digital oleh pengguna <b>sistem</b></label>
					<br>
				</div>
				<div class='form-group'>
					<label class='control-label'>Nama Pengguna</label>
					<div>
						<h5 class='bold-text'>".$Nama_User."</h5>
					</div>
				</div>
				<div class='form-group'>
					<label class='control-label'>Tanggal & Waktu</label>
					<div>
						<h5 class='bold-text'>".$rows_header[0]->approve_date."</h5>
					</div>
				</div>
			</div>
		</div>
		";
	}
	?>
	
<style>
	
	.bold-text{
		font-weight:bold !important;
	}
	
	 body{
		overflow: auto;
		-webkit-overflow-scrolling: touch;
		font-family: "Open Sans", sans-serif;
		height: 100vh;
		background: url("<?php echo base_url();?>assets/images/bg_inv.jpg") 40% fixed;
		background-position: center center;
		background-size: cover;
		background-repeat: no-repeat;
		background-attachment: fixed;
		
	}
	
	
	.form-box{
		opacity :0.9;
		border-radius:8px;
		width: 360px;
		margin: 90px auto 0 auto;
		/*
		background-color:#154c79;
		*/
	}
	.login {
		padding: 3em 2em;
		background: white;
		margin: 3em auto;
		width: 50%;
		border-radius: 10px;
	}

	@media (max-width: 720px) {
		.login {
			margin: 3em 2em;
		}
		
	}

	.login h5 {
		color: #2a5a7a;
	}
	.text-left{
		text-align : left !important;
		vertical-align : iddle !important;
	}
  </style>	
	
<script src="<?php echo base_url();?>adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo base_url()?>adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>adminlte/plugins/iCheck/icheck.min.js"></script>

</body>
</html>
