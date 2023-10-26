<?php
$this->load->view('include/side_menu'); 

?> 

<div class="box box-warning">
	<div class="box-header">
		<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
		<div class="box-tools pull-right">	
			 <button class="btn btn-danger" id="btn-back" type="button">
			   <i class="fa fa-refresh"></i><b> Kembali</b>
			</button>
		</div>		
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<div class="form-group row">
			<label class="control-label col-sm-2">Bast No</label>
			<div class="col-sm-4">
				<?php
				echo form_input(array('id'=>'nobast','name'=>'nobast','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->nomor);
				?>
			</div>
			<label class="control-label col-sm-2">Date</label>
			<div class="col-sm-4">
				<?php
				echo form_input(array('id'=>'periode','name'=>'periode','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header[0]->datet)));
				?>
			</div>
		</div>
		<div class="form-group row">
			<label class="control-label col-sm-2">Company</label>
			<div class="col-sm-4">
				<?php
				echo form_input(array('id'=>'custname','name'=>'custname','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->customer_name);
				?>
			</div>
			<label class="control-label col-sm-2">Address</label>
			<div class="col-sm-4">
				<?php
				echo form_textarea(array('id'=>'keterangan', 'name'=>'keterangan','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->address);
				?>
			</div>
		</div>
		<div class="form-group row">
			<label class="control-label col-sm-2">Sales Order</label>
			<div class="col-sm-4">
				<?php
				echo form_input(array('id'=>'noso','name'=>'noso','class'=>'form-control input-sm','readOnly'=>true),$rows_letter[0]->no_so);
				?>
			</div>
			<label class="control-label col-sm-2">Quotation</label>
			<div class="col-sm-4">
				<?php
				echo form_input(array('id'=>'quotation','name'=>'quotation','class'=>'form-control input-sm','readOnly'=>true),$rows_quot[0]->nomor);
				?>
			</div>
		</div>
		
	</div>
	<div class="box-body">
		<div class="box box-danger">
			<div class="box-header">
				<h3 class="box-title">
					<i class="fa fa-star"></i> <?php echo('<span class="important">Data Detail</span>'); ?>
				</h3>				
			</div>
			<div class="clearfix box-body">
				<table class='table table-bordered table-striped'>
					<thead>
						<tr class='bg-blue'>
							<td align="center"><b>No</b></td>
							<td align="center"><b>Kode Alat</b></td>
							<td align="center"><b>Nama Alat</b></td>
							<td align="center"><b>Qty</b></td>
							<td align="center"><b>Keterangan</b></td>
							<td align="center"><b>Teknisi</b></td>
						</tr>
						
					</thead>
					<tbody id='list_item'>
						<?php
						if($rows_detail){
							$loop	=0;
							foreach($rows_detail as $keys=>$vals){
								$loop++;
								$qty	= $vals->qty;
								
								
								echo"<tr id='tr_".$loop."'>";
									echo"<td align='center'>$loop</td>";
									echo"<td align='left'>".$vals->tool_id."</td>";
									echo"<td align='left'>".$vals->tool_name."</td>";
									echo"<td align='center'>".$qty."</td>";
									echo"<td align='left'>".$vals->descr."</td>";
									echo"<td align='center'>".$vals->member_name."</td>";
								echo"</tr>";
							}
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	</div>

<?php $this->load->view('include/footer'); ?>
<!-- page script -->
<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
    $(function() {		
		 $('#btn-back').click(function(){
			 loading_spinner();
			 window.location.href =  base_url+'index.php/'+active_controller;
		 });
		
	});
	
	
</script>
