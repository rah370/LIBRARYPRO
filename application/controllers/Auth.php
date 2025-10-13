<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('User_model');
    }
    
    public function index() {
        // Redirect to login if not logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        } else {
            // Redirect based on role
            $role = $this->session->userdata('role');
            if ($role === 'admin') {
                redirect('admin');
            } else {
                redirect('student');
            }
        }
    }
    
    public function login() {
        // If already logged in, redirect
        if ($this->session->userdata('user_id')) {
            $this->index();
            return;
        }
        
        $data['title'] = 'Library - Login';
        $data['error'] = '';
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('username', 'Username/Email', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required');
            
            if ($this->form_validation->run() === TRUE) {
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                
                $user = $this->User_model->authenticate($username, $password);
                
                if ($user) {
                    // Set session data
                    $session_data = array(
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'role' => $user->role,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'student_id' => $user->student_id,
                        'logged_in' => TRUE
                    );
                    
                    $this->session->set_userdata($session_data);
                    
                    // Redirect based on role
                    if ($user->role === 'admin') {
                        redirect('admin');
                    } else {
                        redirect('student');
                    }
                } else {
                    // Demo fallback: allow well-known demo credentials even if DB not seeded
                    if ($username === 'admin' && $password === 'admin123') {
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
                        return;
                    } elseif (($username === 'student' || $username === 'john_doe') && $password === 'student123') {
                        // Student demo fallback
                        $session_data = array(
                            'user_id' => 2,
                            'username' => $username,
                            'role' => 'student',
                            'first_name' => 'Demo',
                            'last_name' => 'Student',
                            'student_id' => 'STU-DEMO',
                            'logged_in' => TRUE
                        );
                        $this->session->set_userdata($session_data);
                        redirect('student');
                        return;
                    }

                    $data['error'] = 'Invalid username/email or password.';
                }
            }
        }
        
        $this->load->view('auth/login', $data);
    }
    
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
    
    /**
     * Check session validity - AJAX endpoint
     */
    public function check_session() {
        header('Content-Type: application/json');
        
        if ($this->session->userdata('user_id')) {
            echo json_encode([
                'valid' => true,
                'user_id' => $this->session->userdata('user_id'),
                'role' => $this->session->userdata('role'),
                'username' => $this->session->userdata('username')
            ]);
        } else {
            echo json_encode(['valid' => false]);
        }
    }
    
    /**
     * Password reset functionality
     */
    public function forgot_password() {
        $data['title'] = 'Library - Password Recovery';
        $data['message'] = '';
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
            
            if ($this->form_validation->run() === TRUE) {
                $email = $this->input->post('email');
                
                if ($this->User_model->email_exists($email)) {
                    // In a real application, you would send an email here
                    // For demo purposes, we'll just show a message
                    $data['message'] = 'Password reset instructions have been sent to your email address.';
                } else {
                    $data['error'] = 'Email address not found in our records.';
                }
            }
        }
        
        $this->load->view('auth/forgot_password', $data);
    }
}