<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('User_model');
        $this->load->model('Book_model');
        $this->load->model('Borrow_model');
        
        // Check if user is logged in and is admin
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            redirect('auth/login');
        }
    }
    
    public function index() {
        $data['title'] = 'Reports & Analytics';
        $data['user'] = $this->session->userdata();
        
        // Get comprehensive statistics
        $data['book_stats'] = $this->Book_model->get_book_statistics();
        $data['borrow_stats'] = $this->Borrow_model->get_borrow_statistics();
        $data['user_stats'] = $this->get_user_statistics();
        $data['monthly_stats'] = $this->get_monthly_statistics();
        $data['overdue_report'] = $this->Borrow_model->get_overdue_borrows();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/reports', $data);
        $this->load->view('admin/footer');
    }
    
    public function export_books() {
        $books = $this->Book_model->get_all_books_admin();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="books_report_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Title', 'Author', 'ISBN', 'Available', 'Times Borrowed', 'Created']);
        
        foreach ($books as $book) {
            fputcsv($output, [
                $book->id,
                $book->title,
                $book->author,
                $book->isbn,
                $book->available ? 'Yes' : 'No',
                $book->borrow_count ?? 0,
                $book->created_at
            ]);
        }
        
        fclose($output);
    }
    
    public function export_borrows() {
        $borrows = $this->Borrow_model->get_all_borrows();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="borrows_report_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Student', 'Student ID', 'Book', 'Author', 'Borrow Date', 'Due Date', 'Return Date', 'Status']);
        
        foreach ($borrows as $borrow) {
            fputcsv($output, [
                $borrow->id,
                $borrow->first_name . ' ' . $borrow->last_name,
                $borrow->student_id,
                $borrow->title,
                $borrow->author,
                $borrow->borrow_date,
                $borrow->return_date,
                $borrow->actual_return_date ?? 'Not returned',
                $borrow->status
            ]);
        }
        
        fclose($output);
    }
    
    private function get_user_statistics() {
        $stats = array();
        
        // Total students
        $this->db->select('COUNT(*) as total');
        $this->db->from('users');
        $this->db->where('role', 'student');
        $query = $this->db->get();
        $stats['total_students'] = $query->row()->total;
        
        // Active students (students who have borrowed at least one book)
        $this->db->select('COUNT(DISTINCT user_id) as active');
        $this->db->from('borrows');
        $query = $this->db->get();
        $stats['active_students'] = $query->row()->active;
        
        // Recent registrations (last 30 days)
        $this->db->select('COUNT(*) as recent');
        $this->db->from('users');
        $this->db->where('role', 'student');
        $this->db->where('created_at >', date('Y-m-d H:i:s', strtotime('-30 days')));
        $query = $this->db->get();
        $stats['recent_students'] = $query->row()->recent;
        
        return $stats;
    }
    
    private function get_monthly_statistics() {
        $stats = array();
        
        // Borrows per month (last 6 months)
        for ($i = 5; $i >= 0; $i--) {
            $month_start = date('Y-m-01', strtotime("-$i months"));
            $month_end = date('Y-m-t', strtotime("-$i months"));
            
            $this->db->select('COUNT(*) as count');
            $this->db->from('borrows');
            $this->db->where('borrow_date >=', $month_start);
            $this->db->where('borrow_date <=', $month_end . ' 23:59:59');
            $query = $this->db->get();
            
            $stats['monthly_borrows'][] = array(
                'month' => date('M Y', strtotime($month_start)),
                'count' => $query->row()->count
            );
        }
        
        return $stats;
    }
}