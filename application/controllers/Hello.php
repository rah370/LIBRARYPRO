<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hello extends CI_Controller {
    
    public function index() {
        echo "<h1>Hello from CodeIgniter!</h1>";
        echo "<p>This is a simple PHP page.</p>";
        echo "<p>CodeIgniter is working!</p>";
        echo "<p><a href='" . base_url() . "'>Go to Home</a></p>";
        echo "<p><a href='" . base_url('library') . "'>Go to Library</a></p>";
    }
}


