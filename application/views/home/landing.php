<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?php echo $title; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('frontend/assets/logo.png'); ?>">
    <meta name="theme-color" content="#1e3a8a">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo base_url('frontend/css/style.css'); ?>" rel="stylesheet">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:description" content="Revolutionize your library operations with LibraryPro — automate cataloging, streamline borrowing, and provide seamless access to your digital collection with enterprise-grade security.">
    <meta property="og:image" content="<?php echo base_url('frontend/assets/og-image.jpg'); ?>">
</head>
<body>
    <!-- Navigation -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top" aria-label="Primary">
            <div class="container">
                <a class="navbar-brand fw-bold" href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url('frontend/assets/logo.png'); ?>" alt="LibraryPro" width="40" height="40" class="me-2">
                    LibraryPro
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                        <li class="nav-item ms-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="loginMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-sign-in-alt me-1" aria-hidden="true"></i><span class="visually-hidden">Login</span><span aria-hidden="true"> Login</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginMenu">
                                    <li><a class="dropdown-item" href="<?php echo base_url('auth/login?role=student'); ?>"><i class="fas fa-graduation-cap me-2 text-primary" aria-hidden="true"></i>Student Login</a></li>
                                    <li><a class="dropdown-item" href="<?php echo base_url('auth/login?role=admin'); ?>"><i class="fas fa-user-shield me-2 text-success" aria-hidden="true"></i>Admin Login</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item text-muted" onclick="showDemo()" type="button"><i class="fas fa-play me-2" aria-hidden="true"></i>View Demo</button></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main id="main" tabindex="-1">
        <!-- Hero Section -->
        <section id="home" class="hero-section" aria-labelledby="hero-heading">
            <div class="hero-overlay">
                <div class="container">
                    <div class="row justify-content-center text-center">
                        <div class="col-lg-8 col-xl-7">
                            <div class="hero-content">
                                <div class="badge bg-primary bg-opacity-20 text-primary px-3 py-2 mb-4">
                                    <i class="fas fa-star me-1"></i>Trusted by 500+ Institutions
                                </div>
                                <h1 id="hero-heading" class="display-3 fw-bold text-dark mb-4">
                                    Transform Your
                                    <span class="text-gradient">Library Management</span>
                                </h1>
                                <p class="lead text-muted mb-5">
                                    Revolutionize your library operations with LibraryPro — automate cataloging, streamline borrowing,
                                    and provide seamless access to your digital collection with enterprise-grade security.
                                </p>

                                <!-- Real-time Statistics -->
                                <div class="hero-stats row text-center mb-5" aria-hidden="false" aria-live="polite">
                                    <div class="col-md-4 mb-3">
                                        <div class="stat-card">
                                            <h3 class="text-primary fw-bold mb-0 counter" data-count="<?php echo $stats['total_books']; ?>" aria-label="Books managed"><?php echo number_format($stats['total_books']); ?></h3>
                                            <small class="text-muted">Books Managed</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="stat-card">
                                            <h3 class="text-primary fw-bold mb-0 counter" data-count="<?php echo $stats['total_users']; ?>" aria-label="Active users"><?php echo number_format($stats['total_users']); ?></h3>
                                            <small class="text-muted">Active Users</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="stat-card">
                                            <h3 class="text-primary fw-bold mb-0 counter" data-count="<?php echo $stats['system_uptime']; ?>" aria-label="Uptime percentage"><?php echo $stats['system_uptime']; ?></h3>
                                            <small class="text-muted">% Uptime</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="hero-buttons">
                                    <a href="<?php echo base_url('auth/login'); ?>" class="btn btn-primary btn-lg px-5 me-3" role="button">
                                        <i class="fas fa-sign-in-alt me-2" aria-hidden="true"></i>Access System
                                    </a>
                                    <button class="btn btn-outline-primary btn-lg px-5" type="button" onclick="scrollToSection('features')">
                                        <i class="fas fa-play me-2" aria-hidden="true"></i>Learn More
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="py-5" style="background-color: #f8f9fa;" aria-labelledby="features-heading">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-lg-8 mx-auto">
                        <h2 id="features-heading" class="display-5 fw-bold">Powerful Features</h2>
                        <p class="lead text-muted">Everything you need to manage your library efficiently</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="feature-icon bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-book fa-lg"></i>
                                </div>
                                <h5 class="card-title">Smart Cataloging</h5>
                                <p class="card-text text-muted">Automated book cataloging with ISBN lookup, cover images, and metadata extraction.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="feature-icon bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-users fa-lg"></i>
                                </div>
                                <h5 class="card-title">User Management</h5>
                                <p class="card-text text-muted">Comprehensive user management with role-based access control and activity tracking.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="feature-icon bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-chart-bar fa-lg"></i>
                                </div>
                                <h5 class="card-title">Analytics & Reports</h5>
                                <p class="card-text text-muted">Detailed analytics and reports to help you make informed decisions about your library.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- System Status -->
        <section class="py-5" style="background-color: #f8f9fa;" aria-labelledby="status-heading">
            <div class="container">
                <div class="row text-center">
                    <div class="col-lg-12 mb-4">
                        <h2 id="status-heading" class="display-6 fw-bold mb-3 text-dark">System Status</h2>
                        <p class="lead text-muted">Real-time system information and performance metrics</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center border-0 shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-database fa-2x text-primary mb-3"></i>
                                <h5 class="card-title">Database</h5>
                                <p class="card-text text-success fw-bold"><?php echo ucfirst($system_status['database']); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center border-0 shadow-sm">
                            <div class="card-body">
                                <i class="fab fa-php fa-2x text-primary mb-3"></i>
                                <h5 class="card-title">PHP Version</h5>
                                <p class="card-text text-dark fw-bold"><?php echo $system_status['php_version']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center border-0 shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-primary mb-3"></i>
                                <h5 class="card-title">Server Time</h5>
                                <p class="card-text text-dark fw-bold"><?php echo date('H:i:s', strtotime($system_status['server_time'])); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center border-0 shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-memory fa-2x text-primary mb-3"></i>
                                <h5 class="card-title">Memory Usage</h5>
                                <p class="card-text text-dark fw-bold"><?php echo round($system_status['memory_usage'] / 1024 / 1024, 2); ?> MB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>LibraryPro</h5>
                    <p class="text-muted">Advanced Library Management System</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> LibraryPro. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo base_url('frontend/js/main.js'); ?>"></script>
    
    <!-- Real-time Stats Update -->
    <script>
        // Update stats every 30 seconds
        setInterval(function() {
            fetch('<?php echo base_url('home/api_stats'); ?>')
                .then(response => response.json())
                .then(data => {
                    // Update counter elements with new data
                    document.querySelector('[data-count="<?php echo $stats['total_books']; ?>"]').textContent = data.total_books.toLocaleString();
                    document.querySelector('[data-count="<?php echo $stats['total_users']; ?>"]').textContent = data.total_users.toLocaleString();
                    document.querySelector('[data-count="<?php echo $stats['system_uptime']; ?>"]').textContent = data.system_uptime;
                })
                .catch(error => console.log('Stats update failed:', error));
        }, 30000);
    </script>
</body>
</html>
