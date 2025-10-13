<?php // Student profile view - allows viewing/updating basic profile info ?>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-circle me-2"></i>My Profile</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="post" action="<?php echo base_url('student/profile'); ?>">
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" required
                               value="<?php echo isset($user->first_name) ? htmlspecialchars($user->first_name) : (isset($user['first_name']) ? htmlspecialchars($user['first_name']) : ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required
                               value="<?php echo isset($user->last_name) ? htmlspecialchars($user->last_name) : (isset($user['last_name']) ? htmlspecialchars($user['last_name']) : ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required
                               value="<?php echo isset($user->email) ? htmlspecialchars($user->email) : (isset($user['email']) ? htmlspecialchars($user['email']) : ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
