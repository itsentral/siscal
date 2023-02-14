
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-envelope"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
			</h3>
			
			
		</div>
		<div class="box-body">
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Nomor</label>
						<?php
							echo form_input(array('id'=>'nomor_bast','name'=>'nomor_bast','class'=>'form-control input-sm','readOnly'=>true),$rows_header['nomor']);						
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tanggal BAST</label>
						<?php
							echo form_input(array('id'=>'tgl_bast','name'=>'tgl_bast','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header['datet'])));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Customer</label>
						<?php
							echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header['customer_name']);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Alamat</label>
						<?php
							echo form_textarea(array('id'=>'address', 'name'=>'address','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header['address']);						
						?>
					</div>
				</div>				
			</div>
			
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Pengirim</label>
						<?php
							echo form_input(array('id'=>'send_by','name'=>'send_by','class'=>'form-control input-sm','readOnly'=>true),$rows_header['send_by']);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Notes</label>
						<?php
							echo form_textarea(array('id'=>'notes', 'name'=>'notes','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header['descr']);
						?>
					</div>
				</div>
								
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Penerima</label>
						<?php
							echo form_input(array('id'=>'receive_by','name'=>'receive_by','class'=>'form-control input-sm','readOnly'=>true),$rows_header['receive_by']);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tgl Terima</label>
						<?php
							$Tgl_Terima	= '-';
							if($rows_header['receive_date']){
								$Tgl_Terima	= date('d F Y',strtotime($rows_header['receive_date']));
							}
							echo form_input(array('id'=>'receive_date','name'=>'receive_date','class'=>'form-control input-sm','readOnly'=>true),$Tgl_Terima);
						?>
					</div>
				</div>
								
			</div>
		</div>		
	</div>
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-refresh"></i> <?php echo('<span class="important">Data Detail</span>'); ?>
			</h3>			
		</div>
		<div class="box-body">
			<table class="table table-striped table-bordered" id="my-grid">
				<thead>
					<tr class="bg-blue">
						<td class="text-center">No</td>
						<th class="text-center">Nama Alat</th>
						<th class="text-center">Merk</th>				
						<th class="text-center">Tipe</th>
						<th class="text-center">No Identifikasi</th>
						<th class="text-center">No Sertifikat</th>
						<th class="text-center">No PO</th>
						<th class="text-center">No SO</th>
					</tr>
				</thead>
				<tbody id="list_detail">
					<?php
					if($rows_detail){
						$intI		= 0;
						
						foreach($rows_detail as $ketD=>$valD){
							$intI++;
							$Qry_SO		= "SELECT no_so FROM letter_orders WHERE id='".$valD['letter_order_id']."'";
							$det_SO		= $this->db->query($Qry_SO)->result();
							
							$Qry_Quot	= "SELECT pono FROM quotations WHERE id='".$valD['quotation_id']."'";
							$det_Quot	= $this->db->query($Qry_Quot)->result();
							
							echo"<tr>";
								
								echo"<td class='text-center'>".$intI."</td>";
								echo"<td class='text-left'>".$valD['tool_name']."</td>";
								echo"<td class='text-left'>".$valD['merk']."</td>";
								echo"<td class='text-left'>".$valD['tool_type']."</td>";
								echo"<td class='text-center'>".$valD['no_identifikasi']."</td>";
								echo"<td class='text-left'>".$valD['certificate_no']."</td>";
								echo"<td class='text-center'>".$det_Quot[0]->pono."</td>";
								echo"<td class='text-center'>".$det_SO[0]->no_so."</td>";
								
							echo"</tr>";
						}
					}
					
				echo"</tbody>";
				
				?>
			</table>
		</div>
		
	</div>
</form>


<style>
	
</style>

