<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Book_model');
        $this->load->model('Borrow_model');
        $this->load->model('User_model');
        
        // Only admin can access system info
        if ($this->session->userdata('role') !== 'admin') {
            redirect('auth/login');
        }
    }
    
    public function index() {
        $data['title'] = 'System Status';
        $data['user'] = $this->session->userdata();
        
        // Get system information
        $data['system_info'] = $this->get_system_info();
        $data['database_info'] = $this->get_database_info();
        $data['library_stats'] = $this->get_library_stats();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/system_status', $data);
        $this->load->view('admin/footer');
    }
    
    public function backup_database() {
        try {
            $backup_file = $this->create_database_backup();
            
            // Force download
            force_download($backup_file, file_get_contents(FCPATH . $backup_file));
            
            $this->session->set_flashdata('success', 'Database backup created successfully.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Failed to create backup: ' . $e->getMessage());
        }
        
        redirect('system');
    }
    
    public function clear_logs() {
        try {
            $log_path = APPPATH . 'logs/';
            $files = glob($log_path . '*.php');
            
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            
            $this->session->set_flashdata('success', 'Log files cleared successfully.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Failed to clear logs: ' . $e->getMessage());
        }
        
        redirect('system');
    }
    
    private function get_system_info() {
        return array(
            'php_version' => phpversion(),
            'codeigniter_version' => CI_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
            'server_time' => date('Y-m-d H:i:s'),
            'memory_limit' => ini_get('memory_limit'),
            'memory_usage' => $this->format_bytes(memory_get_usage(true)),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'timezone' => date_default_timezone_get()
        );
    }
    
    private function get_database_info() {
        $db_info = array();
        
        try {
            // Database file info
            $db_file = FCPATH . 'library.db';
            if (file_exists($db_file)) {
                $db_info['database_file'] = 'library.db';
                $db_info['database_size'] = $this->format_bytes(filesize($db_file));
                $db_info['last_modified'] = date('Y-m-d H:i:s', filemtime($db_file));
                $db_info['file_permissions'] = substr(sprintf('%o', fileperms($db_file)), -4);
            } else {
                $db_info['error'] = 'Database file not found';
            }
            
            // Table information
            $tables = $this->db->list_tables();
            $db_info['tables'] = array();
            
            foreach ($tables as $table) {
                $query = $this->db->query("SELECT COUNT(*) as count FROM " . $table);
                $count = $query->row()->count;
                $db_info['tables'][$table] = $count;
            }
            
        } catch (Exception $e) {
            $db_info['error'] = $e->getMessage();
        }
        
        return $db_info;
    }
    
    private function get_library_stats() {
        return array(
            'total_books' => $this->Book_model->count_all(),
            'available_books' => $this->Book_model->count_available(),
            'borrowed_books' => $this->Book_model->count_borrowed(),
            'total_students' => $this->User_model->count_students(),
            'active_borrows' => $this->Borrow_model->count_active(),
            'overdue_books' => $this->Borrow_model->count_overdue(),
            'total_borrows_today' => $this->Borrow_model->count_today(),
            'total_returns_today' => $this->Borrow_model->count_returns_today()
        );
    }
    
    private function create_database_backup() {
        $backup_name = 'library_backup_' . date('Y_m_d_H_i_s') . '.db';
        $source = FCPATH . 'library.db';
        $destination = FCPATH . $backup_name;
        
        if (!file_exists($source)) {
            throw new Exception('Database file not found');
        }
        
        if (!copy($source, $destination)) {
            throw new Exception('Failed to create backup file');
        }
        
        return $backup_name;
    }
    
    private function format_bytes($size, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $base = log($size, 1024);
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $units[floor($base)];
    }
}