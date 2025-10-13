<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
    
    public function index() {
        echo "Test controller is working!";
        echo "<br>CodeIgniter is running properly.";
        echo "<br><a href='" . base_url() . "'>Go to Home</a>";
    }
}
