<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5><?php echo $title;?></h5>
				</div>
				
			</div>
			<?php
			if(empty($rows_header)){
				echo"<div class='row'>
						<div class='col-sm-12'>
							<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
						</div>
					</div>";
			}else{
				
			?>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SPK No</label>
							<?php
								echo form_input(array('id'=>'nomor_spk','name'=>'nomor_spk','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->nomor);	
								echo form_input(array('id'=>'code_spk','name'=>'code_spk','class'=>'form-control input-sm','type'=>'hidden'),$rows_header[0]->id);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SPK Date</label>
							<?php
								echo form_input(array('id'=>'tgl_spk','name'=>'tgl_spk','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header[0]->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Driver</label>
							<?php
								echo form_input(array('id'=>'member_name','name'=>'member_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->member_name);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Description</label>
							<?php
								echo form_textarea(array('id'=>'keterangan','name'=>'keterangan','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$rows_header[0]->descr);						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL SPK </h5>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center">No</th>
									<th class="text-center">SO No</th>
									<th class="text-center">Company</th>				
									<th class="text-center">Address</th>
									<th class="text-center">Description</th>
									<th class="text-center">Type</th>
									<th class="text-center">Option</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;
										
										$Code_SPK	= $valD->spk_driver_id;
										$Code_SO	= $valD->letter_order_id;
										$Nomor_SO	= $valD->no_so;
										$Category	= $valD->kategori;
										$Tipe		= $valD->tipe;
										$Comp_Code	= $valD->comp_kode;
										$Flag_Pros	= $valD->flag_proses;
										$Flag_Print	= $valD->flag_print;
										$Flag_Update= $valD->flag_update;
										$Code_BAST	= $valD->bast_id;
										
										$Company    = $Address = '-';
										if($Category == 'CUST'){
											$Query_Cust	= "SELECT name, address FROM customers WHERE id = '".$Comp_Code."'";
											$rows_Cust	= $this->db->query($Query_Cust)->result();
											if($rows_Cust){
												$Company    = $rows_Cust[0]->name;
												$Address	= $rows_Cust[0]->address;
											}
										}else{
											$Query_Cust	= "SELECT supplier, address FROM suppliers WHERE id = '".$Comp_Code."'";
											$rows_Cust	= $this->db->query($Query_Cust)->result();
											if($rows_Cust){
												$Company    = $rows_Cust[0]->supplier;
												$Address	= $rows_Cust[0]->address;
											}
										}
										
										$Code_Unik	= $Category.'_'.$Comp_Code.'_'.$Code_SO;
										
										$Template		= '';
										if($Flag_Pros == 'N' && $akses_menu['create'] == 1){
											$Template		="<a href='".site_url('Warehouse_order/create_bast_spk_driver?spk='.urlencode($Code_SPK).'&jenis='.urldecode($Tipe).'&code='.urldecode($Code_Unik))."' class='btn btn-sm bg-navy-active' title='CREATE BAST'> <i class='fa fa-plus'></i> </a>";
										}										
										
										if($akses_menu['download'] == 1 && $Flag_Print == 'N' && $Flag_Pros == 'Y'){
											if(!empty($Template))$Template .="&nbsp;";
											$Template		.="<a href='".site_url('Warehouse_order/print_bast/'.$Code_BAST)."' class='btn btn-sm btn-primary' title='Print BAST' target='_blank'> <i class='fa fa-print'></i> </a>";
										}
										
										
										echo"<tr>";										
											echo"<td align='center'>".$intL."</td>";
											echo"<td align='center'>".$Nomor_SO."</td>";
											echo"<td align='left'>".$Company."</td>";
											echo"<td align='left' class='text-wrap'>".$Address."</td>";
											echo"<td align='center'>".(($Tipe == 'SEND')?'Send Tools':'Pick Up Tool')."</td>";
											echo"<td align='center'>".(($Category == 'SUB')?'SUBCON':'CUSTOMER')."</td>";
											echo"<td align='center'>".$Template."</td>";
										echo"</tr>";
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				
			<?php
			}
		echo'</div>';
		echo"<div class='box-footer'>";	
			echo'
				<button type="button" class="btn btn-md btn-danger" id="btn-back"> <i class="fa fa-long-arrow-left"></i> BACK </button>';
		
		echo"</div>";
		?>
		
	</div>
</form>

<?php $this->load->view('include/footer'); ?>
<style>
	.sub-heading{
		border-radius :5px;
		background-color :#03506F;
		color : white;
		margin : 20px 10px 15px 10px !important;
		width :98% !important;
	}
	.ui-spinner-input{
		padding :10px 5px 10px 10px !important;
	}
	.text-wrap{
		word-wrap : break-word !important;
	}
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'/'+active_controller;
		});
		
	});
	
	
	
</script>
