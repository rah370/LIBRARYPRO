<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-hand-holding me-2"></i>Borrow Book
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <i class="fas fa-book fa-4x text-primary mb-3"></i>
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-primary"><?php echo htmlspecialchars($book->title); ?></h4>
                            <h6 class="text-muted">by <?php echo htmlspecialchars($book->author); ?></h6>
                            <p class="text-muted mb-2">
                                <i class="fas fa-barcode me-1"></i>ISBN: <?php echo htmlspecialchars($book->isbn); ?>
                            </p>
                            <?php if(isset($book->description)): ?>
                                <p class="text-muted"><?php echo htmlspecialchars($book->description); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr>

                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Full Name *
                                </label>
                                <input type="text" class="form-control <?php echo (form_error('user_name')) ? 'is-invalid' : ''; ?>" 
                                       id="user_name" name="user_name" value="<?php echo set_value('user_name'); ?>" required>
                                <?php if(form_error('user_name')): ?>
                                    <div class="invalid-feedback"><?php echo form_error('user_name'); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="user_email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email Address *
                                </label>
                                <input type="email" class="form-control <?php echo (form_error('user_email')) ? 'is-invalid' : ''; ?>" 
                                       id="user_email" name="user_email" value="<?php echo set_value('user_email'); ?>" required>
                                <?php if(form_error('user_email')): ?>
                                    <div class="invalid-feedback"><?php echo form_error('user_email'); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Borrowing Terms</h6>
                            <ul class="mb-0">
                                <li>Books can be borrowed for up to <strong>14 days</strong></li>
                                <li>Please return books on time to avoid late fees</li>
                                <li>You will receive a confirmation email with return details</li>
                                <li>Maximum 3 books can be borrowed at a time</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo base_url('library'); ?>" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-hand-holding me-1"></i>Confirm Borrow
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
