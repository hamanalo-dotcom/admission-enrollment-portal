@extends('layout.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fa;
    }

    .dashboard-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* KPI Cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .kpi-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-left: 4px solid;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .kpi-card.total-applications {
        border-left-color: #2c5aa0;
    }

    .kpi-card.enrolled {
        border-left-color: #4caf50;
    }

    .kpi-card.pending {
        border-left-color: #ff9800;
    }

    .kpi-card.rejected {
        border-left-color: #f44336;
    }

    .kpi-card h3 {
        font-size: 14px;
        color: #666;
        margin: 0 0 10px 0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-card .value {
        font-size: 32px;
        font-weight: 700;
        color: #2c5aa0;
        margin: 0;
    }

    .kpi-card.enrolled .value {
        color: #4caf50;
    }

    .kpi-card.pending .value {
        color: #ff9800;
    }

    .kpi-card.rejected .value {
        color: #f44336;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .chart-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .chart-card h3 {
        font-size: 18px;
        color: #2c5aa0;
        margin: 0 0 20px 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-card.full-width {
        grid-column: 1 / -1;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    .chart-container.pie-chart {
        height: 350px;
    }

    .chart-container.line-chart {
        height: 350px;
    }

    @media (max-width: 1200px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .dashboard-container {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-container">
    <h1 style="color: #2c5aa0; margin-bottom: 30px; font-size: 28px; font-weight: 700;">
        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
    </h1>

    <!-- KPI Cards -->
    <div class="kpi-grid">
        <div class="kpi-card total-applications">
            <h3><i class="fas fa-file-alt"></i> Total Applications</h3>
            <p class="value">{{ $totalApplications }}</p>
        </div>
        <div class="kpi-card enrolled">
            <h3><i class="fas fa-check-circle"></i> Enrolled</h3>
            <p class="value">{{ $totalEnrolled }}</p>
        </div>
        <div class="kpi-card pending">
            <h3><i class="fas fa-clock"></i> Pending</h3>
            <p class="value">{{ $totalPending }}</p>
        </div>
        <div class="kpi-card rejected">
            <h3><i class="fas fa-times-circle"></i> Rejected</h3>
            <p class="value">{{ $totalRejected }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-grid">
        <!-- Pie Chart: Student Distribution -->
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Student Distribution</h3>
            <div class="chart-container pie-chart">
                <canvas id="studentDistributionChart"></canvas>
            </div>
        </div>

        <!-- Bar Chart: Applicants per Grade -->
        <div class="chart-card">
            <h3><i class="fas fa-chart-bar"></i> Applicants per Grade Level</h3>
            <div class="chart-container">
                <canvas id="applicantsByGradeChart"></canvas>
            </div>
        </div>

        <!-- Line Chart: Daily/Weekly Applicant Count -->
        <div class="chart-card full-width">
            <h3><i class="fas fa-chart-line"></i> Applicant Count Trend (Last 30 Days)</h3>
            <div class="chart-container line-chart">
                <canvas id="applicantTrendChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script>
    // Wait for DOM and Chart.js to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            return;
        }

        // Chart data from PHP
        const studentDistributionData = @json($studentDistribution);
        const applicantsByGradeData = @json($applicantsByGrade);
        const dailyApplicantsData = @json($dailyApplicants);

        // Helper function to check if data has any non-zero values
        function hasData(data) {
            if (Array.isArray(data)) {
                return data.some(val => val > 0);
            }
            if (typeof data === 'object') {
                return Object.values(data).some(val => val > 0);
            }
            return false;
        }

        // Helper function to show "No data" message
        function showNoDataMessage(canvasId, message) {
            const canvas = document.getElementById(canvasId);
            if (canvas) {
                const container = canvas.parentElement;
                container.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #999; font-size: 14px;">
                        <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                        ${message}
                    </div>
                `;
            }
        }

        // Pie Chart: Student Distribution
        const studentDistributionCtx = document.getElementById('studentDistributionChart');
        if (studentDistributionCtx) {
            if (hasData(studentDistributionData)) {
                new Chart(studentDistributionCtx.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: Object.keys(studentDistributionData),
                        datasets: [{
                            data: Object.values(studentDistributionData),
                            backgroundColor: [
                                '#4caf50',
                                '#ff9800',
                                '#f44336'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    font: {
                                        size: 12,
                                        family: 'Poppins'
                                    }
                                }
                            },
                            tooltip: {
                                font: {
                                    family: 'Poppins'
                                }
                            }
                        }
                    }
                });
            } else {
                showNoDataMessage('studentDistributionChart', 'No enrollment data available');
            }
        }

        // Bar Chart: Applicants per Grade
        const applicantsByGradeCtx = document.getElementById('applicantsByGradeChart');
        if (applicantsByGradeCtx) {
            if (hasData(applicantsByGradeData) && Object.keys(applicantsByGradeData).length > 0) {
                new Chart(applicantsByGradeCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: Object.keys(applicantsByGradeData),
                        datasets: [{
                            label: 'Applicants',
                            data: Object.values(applicantsByGradeData),
                            backgroundColor: 'rgba(44, 90, 160, 0.8)',
                            borderColor: 'rgba(44, 90, 160, 1)',
                            borderWidth: 2,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                font: {
                                    family: 'Poppins'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        family: 'Poppins'
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        family: 'Poppins'
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } else {
                showNoDataMessage('applicantsByGradeChart', 'No admission applications by grade level');
            }
        }

        // Line Chart: Daily Applicant Trend
        const applicantTrendCtx = document.getElementById('applicantTrendChart');
        if (applicantTrendCtx) {
            if (hasData(dailyApplicantsData)) {
                new Chart(applicantTrendCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: Object.keys(dailyApplicantsData),
                        datasets: [{
                            label: 'Daily Applicants',
                            data: Object.values(dailyApplicantsData),
                            borderColor: 'rgba(44, 90, 160, 1)',
                            backgroundColor: 'rgba(44, 90, 160, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#2c5aa0',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    font: {
                                        family: 'Poppins',
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                font: {
                                    family: 'Poppins'
                                },
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        family: 'Poppins'
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        family: 'Poppins',
                                        size: 10
                                    },
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } else {
                showNoDataMessage('applicantTrendChart', 'No admission trend data available');
            }
        }
    });
</script>
@endsection
