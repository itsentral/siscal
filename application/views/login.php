<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SISCAL | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url()?>adminlte/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()?>adminlte/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url()?>adminlte/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
	.form-box {
	  width: 360px;
	  margin: 90px auto 0 auto;
	}
	.form-box .header {
	  -webkit-border-top-left-radius: 4px;
	  -webkit-border-top-right-radius: 4px;
	  -webkit-border-bottom-right-radius: 0;
	  -webkit-border-bottom-left-radius: 0;
	  -moz-border-radius-topleft: 4px;
	  -moz-border-radius-topright: 4px;
	  -moz-border-radius-bottomright: 0;
	  -moz-border-radius-bottomleft: 0;
	  border-top-left-radius: 4px;
	  border-top-right-radius: 4px;
	  border-bottom-right-radius: 0;
	  border-bottom-left-radius: 0;
	  background: #3c8cbd;
	  box-shadow: inset 0px -3px 0px rgba(0, 0, 0, 0.2);
	  padding: 20px 10px;
	  text-align: center;
	  font-size: 26px;
	  font-weight: 300;
	  color: #fff;
	}
	.form-box .body,
	.form-box .footer {
	  padding: 10px 20px;
	  background:#5d7b89;
	  color: #fff;
	}
	.form-box .body > .form-group,
	.form-box .footer > .form-group {
	  margin-top: 20px;
	}
	.form-box .body > .form-group > input,
	.form-box .footer > .form-group > input {
	  border: #fff;
	}
	.form-box .body > .btn,
	.form-box .footer > .btn {
	  margin-bottom: 10px;
	}
	.form-box .footer {
	  -webkit-border-top-left-radius: 0;
	  -webkit-border-top-right-radius: 0;
	  -webkit-border-bottom-right-radius: 4px;
	  -webkit-border-bottom-left-radius: 4px;
	  -moz-border-radius-topleft: 0;
	  -moz-border-radius-topright: 0;
	  -moz-border-radius-bottomright: 4px;
	  -moz-border-radius-bottomleft: 4px;
	  border-top-left-radius: 0;
	  border-top-right-radius: 0;
	  border-bottom-right-radius: 4px;
	  border-bottom-left-radius: 4px;
	}
	@media (max-width: 767px) {
	  .form-box {
		width: 90%;
	  }
	}
  </style>
</head>
<body class="hold-transition login-page">
	<form action="<?php echo base_url('index.php/login')?>" method="post">
		<div class='form-box'> 
			<?php
			
				echo $this->session->flashdata('alert_data');
			
			?>
			<div class='header'>
				<h3 class='box-title'>SISCAL Dashboard</h3>
				<img src="<?php echo base_url('assets/img/logo.jpg') ?>" height="75" width="75" class="img-circle" alt="Logo">
			</div>
			<div class="body" style="background:#b3c4cb">
				<div class="form-group row">
					<label class="control-label text-blue">Username</label>
					<div class="col-sm-12">
						<input type="text" name="username" id="username" class="form-control input-sm" placeholder="Username" autocomplete="off">
						
					</div>
				</div>
				<div class="form-group row">
					<label class="control-label text-blue">Password</label>
					<div class="col-sm-12">
						<input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off">						
					</div>
				</div>
			</div>
			<div class="footer">
				<button type="submit" class="btn btn-lg btn-primary" value="Submit" id="simpan_bro">Sign In</button>				
			</div>
		</div>    
	</form>
	<div class="modal fade" id="spinner" >
		<div class="modal-dialog">
			<div class="modal-content">	
				<div class="modal-header">
					<h4 class="modal-title">Please Wait.......</h4>
				</div>
				<div class="modal-body">
					<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>
				</div>				
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
<script src="<?php echo base_url();?>adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo base_url()?>adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>adminlte/plugins/iCheck/icheck.min.js"></script>
<script>
	$(function(){
			$('#simpan_bro').click(function(){
				var users		= $('#username').val();
				var password	= $('#password').val();
				if(users=='' || users==null){
					alert('Empty username. Please input username first...');
					return false;
				}
				
				if(password=='' || password==null){
					alert('Empty user password. Please input user password first...');
					return false;
				}
				$('#spinner').modal('show'); 
			});
			if($('#flash-message')){ window.setTimeout(function(){$('#flash-message').fadeOut();}, 3000); }
		});
</script>
</body>
</html>
