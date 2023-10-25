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
					<th class="text-center">Cust Tool</th>
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
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
    $(function() {		
		 $('#btn-excel').click(function(){
			var Links		= base_url+'/'+active_controller+'/get_excel_outs_receive';
			window.open(Links,'_blank');
		 });
		data_display();
		
	});
	
	function data_display(){
		
		let table_data 		= $('#my-grid').DataTable({
			"serverSide": true,
			"destroy"	: true,
			"stateSave" : false,
			"bAutoWidth": false,
			"oLanguage": {
				"sSearch": "<b>Live Search : </b>",
				"sLengthMenu": "_MENU_ &nbsp;&nbsp;<b>Records Per Page</b>&nbsp;&nbsp;",
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
				"sInfoFiltered": "(filtered from _MAX_ total entries)", 
				"sZeroRecords": "No matching records found", 
				"sEmptyTable": "No data available in table", 
				"sLoadingRecords": "Please wait - loading...", 
				"oPaginate": {
					"sPrevious": "Prev",
					"sNext": "Next"
				}
			},
			"aaSorting": [[ 6, "desc" ]],			
			"columnDefs": [
				{"targets":0,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":1,"sClass":"text-left"},
				{"targets":2,"sClass":"text-left"},
				{"targets":3,"sClass":"text-center"},
				{"targets":4,"sClass":"text-center"},
				{"targets":5,"sClass":"text-center"},
				{"targets":6,"sClass":"text-center"},
				{"targets":7,"sClass":"text-left"},
				{"targets":8,"sClass":"text-center"},
				{"targets":9,"sClass":"text-center"},
				{"targets":10,"sClass":"text-left"}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 10,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/get_data_display',
				type	: "post",
				cache	: false,
				data	: {},
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="11">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
</script>
