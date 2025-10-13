<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-users text-primary"></i> User Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo base_url('admin/add_user'); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Users Table Card -->
    <div class="card">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0"><i class="fas fa-list"></i> All Users</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" id="userSearch" placeholder="Search users...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th><i class="fas fa-id-card"></i> ID</th>
                            <th><i class="fas fa-user"></i> Name</th>
                            <th><i class="fas fa-at"></i> Email</th>
                            <th><i class="fas fa-tag"></i> Username</th>
                            <th><i class="fas fa-shield-alt"></i> Role</th>
                            <th><i class="fas fa-circle"></i> Status</th>
                            <th><i class="fas fa-clock"></i> Created</th>
                            <th><i class="fas fa-clock"></i> Last Login</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($users) && !empty($users)): ?>
                            <?php foreach($users as $user_item): ?>
                                <tr>
                                    <td><span class="badge bg-secondary">#<?php echo $user_item->id; ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <?php echo strtoupper(substr($user_item->first_name, 0, 1)); ?>
                                            </div>
                                            <div>
                                                <strong><?php echo htmlspecialchars($user_item->first_name . ' ' . $user_item->last_name); ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($user_item->email); ?></td>
                                    <td><code><?php echo htmlspecialchars($user_item->username); ?></code></td>
                                    <td>
                                        <?php 
                                        $role_colors = [
                                            'admin' => 'bg-danger',
                                            'librarian' => 'bg-warning',
                                            'student' => 'bg-info'
                                        ];
                                        $role_color = $role_colors[$user_item->role] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?php echo $role_color; ?>">
                                            <?php echo ucfirst($user_item->role); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $status_colors = [
                                            'active' => 'bg-success',
                                            'inactive' => 'bg-warning',
                                            'suspended' => 'bg-danger'
                                        ];
                                        $status_color = $status_colors[$user_item->status] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?php echo $status_color; ?>">
                                            <?php echo ucfirst($user_item->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('M j, Y', strtotime($user_item->created_at)); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php 
                                            if($user_item->last_login) {
                                                echo date('M j, Y H:i', strtotime($user_item->last_login));
                                            } else {
                                                echo 'Never';
                                            }
                                            ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('admin/edit_user/' . $user_item->id); ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if($user_item->id != $this->session->userdata('user_id')): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmDelete(<?php echo $user_item->id; ?>, '<?php echo htmlspecialchars($user_item->first_name . ' ' . $user_item->last_name); ?>')"
                                                        title="Delete User">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No users found.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if(isset($pagination) && !empty($pagination)): ?>
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    <?php echo $pagination; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the user <strong id="userName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i> This action cannot be undone. The user will be permanently removed from the system.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete User
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: bold;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75em;
}
</style>

<script>
// User search functionality
document.getElementById('userSearch').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Delete confirmation
function confirmDelete(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteBtn').href = '<?php echo base_url('admin/delete_user/'); ?>' + userId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Auto-hide alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>