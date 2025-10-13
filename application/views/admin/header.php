<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Admin Dashboard'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            width: 280px;
        }
        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 15px 25px;
            border-radius: 10px;
            margin: 5px 15px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
            transform: translateX(5px);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        .stat-card-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .stat-card-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="p-4">
                <div class="text-center mb-4">
                    <img src="<?php echo base_url('frontend/assets/logo.png'); ?>" alt="LibraryPro" class="site-logo-lg" style="border-radius:6px;">
                    <h5 class="text-white mb-0">Admin Panel</h5>
                    <small class="text-muted">Library Management</small>
                </div>
                
                <div class="user-info bg-dark p-3 rounded mb-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h6 class="text-white mb-0"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h6>
                            <small class="text-muted">Administrator</small>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (uri_string() == 'admin' || uri_string() == 'admin/index') ? 'active' : ''; ?>" href="<?php echo base_url('admin'); ?>">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos(uri_string(), 'admin/books') === 0) ? 'active' : ''; ?>" href="<?php echo base_url('admin/books'); ?>">
                            <i class="fas fa-book me-2"></i>
                            Manage Books
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos(uri_string(), 'admin/borrows') === 0) ? 'active' : ''; ?>" href="<?php echo base_url('admin/borrows'); ?>">
                            <i class="fas fa-exchange-alt me-2"></i>
                            Manage Borrows
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos(uri_string(), 'admin/students') === 0) ? 'active' : ''; ?>" href="<?php echo base_url('admin/students'); ?>">
                            <i class="fas fa-users me-2"></i>
                            Manage Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos(uri_string(), 'admin/statistics') === 0) ? 'active' : ''; ?>" href="<?php echo base_url('admin/statistics'); ?>">
                            <i class="fas fa-chart-bar me-2"></i>
                            Statistics
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo (strpos(uri_string(), 'admin/reports') === 0 || strpos(uri_string(), 'admin/analytics') === 0) ? 'active' : ''; ?>" 
                           href="#" id="analyticsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-chart-line me-2"></i>
                            Analytics & Reports
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo base_url('admin/reports'); ?>">
                                <i class="fas fa-file-alt me-2"></i>Reports
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('admin/analytics'); ?>">
                                <i class="fas fa-chart-bar me-2"></i>Analytics Dashboard
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('admin/system_status'); ?>">
                                <i class="fas fa-server me-2"></i>System Status
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos(uri_string(), 'admin/system_status') === 0) ? 'active' : ''; ?>" href="<?php echo base_url('admin/system_status'); ?>">
                            <i class="fas fa-server me-2"></i>
                            System Status
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="exportDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>
                            Export Data
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo base_url('admin/export_data?type=books'); ?>">
                                <i class="fas fa-book me-2"></i>Export Books
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('admin/export_data?type=users'); ?>">
                                <i class="fas fa-users me-2"></i>Export Users
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('admin/export_data?type=borrows'); ?>">
                                <i class="fas fa-exchange-alt me-2"></i>Export Borrows
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link text-danger" href="<?php echo base_url('auth/logout'); ?>">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-fill main-content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light px-4">
                <div class="container-fluid">
                    <h4 class="mb-0"><?php echo isset($title) ? $title : 'Admin Dashboard'; ?></h4>
                    <div class="d-flex align-items-center">
                        <span class="me-3">
                            <i class="fas fa-clock me-1"></i>
                            <?php echo date('M d, Y - H:i'); ?>
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                <?php echo $user['first_name']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo base_url('auth/logout'); ?>">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid p-4">
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