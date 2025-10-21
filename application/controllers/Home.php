<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Book_model');
        $this->load->model('User_model');
        $this->load->model('Borrow_model');
    }
    
    /**
     * Index Page - Landing/Home Page
     * This is the main entry point that shows the beautiful frontend landing page
     */
    public function index() {
        // Check if user is already logged in
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            if ($role === 'admin') {
                redirect('admin');
            } else {
                redirect('student');
            }
        }
        
        // Redirect to the frontend landing page for new visitors
        redirect(base_url('frontend/index.html'));
    }
    
    /**
     * Enhanced landing page with backend integration
     * This serves the landing page with real-time data from the backend
     */
    public function landing() {
        // Check if user is already logged in
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            if ($role === 'admin') {
                redirect('admin');
            } else {
                redirect('student');
            }
        }
        
        // Get real-time statistics for the landing page
        $data = array(
            'title' => 'LibraryPro — Advanced Library Management System',
            'stats' => array(
                'total_books' => $this->Book_model->get_total_books(),
                'total_users' => $this->User_model->get_total_users(),
                'active_borrows' => $this->Borrow_model->get_active_borrows_count(),
                'system_uptime' => $this->get_system_uptime()
            ),
            'recent_books' => $this->Book_model->get_recent_books(6),
            'system_status' => $this->get_system_status()
        );
        
        // Load the enhanced landing page view
        $this->load->view('home/landing', $data);
    }
    
    /**
     * API endpoint for landing page statistics
     */
    public function api_stats() {
        header('Content-Type: application/json');
        
        $stats = array(
            'total_books' => $this->Book_model->get_total_books(),
            'total_users' => $this->User_model->get_total_users(),
            'active_borrows' => $this->Borrow_model->get_active_borrows_count(),
            'system_uptime' => $this->get_system_uptime(),
            'timestamp' => time()
        );
        
        echo json_encode($stats);
    }
    
    /**
     * Get system uptime percentage
     */
    private function get_system_uptime() {
        // Simple uptime calculation based on system load
        $load = sys_getloadavg();
        if ($load[0] < 1.0) {
            return 99;
        } elseif ($load[0] < 2.0) {
            return 95;
        } else {
            return 90;
        }
    }
    
    /**
     * Get system status information
     */
    private function get_system_status() {
        return array(
            'database' => $this->test_database_connection(),
            'php_version' => PHP_VERSION,
            'server_time' => date('Y-m-d H:i:s'),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true)
        );
    }
    
    /**
     * Test database connection
     */
    private function test_database_connection() {
        try {
            $this->load->database();
            $query = $this->db->query("SELECT 1");
            return $query ? 'connected' : 'disconnected';
        } catch (Exception $e) {
            return 'error';
        }
    }
    
    /**
     * Demo method for testing
     */
    public function demo() {
        $data = array(
            'title' => 'LibraryPro Demo',
            'message' => 'Welcome to LibraryPro!',
            'features' => array(
                'Modern Dashboard',
                'Real-time Statistics',
                'User Management',
                'Book Cataloging',
                'Borrowing System',
                'Reports & Analytics'
            )
        );
        
        $this->load->view('home/demo', $data);
    }
}
