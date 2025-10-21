<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .demo-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 2rem auto;
            max-width: 800px;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list i {
            color: #667eea;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="demo-container p-5">
            <div class="text-center mb-4">
                <i class="fas fa-rocket fa-3x text-primary mb-3"></i>
                <h1 class="display-4 fw-bold text-primary"><?php echo $message; ?></h1>
                <p class="lead text-muted">Experience the power of modern library management</p>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <h3 class="mb-3"><i class="fas fa-star text-warning me-2"></i>Key Features</h3>
                    <ul class="feature-list">
                        <?php foreach($features as $feature): ?>
                        <li><i class="fas fa-check-circle"></i><?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h3 class="mb-3"><i class="fas fa-info-circle text-info me-2"></i>System Info</h3>
                    <div class="card bg-light">
                        <div class="card-body">
                            <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
                            <p><strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                            <p><strong>Memory Usage:</strong> <?php echo round(memory_get_usage(true) / 1024 / 1024, 2); ?> MB</p>
                            <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="<?php echo base_url('frontend/index.html'); ?>" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-home me-2"></i>Go to Landing Page
                </a>
                <a href="<?php echo base_url('auth/login'); ?>" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Login to System
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
