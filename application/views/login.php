<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="icon" type="image/png" href="<?php echo base_url();?>assets/img/sc_logo.png">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/styles-login.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/font-awesome/css/font-awesome.min.css">
		
        <title>SISCAL Dashboard | Log in</title>  

    </head>
    <body>
        <div class="l-form">
            <div class="shape1"></div>
            <div class="shape2"></div>

            <div class="form">
                <img src="<?php echo base_url()?>assets/img/logo-sc.jpg" alt="" class="form__img">

                <form class="form__content" action="<?php echo base_url('login')?>" method="post" autocomplete="off">
                    <h1 class="form__title">LOGIN<br/><text style="font-size: 20px;color:#03506F;">SISCAL Dashboard</text></h1>
					

                    <div class="form__div form__div-one">
                        <div class="form__icon">
                            <i class='fa fa-user'></i>
                        </div>

                        <div class="form__div-input">
                            <label for="" class="form__label"></label>
                            <input type="text" class="form__input" name="username" id="username" placeholder="Username" required>
                        </div>
                    </div>

                    <div class="form__div">
                        <div class="form__icon">
                            <i class='fa fa-lock' ></i>
                        </div>

                        <div class="form__div-input">
                            <label for="" class="form__label"></label>
                            <input type="password" class="form__input" name="password" id="password" placeholder="Password" required>
                        </div>
                    </div>
					
					<div>
						<text style="font-size:11px;color:red;"><?php echo $this->session->flashdata('alert_data'); ?></text>
					</div>

                    <a href="javascript:void(0);" class="form__forgot" onclick="lupapw();">Lupa Password?</a>
					<div class="alert alert-primary">
						<span class="icon">
							<i class="fa fa-info"></i>
						</span>
						
						<div class="text">
							<strong>Informasi</strong>
							<p>Hubungi admin untuk informasi password anda.</p>
						</div>
						
						<button type="button" class="close" onclick="closeinfo();">
							<i class="fa fa-close"></i>
						</button>
					</div>
                    <input type="submit" class="form__button" value="Login">

                    <div class="form__web">
                        <span class="form__web-text">Kunjungi Website</span><br/>
						<a href="https://www.sentralkalibrasi.co.id/" class="form__link" target="_blank"><text>www.sentralkalibrasi.co.id</text></a>
                    </div>
                </form>
            </div>

        </div>

		<script src="<?php echo base_url('adminlte/plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>
		<script>
			$(".alert").hide();
			function lupapw() {
				$(".alert").show();
			}

			function closeinfo() {
				$(".alert").hide();
			}
			$(function(){
				if($('#flash-message')){ window.setTimeout(function(){$('#flash-message').fadeOut();}, 3000); }
			});
		</script>
    </body>
</html>
