<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
		
		function Enkripsi($sData, $sKey='200881173'){ 
			$sResult = '';
			for($i=0;$i<strlen($sData);$i++){
				$sChar    = substr($sData, $i, 1);
				$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
				$sChar    = chr(ord($sChar) + ord($sKeyChar));
				$sResult .= $sChar;
			}
			return Enkripsi_base64($sResult);
		}
		
		function Dekripsi($sData, $sKey='200881173'){
			$sResult = '';
			$sData   = Dekripsi_base64($sData);
			for($i=0;$i<strlen($sData);$i++){
				$sChar    = substr($sData, $i, 1);
				$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
				$sChar    = chr(ord($sChar) - ord($sKeyChar));
				$sResult .= $sChar;
			}
			return $sResult;
		}
		
		function Enkripsi_base64($sData){
			$sBase64 = base64_encode($sData);
			return strtr($sBase64, '+/', '-_');
		}
		
		function Dekripsi_base64($sData){
			$sBase64 = strtr($sData, '-_', '+/');
			return base64_decode($sBase64);
		}
		
		function history($desc=NULL){
			$CI 			=& get_instance();
			$path			= $CI->uri->segment(1);
			$userID			= $CI->session->userdata('siscal_userid');
			$Date			= date('Y-m-d H:i:s');
			
			$DataHistory=array();
			$DataHistory['user_id']		= $userID;
			$DataHistory['path']		= $path;
			$DataHistory['description']	= $desc;
			$DataHistory['created']		= $Date;		
			$CI->db->insert('histories',$DataHistory);
		}
		
		function cryptSHA1($fields){
			$key			='-SonHyunJoo173';
			$Encrpt_Kata	= sha1($fields.$key);
			return $Encrpt_Kata;
		}
		
		function getRomawi($bulan){
			$month	= intval($bulan);
			switch($month){
				case "1":
					$romawi	='I';	
					break;
				case "2":
					$romawi	='II';	
					break;
				case "3":
					$romawi	='III';	
					break;
				case "4":
					$romawi	='IV';	
					break;
				case "5":
					$romawi	='V';	
					break;
				case "6":
					$romawi	='VI';	
					break;
				case "7":
					$romawi	='VII';	
					break;
				case "8":
					$romawi	='VIII';	
					break;
				case "9":
					$romawi	='IX';	
					break;
				case "10":
					$romawi	='X';	
					break;
				case "11":
					$romawi	='XI';	
					break;
				case "12":
					$romawi	='XII';	
					break;
			}
			return $romawi;
		}
		
		function getColsChar($colums)
		{
			// Palleng by jester
			
			if($colums>26)
			{
				$modCols = floor($colums/26);
				$ExCols = $modCols*26;
				$totCols = $colums-$ExCols;
				
				if($totCols==0)
				{
					$modCols=$modCols-1;
					$totCols+=26;
				}
				
				$lets1 = getLetColsLetter($modCols);
				$lets2 = getLetColsLetter($totCols);
				return $letsi = $lets1.$lets2;
			}
			else
			{
				$lets = getLetColsLetter($colums);
				return $letsi = $lets;
			}
		}

		function getLetColsLetter($numbs){
		// Palleng by jester
			switch($numbs){
				case 1:
				$Chars = 'A';
				break;
				case 2:
				$Chars = 'B';
				break;
				case 3:
				$Chars = 'C';
				break;
				case 4:
				$Chars = 'D';
				break;
				case 5:
				$Chars = 'E';
				break;
				case 6:
				$Chars = 'F';
				break;
				case 7:
				$Chars = 'G';
				break;
				case 8:
				$Chars = 'H';
				break;
				case 9:
				$Chars = 'I';
				break;
				case 10:
				$Chars = 'J';
				break;
				case 11:
				$Chars = 'K';
				break;
				case 12:
				$Chars = 'L';
				break;
				case 13:
				$Chars = 'M';
				break;
				case 14:
				$Chars = 'N';
				break;
				case 15:
				$Chars = 'O';
				break;
				case 16:
				$Chars = 'P';
				break;
				case 17:
				$Chars = 'Q';
				break;
				case 18:
				$Chars = 'R';
				break;
				case 19:
				$Chars = 'S';
				break;
				case 20:
				$Chars = 'T';
				break;
				case 21:
				$Chars = 'U';
				break;
				case 22:
				$Chars = 'V';
				break;
				case 23:
				$Chars = 'W';
				break;
				case 24:
				$Chars = 'X';
				break;
				case 25:
				$Chars = 'Y';
				break;
				case 26:
				$Chars = 'Z';
				break;
			}

			return $Chars;
		}

		function getColsLetter($char){
		//	Palleng by jester
			$len = strlen($char);
			if($len==1)
			{
				$numb = getLetColsNumber($char);
			}
			elseif($len==2)
			{
				$i=1;
				$j=0;
				$jm=1;
				while($i<$len)
				{
					$let_fst = substr($char, $j,1);
					$dv = getLetColsNumber($let_fst);
					$jm = $dv * 26;
					
					$i++;
					$j++;
				}
				$let_last = substr($char, $j,1);
				$numb = $jm + getLetColsNumber($let_last);
			}
			
			return $numb;
		}
		
		function getLetColsNumber($char)
		{
			// Palleng by jester
			
			switch($char){
				case 'A':$numb = 1;break;
				case 'B':$numb = 2;break;
				case 'C':$numb = 3;break;
				case 'D':$numb = 4;break;
				case 'E':$numb = 5;break;
				case 'F':$numb = 6;break;
				case 'G':$numb = 7;break;
				case 'H':$numb = 8;break;
				case 'I':$numb = 9;break;
				case 'J':$numb = 10;break;
				case 'K':$numb = 11;break;
				case 'L':$numb = 12;break;
				case 'M':$numb = 13;break;
				case 'N':$numb = 14;break;
				case 'O':$numb = 15;break;
				case 'P':$numb = 16;break;
				case 'Q':$numb = 17;break;
				case 'R':$numb = 18;break;
				case 'S':$numb = 19;break;
				case 'T':$numb = 20;break;
				case 'U':$numb = 21;break;
				case 'V':$numb = 22;break;
				case 'W':$numb = 23;break;
				case 'X':$numb = 24;break;
				case 'Y':$numb = 25;break;
				case 'Z':$numb = 26;break;
			}
			
			return $numb;
		}
	
		function getAcccesmenu($controller=NULL){
			$CI 			=& get_instance();
			$group			= $CI->session->userdata('siscal_group_id');
			$Action=array();
			if($group=='1'){
				$action["read"]		= 1;
				$action["create"]	= 1;
				$action["update"]	= 1; 
				$action["delete"]	= 1;
				$action["download"]	= 1;
				$action["approve"]	= 1;
			}else{
				$qMenu		= $CI->db->get_where('new_menus',array('LOWER(path)'=>strtolower($controller),'sts_siscal'=>'Y'));
				$dataMenu	= $qMenu->result();
				if($qMenu->num_rows() > 0){				
					$qAccess	= $CI->db->get_where('new_group_menus',array('menu_id'=>$dataMenu[0]->id,'group_id'=>$group));
					$DataAccess	= $qAccess->result();
					if($DataAccess){
						$action["read"]=(isset($DataAccess[0]->read) && $DataAccess[0]->read)?$DataAccess[0]->read:0;
						$action["create"]=(isset($DataAccess[0]->create) && $DataAccess[0]->create)?$DataAccess[0]->create:0;
						$action["update"]=(isset($DataAccess[0]->update) && $DataAccess[0]->update)?$DataAccess[0]->update:0;
						$action["delete"]=(isset($DataAccess[0]->delete) && $DataAccess[0]->delete)?$DataAccess[0]->delete:0;
						$action["download"]=(isset($DataAccess[0]->download) && $DataAccess[0]->download)?$DataAccess[0]->download:0;
						$action["approve"]=(isset($DataAccess[0]->approve) && $DataAccess[0]->approve)?$DataAccess[0]->approve:0;
					}
				}
				
			}		
			return $action;
		}

		function generate_tree($data=array(),$depth=0,$nilai=array()){
			$ArrDept=array(0=>10,1=>40,2=>70,3=>100);
			if(isset($data) && $data){
				foreach($data as $key=>$value){
					echo create_datas($value,$ArrDept[$depth],$nilai);
					if(array_key_exists('child',$value)){
						generate_tree($value['child'],($depth + 1),$nilai);	
					}
				}
			}
		}
		function create_datas($value=array(),$padding=NULL,$data=array()){				
				$template='<tr>';
				$state['read']		= (isset($data[$value['id']]['read']) && $data[$value['id']]['read'] == 1) ? ' checked="checked"' : '';
				$state['create']	= (isset($data[$value['id']]['create']) && $data[$value['id']]['create'] == 1) ? ' checked="checked"' : '';
				$state['update']	= (isset($data[$value['id']]['update']) && $data[$value['id']]['update'] == 1) ? ' checked="checked"' : '';
				$state['delete']	= (isset($data[$value['id']]['delete']) && $data[$value['id']]['delete'] == 1) ? ' checked="checked"' : '';
				$state['download']	= (isset($data[$value['id']]['download']) && $data[$value['id']]['download'] == 1) ? ' checked="checked"' : '';
				$state['approve']	= (isset($data[$value['id']]['approve']) && $data[$value['id']]['approve'] == 1) ? ' checked="checked"' : '';
				$template.=		'<td align="left" style="padding-left:'.$padding.'px;"><input type="hidden" name="tree['.$value['id'].'][menu_id]" value="'.$value['id'].'">  '.$value['name'].'</td>';
				$template.=		'<td align="center"><input type="checkbox" id="read'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][read]" value="1"'.$state['read'].'></td>';
				$template.=		'<td align="center"><input type="checkbox" id="create'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][create]" value="1"'.$state['create'].'></td>';
				$template.=		'<td align="center"><input type="checkbox" id="update'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][update]" value="1"'.$state['update'].'></td>';
				$template.=		'<td align="center"><input type="checkbox" id="delete'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][delete]" value="1"'.$state['delete'].'></td>';
				$template.=		'<td align="center"><input type="checkbox" id="approve'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][approve]" value="1"'.$state['approve'].'></td>';
				$template.=		'<td align="center"><input type="checkbox" id="download'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][download]" value="1"'.$state['download'].'></td>';
				$template.='</tr>';
			//echo $template;
			return $template;
		}
		
		function group_menus_access(){
			$CI 			=& get_instance();			
			$groupID		= $CI->session->userdata('siscal_group_id');
			
			$ArrMenu	= array();
			if($groupID=='1'){
				$Query	= "SELECT * FROM new_menus WHERE active='1' AND sts_siscal='Y' ORDER BY parent_id,weight,id ASC";
			}else{
				$Query	= "SELECT new_menus.* FROM new_menus INNER JOIN new_group_menus ON new_menus.id=new_group_menus.menu_id WHERE new_menus.active='1' AND new_menus.sts_siscal='Y' AND new_group_menus.group_id='$groupID' ORDER BY new_menus.parent_id,new_menus.weight,new_menus.id ASC";
				}
			//echo $Query;
			$jumlah		= $CI->db->query($Query)->num_rows();		
			
			if($jumlah > 0){
				$hasil		= $CI->db->query($Query)->result_array();
				
				foreach($hasil as $key=>$val){
					$ArrMenu[$key]['Menu']['id']		= $val['id'];
					$ArrMenu[$key]['Menu']['name']		= $val['name'];
					$ArrMenu[$key]['Menu']['path']		= $val['path'];
					$ArrMenu[$key]['Menu']['parent_id']	= $val['parent_id'];
					$ArrMenu[$key]['Menu']['weight']	= $val['weight'];
					$ArrMenu[$key]['Menu']['icon']		= $val['icon'];				
				}
			}
			$Menus	= rebuild_structure($ArrMenu);
			return $Menus;
		}			
		
			
		//echo"<pre>";print_r($Menus);	exit;
			
		
		function rebuild_structure($data){
			$childs = array();

			foreach($data as &$item){
				$childs[$item['Menu']['parent_id']][] = &$item['Menu'];
				unset($item);
			}
			
			foreach($data as &$item){
				if (isset($childs[$item['Menu']['id']])){
					$item['Menu']['child'] = $childs[$item['Menu']['id']];
					unset($childs[$item['Menu']['id']]);
				}
			}

		//	pr($childs);exit;
		//	menu that has no parent, append it as parent
			if(count($childs) > 0){
				foreach($childs as $key => $child){
					if($key != 0){
						$childs[0][] = $child[0];
						unset($childs[$key]);
					}
				}
			}

			return isset($childs[0]) ? $childs[0] : array();
		}
		
		function render_left_menus($fixed_structure=array(),$dept=0){
			//if first render echo wrapper
			if($dept==0){
				echo '<ul class="sidebar-menu">';
				echo '<li class="header">MAIN NAVIGATION</li>';
			}
			
			//loop children
			foreach($fixed_structure as $key=>$value){			
				$path=$value['path']==''?'#':base_url().'index.php/'.strtolower($value['path']);
				$icons=$value['icon'];			
				
				if(array_key_exists('child',$value)){
					echo'<li class="treeview"><a href="'.$path.'"><i class="fa '.$icons.'"></i> <span>'.$value['name'].'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
					echo('<ul class="treeview-menu">');
					render_left_menus($value['child'],$dept+1);
					echo('</ul>');
				}else{
					echo'<li><a href="'.$path.'"><i class="fa '.$icons.'"></i> <span>'.$value['name'].'</span></a>';
				}
				echo('</li>');
			}
			if($dept==0)echo('</ul>');	
		}
		
		function group_access($groupID){
			$CI 			=& get_instance();
			//$groupID		= $CI->session->userdata('siscal_group_id');
			$MenusAccess	= array();
			$Query	= "SELECT new_menus.*,new_group_menus.id AS kode_group,new_group_menus.read,new_group_menus.create,new_group_menus.update,new_group_menus.delete,new_group_menus.approve,new_group_menus.download FROM new_menus LEFT JOIN new_group_menus ON new_menus.id=new_group_menus.menu_id AND new_group_menus.group_id='$groupID'  WHERE new_menus.active='1' AND new_menus.sts_siscal='Y' ORDER BY new_menus.parent_id,new_menus.weight,new_menus.id ASC";
			
			$jumlah		= $CI->db->query($Query);
			//echo"ono bro ".$jumlah;exit;
			if($jumlah->num_rows() > 0){
				$hasil		= $jumlah->result_array();
				
				foreach($hasil as $key=>$val){
					if($groupID=='1'){
						$MenusAccess[$val['id']]['read']=1;	
						$MenusAccess[$val['id']]['create']=1;
						$MenusAccess[$val['id']]['update']=1;
						$MenusAccess[$val['id']]['delete']=1;
						$MenusAccess[$val['id']]['approve']=1;
						$MenusAccess[$val['id']]['download']=1;
					}else{
						if(isset($val['kode_group']) && $val['kode_group']){
							$MenusAccess[$val['id']]['read']=$val['read'];	
							$MenusAccess[$val['id']]['create']=$val['create'];
							$MenusAccess[$val['id']]['update']=$val['update'];
							$MenusAccess[$val['id']]['delete']=$val['delete'];
							$MenusAccess[$val['id']]['approve']=$val['approve'];
							$MenusAccess[$val['id']]['download']=$val['download'];
						}
					}
								
				}
			}
			
			return $MenusAccess;
		}			
		function reconstruction_tree($parent_id=0,$data=array()){
			$menus=array();
			foreach($data as $key=>$value){
				$index=count($menus);
				if($value['parent_id']==$parent_id){
					$menus[$index]=$value;
					if(count($value) >1){
						$menus[$index]['detail']=$value;	
					}
					//unset print
					unset($data[$key]);
					if($child=reconstruction_tree($value['id'],$data)){
						$menus[$index]['child']=$child;	
					}
				}
			}
			return $menus;
		}
		function implode_data($data=array(),$key='key'){
			if(strtolower($key)=='key'){
				$det_imp	="";
				foreach($data as $key=>$val){
					if(!empty($det_imp))$det_imp.="','";
					$det_imp	.=$key;
				}
			}else{
				$det_imp	=implode("','",$data);
			}
			return $det_imp;
		}
		
		function getExtension($str) {

			 $i = strrpos($str,".");
			 if (!$i) { return ""; } 

			 $l = strlen($str) - $i;
			 $ext = substr($str,$i+1,$l);
			 return $ext;
		}
		
		function ImageResizes($data,$location,$NewName=NULL){
			 $CI 			=& get_instance();
			 $image 		= $data["name"];
			 $uploadedfile 	= $data['tmp_name'];
			 $Arr_Return	= array();
			 if ($image){
				$filename 	= stripslashes($data['name']);
				$extension 	= getExtension($filename);
				$extension 	= strtolower($extension);
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$Arr_Return	= array(
							'status'	=> 2,
							'pesan'		=> 'File ekstension tidak valid.....'
						);
											
						
				}else{
					$size	= filesize($data['tmp_name']);
					// cek image size		 
					if ($size > (3840*3840))	{
						$Arr_Return	= array(
							'status'	=> 2,
							'pesan'		=> 'Ukuran File terlalu besar......'
						);				
						 
					}else{
		 
						if($extension=="jpg" || $extension=="jpeg" ){
							$uploadedfile = $data['tmp_name'];
							$src = imagecreatefromjpeg($uploadedfile);
						}else if($extension=="png"){
							$uploadedfile = $data['tmp_name'];
							$src = imagecreatefrompng($uploadedfile);
						}else {
							$src = imagecreatefromgif($uploadedfile);
						}
			 
						list($width,$height)=getimagesize($uploadedfile);
			
						$newwidth	= 1024;
						$newheight	= ($height/$width)*$newwidth;
						$tmp		= imagecreatetruecolor($newwidth,$newheight);		
						imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
						$uploaddir 	= $inputFileName = './assets/images/'.$location.'/';
						if($NewName){
							$filename = $uploaddir.$NewName.'.'.$extension;
						}else{
							$filename = $uploaddir.$data['name'];
						}
						unlink($filename);
						imagejpeg($tmp,$filename,100);
			
						imagedestroy($src);
						imagedestroy($tmp);
						$Arr_Return	= array(
							'status'	=> 1,
							'pesan'		=> 'Upload Image Success....'
						);
					}
		
				}
			}
			
			return $Arr_Return;
		}
	
		function akses_server_side(){
			$Arr_Balik	= array(
				'hostname'	=> 'localhost',
				'hostuser'	=> 'root',
				'hostpass'	=> 'bcava',
				'hostdb'	=> 'calibrations_new'
			);
			return $Arr_Balik;
		}
		
		function security_hash($text=''){
			$Text_Salt		= 'DYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi';
			$text_encrypt	= sha1($Text_Salt.$text);
			return $text_encrypt;
		}
		
		
		/*
		| ------------------------------- |
		| 		ENCRYPT PASSWORD CRM	  |
		|			2022-12-18            |
		| ------------------------------- |	
		*/
		function security_hash_crm($text=''){
			$CI 			= get_instance();
			$Text_Salt		= $CI->config->item("login_key");
			$text_encrypt	= sha1($Text_Salt.$text);
			return $text_encrypt;
		}
		
		function Hitung_Nilai_SO(){
			set_time_limit(0);
			$CI 			=& get_instance(); 
			
			$Periode_Akhir	= date('Y-m-d');
			$Periode_Awal	= date('Y-m-d',strtotime($Periode_Akhir.' -6 day'));
			
			
			
			
			$Query_Letter	= "SELECT * FROM letter_orders WHERE sts_so NOT IN ('REV') AND ((tgl_so BETWEEN '".$Periode_Awal."' AND '".$Periode_Akhir."') OR (DATE_FORMAT(cancel_date,'%Y-%m-%d') BETWEEN '".$Periode_Awal."' AND '".$Periode_Akhir."'))";
			
			
			$Proses_Letter	= $CI->db->query($Query_Letter);
			$Num_Letter 	= $Proses_Letter->num_rows();
			//echo $Num_Letter;exit;
			
			if($Num_Letter > 0){
				$intL		= 0;	
				$rows_detail	= $Proses_Letter->result_array();
				foreach($rows_detail AS $key=>$det_Leter){ 
					$intL++;
					
					$Kode_SO		= $det_Leter['id'];
					$Nomor_SO		= $det_Leter['no_so'];
					$Tgl_SO			= $det_Leter['tgl_so'];
					$Custid_SO		= $det_Leter['customer_id'];
					$Quot_SO		= $det_Leter['quotation_id'];
					$Custname_SO	= $det_Leter['customer_name'];
					$Address_SO		= $det_Leter['address'];
					$Pic_SO			= $det_Leter['pic'];
					$Phone_SO		= $det_Leter['phone'];
					$Status_SO		= $det_Leter['sts_so'];
					$Flag_Insitu	= $det_Leter['flag_so_insitu'];
					$Cancel_Date	= ($Status_SO ==='CNC')?date('Y-m-d',strtotime($det_Leter['cancel_date'])):'';
					
					$Quot_Nett		= 0;
					$Qry_Quotation	= "SELECT * FROM quotations WHERE id='".$Quot_SO."'";
					//echo"<pre>";print_r($Qry_Quotation);
					$Pros_Quotation	= $CI->db->query($Qry_Quotation);
					$det_Quotation	= $Pros_Quotation->result_array();
					if($det_Quotation){
						$Quot_Nomor		= $det_Quotation[0]['nomor'];
						$Quot_Date		= $det_Quotation[0]['datet'];
						$Quot_PO		= $det_Quotation[0]['pono'];
						$Quot_PO_Date	= $det_Quotation[0]['podate'];				
						$DPP			= $det_Quotation[0]['total_dpp'];
						$Total_Insitu	= $det_Quotation[0]['total_insitu'];
						$Total_Akomodasi= $det_Quotation[0]['total_akomodasi'];
						$PPN			= $det_Quotation[0]['ppn'];
						$Quot_Total		= $det_Quotation[0]['grand_tot'];
						$Member_Id		= $det_Quotation[0]['member_id'];
						$Member_Name	= $det_Quotation[0]['member_name'];
						$Cust_Fee		= 0;
						if($det_Quotation[0]['success_fee'] > 0)$Cust_Fee =$det_Quotation[0]['success_fee'];
						
						$Quot_Nett		= $Quot_Total - $PPN  - $Total_Insitu - $Total_Akomodasi;
					}
					
					## CUSTOMER ##
					$First_Date		= '';
					$Flag_Bill		= '';
					$Qry_Customer	= "SELECT * FROM customers WHERE id='".$Custid_SO."'";
					$Pros_Customer	= $CI->db->query($Qry_Customer);
					$det_Customer	= $Pros_Customer->result_array();
					if($det_Customer){
						$First_Date	= $det_Customer[0]['first_so_date'];
						$Flag_Bill	= $det_Customer[0]['flag_billing'];
						$Reff_By	= $det_Customer[0]['reference_by'];
						$Reff_Phone	= $det_Customer[0]['reference_phone'];
						$Reff_Name	= $det_Customer[0]['reference_name'];
					}
					
					
					## FIRST SO ##
					$First_SO		= '';
					$Qry_First_SO	= "SELECT
											no_so
										FROM
											letter_orders 
										WHERE
											quotation_id = '".$Quot_SO."'
										AND tgl_so <= '".$Tgl_SO."'
										AND sts_so NOT IN ('REV', 'CNC')
										ORDER BY
											tgl_so ASC
										LIMIT 1";
					
					$Pros_First_SO	= $CI->db->query($Qry_First_SO);
					$det_First_SO	= $Pros_First_SO->result_array();
					if($det_First_SO){
						$First_SO	= $det_First_SO[0]['no_so'];
					}
					
					## HITUNG TOTAL SO ##
					$Total_SO		= 0;
					$Subcon_SO		= 0;
					
					$Qry_Total_SO	= "SELECT
											SUM(
												ROUND(
													(
														100 -
														IF (
															quot_det.discount > 0,
															quot_det.discount,
															0
														)
													) * (det_so.qty * quot_det.price) / 100
												)
											) as total_so,
											SUM(
												IF (
													det_so.supplier_id <> 'COMP-001',
													det_so.qty * quot_det.hpp,
													0
												)
											) AS so_subcon
										FROM
											letter_order_details det_so
										INNER JOIN quotation_details quot_det ON det_so.quotation_detail_id = quot_det.id
										WHERE
											det_so.letter_order_id = '".$Kode_SO."'";
					$Pros_Total_SO	= $CI->db->query($Qry_Total_SO);
					$det_Total_SO	= $Pros_Total_SO->result_array();
					if($det_Total_SO){
						$Total_SO	= $det_Total_SO[0]['total_so'];
						$Subcon_SO	= $det_Total_SO[0]['so_subcon'];
					}
					
					
					$Jumlah_SO		= $Total_SO;
					$Jum_Insitu		= $Jum_Akomodasi	= $Jum_Fee = 0;
					if(($Nomor_SO === $First_SO || $Total_SO === $Quot_Nett) && $Flag_Insitu === 'Y' && $Status_SO !=='CNC'){
						$Jumlah_SO		+= $Total_Insitu; 
						$Jum_Insitu		= $Total_Insitu;
					}
					
					if(($Nomor_SO === $First_SO || $Total_SO === $Quot_Nett) && $Status_SO !=='CNC'){
						$Jumlah_SO		+= $Total_Akomodasi; 
						$Jum_Akomodasi	= $Total_Akomodasi;
						$Jum_Fee		= $Cust_Fee;
					}
					
					$Nett_SO			= $Total_SO - $Subcon_SO - $Jum_Fee;
					
					$Qry_Delete 		= "DELETE FROM temp_sales_order_value WHERE id='".$Kode_SO."'";
					$Pros_Delete		= $CI->db->query($Qry_Delete);
					
					$Qry_Insert			= "INSERT INTO temp_sales_order_value (
												id,
												noso,
												tgl_so,
												quotation_id,
												quotation_nomor,
												quotation_date,
												customer_id,
												customer_name,
												pono,
												podate,
												address,
												pic,
												phone,
												member_id,
												member_name,
												sts_so,
												total_so,
												akomodasi,
												insitu,
												cust_fee,
												subcon_so,
												total_net,
												first_so,
												first_so_cust_date,
												reference_by,
												reference_name,
												reference_phone,
												cancel_date
											)
											VALUES
												(
													'".$Kode_SO."',
													'".$Nomor_SO."',
													'".$Tgl_SO."',
													'".$Quot_SO."',
													'".$Quot_Nomor."',
													'".$Quot_Date."',
													'".$Custid_SO."',
													'".$Custname_SO."',
													'".$Quot_PO."',
													'".$Quot_PO_Date."',
													'".$Address_SO."',
													'".addslashes($Pic_SO)."',
													'".$Phone_SO."',
													'".$Member_Id."',
													'".$Member_Name."',
													'".$Status_SO."',
													'".$Jumlah_SO."',
													'".$Jum_Akomodasi."',
													'".$Jum_Insitu."',
													'".$Jum_Fee."',
													'".$Subcon_SO."',
													'".$Nett_SO."',
													'".$First_SO."',
													'".$First_Date."',
													'".$Reff_By."',
													'".$Reff_Name."',
													'".$Reff_Phone."',
													'".$Cancel_Date."'
												)";
					$Pros_Insert	= $CI->db->query($Qry_Insert);
					
				}
				
				
			}
			
		}
	
	/*
	## UPLOAD FILE KE CALIBRASI - FOLDER UPLOAD (LIVE) ##
	
	*/
	function ImageResizes_Kalibrasi($data,$location,$NewName=NULL){	
		$CI 			=& get_instance();
		$image 			= $data["name"];
		$uploadedfile 	= $data['tmp_name'];
		$Folder_Cal		= $CI->config->item('location_file');
		$filename 		= stripslashes($data['name']);	 
		if ($image){
			
			$extension 	= getExtension($filename);
			$extension 	= strtolower($extension);			
			$size		= filesize($uploadedfile);
			// cek image size		 
			
			if(strtolower($extension)=="jpg" || strtolower($extension)=="jpeg" ){				
				$src = imagecreatefromjpeg($uploadedfile);
			}else if($extension=="png"){				
				$src = imagecreatefrompng($uploadedfile);
			}else {
				$src = imagecreatefromgif($uploadedfile);
			}
			
			list($width,$height)	= getimagesize($uploadedfile);

			$newwidth				= 1024;
			$newheight				= ($height/$width)*$newwidth;
			$tmp					= imagecreatetruecolor($newwidth,$newheight);		
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
			$uploaddir 				= './assets/file/';
			$dir2	   				= explode('/',$location);
			//$uploaddir2 			= $dir2[1].'/';
			$uploaddir2 			= $Folder_Cal.$location.'/';
			
			if($NewName){
				$filename = $uploaddir.$NewName.'.'.$extension;
				$filename2 = $uploaddir2.$NewName.'.'.$extension;
			}else{
				$filename = $uploaddir.$data['name'];
				$filename2 = $uploaddir2.$data['name'];
				
			}
			if (file_exists($filename)) {
				unlink($filename);
			}
			
			imagejpeg($tmp,$filename2,100);
			
			

			// upload a file
			/*
			if (move_uploaded_file($filename, $filename2)) {
				unlink($filename);
			}
			*/
			imagedestroy($src);
			imagedestroy($tmp);
		}
	}
	function delFile_Kalibrasi($location,$filename=NULL){
		$CI 			=& get_instance();
		$uploaddir 		= './assets/file/';
		$Folder_Cal		= $CI->config->item('location_file');
		if(!empty($filename)){
			$dir2	   		= explode('/',$location);
			//$uploaddir2 	= $dir2[1].'/';
			$uploaddir2 	= $Folder_Cal.$location;
			$file_hapus		= $uploaddir2.'/'.$filename;
			
			// upload a file
			if(file_exists($file_hapus)){
				unlink($file_hapus);
			}
			
		}
	}

	function PdfUpload_Kalibrasi($data,$location,$NewName=NULL){
		$CI 			=& get_instance();
		$Folder_Cal		= $CI->config->item('location_file');
		$nama 			= $data["name"];
		$uploadedfile 	= $data['tmp_name'];
		$filename 		= stripslashes($data['name']);
		 
		
		
		if ($nama){
			$extension 		= getExtension($filename);
			$extension 		= strtolower($extension);			
			$uploaddir 		= './assets/file/';
			$dir2	   		= explode('/',$location);
			//$uploaddir2 	= $dir2[1].'/';
			$uploaddir2 	= $Folder_Cal.$location.'/';
			if($NewName){
				$filename 	= $uploaddir.$NewName.'.'.$extension;
				$filename2 	= $uploaddir2.$NewName.'.'.$extension;
			}else{
				$filename 	= $uploaddir.$data['name'];
				$filename2 	= $uploaddir2.$data['name'];			
			}
			
			if (file_exists($filename)) {
				unlink($filename);
			}
			
			move_uploaded_file($uploadedfile, $filename2);
			
				
			
		}
	}
	
	function GenerateHashKey($Code=''){
		$KeyNew		= $Code.'LimChaeMoo2008811173';
		$HashKey	= hash_hmac('sha256', $Code, 'LimChaeMoo2008811173');
		return $HashKey;
	}
	
	function GenerateHashText($Code='',$key=''){
		$HashTetx	= sha1($Code.$key);
		return $HashTetx;
	}
	
	function cek_ip_client(){
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		} else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}

		return $ip_address;
	}
	
	/*
	| ---------------------------- |
	|	KIRIM WA ~ ALI 2022-05-09  |
	| ---------------------------- |
	*/
	function Kirim_Whatsapp($Phone="",$Pesan="") {
		$Whatsapp_Key   = '86318aefb4dd55a2917c0f2d27bd21b707cefdcb7b826501';
		$Whatsapp_Api   = 'http://116.203.92.59/api/';
		
		
		$message	= $Pesan;
		//$message  = preg_replace( "/(\n)/", "<ENTER>", $Pesan );
		//$message  = preg_replace( "/(\r)/", "<ENTER>", $message );
		$phone_no = preg_replace( "/(\n)/", ",", $Phone );
		$phone_no = preg_replace( "/(\r)/", "", $phone_no );
		
		$rows_data		= array("phone_no" => $phone_no, "key" => $Whatsapp_Key, "message" => $message);
		$data_string 	= json_encode($rows_data);
		
		$ch = curl_init($Whatsapp_Api.'send_message');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);
		$result = curl_exec($ch);
		if(curl_error($ch)){				
			$rows_Return	= array(
				'result'		=> 2,
				'message'		=> curl_error($ch)
			);
		}else if(strtolower($result) !=='success'){
			$rows_Return	= array(
				'result'		=> 2,
				'message'		=> "Send message failed....".$result
			);
		}else{
			$rows_Return	= array(
				'result'		=> 1,
				'message'		=> $result
			);
		}
		return $rows_Return; 
		
	}
	
	function enkripsi_url($string=''){
        $output 		= false;
        $secret_key     = '4987632563987124'; //16 digits
        $secret_iv      = '4532879263570159'; //16 digits 
        $encrypt_method = 'aes-256-cbc';

        //hash $secret_key dengan algoritma sha256 
        $key = hash("sha256", $secret_key);

        //iv(initialize vector), encrypt iv dengan encrypt method AES-256-CBC (16 bytes)
        $iv     = substr(hash("sha256", $secret_iv), 0, 16);
        $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($result);
        return $output;
    }
	
	function dekripsi_url($string='')
    {
        $output 		= false;
        $secret_key     = '4987632563987124'; //16 digits
        $secret_iv      = '4532879263570159'; //16 digits 
        $encrypt_method = 'aes-256-cbc';

        //hash $secret_key dengan algoritma sha256 
        $key = hash("sha256", $secret_key);

        //iv(initialize vector), encrypt $secret_iv dengan encrypt method AES-256-CBC (16 bytes)
        $iv     = substr(hash("sha256", $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }
