<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_followuplist extends CI_Model
{

	var $table 			= 'follow_up_sales';
	var $column_order 	= array('nama_sales', 'date_activity', 'status_activity', 'ket_activity',null); 
	var $column_search 	= array('nama_sales', 'date_activity', 'status_activity', 'ket_activity'); 
	var $order 			= array('date_activity' => 'asc');

	public function __construct()
	{
		parent::__construct();
		
	}

	private function _get_datatables_query()
	{

		$this->db->from($this->table);
		$this->db->where('customer_id =', $this->input->post('cus'));

		// if ($this->input->post('cus') != "") {
        //     $this->db->where('no_so =', $this->input->post('cus'));
        // }else{
		// 	$this->db->where('no_so =', $this->input->post('searchVal'));
		// }
		
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
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function save($data)
	{
		if ($this->db->insert($this->table, $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function update($data, $where)
	{
		if ($this->db->update($this->table, $data, $where)) {
			$this->db->affected_rows();
			return true;
		} else {
			return false;
		}
	}

	public function delete_by_id($id)
	{
		$delete = $this->db->where('id', $id)->delete($this->table);
		if ($delete) {
			return true;
		} else {
			return false;
		}
	}

}
