<?php
$this->load->view('include/side_menu');
?> 
	<div class="box box-warning">
		<div class="box-header">			
			<div class="box-tools pull-right">
			<button type="button" onClick="PrintBarcodeNew()" class="btn btn-sm btn-warning" title="PRINT CALIBRATIONS BARCODE QR"> <i class="fa fa-print"></i> </button>
			</div>
		</div> 
		<div class="box-body">
			
		</div>		
	
		
	</div>
<?php $this->load->view('include/footer'); ?>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= 'Getqr';
	

	function PrintBarcodeNew(){
		var Code_Print = 'SCH-202310-07577-1-1';
		var Flag_QR	= 'Y';
		loading_spinner_new();

			var Barcode_Action	= 'print_barcode_calibration_new';

		 $.post(base_url +'/'+ active_controller+'/'+Barcode_Action,{'code':Code_Print}, function(response) {
			close_spinner_new();
            //console.log(response);
			const datas	= $.parseJSON(response);
			window.open(datas.path,'_blank');
        });
		
	};

	
</script>
