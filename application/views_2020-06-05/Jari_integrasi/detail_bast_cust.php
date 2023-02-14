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
				echo form_input(array('id'=>'custname','name'=>'custname','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->name);
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
				echo form_input(array('id'=>'noso','name'=>'noso','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->no_so);
				?>
			</div>
			<label class="control-label col-sm-2">Driver Order</label>
			<div class="col-sm-4">
				<?php
				echo form_input(array('id'=>'spk_driver','name'=>'spk_driver','class'=>'form-control input-sm','readOnly'=>true),$rows_driver[0]->nomor);
				?>
			</div>
		</div>
		<div class="form-group row">
			<label class="control-label col-sm-2">Category</label>
			<div class="col-sm-4">
				<?php
				$jenis_Bast	= $rows_header[0]->type_bast;
				$jenis_cust	= $rows_header[0]->flag_type;
				if($jenis_Bast==='DEL' && $jenis_cust==='CUST'){
					$Class		= "bg-maroon";
					$Category	= "Kirim Alat Ke Customer";
				}else if($jenis_Bast==='REC' && $jenis_cust==='CUST'){
					$Class		= "bg-green";
					$Category	= "Ambil Alat Ke Customer";
				}else if($jenis_Bast==='REC' && $jenis_cust==='SUPP'){
					$Class		= "bg-blue";
					$Category	= "Ambil Alat Ke Subcon";
				}else if($jenis_Bast==='DEL' && $jenis_cust==='SUPP'){
					$Class		= "bg-purple";
					$Category	= "Kirim Alat Ke Subcon";
				}
				echo "<span class='badge ".$Class."'>".$Category."</span>";
				?>
			</div>
			<label class="control-label col-sm-2">Driver</label>
			<div class="col-sm-4">
				<?php
				echo form_input(array('id'=>'driver','name'=>'driver','class'=>'form-control input-sm','readOnly'=>true),strtoupper($rows_driver[0]->member_name));
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
						</tr>
						
					</thead>
					<tbody id='list_item'>
						<?php
						if($rows_detail){
							$loop	=0;
							foreach($rows_detail as $keys=>$vals){
								$loop++;
								if($vals->qty_io > 0){
									$qty	= $vals->qty_io;
								}else{
									$qty	= $vals->qty;
								}
								
								echo"<tr id='tr_".$loop."'>";
									echo"<td align='center'>$loop</td>";
									echo"<td align='left'>".$vals->tool_id."</td>";
									echo"<td align='left'>".$vals->tool_name."</td>";
									echo"<td align='center'>".$qty."</td>";
									echo"<td align='left'>".$vals->descr."</td>";									
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
