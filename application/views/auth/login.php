<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Library System'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Project stylesheet so logo and utilities render correctly -->
    <link href="<?php echo base_url('frontend/css/style.css'); ?>" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .brand-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
        }
        .form-control {
            border-radius: 15px;
            padding: 15px 20px;
            border: 1px solid #e0e0e0;
        }
        .btn-login {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 15px 50px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card p-5">
                    <!-- Brand Logo -->
                    <div class="brand-logo">
                        <img src="<?php echo base_url('frontend/assets/logo.png'); ?>" alt="LibraryPro logo" class="site-logo-lg" style="max-width:80px;height:auto;">
                    </div>
                    
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-dark mb-2">Library Management System</h2>
                        <p class="text-muted">Please sign in to your account</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo base_url('auth/login'); ?>">
                        <div class="mb-4">
                            <label for="username" class="form-label fw-semibold">
                                <i class="fas fa-user me-2"></i>Username or Email
                            </label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo set_value('username'); ?>" required>
                            <?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mb-2">
                        <p class="text-muted mb-2 d-flex justify-content-center gap-3">
                            <a href="<?php echo base_url('auth/forgot_password'); ?>" class="text-decoration-none">
                                <i class="fas fa-key me-1"></i>Forgot Password?
                            </a>
                            <!-- Home button linking to the public frontend home page -->
                            <a href="<?php echo base_url('frontend/index.html'); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-home me-1"></i>Home
                            </a>
                        </p>
                    </div>

                    <hr class="my-4">
                    
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Demo Accounts:</strong><br>
                            Administrator: <code>admin</code> / <code>admin123</code><br>
                            Student: <code>student</code> / <code>student123</code>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>