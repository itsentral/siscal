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
	position: fixed;
    top: 95px;
    left: 280px;
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
    bottom: 7px;    
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
</style>
<?php
$Header	="
<div id='space'></div>
<div id='space'></div>
<table class='noborder2' width='100%'>
	<tr>
		<td width='25%' align='left'>
			<img src='".$img_file."' width='120' height='70'/>
		</td>
		<td width='50%' align='center'>
			<div style='font-size:17px;font-weight: bold;'>PT. SENTRAL TEHNOLOGI MANAGEMEN</div>
		</td>
		<td width='25%' align='right'>
			<img src='".$img_file2."' width='120' height='70'/>
		</td>
	</tr>
	
	
	<tr>
		<td colspan='3'><img src='".$img_file3."'/></td>
	</tr>
</table>
<div id='space'></div>
<div id='space'></div>
";

$Footer	="<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:center;position:fixed;bottom:5px;width:100%;' class='footer'>
		<b>www.sentralkalibrasi.co.id</b><br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-89321314-15,89321323-24 - <b><br>E-mail</b> : <i>cs@sentralkalibrasi.co.id</i>
	</p>";
$intLoop	= 1;

echo $Header;

	
?>
<table class="noborder3" width='100%'>	
	<tr>
		<td align='center' valign='top' colspan='3'  width='100%' style="font-size:13px;font-family:calibri,arial,sans-serif;"><b>BUKTI PENERIMAAN ALAT</b></td>
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3' height='6' width='100%'></td>
	</tr>
	<tr>
		<td align='left' valign='top' width='24%'>No Receive</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><b><?php echo $rows_header->nomor; ?></b></td>		
	</tr>
	
	<tr>
		<td align='left' valign='top' width='24%'>Tgl Receive</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><b><?php echo date('d F Y',strtotime($rows_header->datet)) ?></b></td>		
	</tr>
	
	<tr>
		<td align='left' valign='top' width='24%'>Nama Perusahaan</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><b><?php echo $rows_header->customer_name ?></b></td>		
	</tr>
	<tr>
		<td align='left' valign='top' width='24%'>No PO</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><b><?php echo $rows_quot->pono ?></b></td>		
	</tr>	
	<tr>
		<td align='left' valign='top' colspan='3' height='3' width='100%'></td>
	</tr>
	
	<tr>
		<td colspan='3' width='100%'>
			<table class="gridtable" width='100%'>
				<tr>
					<th width='5%' align='center' valign='middle'>NO.</th>
					<th width='50%' align='center' valign='middle'>NAMA ALAT</th>					
					<th width='15%' align='center' valign='middle'>QTY</th>
					<th width='30%' align='center' valign='middle'>KETERANGAN</th>					
				</tr>
				
				<?php
					$loop		= 0;
					$Batas		= 20;
					$Page		= 1;
					if($rows_detail){
						$intUrut = 0;
						foreach($rows_detail as $key=>$val){
							$loop++;
							$intUrut++;
							echo"<tr>";
								echo "<td width='5%' align='center'>$intUrut</td>";
								echo "<td width='50%' align='left'>".$val->tool_name."</td>";								
								echo "<td width='15%' align='center'>".$val->qty_rec."</td>";
								echo "<td width='30%' align='center'>".$val->descr."</td>";								
							echo"</tr>";
							if($loop >=$Batas){
								
								$Batas	= 35;
								echo"</table>";
								echo"<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:right;position:fixed;bottom:10px;width:100%;'><b>Page | ".$Page."</b></p>";

								echo $Footer;
								echo "<pagebreak>";
								echo $Header;
								echo"<table class='gridtable' width='100%'>
									<tr>
										<th width='5%' align='center' valign='middle'>NO.</th>
										<th width='50%' align='center' valign='middle'>NAMA ALAT</th>					
										<th width='15%' align='center' valign='middle'>QTY</th>
										<th width='30%' align='center' valign='middle'>KETERANGAN</th>					
									</tr>";
								$loop=0;
								$Page++;
							}
						}
					}
				?>
				
				
			</table>
		</td>
	</tr>
</table>
<?php
	$Next		= $loop + 3;
	if($Next >= $Batas){		
		echo"<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:right;position:fixed;bottom:10px;width:100%;'><b>Page | ".$Page."</b></p>";

		echo $Footer;
		echo "<pagebreak>";
		echo $Header;
		$Page++;
	}
?>
<div id='space'></div>
<div id='space'></div>
<p class='reg'><font color='#6b6b6b'>
	<i>* Bukti Penerimaan ini di-generate otomatis oleh sistem komputer, dianggap sah tanpa memerlukan tanda tangan basah & Cap Perusahaan</i></font>
</p>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>

	<p style="font-family: verdana,arial,sans-serif;font-size:10px;text-align:left;position:fixed;bottom:45px;width:100%;">
		<b><i><?php echo $rows_header->nomor;?>.</i></b>
	</p>

<?php
echo"<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:right;position:fixed;bottom:10px;width:100%;'><b>Page | ".$Page."</b></p>";

echo $Footer;
$html = ob_get_contents();
ob_end_clean();
$mpdf->SetWatermarkText('Receiving Item');
$mpdf->showWatermarkText = true;
$mpdf->WriteHTML($html);

$mpdf->Output($rows_header->nomor.".pdf" ,'I');
exit;
?>
