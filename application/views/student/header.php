<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Student Library'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Project styles -->
    <link href="<?php echo base_url('frontend/css/style.css'); ?>" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
        }
        .nav-link:hover {
            color: white !important;
            transform: translateY(-1px);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .book-card {
            height: 100%;
        }
        .book-card .card-img-top {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .btn-borrow {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            border: none;
            color: white;
            font-weight: 600;
        }
        .btn-borrow:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
            color: white;
        }
        .search-container {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php
    // Normalize $user to an array for backward compatibility with views
    // that use array access (e.g. $user['first_name']). Some controllers
    // or session implementations may provide a stdClass instead.
    if (isset($user) && is_object($user)) {
        $user = (array) $user;
    }
    ?>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand brand-with-logo" href="<?php echo base_url('student'); ?>">
                <img src="<?php echo base_url('frontend/assets/logo.png'); ?>" alt="LibraryPro" class="site-logo header-logo rounded-circle" style="max-width:48px;height:auto;">
                <span class="brand-text">Student Library</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (uri_string() == 'student' || uri_string() == 'student/index') ? 'active' : ''; ?>" 
                           href="<?php echo base_url('student'); ?>">
                            <i class="fas fa-home me-1"></i>Browse Books
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos(uri_string(), 'student/my_borrows') === 0) ? 'active' : ''; ?>" 
                           href="<?php echo base_url('student/my_borrows'); ?>">
                            <i class="fas fa-list me-1"></i>My Borrowed Books
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos(uri_string(), 'search') === 0) ? 'active' : ''; ?>" 
                           href="<?php echo base_url('search'); ?>">
                            <i class="fas fa-search me-1"></i>Advanced Search
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos(uri_string(), 'student/profile') === 0) ? 'active' : ''; ?>" 
                           href="<?php echo base_url('student/profile'); ?>">
                            <i class="fas fa-user me-1"></i>My Profile
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo $user['first_name']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">
                                <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                <br><small class="text-muted"><?php echo $user['student_id']; ?></small>
                            </h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('student/profile'); ?>">
                                <i class="fas fa-user me-2"></i>My Profile
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('auth/logout'); ?>">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>