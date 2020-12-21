<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model {

    private $table = 'employee';

    public function fetchEmployees($empId=0, $filter=[])
    {
        if($filter && isset($filter['filter_type']) && $filter['filter_type'] == 'department') {
            $this->db->where('dept_code', $filter['filter_val']);
        }

        if($empId > 0) {
            $this->db->where('id', $empId);
        }

        $query = $this->db->get($this->table);
        if ($empId > 0) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function unlinkEmployee($empId=0)
    {
        $this ->db->where('id', $empId);
        $this->db->delete($this->table);
    }

    public function addEmployee($empData=[]) {
        $this->db->insert($this->table, $empData);
    }

    public function updateEmployee($empId, $empData=[]) {
        $this->db->where('id', $empId);
        $this->db->update($this->table, $empData);
    }
}