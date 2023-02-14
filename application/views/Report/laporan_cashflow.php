<?php
$this->load->view('include/side_menu'); 
?> 
<form action="<?php echo site_url(strtolower($this->uri->segment(1).'/'.$action))?>" method="POST" id="form-proses">
	<div class="box box-warning">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-money"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
			</h3>
			
		</div>
		<div class="box-body">
			<div class="form-group row">
				<label class='label-control col-sm-1'><b>Periode <span class='text-red'>*</span></b></label> 
				<div class='col-sm-2'>
					<?php
						echo form_input(array('id'=>'periode','name'=>'periode','class'=>'form-control input-sm','readOnly'=>true),$tahun_pilih);
					?>							
				</div>
				
				<div class='col-sm-2'>
					<?php
						echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-primary','value'=>'save','content'=>'Preview','id'=>'btn-preview')).' ';
					?>							
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-12 col-xs-12" id="det-chart">
				
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-12 col-xs-12" style="overflow-x:scroll !important;">
					<table class="table table-bordered table-striped">					
						<?php
						echo"<thead>";
							$Arr_Ket	= array('total_plan'=>'Plan','plan_paid'=>'Payment Of Plan','non_plan'=>'Payment Unplanned');
							echo"<tr class='bg-blue'>";
								foreach($rows_month as $key=>$val){
									echo"<th class='text-center' colspan='3'>".$val."</th>";
								}
							echo"</tr>";
							echo"<tr class='bg-blue'>";
								foreach($rows_month as $key=>$val){
									foreach($Arr_Ket as $keyK=>$valK){
										echo"<th class='text-center'>".$valK."</th>";
									}
									
								}
							echo"</tr>";
						echo"</thead>";
						echo"<tbody>";
							$rows_Detail	= $rows_data['rows_table'];
							echo"<tr>";
								foreach($rows_month as $key=>$val){
									foreach($Arr_Ket as $keyK=>$valK){
										$Nilai_Data	= $rows_Detail[$val][$keyK];
										$Link_Data	= number_format($Nilai_Data);
										if($Nilai_Data > 0){
											$Link_Data	= "<a href='#' onClick='view_detail_data(\"".$tahun_pilih."\",\"".$val."\",\"".$keyK."\");'>".number_format($Nilai_Data)."</a>";
										}
										echo"<td class='text-right'>".$Link_Data."</th>";
									}
									
								}
							echo"</tr>";
						echo"</tbody>";
							?>
						
					</table>
				</div>
			</div>
		</div>		
	</div>

<?php $this->load->view('include/footer'); ?>
<style>
	.ui-datepicker-calendar, 'ui-datepicker-month{
		display : none;
	}
</style>
<script>
	$(document).ready(function(){
		var rows_bulan		= <?php echo preg_replace('/"([a-zA-Z]+[a-zA-Z0-9]*)":/','$1:',json_encode($rows_month));?>;
		var rows_chart		= <?php echo preg_replace('/"([a-zA-Z]+[a-zA-Z0-9]*)":/','$1:',json_encode($rows_data['rows_chart']));?>;
		var options = {
            chart: {
                height: 350,
                type: 'line',
                shadow: {
                    enabled: false,
                    color: '#bbb',
                    top: 3,
                    left: 2,
                    blur: 3,
                    opacity: 1
                },
				toolbar: {
                    show: true
                }
            },
			colors: ['#FBC02D', '#00897B','#03A9F4'],
			dataLabels: {
                enabled: true,
            },
            stroke: {
                width: 7,   
                curve: 'smooth'
            },
            series: rows_chart,
            xaxis: {
                categories: rows_bulan,
            },
            title: {
                text: 'Plan Invoice Payment',
                align: 'left',
                style: {
                    fontSize: "16px",
                    color: '#666'
                }
            },
			grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
           /*
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: [ '#FDD835'],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100, 100, 100]
                },
            },
			*/
            markers: {
                size: 4,
                opacity: 0.9,
                colors: ["#FFA41B"],
                strokeColor: "#fff",
                strokeWidth: 2,
                 
                hover: {
                    size: 7,
                }
            },
            yaxis: {
                min: 0,
				title: {
                    text: 'Payment ( Millions )',
                },                
            }
        }

       var chart = new ApexCharts(
            document.querySelector("#det-chart"),
            options
        );
        
        chart.render();
		
		$("#periode").datepicker({
			changeMonth		: false,
			changeYear		: true,
			showButtonPanel	: true,
			dateFormat		: 'yy',
			minDate			: '2019-01-01',
			maxDate			:'+0d',
			onClose: function(dateText, inst) { 
				$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));				
			}
		});
		$('#btn-preview').click(function(){
			var tgl_awal	= $('#periode').val();
			
			if(tgl_awal ==='' || tgl_awal ===  null){
				swal({
				  title	: "Error Message!",
				  text	: 'Incorrect period Please input correct period.....',
				  type	: "warning"
				});
				return false;
			}
			loading_spinner_new();
			$('#form-proses').submit();
		});
		
	});
	
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
	
	function view_detail_data(tahun_det,bulan_det,tipe_det){
		loading_spinner_new();
		var baseurl		= base_url +'index.php/'+ active_controller+'/view_detail';
		$.ajax({
			url			: baseurl,
			type		: "POST",
			data		: {'periode':tahun_det,'bulan':bulan_det,'tipe':tipe_det},
			cache		: false,
			success		: function(data){
				close_spinner_new();
				$('#Mymodal-list').html(data);
				$('#Mymodal').modal('show');
				$('#Mymodal-title').text(judul);
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
	}
</script>
