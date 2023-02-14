<?php 
$sroot = $_SERVER['DOCUMENT_ROOT'];
include $sroot.'/Siscal_mobile/application/third_party/MPDF57/mpdf.php';
$mpdf=new mPDF('utf-8', array(29,25));				// Create new mPDF Document
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
$img_sentral	= $sroot.'/Siscal_Dashboard/assets/img/logo_flat.png';
$img_kan		= $sroot.'/Siscal_Dashboard/assets/img/kan.png';
//echo"<pre>";print_r($rows_header);exit;

$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
$CodeHash		= Enkripsi($rows_trans->id,$HashKey);
$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;

?>  

<style type="text/css">
@page {
	margin-top: 0.1cm;
    margin-left: 0.1cm;
    margin-right: 0.1cm;
	margin-bottom: 0.1cm;
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
	font-size:9px;
	padding: 2px;
	border-color: #666666;
}

table.noborder td {	
	padding: 1px;
	border-color: #666666;
	background-color: #ffffff;
	font-size:9px;
	font-family: verdana,arial,sans-serif;
}

table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:10px;
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
    height: 14px; 
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
    margin: 1.5mm;
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
    left: 214px;
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
.text-wrap{
	overflow-wrap: break-word !important;
	word-wrap: break-word !important;
	white-space: pre-wrap !important;
    word-break: break-word !important;
}
.text-center{
	text-align:center !important;
	vertical-align : middle !important;
}
.text-left{
	text-align:left !important;
	vertical-align : middle !important;
}
</style>
<?php

$Code_Trans		= $rows_trans->id;
$Code_Serial	= $rows_trans->no_serial_number;
$Code_Identify	= $rows_trans->no_identifikasi;
$Text_Head		= $Code_Trans;
if(!empty($Code_Identify) && $Code_Identify !== '-'){
	$Text_Head		= $Code_Identify;
}

if(!empty($Code_Serial) && $Code_Serial !== '-'){
	$Text_Head		= $Code_Serial;
}

$Font_Footer	= "8px";
$Text_Footer	= date('d-m-Y',strtotime($rows_trans->datet));
if(!empty($rows_trans->valid_until) && $rows_trans->valid_until !== '0000-00-00' && $rows_trans->valid_until !== '1970-01-01'){
	$Text_Footer	.=' sd '.date('d-m-Y',strtotime($rows_trans->valid_until));
	$Font_Footer	= "7px";
}

$rows_Image	= "";
if(strtolower($rows_tool->certification_id) == 'kan'){
	$rows_Image	= "
	<tr>
		<td width='100%' class='text-center'>
			<img src='".$img_kan."' width='30' height='25'>
		</td>
	</tr>
	";
}



$Header	="
<div style='border-width: 1px;border-color: #666666;border-style: solid;'>
	<table class='noborder' width='100%' height='100%'>
		<tr>
			<td width='100%' class='text-center text-wrap'>".$Text_Head."</td>
		</tr>
		<tr>
			<td width='100%' class='text-center'>
				<img src='".$this->file_attachement.'QRCode/'.$rows_trans->qr_code."' width='50' height='45'>
			</td>
		</tr>
		<tr>
			<td width='100%' class='text-center text-wrap' style='font-size:".$Font_Footer." !important;'>".$Text_Footer."</td>
		</tr>
	</table>	
</div>
";

echo $Header;
	
$html = ob_get_contents();
ob_end_clean();
//echo $html;exit;
$mpdf->WriteHTML($html);
$mpdf->Output($rows_header->sentral_tool_code.".pdf" ,'I');
//$mpdf->Error();
exit;
?>