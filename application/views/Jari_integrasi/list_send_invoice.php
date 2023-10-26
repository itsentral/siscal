<?php
$this->load->view('include/side_menu'); 

?> 
<form action="#" method="POST" id="form-proses">
	<div class="box box-warning">
		<div class="box-header">
			<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
				
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="form-group row">
				<label class="control-label col-sm-2">Plan Delivery</label>
				<div class="col-sm-2">
					<?php
					echo form_input(array('id'=>'periode','name'=>'periode','class'=>'form-control input-sm','readOnly'=>true),date('Y-m-d'));
					?>
				</div>
				<label class="control-label col-sm-2">Driver</label>
				<div class="col-sm-2">
					<?php
											
					echo form_dropdown('driver',$rows_driver, '', array('id'=>'driver','class'=>'form-control input-sm'));
					?>
				</div>
				<div class="col-sm-2">
					<?php
					if($akses_menu['download'] == '1'){
						echo form_button(array('type'=>'button','class'=>'btn btn-md btn-success','id'=>'btn-create','content'=>'DOWNLOAD EXCEL'));
					}
					
					?>
				</div>
			</div>			
		</div>
		<div class="box-body">
			<div class="box box-danger">
				<div class="box-header">
					<h4 class="box-title"><i class="fa fa-check-square"></i> Detail Invoice</h4>
					<div class="box-tools pull-right">	
						<?php
						echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','id'=>'btn-add-rows','content'=>'ADD INVOICE'));
						?>
					</div>	
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<table id="my-grid" class="table table-bordered table-striped">
						<thead>
							<tr class="bg-blue">
								<th class="text-center">Invoice No</th>
								<th class="text-center">Inv Date</th>
								<th class="text-center">Customer</th>
								<th class="text-center">Address</th>
								<th class="text-center">DPP</th>
								<th class="text-center">PPN</th>
								<th class="text-center">Total</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody id="list_detail">
							
						</tbody>						
					</table>
				</div>
			</div>
		</div>
		<!-- /.box-body -->
	</div>
	<div class="modal fade" id="MymodalInv" >
		<div class="modal-dialog" style="width:80%">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">List Of Invoice</h4>
				</div>
				<div class="modal-body" id="MymodalInv-list">
				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<?php $this->load->view('include/footer'); ?>
<!-- page script -->
<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var arr_akses			= <?php echo json_encode($akses_menu);?>;
    $(function() {
		
		$('#btn-create').click(function(e){
			e.preventDefault();
			let periode_pilih	= $('#periode').val();
			let nama_driver		= $('#driver').val();			
			let chk_pilih 		= $('#list_detail').find('tr');
			if(periode_pilih == '' || periode_pilih==null){
				swal({
					  title	: 'Error Message',
					  text	: 'Empty Period Task, please input period task first....',
					  type	: 'warning'
				});
				return false;
			}
			
			if(nama_driver == '' || nama_driver==null){
				swal({
					  title	: 'Error Message',
					  text	: 'Empty Driver Name, please choose driver name first....',
					  type	: 'warning'
				});
				return false;
			}
			
			if(chk_pilih.length < 1){
				swal({
					  title	: 'Error Message',
					  text	: 'No Record was selected, please choose at least one record....',
					  type	: 'warning'
				});
				return false;
			}
			$('#form-proses').attr('action',base_url+'index.php/'+active_controller+'/report_excel');
			$('#form-proses').submit();
		 });
		$('#periode').datepicker({
			dateFormat	: 'yy-mm-dd',
			changeMonth	: true,
			changeYear	: true,
			maxDate		: '+7d',
			onSelect	: function(dateText, inst) { 
				
			}
		});
		
		
	});
	$(document).on('click','#btn-add-rows',function(e){
		e.preventDefault();
		$('#MymodalInv-list').empty();
		
		loading_spinner_new();
		var formData 	= new FormData($('#form-proses')[0]);
		var baseurl		= base_url +'index.php/'+active_controller+'/list_detail';
		$.ajax({
			url			: baseurl,
			type		: "POST",
			success		: function(data){
				close_spinner_new();
				$('#MymodalInv-list').html(data);
				$('#MymodalInv').modal('show');
				data_display();	
			},
			error: function() {
				close_spinner_new();
				swal({
				  title				: "Error Message !",
				  text				: 'An Error Occured During Process. Please try again..',						
				  type				: "warning"
				});
				
				return false;
			}
		});	
	});
	
	function data_display(){
		
		var table_data = $('#my-grid-inv').dataTable( {
			"paging"	: true,
			"processing": true,
			"serverSide": true,
			'destroy'	: true,
			"ajax": {
				"url"	:  base_url + 'index.php/'+active_controller+'/get_data_display',
				"type"	: "POST"
			},		 
			"columns": [
				{"data":"invoice_no","sClass":"text-center"},
				{"data":"datet","sClass":"text-center"},
				{"data":"customer_name","sClass":"text-left"},
				{"data":"address","sClass":"text-left"},
				{"data":"total_dpp","sClass":"text-right"},
				{"data":"ppn","sClass":"text-right"},
				{"data":"grand_tot","sClass":"text-right"},
				{"data":"action","sClass":"text-center","searchable":false}
			],
			"rowCallback": function(row,data,index,iDisplayIndexFull){
				
				let kode_inv	= data.id;
				let no_inv		= data.invoice_no;
				let date_inv	= data.datet;
				let cust_inv	= data.customer_name;
				let addr_inv	= data.address;
				let dpp_inv		= data.total_dpp;
				let ppn_inv		= data.ppn;
				let tot_inv		= data.grand_tot;
				let Template2	= '<input type="hidden" id="kode_'+kode_inv+'" value="'+kode_inv+'">';
				Template2		+= '<input type="hidden" id="nomor_'+kode_inv+'" value="'+no_inv+'">';
				Template2		+= '<input type="hidden" id="customer_'+kode_inv+'" value="'+cust_inv+'">';
				Template2		+= '<input type="hidden" id="alamat_'+kode_inv+'" value="'+addr_inv+'">';
				Template2		+= '<input type="hidden" id="harga_'+kode_inv+'" value="'+dpp_inv+'">';
				Template2		+= '<input type="hidden" id="pajak_'+kode_inv+'" value="'+ppn_inv+'">';
				Template2		+= '<input type="hidden" id="jumlah_'+kode_inv+'" value="'+tot_inv+'">';
				Template2		+= '<input type="hidden" id="tanggal_'+kode_inv+'" value="'+date_inv+'">';
				Template2		+= '<button type="button" class="btn btn-sm btn-info" onClick="pilih_invoice('+'\''+kode_inv+'\''+');"> PILIH </button>';
				
				$('td:eq(7)',row).html(Template2);
			},
			"order": [[1,"desc"]]
		});
		
	}
	
	function pilih_invoice(id){	
		
		var invoice_id			= $('#kode_'+id).val();
		var invoice_nomor		= $('#nomor_'+id).val();
		var invoice_cust		= $('#customer_'+id).val();
		var invoice_addr		= $('#alamat_'+id).val();
		var invoice_dpp			= $('#harga_'+id).val();
		var invoice_ppn			= $('#pajak_'+id).val();
		var invoice_total		= $('#jumlah_'+id).val();
		var invoice_date		= $('#tanggal_'+id).val();
		
		
		var total	=$('#list_detail').find('tr').length;
		//alert('total row '+total);
		var ada		= 0;
		var loop	= 1;
		if(parseInt(total) > 0){
			var nil		= $('#list_detail tr:last').attr('id');
			var jum		= nil.split('_');
			var loop	= parseInt(jum[1])+1;
			//alert('ono '+loop+' jumlah '+jum[1]);
			var ada		=0;
			$('#list_detail').find('input.cekD:hidden').each(function(){
				var hasil	= $(this).val();
				if(hasil==invoice_id){
					ada++;
				}
			});
		}
		if(ada==0){
			Template	='<tr id="tr_'+loop+'">';
			Template	+='<td align="left">';
				Template+='<input type="hidden" name="data_pilihan['+loop+']"  value="'+invoice_id+'" class="cekD">'+invoice_nomor;
			Template	+='</td>';
			Template	+='<td align="center">'+invoice_date+'</td>';
			Template	+='<td align="left">'+invoice_cust+'</td>';
			Template	+='<td align="left">'+invoice_addr+'</td>';
			Template	+='<td align="right">'+invoice_dpp+'</td>';
			Template	+='<td align="right">'+invoice_ppn+'</td>';
			Template	+='<td align="right">'+invoice_total+'</td>';
			Template	+='<td align="center"><button type="button" class="btn btn-sm btn-danger" title="Hapus Data" data-role="qtip" onClick="return DelItem('+loop+');"><i class="fa fa-trash-o"></i></button></td>';
			Template	+='</tr>';
			$('#list_detail').append(Template);			
			
		}
		
		
	}
	function DelItem(id){
		$('#list_detail #tr_'+id).remove();
		
	}
</script>
