<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-chart-bar text-info"></i> Library Analytics Dashboard</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Analytics</li>
                </ol>
            </nav>
        </div>
        <div>
            <button class="btn btn-outline-primary" onclick="refreshData()">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button>
        </div>
    </div>

    <!-- Monthly Statistics Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-line-chart"></i> Monthly Library Activity</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyStatsChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Popular Books -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-fire text-danger"></i> Most Popular Books</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($popular_books)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($popular_books as $index => $book): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold"><?php echo htmlspecialchars($book->title); ?></div>
                                        <small class="text-muted">by <?php echo htmlspecialchars($book->author); ?></small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill"><?php echo $book->borrow_count; ?> borrows</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No borrowing data available yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Most Active Users -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users text-success"></i> Most Active Readers</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($active_users)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($active_users as $index => $user): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <?php echo strtoupper(substr($user->first_name, 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($user->username); ?></small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success rounded-pill"><?php echo $user->borrow_count; ?> books</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No user activity data available yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Statistics -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-layer-group text-warning"></i> Books by Category</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($category_stats)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Total Books</th>
                                        <th>Total Copies</th>
                                        <th>Available</th>
                                        <th>Utilization</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($category_stats as $category): ?>
                                        <?php 
                                        $utilization = ($category->total_copies > 0) ? 
                                            (($category->total_copies - $category->available_copies) / $category->total_copies) * 100 : 0;
                                        $utilization_class = $utilization > 75 ? 'text-danger' : ($utilization > 50 ? 'text-warning' : 'text-success');
                                        ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($category->category); ?></strong></td>
                                            <td><?php echo number_format($category->book_count); ?></td>
                                            <td><?php echo number_format($category->total_copies); ?></td>
                                            <td><?php echo number_format($category->available_copies); ?></td>
                                            <td>
                                                <span class="<?php echo $utilization_class; ?>">
                                                    <?php echo number_format($utilization, 1); ?>%
                                                </span>
                                                <div class="progress progress-sm mt-1">
                                                    <div class="progress-bar <?php echo str_replace('text-', 'bg-', $utilization_class); ?>" 
                                                         role="progressbar" 
                                                         style="width: <?php echo $utilization; ?>%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No category data available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Overdue Analysis -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle text-danger"></i> Overdue Analysis</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($overdue_analysis)): ?>
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <h4 class="text-danger"><?php echo $overdue_analysis['total_overdue']; ?></h4>
                                <small class="text-muted">Total Overdue</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-warning"><?php echo $overdue_analysis['avg_days_overdue']; ?></h4>
                                <small class="text-muted">Avg Days Late</small>
                            </div>
                        </div>

                        <?php if(!empty($overdue_analysis['top_overdue_users'])): ?>
                            <h6 class="border-bottom pb-2 mb-3">Top Overdue Users</h6>
                            <div class="list-group list-group-flush">
                                <?php foreach($overdue_analysis['top_overdue_users'] as $user): ?>
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <small><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></small>
                                        <span class="badge bg-danger"><?php echo $user->overdue_count; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                            <p class="text-muted">No overdue books!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
    font-weight: bold;
}

.progress-sm {
    height: 4px;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.list-group-item {
    border-left: none;
    border-right: none;
    border-radius: 0 !important;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Statistics Chart
<?php if(!empty($monthly_stats)): ?>
const monthlyData = {
    labels: [
        <?php foreach($monthly_stats as $stat): ?>
            '<?php echo date('M Y', strtotime($stat['month'] . '-01')); ?>',
        <?php endforeach; ?>
    ],
    datasets: [{
        label: 'Books Borrowed',
        data: [
            <?php foreach($monthly_stats as $stat): ?>
                <?php echo $stat['borrows']; ?>,
            <?php endforeach; ?>
        ],
        borderColor: 'rgb(75, 192, 192)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        tension: 0.1
    }, {
        label: 'New Users',
        data: [
            <?php foreach($monthly_stats as $stat): ?>
                <?php echo $stat['new_users']; ?>,
            <?php endforeach; ?>
        ],
        borderColor: 'rgb(255, 99, 132)',
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        tension: 0.1
    }]
};

const config = {
    type: 'line',
    data: monthlyData,
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Monthly Library Activity Trends'
            },
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    },
};

// Render the chart
const ctx = document.getElementById('monthlyStatsChart').getContext('2d');
const monthlyStatsChart = new Chart(ctx, config);
<?php endif; ?>

// Refresh data function
function refreshData() {
    location.reload();
}

// Auto-refresh every 5 minutes
setInterval(refreshData, 300000);
</script>