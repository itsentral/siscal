<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subcon_open extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->database();
        if (!$this->session->userdata('isSISCALlogin')) {
            redirect('login');
        }
        $controller            = ucfirst(strtolower($this->uri->segment(1)));
        $this->Arr_Akses            = getAcccesmenu($controller);
    }

    public function index()
    {
        $Arr_Akses = $this->Arr_Akses;
        if ($Arr_Akses['read'] != '1') {
            $this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
            redirect(site_url('dashboard'));
        }

        $get_Data_Open            = $this->master_model->getData('subcon_purchase_orders', 'sts_subcon', 'OPN');

        // var_dump($Arr_Akses);
        // die();
        $data = array(
            'title' => 'Data Open',
            'action'        => 'index',
            'row'            => $get_Data_Open,
            'akses_menu'    => $Arr_Akses,
        );

        // var_dump($data);
        // die;

        $this->load->view('Subcon_open/index', $data);
    }



    public function edit($id = '')
    {

        $Arr_Akses = $this->Arr_Akses;
        if ($Arr_Akses['update'] != '1') {
            $this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
            redirect(site_url('dashboard'));
        }
        if ($this->input->post()) {
            //echo"<pre>";print_r($this->input->post());exit;
            $data_item = array(
                'item' => $this->input->post('item')
            );
            $data_akomodasi = array(
                'akomodasi' => $this->input->post('akomodasi'),
            );
            function removeMy1(&$element, $index)
            {
                if ($index === 'price' || $index === 'total') {
                    $element = str_replace(",", "", $element);
                }
            }
            function removeMy2(&$element, $index)
            {
                if ($index === 'dpp' || $index === 'ppn' || $index === 'grand_tot' || $index === 'dpp_after_discount') {
                    $element = str_replace(",", "", $element);
                }
            }
            function removeMy3(&$element, $index)
            {
                if ($index === 'nilai' || $index === 'diskon' || $index === 'total') {
                    $element = str_replace(",", "", $element);
                }
            }

            $data_subcon                    = array(
                'subcon_pono' => $this->input->post('subcon_pono'),
                'datet' => $this->input->post('datet'),
                'supplier_name' => $this->input->post('supplier_name'),
                'pic_name' => $this->input->post('pic_name'),
                'exc_ppn' => $this->input->post('exc_ppn'),
                'dpp_after_discount' => $this->input->post('dpp'),
                'dpp' => $this->input->post('dpp'),
                'ppn' => $this->input->post('ppn'),
                'grand_tot' => $this->input->post('grand_tot'),
                'id' => $this->input->post('id'),
                // 'akomodasi' => $akomodasi,
            );
            $Arr_Kembali            = array();
            // unset($data_subcon['item']);
            $data_subcon['modified_by']    = $this->session->userdata('siscal_username');
            $data_subcon['modified_date']    = date('Y-m-d H:i:s');
            // $data_subcon['sts_subcon'] = 'APV';
            // $count++;
            // var_dump($data_akomodasi['akomodasi']);
            // // var_dump($data_subcon);
            // die();
            $data_histories = array();
            $data_histories['user_id'] = $this->session->userdata('siscal_userid');
            $data_histories['description'] = 'Edit Data Open';
            $data_histories['path'] = 'Subcon_open/Edit';
            $data_histories['created'] = date('Y-m-d H:i:s');
            // var_dump($data_histories);
            // die();
            array_walk_recursive($data_item['item'], "removeMy1");
            foreach ($data_item['item']  as $key) {
                $data[] = $key;
            };

            array_walk_recursive($data_subcon, "removeMy2");
            array_walk_recursive($data_akomodasi, "removeMy3");


            // var_dump($data_akomodasi);
            // die();

            if (is_null($data_akomodasi['akomodasi'])) {
                $this->db->trans_start();
                $this->db->update_batch('subcon_purchase_order_details', $data, 'id');
                $this->master_model->getUpdate('subcon_purchase_orders', $data_subcon, 'id', $data_subcon['id']);
                $this->master_model->Simpan('histories', $data_histories);
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {

                    $Arr_Kembali        = array(
                        'status'        => '2',
                        'pesan'            => 'Edit Data Failed. Please try again later.......'
                    );
                    $this->db->trans_rollback();
                } else {

                    $Arr_Kembali        = array(
                        'status'        => '1',
                        'pesan'            => 'Edit Data Success. Thank you and have a nice day......'
                    );
                    $this->db->trans_commit();
                }
                echo json_encode($Arr_Kembali);
            } else {
                foreach ($data_akomodasi['akomodasi']  as $key) {
                    $data_akoms[] = $key;
                    //check if id exist or not in subcon_accommodations
                    $query = ('SELECT id FROM subcon_accommodations WHERE id = ? ');

                    //get name accommodations
                    $query1 = ('SELECT name FROM accommodations WHERE id = ? ');
                    $exquery = $this->db->query($query1, array($key['accommodation_id']))->result();
                    foreach ($exquery as $var) {
                        $data1[] = $var;
                        $key['name'] = $var->name;
                    }
                    /// check if id on subcon_accommodations exist or not
                    if (empty($this->db->query($query, array($key['id']))->result())) {

                        // OLD QUERY
                        // $query2 = ('INSERT INTO subcon_accommodations (id, subcon_purchase_order_id, accommodation_id, accommodation_name, nilai, diskon, total, flag_inv) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                        // $this->db->query($query2, array($key['id'], $data_subcon['id'], $key['accommodation_id'], $key['name'], $key['nilai'], $key['diskon'], $key['total'], 'Y'));
                        // CLOSE OLD QUERY

                        $query2 = ('REPLACE INTO subcon_accommodations (id, subcon_purchase_order_id, accommodation_id, accommodation_name, nilai, diskon, total, flag_inv) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                        $this->db->query($query2, array($key['id'], $data_subcon['id'], $key['accommodation_id'], $key['name'], $key['nilai'], $key['diskon'], $key['total'], 'Y'));
                    } else {
                        $query2 = ('REPLACE INTO subcon_accommodations (id, subcon_purchase_order_id, accommodation_id, accommodation_name, nilai, diskon, total, flag_inv) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                        $this->db->query($query2, array($key['id'], $data_subcon['id'], $key['accommodation_id'], $key['name'], $key['nilai'], $key['diskon'], $key['total'], 'Y'));
                        // var_dump('ada');
                    }
                };

                $this->db->trans_start();
                $sum = ('SELECT SUM(total) as grandtotal FROM subcon_accommodations WHERE subcon_purchase_order_id = ?');
                $grandtotal = $this->db->query($sum, array($data_subcon['id']))->result();
                foreach ($grandtotal as $gt) {
                    $grandtotal = $gt->grandtotal;
                    $data_subcon['akomodasi'] = $gt->grandtotal;
                };
                $this->db->update_batch('subcon_accommodations', $data_akoms, 'id');
                $this->db->update_batch('subcon_purchase_order_details', $data, 'id');
                $this->master_model->getUpdate('subcon_purchase_orders', $data_subcon, 'id', $data_subcon['id']);
                $this->master_model->Simpan('histories', $data_histories);
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {

                    $Arr_Kembali        = array(
                        'status'        => '2',
                        'pesan'            => 'Edit Data Failed. Please try again later.......'
                    );
                    $this->db->trans_rollback();
                } else {

                    $Arr_Kembali        = array(
                        'status'        => '1',
                        'pesan'            => 'Edit Data Success. Thank you and have a nice day......'
                    );
                    $this->db->trans_commit();
                }
                echo json_encode($Arr_Kembali);
            }
        } else {
            $controller            = ucfirst(strtolower($this->uri->segment(1)));
            $Arr_Akses            = getAcccesmenu($controller);
            if ($Arr_Akses['read'] != '1') {
                $this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
                redirect(site_url('dashboard'));
            }

            $get_Edit_Data_Open            = $this->master_model->getData('subcon_purchase_orders', 'id', $id);
            $get_Detail_Data_Open            = $this->master_model->getData('subcon_purchase_order_details', 'subcon_purchase_order_id', $id);
            $get_Akomodasi                 = $this->master_model->getData('subcon_accommodations', 'subcon_purchase_order_id', $id);
            $get_list_akom                 = $this->master_model->getData('accommodations');

            // $get_list_akom->result();



            $data = array(
                'title' => 'Edit Data Open',
                'action'        => 'index',
                'row'            => $get_Edit_Data_Open,
                'detail'        =>  $get_Detail_Data_Open,
                'akses_menu'    => $Arr_Akses,
                'akomm'         => $get_Akomodasi,
                'list_akomm'    => $get_list_akom
            );

            // var_dump($data['akomm']);
            // die();


            $this->load->view('Subcon_open/edit', $data);
        }
    }

    public function view($id = '')
    {
        $controller            = ucfirst(strtolower($this->uri->segment(1)));
        $Arr_Akses            = getAcccesmenu($controller);
        if ($Arr_Akses['read'] != '1') {
            $this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
            redirect(site_url('dashboard'));
        };

        $get_Edit_Data_Open            = $this->master_model->getData('subcon_purchase_orders', 'id', $id);
        $get_Detail_Data_Open            = $this->master_model->getData('subcon_purchase_order_details', 'subcon_purchase_order_id', $id);
        $get_Akomodasi                 = $this->master_model->getData('subcon_accommodations', 'subcon_purchase_order_id', $id);




        $data = array(
            'title' => 'View Data Open',
            'action'        => 'index',
            'row'            => $get_Edit_Data_Open,
            'detail'        =>  $get_Detail_Data_Open,
            'akses_menu'    => $Arr_Akses,
            'akomodasi' => $get_Akomodasi
        );

        // var_dump($data);
        // die();

        $this->load->view('Subcon_open/view', $data);
    }


    public function modalEdit()
    {
        $controller            = ucfirst(strtolower($this->uri->segment(1)));
        $Arr_Akses            = getAcccesmenu($controller);
        $Arr_Akses = $this->Arr_Akses;
        if ($Arr_Akses['approve'] != '1') {
            $this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
            redirect(site_url('dashboard'));
        }
        $rows_Header    = $rows_Detail    = array();
        if ($this->input->post('code')) {
            $Code_Narasi    = $this->input->post('code');
            $id = $this->input->post('id');
            $rows_Header    = $this->db->get_where('subcon_purchase_orders', array('subcon_pono' => $Code_Narasi))->result();
            $get_Detail_Data_Open = $this->master_model->getData('subcon_purchase_order_details', 'subcon_purchase_order_id', $id);
            $get_Akomodasi                 = $this->master_model->getData('subcon_accommodations', 'subcon_purchase_order_id', $id);
            $get_list_akom                 = $this->master_model->getData('accommodations');
        }

        $data = array(
            'title'            => 'Approve Subcon',
            'action'        => 'modalEdit',
            'rows_header'    => $rows_Header,
            'detail'        =>  $get_Detail_Data_Open,
            'akses_menu'    => $Arr_Akses,
            'akomm'         => $get_Akomodasi,
            'list_akomm'    => $get_list_akom


        );
        // var_dump($id);
        // die();
        $this->load->view('Subcon_open/modalEdit', $data);
    }

    public function editApv()
    {
        $Arr_Akses = $this->Arr_Akses;
        if ($Arr_Akses['approve'] != '1') {
            $this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
            redirect(site_url('dashboard'));
        }
        $Arr_Kembali            = array();
        if ($this->input->post()) {
            $data_subcon = array(
                'id' => $this->input->post('id'),
            );
            $data_subcon['sts_subcon'] = 'APV';
            $data_subcon['modified_by']    = $this->session->userdata('siscal_username');
            $data_subcon['modified_date']    = date('Y-m-d H:i:s');

            $data_histories = array();
            $data_histories['user_id'] = $this->session->userdata('siscal_userid');
            $data_histories['description'] = 'Edit Data Open To Approve ' . $data_subcon['id'];
            $data_histories['path'] = 'Subcon_open/editApv';
            $data_histories['created'] = date('Y-m-d H:i:s');
            // var_dump($data_histories);
            // die();

            $this->db->trans_start();
            $this->db->trans_strict(true);
            $this->master_model->getUpdate('subcon_purchase_orders', $data_subcon, 'id', $data_subcon['id']);
            $this->master_model->Simpan('histories', $data_histories);
            $this->db->trans_complete();
            if ($this->db->trans_status() == false) {
                $Arr_Kembali        = array(
                    'status'        => '2',
                    'pesan'            => 'Edit Data Failed. Please Try Again Later......'
                );
                $this->db->trans_rollback();
            } else {
                $Arr_Kembali        = array(
                    'status'        => '1',
                    'pesan'            => 'Edit Data Success. Thank You and Have a Nice Day......'
                );
                $this->db->trans_commit();
            }
            echo json_encode($Arr_Kembali);

            // var_dump($data_subcon);
            // die();
        }
    }

    public function delete($id)
    {
        $controller            = ucfirst(strtolower($this->uri->segment(1)));
        $Arr_Akses            = getAcccesmenu($controller);
        if ($Arr_Akses['delete'] != '1') {
            $this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
            redirect(site_url('dashboard'));
        }

        if ($this->master_model->getDelete('subcon_accommodations', 'id', $id) > 0) {
            $this->session->set_flashdata("alert_data", "<div class=\"alert alert-success\" id=\"flash-message\">Data has been successfully deleted...........!!</div>");
            history('Delete Data Subcon-Accommodations' . $id);
            redirect(site_url('Subcon_open'));
        }
    }
}
