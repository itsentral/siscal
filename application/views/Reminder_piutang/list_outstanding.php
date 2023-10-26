<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-warning">
	<div class="box-header">
		<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
		<div class="box-tools pull-right">	
			<?php
				echo form_button(array('type'=>'button','class'=>'btn btn-md btn-info','id'=>'btn-back','content'=>'BACK TO LIST'));
				
				
			?>
		</div>	
	</div>
    <!-- /.box-header -->
	<div class="box-body">
        <table id="my-grid" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">Cust Code</th>
					<th class="text-center">Customer Name</th>
					<th class="text-center">Address</th>
					<th class="text-center">Total Invoice</th>
					<th class="text-center">Total Debt</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody id="list_detail">
			<?php
			if($rows_data){
				foreach($rows_data as $key=>$vals){
					echo"<tr>";
						echo"<td class='text-left'>".$vals->customer_id."</td>";
						echo"<td class='text-left'>".$vals->customer_name."</td>";
						echo"<td class='text-left'>".$vals->address."</td>";
						echo"<td class='text-center'>".number_format($vals->jum_invoice)."</td>";
						echo"<td class='text-right'>".number_format($vals->total_piutang)."</td>";
						echo"<td class='text-center'>";
							echo"<a href='#' class='btn btn-md bg-maroon' onClick='process_letter(\"".$vals->customer_id."\");'>PROCESS <i class='fa fa-angle-double-right'></i> </a>";							
						echo"</td>";
					echo"</tr>";
				}
			}
			?>
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
		 $('#my-grid').dataTable();
		 $('#btn-back').click(function(){
			 loading_spinner();
			 window.location.href =  base_url+active_controller;
		 });
		
		
	});
	function process_letter(kode_cust){	
		loading_spinner();
		window.location.href =  base_url+active_controller+'/proses_letter/'+kode_cust;
	}
	

</script>
