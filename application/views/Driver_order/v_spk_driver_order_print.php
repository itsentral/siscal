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
	
	$Tanggal	= $rows_header['datet'];
	$day		= date('D',strtotime($Tanggal));
	$tahun		= date('Y',strtotime($Tanggal));
	$bulan		= date('n',strtotime($Tanggal));
	$hari		= date('d',strtotime($Tanggal));
	$tanggal	= $ArrHari[$day].', '.$hari.' '.$ArrBulan[$bulan].' '.$tahun;
	
	
	
?>
<div id='space'></div>
<h2 align='center'><b>SURAT PERINTAH KERJA</b></h2>
<div id='space'></div>
<table class="noborder3" width='100%'>	
	
	<tr>
		<td align='left' valign='top' width='14%'>Nama Driver</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='46%'><b><?php echo ucwords(strtolower($rows_header['member_name'])) ?></b></td>
		<td align='left' valign='top' width='7%'>Nomor SPK</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='25%'><b><?php echo $rows_header['nomor'] ?></td>
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
<div id='space'></div>
<table class="gridtable" width='100%'>
	<tr>
		<td align='center' rowspan='2' valign='middle' width='5%'><b>No</b></td>
		<td align='center' rowspan='2' valign='middle' width='22%'><b>Perusahaan</b></td>
		<td align='center' rowspan='2' valign='middle' width='30%'><b>Alamat</b></td>
		<td align='center' colspan='2' valign='middle' width='14%'><b>Cek Sebelum Keberangkatan</b></td>
		<td align='center' colspan='2' valign='middle' width='14%'><b>Cek Sesudah Pengiriman</b></td>
		<td align='center' rowspan='2' valign='middle' width='15%'><b>Keterangan</b></td>
	</tr>
	<tr>
		<td align='center' width='7%'><b>Alat</b></td>
		<td align='center' width='7%'><b>Tanda Terima</b></td>
		<td align='center' width='7%'><b>Alat</b></td>
		<td align='center' width='7%'><b>Tanda Terima</b></td>					
	</tr>				
	<?php
		$loop		= 0;
		$Batas		= 25;
		if($rows_detail){
			$intG		=0;
			$Page		= 0;
			foreach($rows_detail as $key=>$val){
				$intG++;
				$loop++;
				echo"<tr>";
					echo "<td width='5%' align='center'>$intG</td>";
					echo "<td width='22%' align='left'>".$val['name']."</td>";
					echo "<td width='30%' align='left'>".$val['address']."</td>";
					echo "<td width='7%' align='center'></td>";
					echo "<td width='7%' align='center'></td>";
					echo "<td width='7%' align='center'></td>";
					echo "<td width='7%' align='center'></td>";
					echo "<td width='15%' align='center'>".$val['keterangan']."</td>";																
				echo"</tr>";
				
				if($loop >=$Batas){
					$Page++;
					$Batas	= 35;
					echo"</table>";
				
					echo "<pagebreak>";
					echo $Header;
					echo"<table class='gridtable' width='100%'>
							<tr>
								<td align='center' rowspan='2' valign='middle' width='5%'><b>No</b></td>
								<td align='center' rowspan='2' valign='middle' width='22%'><b>Perusahaan</b></td>
								<td align='center' rowspan='2' valign='middle' width='30%'><b>Alamat</b></td>
								<td align='center' colspan='2' valign='middle' width='14%'><b>Cek Sebelum Keberangkatan</b></td>
								<td align='center' colspan='2' valign='middle' width='14%'><b>Cek Sesudah Pengiriman</b></td>
								<td align='center' rowspan='2' valign='middle' width='15%'><b>Keterangan</b></td>
							</tr>
							<tr>
								<td align='center' width='7%'><b>Alat</b></td>
								<td align='center' width='7%'><b>Tanda Terima</b></td>
								<td align='center' width='7%'><b>Alat</b></td>
								<td align='center' width='7%'><b>Tanda Terima</b></td>					
							</tr>";
					$loop=0;
				}
				
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
		<td colspan='2' align='left'>Tanggal : <?php echo $tanggal;?></td>
	</tr>
	<tr>
		<td align='left' valign='top' width='65%'></td>
		<td align='center' valign='top' width='35%'>Penerima<br><br><br><br><br><br><u><?php echo $rows_header['member_name']; ?></u></td>
	</tr>	
</table>

<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>


<?php
echo $Footer;
$html = ob_get_contents();
ob_end_clean();

$mpdf->WriteHTML($html);
//$mpdf->addPage();
//$mpdf->WriteHTML($html);
$mpdf->Output(str_replace('/','_',$rows_header['nomor']).".pdf" ,'I');
//$mpdf->Error();
exit;
?>