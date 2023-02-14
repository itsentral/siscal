<?php
$this->load->view('include/side_menu'); 

?> 
<form action="#" method="POST" id="form-proses">
	<div class="box box-warning">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-envelope"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
			</h3>
		</div>
		<div class="box-body">
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>No VoC <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'nomor','name'=>'nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['nomor']);
						echo form_input(array('id'=>'kode_voc','name'=>'kode_voc','type'=>'hidden'),$rows_header[0]['id']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Date VoC <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'datet','name'=>'datet','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header[0]['datet'])));
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Receive By <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'rec_by','name'=>'rec_by','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['rec_by']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Customer <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'customer_id','name'=>'customer_id','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['customer_name']);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>No Order <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'noso','name'=>'noso','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['no_so']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>PIC Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['pic_name']);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>PIC Phone <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['pic_phone']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>PIC Email <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_email','name'=>'pic_email','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['pic_email']);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Plan Close <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'plan_close','name'=>'plan_close','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header[0]['plan_close'])));
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Customer Feedback</b></label> 
				<div class='col-sm-4'>
					<?php
					echo form_textarea(array('id'=>'cust_feedback', 'name'=>'cust_feedback','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'));
					?>
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Rate <span class='text-red'>*</span></b></label> 
				<div class='col-sm-2' id="leads">
					 <div id="stars" class="starrr"></div>
                    <b>You gave a rating of <span id="count" class="text-red">0</span> star(s)</b>
					<input type="hidden" name="rating_val" id="rating_val" value="0">
				</div>
				<label class='label-control col-sm-4 text-blue'><b>Give 1 star for bad service, 5 for good</b></label> 
				<div class='col-sm-4'>
					
				</div>				
			</div>
		</div>
		<div class="box-footer">
			<?php
				echo"<button type='button' class='btn btn-md btn-danger' id='btn-back'> <i class='fa fa-angle-double-left'></i> BACK </button>&nbsp;&nbsp;&nbsp;";	
				echo"<button type='button' class='btn btn-md btn-success' id='btn-save'>CLOSE VOC <i class='fa fa-save'></i>  </button>";
			?>
		</div>
	</div>
	<div class="box box-warning">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-envelope"></i> <?php echo('<span class="important">Detail Complain</span>'); ?>
			</h3>
		</div>
		<div class="box-body">
			<?php
				if($rows_detail){
					$id_Accord	= "MyAccordion";
					echo"<div class='panel-group' id='".$id_Accord."'>";
						$Loop	= 0;
						foreach($rows_detail as $key=>$vals){
							$Loop++;
							$Coll_Name	= "tutup_".$Loop;
							echo"<div class='panel panel-info'>";
								echo"<div class='panel-heading'>";
									echo"<h4 class='panel-title'>";
										echo"<a data-toggle='collapse' href='#".$Coll_Name."' data-parent='#".$id_Accord."'>".$Loop.". ".$vals['descr']."</a>";
										
									echo"</h4>";
								echo"</div>";
								echo"<div id='".$Coll_Name."' class='panel-collapse collapse in'>";
									echo"<div class='panel-body'>";												
										echo"<table id='my-grid' class='table table-bordered table-striped'>
											<thead>					
												<tr class='bg-blue'>
													<th class='text-center' colspan='3'>Plan Action</th>
													<th class='text-center' colspan='3'>Actual</th>													
												</tr>
												<tr class='bg-blue'>
													<th class='text-center'>Description</th>
													<th class='text-center'>Plan Date</th>
													<th class='text-center'>PIC Incharge</th>
													<th class='text-center'>Description</th>
													<th class='text-center'>Actual Date</th>
													<th class='text-center'>PIC Incharge</th>
												</tr>
											</thead>
											<tbody id='list_detail_".$vals['id']."'>";
												$data_Detail	= $this->db->get_where('complain_customer_actions',array('complain_customer_detail_id'=>$vals['id']))->result();
												if($data_Detail){
													foreach($data_Detail as $keyD=>$valD){
														echo"<tr>";
															echo"<td class='text-left'>".$valD->plan_action."</td>";
															echo"<td class='text-center'>".date('d M Y',strtotime($valD->plan_due_date))."</td>";
															echo"<td class='text-center'>".$valD->plan_action_by_name."</td>";
															echo"<td class='text-left'>".$valD->descr."</td>";
															echo"<td class='text-center'>";
																$actual_date	='-';
																if($valD->actual_finish_date){
																	$actual_date	= date('d M Y',strtotime($valD->actual_finish_date));
																}
																echo $actual_date;
															echo"</td>";
															echo"<td class='text-center'>".$valD->actual_action_by_name."</td>";
															
															
														echo"</tr>";
													}
												}
											echo"</tbody>
											
										</table>";
									echo"</div>";
								echo"</div>";
							echo"</div><br>";
						}
						
					echo"</div>";
				}
			?>
				
		</div>
		
	</div>
</form>

<?php $this->load->view('include/footer'); ?>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'index.php/'+active_controller;
		});
		CreateStar();
	});
	$(document).on('click','#btn-save',function(e){
		e.preventDefault();
		$('#btn-save, #btn-back').prop('disabled',true);
		let rate_nil		= $('#rating_val').val();
		
		if(rate_nil === null || rate_nil ==='' || parseInt(rate_nil) === 0){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Rate. Please input rate first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}
		
		swal({
			  title: "Are you sure?",
			  text: "You will not be able to process again this data!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "Yes, Process it!",
			  cancelButtonText: "No, cancel process!",
			  closeOnConfirm: true,
			  closeOnCancel: false
			},
			function(isConfirm) {					
				if (isConfirm) {
					loading_spinner_new();
					var formData 	= new FormData($('#form-proses')[0]);
					var baseurl		= base_url +'index.php/'+ active_controller+'/close_complain';
					$.ajax({
						url			: baseurl,
						type		: "POST",
						data		: formData,
						cache		: false,
						dataType	: 'json',
						processData	: false, 
						contentType	: false,				
						success		: function(data){
							close_spinner_new();
							if(data.status == 1){											
								swal({
									  title	: "Save Success!",
									  text	: data.pesan,
									  type	: "success"
									});
								window.location.href = base_url +'index.php/'+ active_controller;
							}else{
								
								if(data.status == 2){
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning"
									});
									
								}else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning"
									});
									
								}
								$('#btn-save, #btn-back').prop('disabled',false);
								return false;
								
							}
						},
						error: function() {
							close_spinner_new();
							swal({
							  title				: "Error Message !",
							  text				: 'An Error Occured During Process. Please try again..',						
							  type				: "warning"
							});
							$('#btn-save, #btn-back').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-save, #btn-back').prop('disabled',false);
					return false;
				}
		});	
	});
	
	function CreateStar(){
		var __slice = [].slice;

		(function($, window) {
			var Starrr;
			Starrr = (function() {
				Starrr.prototype.defaults = {
					rating: void 0,
					numStars: 5,
					change: function(e, value) {}
				};

				function Starrr($el, options) {
					var i, _, _ref,
					_this = this;

					this.options = $.extend({}, this.defaults, options);
					this.$el = $el;
					_ref = this.defaults;
					for (i in _ref) {
						_ = _ref[i];
						if (this.$el.data(i) != null) {
							this.options[i] = this.$el.data(i);
						}
					}
					this.createStars();
					this.syncRating();
					this.$el.on('mouseover.starrr', 'span', function(e) {
						return _this.syncRating(_this.$el.find('span').index(e.currentTarget) + 1);
					});
					this.$el.on('mouseout.starrr', function() {
						return _this.syncRating();
					});
					this.$el.on('click.starrr', 'span', function(e) {
						return _this.setRating(_this.$el.find('span').index(e.currentTarget) + 1);
					});
					this.$el.on('starrr:change', this.options.change);
				}

				Starrr.prototype.createStars = function() {
					var _i, _ref, _results;

					_results = [];
					for (_i = 1, _ref = this.options.numStars; 1 <= _ref ? _i <= _ref : _i >= _ref; 1 <= _ref ? _i++ : _i--) {
						_results.push(this.$el.append("<span class='glyphicon .glyphicon-star-empty'></span>"));
					}
					return _results;
				};

				Starrr.prototype.setRating = function(rating) {
					
					if (this.options.rating === rating) {
						//console.log('rate '+this.options.rating+' == '+rating);
						rating = 0;
						//console.log('rate '+this.options.rating+' == '+rating);
					}
					this.options.rating = rating;
					this.syncRating();
					return this.$el.trigger('starrr:change', rating);
				};

				Starrr.prototype.syncRating = function(rating) {
					var i, _i, _j, _ref;

					rating || (rating = this.options.rating);
					if (rating) {
						for (i = _i = 0, _ref = rating - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
							this.$el.find('span').eq(i).removeClass('glyphicon-star-empty').addClass('glyphicon-star');
						}
					}
					if (rating && rating < 5) {
						for (i = _j = rating; rating <= 4 ? _j <= 4 : _j >= 4; i = rating <= 4 ? ++_j : --_j) {
							this.$el.find('span').eq(i).removeClass('glyphicon-star').addClass('glyphicon-star-empty');
						}
					}
					if (!rating) {
						return this.$el.find('span').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
					}
				};

				return Starrr;

			})();
			return $.fn.extend({
				starrr: function() {
				var args, option;

				option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
				return this.each(function() {
					var data;

					data = $(this).data('star-rating');
					if (!data) {
						$(this).data('star-rating', (data = new Starrr($(this), option)));
					}
					if (typeof option === 'string') {
						return data[option].apply(data, args);
					}
				});
				}
			});
		})(window.jQuery, window);

		$(function() {
			return $(".starrr").starrr();
		});

		$( document ).ready(function() {
			$('#stars').on('starrr:change', function(e, value){
				$('#count').html(value);
				$('#rating_val').val(value);
			});

			$('#stars-existing').on('starrr:change', function(e, value){
				$('#count-existing').html(value);
			});
		});
	}
</script>
<style type="text/css">
   
    .lead {
        font-size: 37px;
    }
    .glyphicon {
		color		: #f6a821;
		font-size 	: 35px;
	}
</style>