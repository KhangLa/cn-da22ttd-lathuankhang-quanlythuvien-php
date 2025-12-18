<?php
$pageTitle = 'Báo cáo thống kê - Admin';
require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="page-header">
        <h1>Báo cáo thống kê</h1>
        <form method="GET" class="form-inline">
            <select name="year" class="form-control mr-2">
                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                <option value="<?= $y ?>" <?= $y == $data['year'] ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn btn-primary">Xem</button>
        </form>
    </div>
    
    <!-- Thống kê tổng quan -->
    <div class="dashboard-stats">
        <div class="stat-card stat-primary">
            <div class="stat-icon">📚</div>
            <div class="stat-info">
                <h3><?= number_format($data['stats']['total_books']) ?></h3>
                <p>Tổng số sách</p>
            </div>
        </div>
        <div class="stat-card stat-primary">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3><?= number_format($data['stats']['total_students']) ?></h3>
                <p>Tổng sinh viên</p>
            </div>
        </div>
        <div class="stat-card stat-primary">
            <div class="stat-icon">📖</div>
            <div class="stat-info">
                <h3><?= number_format($data['stats']['total_borrows']) ?></h3>
                <p>Tổng lượt mượn</p>
            </div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <h3><?= number_format($data['stats']['active_borrows']) ?></h3>
                <p>Đang mượn</p>
            </div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-icon">🔄</div>
            <div class="stat-info">
                <h3><?= number_format($data['stats']['returned_borrows']) ?></h3>
                <p>Đã trả</p>
            </div>
        </div>
        <div class="stat-card stat-warning">
            <div class="stat-icon">⚠️</div>
            <div class="stat-info">
                <h3><?= number_format($data['stats']['overdue_count']) ?></h3>
                <p>Quá hạn</p>
            </div>
        </div>
    </div>
    
    <!-- Biểu đồ mượn sách theo tháng -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>📊 Thống kê mượn sách theo tháng (<?= $data['year'] ?>)</h3>
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" height="80"></canvas>
        </div>
    </div>
    
    <div class="row mb-4">
        <!-- Biểu đồ tròn danh mục sách -->
        <div class="col-lg-4">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                    <h3>📚 Thống kê sách theo danh mục</h3>
                </div>
                <div class="card-body" style="padding: 2rem;">
                    <canvas id="categoryChart" height="350"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Biểu đồ line top danh mục -->
        <div class="col-lg-4">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                    <h3>📊 Thống kê danh mục phổ biến</h3>
                </div>
                <div class="card-body" style="padding: 2rem 1rem 2rem 2rem;">
                    <div style="width: 100%; height: 350px;">
                        <canvas id="topCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Biểu đồ người dùng -->
        <div class="col-lg-4">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                    <h3>👥 Thống kê người dùng</h3>
                </div>
                <div class="card-body" style="padding: 2rem;">
                    <canvas id="userChart" height="350"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bảng chi tiết danh mục -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>🏆 Chi tiết danh mục</h3>
        </div>
        <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên danh mục</th>
                                    <th>Số lượng sách</th>
                                    <th>% Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalBooks = array_sum(array_column($data['top_categories'], 'book_count'));
                                foreach ($data['top_categories'] as $index => $cat): 
                                    $percentage = $totalBooks > 0 ? round(($cat['book_count'] / $totalBooks) * 100, 1) : 0;
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= $cat['name'] ?></strong></td>
                                    <td><?= number_format($cat['book_count']) ?> cuốn</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2"><?= $percentage ?>%</span>
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar" style="width: <?= $percentage ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    
    <!-- Sách phổ biến -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Top 10 sách được mượn nhiều nhất</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên sách</th>
                            <th>Tác giả</th>
                            <th>Danh mục</th>
                            <th>Số lượt mượn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['popular_books'] as $index => $book): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $book['title'] ?></td>
                            <td><?= $book['author'] ?></td>
                            <td><?= $book['category_name'] ?? '-' ?></td>
                            <td><strong><?= $book['borrow_count'] ?? 0 ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// ========== Biểu đồ mượn sách theo tháng ==========
const monthlyData = <?= json_encode($data['monthly_stats']) ?>;
const months = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
const borrowCounts = new Array(12).fill(0);

monthlyData.forEach(item => {
    borrowCounts[item.month - 1] = item.total;
});

const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Số lượt mượn',
            data: borrowCounts,
            backgroundColor: 'rgba(102, 126, 234, 0.6)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// ========== Biểu đồ tròn danh mục sách ==========
const categoryData = <?= json_encode($data['top_categories']) ?>;
const categoryLabels = categoryData.map(cat => cat.name);
const categoryCounts = categoryData.map(cat => parseInt(cat.book_count));

// Màu sắc đẹp cho biểu đồ tròn
const colors = [
    'rgba(102, 126, 234, 0.8)',  // Purple
    'rgba(16, 185, 129, 0.8)',   // Green
    'rgba(245, 158, 11, 0.8)',   // Orange
    'rgba(239, 68, 68, 0.8)',    // Red
    'rgba(59, 130, 246, 0.8)',   // Blue
    'rgba(139, 92, 246, 0.8)',   // Violet
    'rgba(236, 72, 153, 0.8)',   // Pink
    'rgba(20, 184, 166, 0.8)',   // Teal
    'rgba(251, 146, 60, 0.8)',   // Amber
    'rgba(168, 85, 247, 0.8)'    // Purple Light
];

const borderColors = colors.map(color => color.replace('0.8', '1'));

const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: categoryLabels,
        datasets: [{
            label: 'Số lượng sách',
            data: categoryCounts,
            backgroundColor: colors,
            borderColor: borderColors,
            borderWidth: 2,
            hoverOffset: 15
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                align: 'start',
                labels: {
                    padding: 10,
                    font: {
                        size: 11
                    },
                    boxWidth: 15,
                    usePointStyle: false,
                    generateLabels: function(chart) {
                        const data = chart.data;
                        if (data.labels.length && data.datasets.length) {
                            const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                            return data.labels.map((label, i) => {
                                const value = data.datasets[0].data[i];
                                const percentage = ((value / total) * 100).toFixed(1);
                                return {
                                    text: `${label}: ${value} (${percentage}%)`,
                                    fillStyle: data.datasets[0].backgroundColor[i],
                                    strokeStyle: data.datasets[0].borderColor[i],
                                    lineWidth: 2,
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
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} cuốn (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// ========== Biểu đồ line top danh mục ==========
const topCategoryCtx = document.getElementById('topCategoryChart').getContext('2d');
new Chart(topCategoryCtx, {
    type: 'line',
    data: {
        labels: categoryLabels,
        datasets: [{
            label: 'Số lượng sách',
            data: categoryCounts,
            backgroundColor: 'rgba(102, 126, 234, 0.2)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: 'rgba(102, 126, 234, 1)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                right: 0,
                left: 0,
                top: 10,
                bottom: 0
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        return `Số lượng: ${context.parsed.y} cuốn`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: Math.max(...categoryCounts) + 0.5,
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 12
                    },
                    padding: 5
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 11
                    },
                    maxRotation: 45,
                    minRotation: 45,
                    autoSkip: false
                },
                grid: {
                    display: false
                }
            }
        }
    }
});

// ========== Biểu đồ người dùng ==========
const userCtx = document.getElementById('userChart').getContext('2d');
const totalUsers = <?= $data['stats']['total_students'] + ($data['stats']['total_librarians'] ?? 0) ?>;
new Chart(userCtx, {
    type: 'doughnut',
    data: {
        labels: ['Sinh viên', 'Thủ thư'],
        datasets: [{
            label: 'Số lượng',
            data: [<?= $data['stats']['total_students'] ?>, <?= $data['stats']['total_librarians'] ?? 0 ?>],
            backgroundColor: [
                'rgba(59, 130, 246, 0.7)',
                'rgba(16, 185, 129, 0.7)'
            ],
            borderColor: [
                'rgba(59, 130, 246, 1)',
                'rgba(16, 185, 129, 1)'
            ],
            borderWidth: 2,
            hoverOffset: 15
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
                    padding: 15,
                    font: {
                        size: 12
                    },
                    generateLabels: function(chart) {
                        const data = chart.data;
                        if (data.labels.length && data.datasets.length) {
                            return data.labels.map((label, i) => {
                                const value = data.datasets[0].data[i];
                                const percentage = ((value / totalUsers) * 100).toFixed(1);
                                return {
                                    text: `${label}: ${value} (${percentage}%)`,
                                    fillStyle: data.datasets[0].backgroundColor[i],
                                    strokeStyle: data.datasets[0].borderColor[i],
                                    lineWidth: 2,
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
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const percentage = ((value / totalUsers) * 100).toFixed(1);
                        return `${label}: ${value} người (${percentage}%)`;
                    }
                }
            }
        }
    }
});

</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
