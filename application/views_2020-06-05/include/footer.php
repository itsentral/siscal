					</div>
				</div>
			</section>
		</div>
	</div>
  <!-- /.content-wrapper -->
	<footer class="main-footer">    
		<strong>Copyright &copy; <?php echo date('Y');?> <a href="#">SENTRAL KALIBRASI</a>.</strong> All rights reserved.
	</footer>

  
	<aside class="control-sidebar control-sidebar-dark">
		<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
		  <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
		  <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
		</ul>
    <!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane" id="control-sidebar-home-tab"></div>
			<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
			<div class="tab-pane" id="control-sidebar-settings-tab">
				<form method="post">
					<h3 class="control-sidebar-heading">General Settings</h3>
				</form>
			</div>     
		</div>
	</aside>
	<div class="control-sidebar-bg"></div>
</div>

<div id="spinner" class="spinner" style="display:none;">
	<img src="<?php echo base_url('assets/img/loading.gif') ?>" id="img-spinner" alt="Loading...">
</div>
<div class="modal fade" id="Mymodal" >
	<div class="modal-dialog" style="width:80%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="Mymodal-title"></h4>
			</div>
			<div class="modal-body" id="Mymodal-list">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<style>
	.ui-datepicker select.ui-datepicker-month, .ui-datepicker select.ui-datepicker-year{ 
		color:#666;
	}
	.spinner{
		position	: fixed;		 
		top			: 0; 
		right		: 0;
		bottom		: 0; 
		left		: 0;
		background-color : rgba(255,255,255,0.7);
		
	}
	#img-spinner {
		left: 50%;
		margin-left: -4em;
		font-size: 16px;
		border: .8em solid rgba(218, 219, 223, 1);
		border-left: .8em solid rgba(58, 166, 165, 1);
		animation: spin 1.1s infinite linear;
		
	}
	#img-spinner, #img-spinner:after {
		border-radius: 50%;
		width: 8em;
		height: 8em;
		display: block;
		position: absolute;
		top: 50%;
		margin-top: -4.05em;
	}

	@keyframes spin {
	  0% {
		transform: rotate(360deg);
	  }
	  100% {
		transform: rotate(0deg);
	  }
	}
</style>



<script src="<?php echo base_url('adminlte/plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url('adminlte/bootstrap/js/bootstrap.min.js'); ?>"></script>
<!-- DataTables -->
<script src="<?php echo base_url('adminlte/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('adminlte/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url('adminlte/plugins/fastclick/fastclick.js'); ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url('adminlte/plugins/daterangepicker/daterangepicker.js') ?>"></script>
<!-- bootstrap datepicker -->
<!--<script src="<?php echo base_url('adminlte/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>!-->	
<script src="<?php echo base_url('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>

<!-- AdminLTE App -->
<script src="<?php echo base_url('adminlte/dist/js/app.min.js'); ?>"></script>
<!-- Sparkline -->
<script src="<?php echo base_url('adminlte/plugins/sparkline/jquery.sparkline.min.js'); ?>"></script>
<!-- jvectormap -->
<script src="<?php echo base_url('adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'); ?>"></script>
<script src="<?php echo base_url('adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'); ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url('adminlte/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
<!-- ChartJS 1.0.1 -->
<script src="<?php echo base_url('adminlte/plugins/chartjs/Chart.min.js'); ?>"></script>
<script src="<?php echo base_url('jquery-ui/jquery-ui.min.js') ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url('adminlte/plugins/iCheck/icheck.min.js') ?>"></script>
<script src="<?php echo base_url('chosen/chosen.jquery.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dist/event_keypress.js'); ?>"></script>
<script src="<?php echo base_url('assets/dist/jquery.maskMoney.js'); ?>"></script>
<script src="<?php echo base_url('assets/dist/jquery.maskedinput.min.js'); ?>"></script>
<!-- AdminLTE for demo purposes -->

<script src="<?php echo base_url('adminlte/dist/js/demo.js'); ?>"></script>
<script src="<?php echo base_url('sweetalert/dist/sweetalert.min.js'); ?>"></script>
<!--<script src="<?php echo base_url('assets/dist/bootstrap-datepicker.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/fusioncharts/fusioncharts.js');?>"></script>!-->
<script src="<?php echo base_url('assets/plugins/apexcharts/dist/apexcharts.min.js');?>"></script>
<script type="text/javascript">
	$.widget.bridge('uibutton', $.ui.button);
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var active_action		= '<?php echo($this->uri->segment(2)); ?>';

	
	$(function(){
		$('#spinner').bind("ajaxSend",function(){
			$(this).show();
		}).bind("ajaxStop",function(){
			$(this).hide();
		}).bind("ajaxError",function(){
			$(this).hide();
		});
		$("#example1").DataTable();
		$('select').addClass('chosen-select');
		$('input[type="text"][data-role="datepicker"]').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			maxDate:'+0d'
		});
		$('[data-role="qtip"]').tooltip();
		
		if($('#flash-message')){ window.setTimeout(function(){$('#flash-message').fadeOut();}, 3000); }
		
		
		//	B:CHOSEN SETUP =================================================================================================================================

		//	general setup
		$('.chosen-select').chosen({
			allow_single_deselect	: true, 
			search_contains			: true, 
			no_results_text			: 'No result found for : ', 
			placeholder_text_single	: 'Select an option'
		});
		
		//	disable chosen for multiple select, and data grid's select
		//select[multiple="multiple"],
		$('#data-grid select , #listDetailShift select').removeAttr('style', '').removeClass('chzn-done').data('chosen', null).next().remove();

		//	E:CHOSEN SETUP =================================================================================================================================
	
		
	});
	
	function back(){			
		loading_spinner();
		window.location.href = base_url +'index.php/'+ active_controller;
	}
	
	
	function loading_spinner(){
		swal({
		  title: "Loading!",
		  text: "Please Wait..........",
		  imageUrl: base_url+'assets/img/loading.gif',
		  showConfirmButton: false,
		  showCancelButton: false
		});
	}
	
	function loading_spinner_new(){
		$('#spinner').show();
	}
	function close_spinner_new(){
		$('#spinner').hide();
	}
</script>

</body>
</html>
