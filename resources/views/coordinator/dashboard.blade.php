@extends('layouts.coordinator')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="dashboard-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Students</p>
                    <p class="stat-value">{{ $totalStudents }}</p>
                </div>
                <div class="text-blue-500 bg-blue-100 rounded-full p-3">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>
            </div>
        </div>
        <div class="dashboard-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Lecturers</p>
                    <p class="stat-value">{{ $totalLecturers }}</p>
                </div>
                <div class="text-green-500 bg-green-100 rounded-full p-3">
                    <i class="fas fa-chalkboard-teacher text-xl"></i>
                </div>
            </div>
        </div>
        <div class="dashboard-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Research Groups</p>
                    <p class="stat-value">{{ count($researchGroups) }}</p>
                </div>
                <div class="text-purple-500 bg-purple-100 rounded-full p-3">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Program Distribution -->
        <div class="dashboard-card p-6">
            <h3 class="text-lg font-semibold mb-4">Student Distribution by Program</h3>
            <div class="chart-container" style="height: 300px;">
                <canvas id="programChart"></canvas>
            </div>
        </div>

        <!-- Research Groups Section -->
        <div class="dashboard-card p-6">
            <h3 class="text-lg font-semibold mb-4">Research Groups</h3>
            <div class="research-groups-list" style="height: 400px; overflow-y: auto; overflow-x: hidden;">
                @foreach($researchGroups as $group)
                <div class="group-item bg-white rounded-lg p-4 mb-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0">
                            <i class="fas fa-users text-[{{ $group['color'] }}] text-2xl mr-4 flex-shrink-0"></i>
                            <div class="truncate">
                                <h4 class="text-lg font-bold">{{ $group['name'] }}</h4>
                                <p class="text-gray-600">{{ $group['description'] }}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-4">{{ $group['members'] }} Members</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Add Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Initialize Charts -->
<script>
    // Program Distribution Chart
    const programCtx = document.getElementById('programChart').getContext('2d');
    new Chart(programCtx, {
        type: 'pie',
        data: {
            labels: [
                'Software Engineering (CB)',
                'Computer System & Networking (CA)',
                'Computer Graphics & Multimedia (CD)',
                'Cybersecurity (CF)'
            ],
            datasets: [{
                data: [
                    {{ $studentDistribution['Software Engineering'] }},
                    {{ $studentDistribution['Computer System & Networking'] }},
                    {{ $studentDistribution['Computer Graphics & Multimedia'] }},
                    {{ $studentDistribution['Cybersecurity'] }}
                ],
                backgroundColor: [
                    '#2193b0',
                    '#6dd5ed',
                    '#4CAF50',
                    '#FF9800'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

<!-- Add these styles -->
<style>
    .dashboard-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .stat-value {
        font-size: 1.875rem;
        font-weight: 600;
        color: #1a202c;
    }

    .chart-container {
        position: relative;
        margin: auto;
    }

    .research-groups-list {
        scrollbar-width: thin;
        scrollbar-color: #2193b0 #f0f0f0;
    }

    .research-groups-list::-webkit-scrollbar {
        width: 4px;
    }

    .research-groups-list::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 2px;
    }

    .research-groups-list::-webkit-scrollbar-thumb {
        background-color: #2193b0;
        border-radius: 2px;
    }

    .group-item {
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .group-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Keep your existing styles -->
@endsection 