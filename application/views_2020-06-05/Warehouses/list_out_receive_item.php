<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-warning">
	<div class="box-header">
		<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
		<div class="box-tools pull-right">	
			<?php
				echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','id'=>'btn-excel','content'=>'DOWNLOAD EXCEL'));	
			?>
		</div>	
	</div>
    <!-- /.box-header -->
	<div class="box-body">
        <table id="my-grid" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">Tool ID</th>
					<th class="text-center">Tool Name</th>
					<th class="text-center">Qty</th>
					<th class="text-center">Qty Outs</th>
					<th class="text-center">Quotation</th>
					<th class="text-center">Quot Date</th>
					<th class="text-center">Company</th>
					<th class="text-center">No PO</th>
					<th class="text-center">PO Date</th>
					<th class="text-center">Supplier</th>
				</tr>
			</thead>

			<tbody id="list_detail">
           
			</tbody>
			
        </table>
    </div>
	
    <!-- /.box-body -->
</div>
<?php $this->load->view('include/footer'); ?>
<!-- page script -->
<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
    $(function() {		
		 $('#btn-excel').click(function(){
			var Links		= base_url+active_controller+'/get_excel_outs_receive';
			window.open(Links,'_blank');
		 });
		data_display();
		
	});
	

	function data_display(){
		var cabang		= $('#kdcab').val();
		var table_data = $('#my-grid').dataTable( {
			"paging"	: true,
			"processing": true,
			"serverSide": true,
			'destroy'	: true,
			"ajax": {
				"url"	:  base_url + active_controller+'/get_data_display',
				"type"	: "POST"
				/*
				"data"	:{'cabang':cabang}
				*/
							
			},		 
			"columns": [
				{"data":"tool_id","sClass":"text-center"},
				{"data":"tool_name","sClass":"text-left"},
				{"data":"qty","sClass":"text-center"},
				{"data":"sisa_so","sClass":"text-center"},
				{"data":"nomor","sClass":"text-left"},
				{"data":"datet","sClass":"text-center"},
				{"data":"customer_name","sClass":"text-left"},
				{"data":"pono","sClass":"text-center"},
				{"data":"podate","sClass":"text-center"},
				{"data":"supplier_name","sClass":"text-left"}
			],
			"rowCallback": function(row,data,index,iDisplayIndexFull){
				let Template	='<span class="badge bg-green">'+data.sisa_so+'</span>';
				$('td:eq(3)',row).html(Template);
			},			
			"order": [[5,"desc"]]
		});
		
	}
	
</script>
