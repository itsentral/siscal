<?php 
$sroot = $_SERVER['DOCUMENT_ROOT'];
include $sroot.'/Siscal_Dashboard/application/libraries/MPDF57/mpdf.php';
$mpdf=new mPDF('utf-8', 'A4-L');				// Create new mPDF Document
$ArrBulan	=array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember');
$ArrHari	= array(
	'Sun'	=> 'Minggu',
	'Mon'	=> 'Senin',
	'Tue'	=> 'Selasa',
	'Wed'	=> 'Rabu',
	'Thu'	=> 'Kamis',
	'Fri'	=> 'Jumat',
	'Sat'	=> 'Sabtu'
	);
//Beginning Buffer to save PHP variables and HTML tags
ob_start();
$img_file	= $sroot.'/Siscal_Dashboard/assets/img/logo.jpg';
$img_file2	= $sroot.'/Siscal_Dashboard/assets/img/kan.png';
$img_file3 	= $sroot.'/Siscal_Dashboard/assets/img/line.jpg';

//echo"<pre>";print_r($records);exit;

?>  

<style type="text/css">
@page {
	margin-top: 0.8cm;
    margin-left: 1cm;
    margin-right: 1cm;
	margin-bottom: 0.8cm;
}
.font{
	font-family: verdana,arial,sans-serif;
	font-size:14px;
}
.fontheader{
	font-family: verdana,arial,sans-serif;
	font-size:13px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}

table.noborder2 th {
	font-size:11px;
	padding: 1px;
	border-color: #666666;
}

table.noborder2 td {	
	padding: 1px;
	border-color: #666666;
	background-color: #ffffff;
	font-size:10px;
	font-family: verdana,arial,sans-serif;
}
table.noborder3 td {	
	padding: 1px;
	border-color: #666666;
	background-color: #ffffff;
	font-size:12px;
	font-family: verdana,arial,sans-serif;
}
table.noborder, .noborder2,noborder3 {
	font-family: verdana,arial,sans-serif;
}

table.noborder th {
	font-size:12px;
	padding: 1px;
	border-color: #666666;
}

table.noborder td {	
	padding: 1px;
	border-color: #666666;
	background-color: #ffffff;
	font-size:13px;
	font-family: verdana,arial,sans-serif;
}

table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}

table.gridtable th {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: #666666;
	background-color: #f2f2f2;
	
}

table.gridtable th.head {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #7f7f7f;
	color: #ffffff;
}
table.gridtable td {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}

table.gridtable td zero {
	border-width: 1px;
	padding: 5px;
	border-color: #666666;
	background-color: #ffffff;
	
}

table.gridtable td.cols {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}

table.cooltabs {
	font-size:12px;
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
}

table.cooltabs th.reg {
	font-family: verdana,arial,sans-serif;
    border-radius: 5px 5px 5px 5px;
    background: #e3e0e4;
    padding: 5px;
}

table.cooltabs td.reg {
	font-family: verdana,arial,sans-serif;
    border-radius: 5px 5px 5px 5px;
    padding: 5px;
	border-width: 1px;
}

#cooltabs {
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 5px 5px 5px 5px;
    background: #e3e0e4;
    padding: 5px; 
    width: 800px;
    height: 20px; 
}

#cooltabs2{
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 5px 5px 5px 5px;
    background: #e3e0e4;
    padding: 5px; 
    width: 180px;
    height: 10px;
}

#space{
    padding: 3px; 
    width: 180px;
    height: 1px;
}

#cooltabshead{
	font-size:12px;
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 5px 5px 0 0;
    background: #dfdfdf;
    padding: 5px; 
    width: 162px;
    height: 10px;
	float:left;
}

#cooltabschild{
	font-size:10px;
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 0 0 5px 5px;
    padding: 5px; 
    width: 162px;
    height: 10px;
	float:left;
}

p {
  margin: 0 0 0 0;
}

p.pos_fixed {
	font-family: verdana,arial,sans-serif;
    position: fixed;
    top: 50px;
    left: 230px;
}

p.pos_fixed2 {
	font-family: verdana,arial,sans-serif;
    position: fixed;
    top: 589px;
    left: 230px;
}

p.notesmall {
	font-size: 9px;
}

.barcode {
    padding: 1.5mm;
    margin: 0;
    vertical-align: top;
    color: #000044;
}

.barcodecell {
    text-align: center;
    vertical-align: middle;
	position: fixed;
	top: 14px;
	right: 10px;
}
p.pt {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 62px;
    left: 5px;
}
h3.pt {
	font-family: calibri,arial,sans-serif;
	position: fixed;	
    top: 175px;
    left: 250px;
	}

h3 {
	font-family: calibri,arial,sans-serif;
	position: fixed;	
    top: 65px;
    left: 200px;
	}

h2 {
	font-family: calibri,arial,sans-serif;	
    top: 50px;
	}
	
p.reg {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
}

p.sub {
	font-family: verdana,arial,sans-serif;
	font-size:13px;
    position: fixed;
    top: 55px;
    left: 220px;
	color: #6b6b6b;
}

p.header {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color: #330000;
}

p.barcs {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
    position: fixed;
    top: 13px;
    right: 1px;
}

p.alamat {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 71px;
    left: 5px;
}

p.tlp {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 80px;
    left: 5px;
}

p.date {
	font-family: verdana,arial,sans-serif;
	font-size:12px;
    text-align: right;
}

p.foot {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 750px;
    left: 5px;
}

p.footer {
	font-family: verdana,arial,sans-serif;
	font-size:10px;
    position: fixed;
    bottom: 5px;
    left: 2px;
}

p.ln {
	font-family: verdana,arial,sans-serif;
	font-size:9px;
    position: fixed;
    bottom: 1px;
    left: 2px;
}

#hrnew {
    border: 0;
    border-bottom: 1px solid #ccc;
    background: #999;
}

.textleft {
	text-align: left;		
}

.textright {
	text-align: right;		
}

.textcenter {
	text-align: center;		
}

.bold {
	font-weight: bold;		
}
</style>
<?php
$Header	="
<div id='space'></div>
<div id='space'></div>
<table class='noborder2' width='100%'>
	<tr>
		<td width='30%' align='left' rowspan='2'>
			<img src='".$img_file."' width='160' height='70'/>
		</td>
		<td><div style='font-size:18px;font-weight: bold;'>PT. SENTRAL TEHNOLOGI MANAGEMEN</div></td>
	</tr>
	<tr>
		<td valign='bottom'><div style='font-size:9px;font-weight: bold;'>TEMPERATURE, PRESSURE, MASS, DIMENSION, ANALITICAL INSTRUMENT, FORCE, ISO 17025</div></td>
	</tr>
	<tr>
		<td colspan='2' align='center'><img src='".$img_file3."'/></td>
	</tr>
	<tr>
		<td class='textcenter bold' colspan='2'><div style='font-size:18px;font-weight: bold;'>SURAT PERINTAH KERJA</div></td>
	</tr>
	<tr>
		<td height='20px' colspan='2'></td>
	</tr>
	
	
</table>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
";

$Footer	="<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:center;position:fixed;bottom:5px;width:100%;' class='footer'>
		<b>www.sentralkalibrasi.co.id</b><br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-89321314-15,89321323-24 - <b><br>E-mail</b> : <i>cs@sentralkalibrasi.co.id</i>
	</p>";
	$intLoop	= 0;
	$valHeader 	= $rows_header;
	
	echo $Header;
		
	
	$Tanggal	= $valHeader['datet'];
	$day		= date('D',strtotime($Tanggal));
	$tahun		= date('Y',strtotime($Tanggal));
	$bulan		= date('n',strtotime($Tanggal));
	$hari		= date('d',strtotime($Tanggal));
	$tanggal	= $ArrHari[$day].', '.$hari.' '.$ArrBulan[$bulan].' '.$tahun;
	
	
		
	?>
	<table class="noborder3" width='100%'>	
		<tr>
			<td align='left' valign='top' width='14%'>Nama Teknisi</td>
			<td align='center' valign='top' width='4%'>:</td>
			<td align='left' valign='top' width='46%'><b><?php echo $valHeader['member_name'] ?></b></td>
			<td align='left' valign='top' width='7%'>Nomor SPK</td>
			<td align='center' valign='top' width='4%'>:</td>
			<td align='left' valign='top' width='25%'><b><?php echo $valHeader['id'] ?></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='14%'>Tanggal SPK</td>
			<td align='center' valign='top' width='4%'>:</td>
			<td align='left' valign='top' width='46%'><b><?php echo $tanggal ?></b></td>
			<td align='left' valign='top' width='7%'></td>
			<td align='center' valign='top' width='4%'></td>
			<td align='left' valign='top' width='25%'></td>		
		</tr>
			
		<tr>
			<td align='left' valign='top' colspan='6' height='3' width='100%'></td>
		</tr>	
	</table>
	<table class="gridtable" width='100%'>
		<tr>
			<td align='center' rowspan='2' valign='middle' width='4%'><b>No</b></td>
			<td align='center' rowspan='2' valign='middle' width='13%'><b>Nama Alat</b></td>
			<td align='center' rowspan='2' valign='middle' width='7%'><b>Range</b></td>
			<td align='center' rowspan='2' valign='middle' width='14%'><b>Perusahaan</b></td>
			<td align='center' rowspan='2' valign='middle' width='7%'><b>No SO</b></td>					
			<td align='center' colspan='3' valign='middle' width='12%'><b>Plan</b></td>
			<td align='center' colspan='3' valign='middle' width='12%'><b>Aktual</b></td>
			<td align='center' rowspan='2' valign='middle' width='5%'><b>Delay/Ontime</b></td>
			<td align='center' rowspan='2' valign='middle' width='5%'><b>Insitu</b></td>
			<td align='center' rowspan='2' valign='middle' width='5%'><b>Labs</b></td>
			<td align='center' rowspan='2' valign='middle' width='8%'><b>Request Cust</b></td>
			<td align='center' rowspan='2' valign='middle' width='8%'><b>Keterangan</b></td>
		</tr>
		<tr>
			<td align='center' width='4%'><b>Qty</b></td>
			<td align='center' width='4%'><b>Start</b></td>
			<td align='center' width='4%'><b>Finish</b></td>
			<td align='center' width='4%'><b>Qty</b></td>
			<td align='center' width='4%'><b>Start</b></td>
			<td align='center' width='4%'><b>Finish</b></td>					
		</tr>				
		<?php
		
			if($rows_detail){
				$intG	=0;
				foreach($rows_detail as $key=>$val){
					$intG++;
					$Plan_Start	= $Plan_End = $Nomor_SO = $Company = $Range = '';
					$Ket_SO		= $Labs = $Insitu = '';
					$Quot_Descr	= '';
					$Query_SO	= "SELECT no_so,customer_name,plan_process_date,plan_time_start, plan_time_end, `range`, piece_id, insitu, labs, so_descr, quotation_detail_id FROM trans_details WHERE id = '".$val['detail_id']."'";
					$rows_SO	= $this->db->query($Query_SO)->row();
					if($rows_SO){
						$Range		= $rows_SO->range.' '.$rows_SO->piece_id;
						$Nomor_SO	= $rows_SO->no_so;
						$Ket_SO		= $rows_SO->so_descr;
						$Insitu		= $rows_SO->insitu;
						$Labs		= $rows_SO->labs;
						$QuotDet	= $rows_SO->quotation_detail_id;
						$Company	= $rows_SO->customer_name;
						$Plan_Start	= date('H:i',strtotime($rows_SO->plan_process_date.' '.$rows_SO->plan_time_start));
						$Plan_End	= date('H:i',strtotime($rows_SO->plan_process_date.' '.$rows_SO->plan_time_end));
						
						$rows_Quot	= $this->db->get_where('quotation_details',array('id'=>$QuotDet))->row();
						if($rows_Quot){
							$Quot_Descr	= $rows_Quot->descr;
						}
					}
					echo"<tr>";
						echo "<td width='4%' align='center'>$intG</td>";
						echo "<td width='13%' align='left'>".$val['tool_name']."</td>";
						echo "<td width='7%' align='left'>".$Range."</td>";
						echo "<td width='14%' align='left'>".$Company."</td>";
						echo "<td width='7%' align='left'>".$Nomor_SO."</td>";
						echo "<td width='4%' align='center'>".$val['qty']."</td>";
						echo "<td width='4%' align='center'>".$Plan_Start."</td>";
						echo "<td width='4%' align='center'>".$Plan_End."</td>";
						echo "<td width='4%' align='center'></td>";
						echo "<td width='4%' align='center'></td>";
						echo "<td width='4%' align='center'></td>";
						echo "<td width='5%' align='center'></td>";
						echo "<td width='5%' align='center'>".$Insitu."</td>";
						echo "<td width='5%' align='center'>".$Labs."</td>";
						echo "<td width='8%' align='left'>".$Quot_Descr."</td>";
						echo "<td width='8%' align='left'>".$Ket_SO."</td>";
					echo"</tr>";
				}
			}
			
		?>				
		
	</table>
		
	
			
	<div id='space'></div>
	<table class="noborder3" width='100%'>
		<tr>
			<td align='left' valign='top' colspan='2' height='3' width='100%'></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='65%'></td>
			<td align='center' valign='top' width='35%'><?php echo $tanggal;?></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='65%'><b>Ket </b>:</td>
			<td align='center' valign='top' width='35%'></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='65%'>1. Penerima SPK hanya mengisi barisan pada kolom <b><i>aktual</i></b> dan <b><i>tanda tangan</i></b>.</td>
			<td align='center' valign='top' width='35%'></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='65%'>2. Jika terjadi ketidaksesuaian pengerjaan diisi dibagian <b><i>keterangan</i></b>.</td>
			<td align='center' valign='top' width='35%'></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='65%'>3. SPK diberikan kepada bagian penjadwalan setiap sore.</td>
			<td align='center' valign='top' width='35%'></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='65%'></td>
			<td align='center' valign='top' width='35%'><br><br><br><u><?php echo $valHeader['member_name']; ?></u></td>
		</tr>	
	</table>

	<div id='space'></div>
	<div id='space'></div>
	<div id='space'></div>
	<div id='space'></div>


	<?php
	$html = ob_get_contents();
	ob_end_clean();

	$mpdf->WriteHTML($html);
	$mpdf->Output($valHeader['id'].".pdf" ,'I');
	//$mpdf->Error();
	exit;
	?>