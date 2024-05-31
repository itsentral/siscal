<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_dashboard_selia extends CI_Model
{

	var $table 			= 'trans_data_details';
	var $column_order 	= array(
								'trans_data_details.id', 
								'trans_details.customer_name', 
								//'trans_details.address_so', 
								'trans_details.no_so', 
								'trans_data_details.tool_name', 
								'trans_data_details.no_identifikasi', 
								'trans_data_details.no_serial_number',
								'trans_data_details.actual_teknisi_name', 
								'members.nama', 
								'trans_data_details.datet',
								null
								); 
	var $column_search 	= array(
								'trans_data_details.id', 
								'trans_details.customer_name', 
								'trans_details.address_so',
								'trans_details.no_so', 
								'trans_data_details.tool_name', 
								'trans_data_details.no_identifikasi', 
								'trans_data_details.no_serial_number',
								'trans_data_details.actual_teknisi_name', 
								'members.nama', 
								'trans_details.address_so',
								'trans_data_details.datet'); 
	var $order 			= array('trans_data_details.datet' => 'asc');

	public function __construct()
	{
		parent::__construct();
		
	}

	private function _get_datatables_query()
	{
		$Penyelia		= $this->session->userdata('siscal_userid');
		$siscalGroup	= $this->session->userdata('siscal_group_id');
		$Status			= $this->input->post('status_dashboard');

		$this->db->select('	trans_data_details.id, trans_details.no_so, trans_data_details.tool_name, 
							trans_data_details.no_identifikasi, trans_data_details.no_serial_number, 
							trans_details.address_so, trans_data_details.datet, trans_details.customer_name, trans_data_details.actual_teknisi_name,
							trans_data_details.status_selia, trans_data_details.file_kalibrasi,
							trans_data_details.modified_date, members.nama');
		$this->db->from($this->table);
		$this->db->join('trans_details', 'trans_data_details.trans_detail_id = trans_details.id');
		$this->db->join('users', 'trans_data_details.id_selia = users.id');
		$this->db->join('members', 'users.member_id = members.id');
		$this->db->where('trans_data_details.flag_proses', 'Y');
		$this->db->where('trans_data_details.approve_certificate !=', 'APV');
		$this->db->where('trans_data_details.status_selia', $Status);

		if($Status == "PRINT"){
			$this->db->where('trans_data_details.no_sertifikat !=', '');
		}

		if($siscalGroup == "10"){
			$this->db->where('trans_data_details.id_selia', $Penyelia);
		}

		if($siscalGroup == "8"){
			$this->db->where('trans_data_details.actual_teknisi_id', $Penyelia);
		}
		


		$i = 0;

		foreach ($this->column_search as $item)
		{
			if ($_POST['search']['value'])
			{
				if ($i === 0)
				{
					$this->db->group_start(); 
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) 
					$this->db->group_end(); 
			}
			$i++;
		}

		if (isset($_POST['order']))
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$Penyelia		= $this->session->userdata('siscal_userid');
		$siscalGroup	= $this->session->userdata('siscal_group_id');
		$Status			= $this->input->post('status_dashboard');

		$this->db->select('	trans_data_details.id, trans_details.no_so, trans_data_details.tool_name, 
							trans_data_details.no_identifikasi, trans_data_details.no_serial_number, 
							trans_details.address_so, trans_data_details.datet, trans_details.customer_name, trans_data_details.actual_teknisi_name,
							trans_data_details.status_selia, trans_data_details.file_kalibrasi,
							trans_data_details.modified_date, members.nama');
		$this->db->from($this->table);
		$this->db->join('trans_details', 'trans_data_details.trans_detail_id = trans_details.id');
		$this->db->join('users', 'trans_data_details.id_selia = users.id');
		$this->db->join('members', 'users.member_id = members.id');
		$this->db->where('trans_data_details.flag_proses', 'Y');
		$this->db->where('trans_data_details.approve_certificate !=', 'APV');
		$this->db->where('trans_data_details.status_selia', $Status);

		if($siscalGroup == "10"){
			$this->db->where('trans_data_details.id_selia', $Penyelia);
		}
		if($siscalGroup == "8"){
			$this->db->where('trans_data_details.actual_teknisi_id', $Penyelia);
		}
		return $this->db->count_all_results();
	}

	public function count_all_pending()
	{
		$Penyelia		= $this->session->userdata('siscal_userid');
		$siscalGroup	= $this->session->userdata('siscal_group_id');

		$this->db->select('	trans_data_details.id, trans_details.no_so, trans_data_details.tool_name, 
							trans_data_details.no_identifikasi, trans_data_details.no_serial_number, 
							trans_details.address_so, trans_data_details.datet, trans_details.customer_name, trans_data_details.actual_teknisi_name,
							trans_data_details.status_selia, trans_data_details.file_kalibrasi,
							trans_data_details.modified_date');
		$this->db->from($this->table);
		$this->db->join('trans_details', 'trans_data_details.trans_detail_id = trans_details.id');
		$this->db->where('trans_data_details.flag_proses', 'Y');
		$this->db->where('trans_data_details.status_selia', 'PENDING');
		$this->db->where('trans_data_details.approve_certificate !=', 'APV');

		if($siscalGroup == "10"){
			$this->db->where('trans_data_details.id_selia', $Penyelia);
		}
		if($siscalGroup == "8"){
			$this->db->where('trans_data_details.actual_teknisi_id', $Penyelia);
		}
		return $this->db->count_all_results();
	}

	public function count_all_revisi()
	{
		$Penyelia		= $this->session->userdata('siscal_userid');
		$siscalGroup	= $this->session->userdata('siscal_group_id');

		$this->db->select('	trans_data_details.id, trans_details.no_so, trans_data_details.tool_name, 
							trans_data_details.no_identifikasi, trans_data_details.no_serial_number, 
							trans_details.address_so, trans_data_details.datet, trans_details.customer_name, trans_data_details.actual_teknisi_name,
							trans_data_details.status_selia, trans_data_details.file_kalibrasi,
							trans_data_details.modified_date');
		$this->db->from($this->table);
		$this->db->join('trans_details', 'trans_data_details.trans_detail_id = trans_details.id');
		$this->db->where('trans_data_details.flag_proses', 'Y');
		$this->db->where('trans_data_details.status_selia', 'REVISI');
		$this->db->where('trans_data_details.approve_certificate !=', 'APV');

		if($siscalGroup == "10"){
			$this->db->where('trans_data_details.id_selia', $Penyelia);
		}
		if($siscalGroup == "8"){
			$this->db->where('trans_data_details.actual_teknisi_id', $Penyelia);
		}
		return $this->db->count_all_results();
	}

	public function count_all_print()
	{
		$Penyelia		= $this->session->userdata('siscal_userid');
		$siscalGroup	= $this->session->userdata('siscal_group_id');

		$this->db->select('	trans_data_details.id, trans_details.no_so, trans_data_details.tool_name, 
							trans_data_details.no_identifikasi, trans_data_details.no_serial_number, 
							trans_details.address_so, trans_data_details.datet, trans_details.customer_name, trans_data_details.actual_teknisi_name,
							trans_data_details.status_selia, trans_data_details.file_kalibrasi,
							trans_data_details.modified_date');
		$this->db->from($this->table);
		$this->db->join('trans_details', 'trans_data_details.trans_detail_id = trans_details.id');
		$this->db->where('trans_data_details.flag_proses', 'Y');
		$this->db->where('trans_data_details.status_selia', 'PRINT');
		$this->db->where('trans_data_details.approve_certificate !=', 'APV');

		if($siscalGroup == "10"){
			$this->db->where('trans_data_details.id_selia', $Penyelia);
		}
		if($siscalGroup == "8"){
			$this->db->where('trans_data_details.actual_teknisi_id', $Penyelia);
		}
		return $this->db->count_all_results();
	}

	public function count_all_selesai()
	{
		$Penyelia		= $this->session->userdata('siscal_userid');
		$siscalGroup	= $this->session->userdata('siscal_group_id');

		$this->db->select('	trans_data_details.id, trans_details.no_so, trans_data_details.tool_name, 
							trans_data_details.no_identifikasi, trans_data_details.no_serial_number, 
							trans_details.address_so, trans_data_details.datet, trans_details.customer_name, trans_data_details.actual_teknisi_name,
							trans_data_details.status_selia, trans_data_details.file_kalibrasi,
							trans_data_details.modified_date');
		$this->db->from($this->table);
		$this->db->join('trans_details', 'trans_data_details.trans_detail_id = trans_details.id');
		$this->db->where('trans_data_details.flag_proses', 'Y');
		$this->db->where('trans_data_details.status_selia', 'SELESAI');
		$this->db->where('trans_data_details.approve_certificate !=', 'APV');

		if($siscalGroup == "10"){
			$this->db->where('trans_data_details.id_selia', $Penyelia);
		}
		if($siscalGroup == "8"){
			$this->db->where('trans_data_details.actual_teknisi_id', $Penyelia);
		}
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select('	trans_data_details.id, trans_details.no_so, trans_data_details.tool_name, 
							trans_data_details.no_identifikasi, trans_data_details.no_serial_number, 
							trans_details.address_so, trans_data_details.datet, trans_details.customer_name, trans_data_details.actual_teknisi_name, 
							trans_data_details.status_selia, trans_data_details.file_kalibrasi,
							trans_data_details.modified_date');
		$this->db->from($this->table);
		$this->db->join('trans_details', 'trans_data_details.trans_detail_id = trans_details.id');
		$this->db->where('trans_data_details.id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	// public function update($data, $where)
	// {
	// 	if ($this->db->update($this->table, $data, $where)) {
	// 		$this->db->affected_rows();
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
	// }

	// public function update_detail($data, $where)
	// {
	// 	if ($this->db->update($this->table_detail, $data, $where)) {
	// 		$this->db->affected_rows();
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
	// }

}
