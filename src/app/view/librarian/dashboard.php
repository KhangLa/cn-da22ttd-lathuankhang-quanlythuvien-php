<?php
$pageTitle = 'Dashboard Thủ thư - Thư viện TVU';
require_once __DIR__ . '/../layouts/librarian_header.php';
?>

<div class="page-header">
    <h1>📊 Dashboard Thủ thư</h1>
    <p>Chào mừng, <strong><?= getCurrentUser()['full_name'] ?></strong>!</p>
</div>

<!-- Thống kê tổng quan -->
<div class="dashboard-stats">
    <div class="stat-card stat-primary">
        <div class="stat-icon">📚</div>
        <div class="stat-info">
            <h3><?= number_format($data['total_books']) ?></h3>
            <p>Tổng số sách</p>
        </div>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-icon">✅</div>
        <div class="stat-info">
            <h3><?= number_format($data['available_books']) ?></h3>
            <p>Sách có sẵn</p>
        </div>
    </div>
    
    <div class="stat-card stat-primary">
        <div class="stat-icon">👥</div>
        <div class="stat-info">
            <h3><?= number_format($data['total_students']) ?></h3>
            <p>Sinh viên</p>
        </div>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-icon">📖</div>
        <div class="stat-info">
            <h3><?= number_format($data['active_borrows']) ?></h3>
            <p>Đang mượn</p>
        </div>
    </div>
    
    <div class="stat-card stat-warning">
        <div class="stat-icon">⚠️</div>
        <div class="stat-info">
            <h3><?= number_format($data['overdue_borrows']) ?></h3>
            <p>Quá hạn</p>
        </div>
    </div>
    
    <div class="stat-card stat-primary">
        <div class="stat-icon">📑</div>
        <div class="stat-info">
            <h3><?= number_format($data['total_categories']) ?></h3>
            <p>Danh mục</p>
        </div>
    </div>
</div>

<!-- Yêu cầu cần xử lý -->
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header alert-warning">
                <h3>⏳ Yêu cầu mượn chờ duyệt</h3>
            </div>
            <div class="card-body">
                <?php
                require_once __DIR__ . '/../../models/Borrow.php';
                $borrowModelDashboard = new Borrow();
                $pendingRequests = $borrowModelDashboard->countPendingRequests();
                ?>
                <div class="text-center py-3">
                    <h2 class="text-warning"><?= number_format($pendingRequests) ?></h2>
                    <p>yêu cầu đang chờ phê duyệt</p>
                    <a href="<?= BASE_URL ?>/librarian/borrowRequests" class="btn btn-warning">Xem và duyệt ngay</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header alert-danger">
                <h3>📋 Báo cáo sách hư hỏng</h3>
            </div>
            <div class="card-body">
                <?php
                require_once __DIR__ . '/../../models/BookReport.php';
                $bookReportModelDashboard = new BookReport();
                $pendingReportsCount = $bookReportModelDashboard->countByStatus('pending');
                ?>
                <div class="text-center py-3">
                    <h2 class="text-danger"><?= number_format($pendingReportsCount) ?></h2>
                    <p>báo cáo mới cần xử lý</p>
                    <?php if ($pendingReportsCount > 0): ?>
                        <a href="<?= BASE_URL ?>/librarian/book-reports?status=pending" class="btn btn-danger">Xử lý ngay</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/librarian/book-reports" class="btn btn-outline-secondary">Xem tất cả</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
        <!-- Phiếu mượn gần đây -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3>Phiếu mượn gần đây</h3>
                    <a href="<?= BASE_URL ?>/librarian/borrows" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mã SV</th>
                                    <th>Sinh viên</th>
                                    <th>Sách</th>
                                    <th>Ngày mượn</th>
                                    <th>Hạn trả</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['recent_borrows'] as $borrow): ?>
                                <tr>
                                    <td><?= $borrow['student_code'] ?></td>
                                    <td><?= $borrow['user_name'] ?></td>
                                    <td><?= $borrow['book_title'] ?></td>
                                    <td><?= formatDate($borrow['borrow_date']) ?></td>
                                    <td><?= formatDate($borrow['due_date']) ?></td>
                                    <td>
                                        <?php if ($borrow['status'] === 'borrowed'): ?>
                                            <span class="badge badge-info">Đang mượn</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Đã trả</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sách quá hạn -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header alert-warning">
                    <h3>⚠️ Sách quá hạn (<?= count($data['overdue_books']) ?>)</h3>
                </div>
                <div class="card-body">
                    <?php if (count($data['overdue_books']) > 0): ?>
                        <div class="overdue-simple-list">
                            <?php 
                            // Group by user_id to avoid duplicates
                            $overdueByUser = [];
                            foreach ($data['overdue_books'] as $overdue) {
                                $userId = $overdue['user_id'];
                                if (!isset($overdueByUser[$userId])) {
                                    $overdueByUser[$userId] = [
                                        'user_id' => $userId,
                                        'user_name' => $overdue['user_name'],
                                        'student_code' => $overdue['student_code'] ?? '',
                                        'count' => 0
                                    ];
                                }
                                $overdueByUser[$userId]['count']++;
                            }
                            
                            // Display up to 10 students
                            $count = 0;
                            foreach (array_slice($overdueByUser, 0, 10) as $student): 
                                $count++;
                            ?>
                            <div class="overdue-simple-item">
                                <div class="student-info-row">
                                    <div class="student-basic">
                                        <span class="student-number"><?= $count ?></span>
                                        <div>
                                            <strong><?= $student['user_name'] ?></strong>
                                            <?php if (!empty($student['student_code'])): ?>
                                                <br><small class="text-muted"><?= $student['student_code'] ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="overdue-badge">
                                        <span class="badge badge-danger"><?= $student['count'] ?> sách</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if (count($overdueByUser) > 10): ?>
                            <div class="text-center mt-3">
                                <small class="text-muted">Còn <?= count($overdueByUser) - 10 ?> sinh viên khác...</small>
                            </div>
                        <?php endif; ?>
                        
                        <div class="text-center mt-3">
                            <a href="<?= BASE_URL ?>/librarian/borrows?status=borrowed" class="btn btn-sm btn-warning">
                                Xem chi tiết
                            </a>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">Không có sách quá hạn 🎉</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sách phổ biến -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3>📊 Sách phổ biến</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['popular_books'])): ?>
                        <ul class="list-unstyled">
                            <?php foreach ($data['popular_books'] as $book): ?>
                            <li class="mb-2 pb-2 border-bottom">
                                <strong><?= $book['title'] ?></strong><br>
                                <small class="text-muted">
                                    <?= $book['author'] ?> - 
                                    <span class="badge badge-info"><?= $book['borrow_count'] ?? 0 ?> lượt mượn</span>
                                </small>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">Chưa có dữ liệu</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Biểu đồ thống kê -->
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3>📈 Biểu đồ thống kê mượn sách theo tháng (<?= date('Y') ?>)</h3>
            </div>
            <div class="card-body">
                <canvas id="borrowChart" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Biểu đồ danh mục -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📚 Phân bố sách theo danh mục</h3>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
        
        <!-- Biểu đồ phiếu phạt/mượn -->
        <div class="card">
            <div class="card-header">
                <h3>💰 Tỷ lệ phiếu phạt</h3>
            </div>
            <div class="card-body">
                <canvas id="fineChart" style="max-height: 250px;"></canvas>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        Phiếu phạt: <strong><?= $data['total_fines'] ?></strong> / 
                        Tổng phiếu: <strong><?= $data['total_borrows_all'] ?></strong>
                        <?php 
                        $fineRate = $data['total_borrows_all'] > 0 ? 
                            round(($data['total_fines'] / $data['total_borrows_all']) * 100, 1) : 0;
                        ?>
                        <br>Tỷ lệ: <strong class="text-danger"><?= $fineRate ?>%</strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <h3>⚡ Truy cập nhanh</h3>
    <div class="action-buttons">
        <a href="<?= BASE_URL ?>/librarian/create-borrow" class="btn btn-primary">
            📝 Tạo phiếu mượn
        </a>
        <a href="<?= BASE_URL ?>/librarian/add-book" class="btn btn-success">
            ➕ Thêm sách mới
        </a>
        <a href="<?= BASE_URL ?>/librarian/books" class="btn btn-info">
            � Quản lý sách
        </a>
        <a href="<?= BASE_URL ?>/librarian/categories" class="btn btn-secondary">
            � Quản lý danh mục
        </a>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Prepare monthly data
const monthlyData = <?= json_encode($data['monthly_stats']) ?>;
const months = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];

// Create array with all 12 months
const chartData = Array(12).fill(0);
monthlyData.forEach(item => {
    chartData[item.month - 1] = parseInt(item.total);
});

// Create chart
const ctx = document.getElementById('borrowChart').getContext('2d');
const borrowChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Số lượt mượn',
            data: chartData,
            backgroundColor: 'rgba(37, 99, 235, 0.7)',
            borderColor: 'rgba(37, 99, 235, 1)',
            borderWidth: 2,
            borderRadius: 5,
            hoverBackgroundColor: 'rgba(37, 99, 235, 0.9)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        return 'Số lượt mượn: ' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 12
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 12
                    }
                },
                grid: {
                    display: false
                }
            }
        }
    }
});

// ===== Biểu đồ danh mục (Doughnut Chart) =====
const categoryData = <?= json_encode($data['top_categories']) ?>;
const categoryLabels = categoryData.map(cat => cat.name);
const categoryValues = categoryData.map(cat => parseInt(cat.book_count));
const categoryColors = [
    'rgba(37, 99, 235, 0.8)',   // Blue
    'rgba(16, 185, 129, 0.8)',  // Green
    'rgba(245, 158, 11, 0.8)',  // Orange
    'rgba(239, 68, 68, 0.8)',   // Red
    'rgba(139, 92, 246, 0.8)',  // Purple
    'rgba(236, 72, 153, 0.8)',  // Pink
    'rgba(14, 165, 233, 0.8)',  // Sky
    'rgba(132, 204, 22, 0.8)'   // Lime
];

const ctxCategory = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(ctxCategory, {
    type: 'doughnut',
    data: {
        labels: categoryLabels,
        datasets: [{
            data: categoryValues,
            backgroundColor: categoryColors,
            borderColor: '#fff',
            borderWidth: 2,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    padding: 10,
                    font: {
                        size: 11
                    },
                    generateLabels: function(chart) {
                        const data = chart.data;
                        if (data.labels.length && data.datasets.length) {
                            return data.labels.map((label, i) => {
                                const value = data.datasets[0].data[i];
                                return {
                                    text: `${label}: ${value}`,
                                    fillStyle: data.datasets[0].backgroundColor[i],
                                    hidden: false,
                                    index: i
                                };
                            });
                        }
                        return [];
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': ' + context.parsed + ' sách (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// ===== Biểu đồ phiếu phạt (Pie Chart) =====
const totalFines = <?= $data['total_fines'] ?>;
const totalBorrows = <?= $data['total_borrows_all'] ?>;
const borrowsWithoutFine = totalBorrows - totalFines;

const ctxFine = document.getElementById('fineChart').getContext('2d');
const fineChart = new Chart(ctxFine, {
    type: 'pie',
    data: {
        labels: ['Có phiếu phạt', 'Không có phạt'],
        datasets: [{
            data: [totalFines, borrowsWithoutFine],
            backgroundColor: [
                'rgba(239, 68, 68, 0.8)',   // Red for fines
                'rgba(34, 197, 94, 0.8)'    // Green for no fines
            ],
            borderColor: '#fff',
            borderWidth: 2,
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    padding: 10,
                    font: {
                        size: 11
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                callbacks: {
                    label: function(context) {
                        const total = totalBorrows;
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': ' + context.parsed + ' phiếu (' + percentage + '%)';
                    }
                }
            }
        }
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/librarian_footer.php'; ?>
