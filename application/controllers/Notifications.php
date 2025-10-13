<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('email');
        $this->load->model('Borrow_model');
        $this->load->model('User_model');
        
        // Only admin can access notifications
        if ($this->session->userdata('role') !== 'admin') {
            redirect('auth/login');
        }
    }
    
    public function index() {
        $data['title'] = 'Notification Center';
        $data['user'] = $this->session->userdata();
        
        // Get overdue books
        $data['overdue_books'] = $this->get_overdue_books();
        
        // Get books due soon (within 3 days)
        $data['due_soon_books'] = $this->get_due_soon_books();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/notifications', $data);
        $this->load->view('admin/footer');
    }
    
    public function send_overdue_reminders() {
        $overdue_books = $this->get_overdue_books();
        $sent_count = 0;
        $errors = array();
        
        foreach ($overdue_books as $borrow) {
            try {
                $this->send_overdue_email($borrow);
                $sent_count++;
                
                // Log the notification
                $this->log_notification($borrow->user_id, 'overdue_reminder', 
                    "Overdue reminder sent for book: {$borrow->book_title}");
                    
            } catch (Exception $e) {
                $errors[] = "Failed to send email to {$borrow->user_email}: " . $e->getMessage();
            }
        }
        
        $this->session->set_flashdata('success', 
            "Overdue reminders sent to {$sent_count} users.");
            
        if (!empty($errors)) {
            $this->session->set_flashdata('error', implode('<br>', $errors));
        }
        
        redirect('notifications');
    }
    
    public function send_due_soon_reminders() {
        $due_soon_books = $this->get_due_soon_books();
        $sent_count = 0;
        $errors = array();
        
        foreach ($due_soon_books as $borrow) {
            try {
                $this->send_due_soon_email($borrow);
                $sent_count++;
                
                // Log the notification
                $this->log_notification($borrow->user_id, 'due_soon_reminder', 
                    "Due soon reminder sent for book: {$borrow->book_title}");
                    
            } catch (Exception $e) {
                $errors[] = "Failed to send email to {$borrow->user_email}: " . $e->getMessage();
            }
        }
        
        $this->session->set_flashdata('success', 
            "Due soon reminders sent to {$sent_count} users.");
            
        if (!empty($errors)) {
            $this->session->set_flashdata('error', implode('<br>', $errors));
        }
        
        redirect('notifications');
    }
    
    public function send_individual_reminder($borrow_id) {
        $this->db->select('b.*, u.email as user_email, u.name as user_name, books.title as book_title');
        $this->db->from('borrows b');
        $this->db->join('users u', 'b.user_id = u.id');
        $this->db->join('books', 'b.book_id = books.id');
        $this->db->where('b.id', $borrow_id);
        $query = $this->db->get();
        
        if ($query->num_rows() == 0) {
            $this->session->set_flashdata('error', 'Borrow record not found.');
            redirect('notifications');
        }
        
        $borrow = $query->row();
        
        try {
            if (strtotime($borrow->due_date) < time()) {
                $this->send_overdue_email($borrow);
                $message = "Overdue reminder sent to {$borrow->user_name}";
            } else {
                $this->send_due_soon_email($borrow);
                $message = "Due soon reminder sent to {$borrow->user_name}";
            }
            
            // Log the notification
            $this->log_notification($borrow->user_id, 'manual_reminder', 
                "Manual reminder sent for book: {$borrow->book_title}");
            
            $this->session->set_flashdata('success', $message);
            
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 
                "Failed to send reminder: " . $e->getMessage());
        }
        
        redirect('notifications');
    }
    
    private function get_overdue_books() {
        $this->db->select('b.*, u.email as user_email, u.name as user_name, books.title as book_title');
        $this->db->from('borrows b');
        $this->db->join('users u', 'b.user_id = u.id');
        $this->db->join('books', 'b.book_id = books.id');
        $this->db->where('b.returned_at IS NULL');
        $this->db->where('b.due_date <', date('Y-m-d'));
        $this->db->order_by('b.due_date', 'ASC');
        
        $query = $this->db->get();
        return $query->result();
    }
    
    private function get_due_soon_books() {
        $today = date('Y-m-d');
        $in_three_days = date('Y-m-d', strtotime('+3 days'));
        
        $this->db->select('b.*, u.email as user_email, u.name as user_name, books.title as book_title');
        $this->db->from('borrows b');
        $this->db->join('users u', 'b.user_id = u.id');
        $this->db->join('books', 'b.book_id = books.id');
        $this->db->where('b.returned_at IS NULL');
        $this->db->where('b.due_date >=', $today);
        $this->db->where('b.due_date <=', $in_three_days);
        $this->db->order_by('b.due_date', 'ASC');
        
        $query = $this->db->get();
        return $query->result();
    }
    
    private function send_overdue_email($borrow) {
        $days_overdue = floor((time() - strtotime($borrow->due_date)) / (60 * 60 * 24));
        
        $subject = "Overdue Book Reminder - {$borrow->book_title}";
        $message = "
        <h2>Overdue Book Reminder</h2>
        <p>Dear {$borrow->user_name},</p>
        <p>This is a reminder that the following book is overdue:</p>
        <div style='background-color: #f8f9fa; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0;'>
            <h3>{$borrow->book_title}</h3>
            <p><strong>Due Date:</strong> " . date('M j, Y', strtotime($borrow->due_date)) . "</p>
            <p><strong>Days Overdue:</strong> {$days_overdue} days</p>
        </div>
        <p>Please return this book as soon as possible to avoid any late fees.</p>
        <p>If you have any questions, please contact the library administrator.</p>
        <p>Thank you,<br>Library Management System</p>
        ";
        
        $this->send_email($borrow->user_email, $subject, $message);
    }
    
    private function send_due_soon_email($borrow) {
        $days_until_due = floor((strtotime($borrow->due_date) - time()) / (60 * 60 * 24));
        
        $subject = "Book Due Soon - {$borrow->book_title}";
        $message = "
        <h2>Book Due Soon Reminder</h2>
        <p>Dear {$borrow->user_name},</p>
        <p>This is a reminder that the following book is due soon:</p>
        <div style='background-color: #f8f9fa; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;'>
            <h3>{$borrow->book_title}</h3>
            <p><strong>Due Date:</strong> " . date('M j, Y', strtotime($borrow->due_date)) . "</p>
            <p><strong>Days Until Due:</strong> {$days_until_due} days</p>
        </div>
        <p>Please make arrangements to return this book on or before the due date.</p>
        <p>If you need to extend your borrowing period, please contact the library administrator.</p>
        <p>Thank you,<br>Library Management System</p>
        ";
        
        $this->send_email($borrow->user_email, $subject, $message);
    }
    
    private function send_email($to, $subject, $message) {
        $config = array(
            'protocol' => 'mail',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => TRUE
        );
        
        $this->email->initialize($config);
        $this->email->from('noreply@library.com', 'Library Management System');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        
        if (!$this->email->send()) {
            throw new Exception($this->email->print_debugger());
        }
    }
    
    private function log_notification($user_id, $type, $message) {
        $data = array(
            'user_id' => $user_id,
            'type' => $type,
            'message' => $message,
            'sent_at' => date('Y-m-d H:i:s')
        );
        
        // Create notifications table if it doesn't exist
        $this->create_notifications_table();
        
        $this->db->insert('notifications', $data);
    }
    
    private function create_notifications_table() {
        if (!$this->db->table_exists('notifications')) {
            $this->db->query("
                CREATE TABLE notifications (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    type VARCHAR(50) NOT NULL,
                    message TEXT NOT NULL,
                    sent_at DATETIME NOT NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id)
                )
            ");
        }
    }
}