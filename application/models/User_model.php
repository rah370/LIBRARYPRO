<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function authenticate($username, $password) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->or_where('email', $username);
        $query = $this->db->get();
        
        $user = $query->row();
        
        if ($user && password_verify($password, $user->password)) {
            // Update last login
            $this->db->where('id', $user->id);
            $this->db->update('users', array('last_login' => date('Y-m-d H:i:s')));
            
            return $user;
        }
        
        return false;
    }
    
    public function get_user_by_id($id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Get a user by username (or email) — helper used for demo fallbacks
     */
    public function get_user_by_username($username) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function get_all_students() {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('role', 'student');
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function create_student($data) {
        // Hash the password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['role'] = 'student';
        
        return $this->db->insert('users', $data);
    }
    
    public function update_user($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }
    
    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }
    
    public function username_exists($username, $exclude_id = null) {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('username', $username);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $query = $this->db->get();
        return $query->num_rows() > 0;
    }
    
    public function email_exists($email, $exclude_id = null) {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('email', $email);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $query = $this->db->get();
        return $query->num_rows() > 0;
    }
    
    public function count_students() {
        $this->db->select('COUNT(*) as count');
        $this->db->from('users');
        $this->db->where('role', 'student');
        $query = $this->db->get();
        return $query->row()->count;
    }
    
    public function get_user_statistics() {
        $stats = array();
        
        // Total users
        $this->db->select('COUNT(*) as total');
        $this->db->from('users');
        $query = $this->db->get();
        $stats['total_users'] = $query->row()->total;
        
        // Students count
        $this->db->select('COUNT(*) as students');
        $this->db->from('users');
        $this->db->where('role', 'student');
        $query = $this->db->get();
        $stats['students'] = $query->row()->students;
        
        // Admins count
        $this->db->select('COUNT(*) as admins');
        $this->db->from('users');
        $this->db->where('role', 'admin');
        $query = $this->db->get();
        $stats['admins'] = $query->row()->admins;
        
        // Active users
        $this->db->select('COUNT(*) as active');
        $this->db->from('users');
        $this->db->where('status', 'active');
        $query = $this->db->get();
        $stats['active_users'] = $query->row()->active;
        
        return $stats;
    }
    
    /**
     * Get all users with pagination
     */
    public function get_users($limit = null, $offset = null) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->order_by('created_at', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Count all users
     */
    public function count_all_users() {
        return $this->db->count_all('users');
    }
    
    /**
     * Create a new user (Admin function)
     */
    public function create_user($data) {
        return $this->db->insert('users', $data);
    }
    

    
    /**
     * Get most active users
     */
    public function get_most_active_users($limit = 10) {
        $this->db->select('u.*, COUNT(b.id) as borrow_count');
        $this->db->from('users u');
        $this->db->join('borrows b', 'u.id = b.user_id', 'left');
        $this->db->where('u.role', 'student');
        $this->db->group_by('u.id');
        $this->db->order_by('borrow_count', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Search users
     */
    public function search_users($search_term, $role = null) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->group_start();
        $this->db->like('first_name', $search_term);
        $this->db->or_like('last_name', $search_term);
        $this->db->or_like('username', $search_term);
        $this->db->or_like('email', $search_term);
        $this->db->group_end();
        
        if ($role) {
            $this->db->where('role', $role);
        }
        
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
}