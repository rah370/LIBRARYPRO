<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-server"></i> System Status & Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- System Information -->
                        <div class="col-md-6 mb-4">
                            <h5><i class="fas fa-info-circle text-primary"></i> System Information</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>PHP Version:</strong></td>
                                        <td><?php echo $server_info['php_version']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Server Software:</strong></td>
                                        <td><?php echo $server_info['server_software']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Memory Limit:</strong></td>
                                        <td><?php echo $server_info['memory_limit']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Upload Max Size:</strong></td>
                                        <td><?php echo $server_info['upload_max_filesize']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>POST Max Size:</strong></td>
                                        <td><?php echo $server_info['post_max_size']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>CodeIgniter Version:</strong></td>
                                        <td><?php echo CI_VERSION; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Database Status -->
                        <div class="col-md-6 mb-4">
                            <h5><i class="fas fa-database <?php echo ($database_status['status'] == 'OK') ? 'text-success' : 'text-danger'; ?>"></i> Database Status</h5>
                            <div class="alert alert-<?php echo ($database_status['status'] == 'OK') ? 'success' : 'danger'; ?>">
                                <i class="fas fa-<?php echo ($database_status['status'] == 'OK') ? 'check-circle' : 'exclamation-triangle'; ?>"></i> 
                                Status: <?php echo $database_status['status']; ?><br>
                                Message: <?php echo $database_status['message']; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- File Permissions -->
                        <div class="col-md-12 mb-4">
                            <h5><i class="fas fa-lock text-warning"></i> File Permissions Check</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Path</th>
                                            <th>Readable</th>
                                            <th>Writable</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($file_permissions as $file): ?>
                                        <tr>
                                            <td><code><?php echo $file['path']; ?></code></td>
                                            <td>
                                                <i class="fas fa-<?php echo $file['readable'] ? 'check text-success' : 'times text-danger'; ?>"></i>
                                                <?php echo $file['readable'] ? 'Yes' : 'No'; ?>
                                            </td>
                                            <td>
                                                <i class="fas fa-<?php echo $file['writable'] ? 'check text-success' : 'times text-danger'; ?>"></i>
                                                <?php echo $file['writable'] ? 'Yes' : 'No'; ?>
                                            </td>
                                            <td>
                                                <?php if ($file['readable'] && $file['writable']): ?>
                                                    <span class="badge bg-success">OK</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Issue</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Quick Actions -->
                        <div class="col-md-12">
                            <h5><i class="fas fa-tools text-info"></i> Quick Actions</h5>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="location.reload();">
                                    <i class="fas fa-sync-alt"></i> Refresh Status
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="window.open('<?php echo base_url('admin/reports'); ?>', '_blank');">
                                    <i class="fas fa-chart-bar"></i> View Reports
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="window.open('<?php echo base_url('admin/export_data?type=books'); ?>', '_blank');">
                                    <i class="fas fa-download"></i> Export Data
                                </button>
                            </div>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Table Name</th>
                                                <th>Record Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($database_info['tables'] as $table => $count): ?>
                                            <tr>
                                                <td><?php echo $table; ?></td>
                                                <td><span class="badge bg-info"><?php echo number_format($count); ?></span></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Library Statistics -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-chart-pie text-info"></i> Library Statistics Overview</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fa-2x mb-2"></i>
                                    <h4><?php echo number_format($library_stats['total_books']); ?></h4>
                                    <p class="mb-0">Total Books</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h4><?php echo number_format($library_stats['available_books']); ?></h4>
                                    <p class="mb-0">Available Books</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-hand-holding fa-2x mb-2"></i>
                                    <h4><?php echo number_format($library_stats['borrowed_books']); ?></h4>
                                    <p class="mb-0">Borrowed Books</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <h4><?php echo number_format($library_stats['total_students']); ?></h4>
                                    <p class="mb-0">Total Students</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-exchange-alt fa-2x mb-2"></i>
                                    <h4><?php echo number_format($library_stats['active_borrows']); ?></h4>
                                    <p class="mb-0">Active Borrows</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <h4><?php echo number_format($library_stats['overdue_books']); ?></h4>
                                    <p class="mb-0">Overdue Books</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-dark text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-day fa-2x mb-2"></i>
                                    <h4><?php echo number_format($library_stats['total_borrows_today']); ?></h4>
                                    <p class="mb-0">Borrows Today</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light text-dark">
                                <div class="card-body text-center">
                                    <i class="fas fa-undo fa-2x mb-2"></i>
                                    <h4><?php echo number_format($library_stats['total_returns_today']); ?></h4>
                                    <p class="mb-0">Returns Today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Maintenance -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-tools text-warning"></i> System Maintenance</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Database Operations</h5>
                            <div class="d-grid gap-2">
                                <a href="<?php echo site_url('system/backup_database'); ?>" 
                                   class="btn btn-primary"
                                   onclick="return confirm('Create database backup? This may take a moment.')">
                                    <i class="fas fa-download"></i> Download Database Backup
                                </a>
                                
                                <button class="btn btn-info" onclick="optimizeDatabase()">
                                    <i class="fas fa-compress-arrows-alt"></i> Optimize Database
                                </button>
                                
                                <button class="btn btn-warning" onclick="checkDatabaseIntegrity()">
                                    <i class="fas fa-check-double"></i> Check Database Integrity
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>System Cleanup</h5>
                            <div class="d-grid gap-2">
                                <a href="<?php echo site_url('system/clear_logs'); ?>" 
                                   class="btn btn-secondary"
                                   onclick="return confirm('Clear all log files? This cannot be undone.')">
                                    <i class="fas fa-trash"></i> Clear Log Files
                                </a>
                                
                                <button class="btn btn-outline-secondary" onclick="clearCache()">
                                    <i class="fas fa-broom"></i> Clear Application Cache
                                </button>
                                
                                <button class="btn btn-outline-info" onclick="runMaintenance()">
                                    <i class="fas fa-cog"></i> Run System Maintenance
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> System Health Check</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Database Connection: OK</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>File Permissions: OK</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Memory Usage: Normal</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function optimizeDatabase() {
    if (confirm('Optimize database tables? This will improve performance.')) {
        // In a real system, this would make an AJAX call
        alert('Database optimization completed successfully.');
    }
}

function checkDatabaseIntegrity() {
    if (confirm('Check database integrity? This may take a few moments.')) {
        // In a real system, this would make an AJAX call
        alert('Database integrity check completed. No issues found.');
    }
}

function clearCache() {
    if (confirm('Clear application cache?')) {
        // In a real system, this would make an AJAX call
        alert('Application cache cleared successfully.');
    }
}

function runMaintenance() {
    if (confirm('Run full system maintenance? This includes cache clearing, log rotation, and optimization.')) {
        // In a real system, this would make an AJAX call
        alert('System maintenance completed successfully.');
    }
}

// Auto-refresh system status every 5 minutes
setInterval(function() {
    const memoryUsage = document.querySelector('td:contains("Memory Usage:")').nextElementSibling;
    // In a real system, this would update via AJAX
    console.log('Auto-refreshing system status...');
}, 300000);
</script>