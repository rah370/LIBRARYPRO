<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }
    
    public function index() {
        // Simple redirect to login
        redirect('auth/login');
    }
    
    public function login() {
        $data['title'] = 'Library - Login';
        $data['error'] = '';
        
        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            // Simple hardcoded authentication for testing
            if (($username === 'admin' && $password === 'admin123') || 
                ($username === 'john_doe' && $password === 'student123')) {
                
                // Set session data
                if ($username === 'admin') {
                    $session_data = array(
                        'user_id' => 1,
                        'username' => 'admin',
                        'role' => 'admin',
                        'first_name' => 'Library',
                        'last_name' => 'Administrator',
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata($session_data);
                    redirect('admin');
                } else {
                    $session_data = array(
                        'user_id' => 2,
                        'username' => 'john_doe',
                        'role' => 'student',
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'student_id' => 'STU001',
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata($session_data);
                    redirect('student');
                }
            } else {
                $data['error'] = 'Invalid username or password.';
            }
        }
        
        $this->load->view('auth/login', $data);
    }
    
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}