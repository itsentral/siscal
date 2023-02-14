<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-warning">
	<div class="box-header">
		<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
		
	</div>
    <!-- /.box-header -->
	<div class="box-body">
		
        <table id="my-grid" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">Nomor</th>
					<th class="text-center">Date</th>
					<th class="text-center">Customer</th>
					<th class="text-center">Sales Order</th>
					<th class="text-center">PIC</th>
					<th class="text-center">Status</th>
					<th class="text-center">PIC Incharge</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody id="list_detail">
				<?php
				if($rows_data){
					foreach($rows_data as $key=>$vals){
						if($vals->sts_voc === 'CLS'){
							$Status		= 'FINISH JOB';
							$Kelas		= 'bg-maroon';
						}else if($vals->sts_voc === 'FOL'){
							$Status		= 'FOLLOW UP';
							$Kelas		= 'bg-green';
						}else{
							$Status		= 'ON PROGRESS';
							$Kelas		= 'bg-orange';
						}
						echo"<tr>";
							echo"<td class='text-center'>".$vals->nomor."</td>";
							echo"<td class='text-center'>".date('d M Y',strtotime($vals->datet))."</td>";
							echo"<td class='text-left'>".$vals->customer_name."</td>";
							echo"<td class='text-center'>".$vals->no_so."</td>";
							echo"<td class='text-center'>".$vals->pic_name."</td>";
							echo"<td class='text-center'>";
								echo"<span class='badge $Kelas'>".$Status."</span>";
								echo"<td class='text-center'>".$vals->pic_incharge_name."</td>";
							echo"</td>";
							echo"<td class='text-center'>";
								echo"<a href='#' onClick='viewDetail(\"".$vals->id."\");' class='btn btn-sm bg-blue' title='View Detail'> <i class='fa fa-search'></i></a>";
								if($vals->sts_voc === 'CLS' && $akses_menu['update']===1){
									echo"&nbsp;&nbsp;&nbsp;<a href='#' onClick='closeDetail(\"".$vals->id."\");' class='btn btn-sm bg-red' title='Close VoC'> <i class='fa fa-check-square'></i></a>";
								}
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
<style>
	.ui-datepicker-calendar{
		display : none;
	}
</style>

<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
    $(function() {
		
		
	});
	
	function viewDetail(kode){
		loading_spinner();
		window.location.href =  base_url+'index.php/'+active_controller+'/view_detail/'+kode;
	}
	
	function closeDetail(kode){
		loading_spinner();
		window.location.href =  base_url+'index.php/'+active_controller+'/close_complain/'+kode;
	}
	
</script>
