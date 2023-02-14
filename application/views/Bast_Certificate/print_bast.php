<?php 
$sroot = $_SERVER['DOCUMENT_ROOT'];
include $sroot.'/Siscal_Dashboard/application/libraries/MPDF57/mpdf.php';
$mpdf=new mPDF('utf-8', 'A4');				// Create new mPDF Document
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

table.noborder {
	font-family: verdana,arial,sans-serif;
}

table.noborder th {
	font-size:12px;
	padding: 1px;
	border-color: #666666;
}

table.noborder td {
	font-size:12px;
	padding: 1px;
	border-color: #666666;
	background-color: #ffffff;
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

h2 {
	font-family: calibri,arial,sans-serif;
	position: fixed;
    top: 15px;
    left: 225px;
	}

h3 {
	font-family: calibri,arial,sans-serif;
	position: fixed;
	top: 40px;
	left: 290px;
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
    top: 30px;
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
	font-size:9px;
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
    border-bottom: 1px dashed #ccc;
    background: #999;
}
.text-center {
	text-align 		: center !important;
	vertical-align 	: middle !important;
}
.text-right {
	text-align 		: right !important;
	vertical-align 	: middle !important;
}
.text-left {
	text-align 		: left !important;
	vertical-align 	: middle !important;
}
</style>
<?php
$Header	="
<div id='space'></div>
<div id='space'></div>
<table class='noborder2' width='100%'>
	<tr>
		<td width='50%' align='left'>
			<img src='".$img_file."' width='90' height='70'/>
		</td>
		<td width='50%' align='right'>
			<img src='".$img_file2."' width='90' height='70'/>
		</td>
	</tr>
	<tr>
		<td colspan='2'><img src='".$img_file3."'/></td>
	</tr>
</table>
<div id='space'></div>
<div id='space'></div>
";

$Footer	="<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:center;position:fixed;bottom:5px;width:100%;' class='footer'>
		<b>www.sentralkalibrasi.co.id</b><br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-89321314-15,89321323-24 - <b><br>E-mail</b> : <i>cs@sentralkalibrasi.co.id</i>
	</p>";
echo $Header;
?>

<?php

	$day	= date('D',strtotime($rows_header[datet]));
	$tahun	= substr($rows_header[datet],0,4);
	$bulan	= intval(substr($rows_header[datet],5,2));
	$hari	= substr($rows_header[datet],8,2);
	$tanggal= $ArrHari[$day].', '.$hari.' '.$ArrBulan[$bulan].' '.$tahun;
	
	$rows_SO	= $this->db->get_where('letter_orders',array('id'=>$rows_detail[0]['letter_order_id']))->result();
	
?>
<table class="noborder" width='100%'>	
	<tr>
		<td align='center' valign='top' colspan='3'  width='100%' style="font-size:13px;font-family:calibri,arial,sans-serif;"><b>TANDA TERIMA SERTIFIKAT</b></td>
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3' height='6' width='100%'></td>
	</tr>
	<tr>
		<td align='left' valign='top' width='24%'>No Tanda Terima</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><b><?php echo $rows_header[nomor] ?></b></td>		
	</tr>	
	
	<tr>
		<td align='left' valign='top' width='24%'>Nama Perusahaan</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><b><?php echo $rows_header[customer_name] ?></b></td>		
	</tr>
	<tr>
		<td align='left' valign='top' width='24%'>Alamat</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><b><?php echo $rows_SO[0]->address_send ?></b></td>		
	</tr>
	<tr>
		<td align='left' valign='top' width='24%'>PIC</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><b><?php echo $rows_SO[0]->pic; ?></b></td>		
	</tr>	
		<tr>
		<td align='left' valign='top' width='24%'>PIC Phone</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><b><?php echo $rows_SO[0]->phone; ?></b></td>		
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3' height='3' width='100%'></td>
	</tr>
</table>
	
<table class="gridtable" width='100%'>
	<tr>
		<th width='5%' align='center' valign='middle'>No.</th>
		<th width='35%' align='center' valign='middle'>Nama Alat</th>
		<th width='10%' align='center' valign='middle'>Merk</th>
		<th width='10%' align='center' valign='middle'>Type</th>
		<th width='10%' align='center' valign='middle'>No Sertifikat</th>	
		<th width='15%' align='center' valign='middle'>No PO</th>
		<th width='15%' align='center' valign='middle'>No SO</th>
	</tr>
	
	<?php
		$Batas	= 24;
		$loop	=0;
		if($rows_detail){
			
			foreach($rows_detail as $key=>$val){
				$loop++;
				$Qry_SO		= "SELECT no_so FROM letter_orders WHERE id='".$val['letter_order_id']."'";
				$det_SO		= $this->db->query($Qry_SO)->result();
				
				$Qry_Quot	= "SELECT pono FROM quotations WHERE id='".$val['quotation_id']."'";
				$det_Quot	= $this->db->query($Qry_Quot)->result();
				
				if($loop > $Batas){
					echo"</table>";
					echo $Footer;
					echo "<pagebreak>";
					echo $Header;
					echo"<table class='gridtable' width='100%'>
							<tr>
								<th width='5%' align='center' valign='middle'>No.</th>
								<th width='35%' align='center' valign='middle'>Nama Alat</th>
								<th width='10%' align='center' valign='middle'>Merk</th>
								<th width='10%' align='center' valign='middle'>Type</th>
								<th width='10%' align='center' valign='middle'>No Sertifikat</th>	
								<th width='15%' align='center' valign='middle'>No PO</th>
								<th width='15%' align='center' valign='middle'>No SO</th>
							</tr>";
					$loop=0;
					
				}
				echo"<tr>";
					echo "<td width='5%' align='center'>$loop</td>";
					echo "<td width='35%' align='left'>".$val[tool_name]."</td>";
					echo "<td width='10%' align='center'>".$val[merk]."</td>";
					echo "<td width='10%' align='center'>".$val[tool_type]."</td>";
					echo "<td width='10%' align='center'>".$val[certificate_no]."</td>";
					echo "<td width='15%' align='center'>".$det_Quot[0]->pono."</td>";
					echo "<td width='15%' align='center'>".$det_SO[0]->no_so."</td>";
				echo"</tr>";
			}
		}
		
	?>
	
	
</table>
<div id='space'></div>
<?php
$Mulai = $loop + 3;
if($Mulai >= $Batas){		
	echo $Footer;
	echo "<pagebreak>";
	echo $Header;
	$Mulai	= 0;
}
?>
<div id='space'></div>
<table class="noborder" width='100%'>
	<tr>
		<td colspan='3' align='left'>Tanggal : <?php echo $tanggal;?></td>
	</tr>
	<tr>
		<td colspan='3' align='left'>
			<table class="gridtable" width='100%'>
				<tr>
					<th width='50%' align='center' valign='middle'>Diserahkan oleh</th>
					<th width='50%' align='center' valign='middle'>Diterima oleh</th>										
				</tr>
				<tr>
					<td align='center' width='50%'><br><br><br><br><br><br><br></td>
					<td align='center' width='50%'><br><br><br><br><br><br><br></td>
				</tr>
			</table>
		</td>		
	</tr>	
</table>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>

	<p style="font-family: verdana,arial,sans-serif;font-size:10px;text-align:left;position:fixed;bottom:45px;width:100%;">
		<b><i><?php echo $rows_header[nomor];?>.</i></b>
	</p>
<?php
echo $Footer;
$html = ob_get_contents();
ob_end_clean();

$mpdf->WriteHTML($html);
$mpdf->Output($rows_header[nomor].".pdf" ,'I');
//$mpdf->Error();
exit;
?>