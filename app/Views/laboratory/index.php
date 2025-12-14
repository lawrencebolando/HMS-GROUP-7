<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="bg-blue-600 text-white p-4 rounded-lg mb-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Laboratory Dashboard</h1>
                <span class="text-sm font-medium">Admin</span>
            </div>
        </div>
        
        <div class="bg-blue-50 p-4 rounded-lg mb-4">
            <div class="flex items-center space-x-2 mb-2">
                <i class="fas fa-check-circle text-green-600"></i>
                <h2 class="text-xl font-semibold text-gray-800">Laboratory Dashboard</h2>
            </div>
            <p class="text-gray-600">Monitor lab performance and critical alerts across all branches • Date: <?= date('F d, Y') ?></p>
        </div>

        <?php if (!$lab_requests_exists || !$lab_results_exists): ?>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Laboratory data is not ready yet. Please ensure the latest migrations are run and sample data is available.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Pending Test Requests -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Test Requests</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['pending_requests'] ?? 0) ?></p>
                    <p class="text-sm text-orange-600 mt-1">Awaiting action</p>
                </div>
                <i class="fas fa-clipboard-list text-orange-500 text-3xl"></i>
            </div>
        </div>

        <!-- Completed Tests Today -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Completed Tests Today</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['completed_today'] ?? 0) ?></p>
                    <p class="text-sm text-green-600 mt-1">Today</p>
                </div>
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
        </div>

        <!-- Critical Results -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Critical Results</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['critical_results'] ?? 0) ?></p>
                    <p class="text-sm text-red-600 mt-1">Requires attention</p>
                </div>
                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
            </div>
        </div>

        <!-- Active Lab Staff -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Lab Staff</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['active_lab_staff'] ?? 0) ?></p>
                    <p class="text-sm text-green-600 mt-1">On duty</p>
                </div>
                <i class="fas fa-user-md text-blue-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Recent Test Requests Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Recent Test Requests</h2>
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View all</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Requesting Doctor</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Test Type</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Priority</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date Requested</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Branch</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($test_requests)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No recent requests.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($test_requests as $request): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4"><?= esc($request['patient_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4"><?= esc($request['doctor_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4"><?= esc($request['test_type'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $priority = $request['priority'] ?? 'normal';
                                    $priorityClass = $priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                                    ($priority === 'emergency' ? 'bg-purple-100 text-purple-800' : 
                                                    'bg-blue-100 text-blue-800');
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $priorityClass ?>">
                                        <?= esc(ucfirst($priority)) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $status = $request['status'] ?? 'pending';
                                    $statusClass = $status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                   'bg-gray-100 text-gray-800'));
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusClass ?>">
                                        <?= esc(ucfirst(str_replace('_', ' ', $status))) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <?= isset($request['requested_date']) ? date('M d, Y', strtotime($request['requested_date'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4"><?= esc($request['branch'] ?? 'Main Branch') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Test Results Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Recent Test Results</h2>
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View all</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Test Type</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Result Summary</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Released By</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Released</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Critical</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($test_results)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No recent results.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($test_results as $result): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4"><?= esc($result['patient_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4"><?= esc($result['test_type'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4">
                                    <span class="text-gray-700"><?= esc(substr($result['result_summary'] ?? 'N/A', 0, 50)) ?><?= strlen($result['result_summary'] ?? '') > 50 ? '...' : '' ?></span>
                                </td>
                                <td class="py-3 px-4"><?= esc($result['released_by_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4">
                                    <?php 
                                    if (isset($result['released_date'])) {
                                        $date = date('M d, Y', strtotime($result['released_date']));
                                        if (isset($result['released_time'])) {
                                            $date .= ' ' . date('h:i A', strtotime($result['released_time']));
                                        }
                                        echo $date;
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $status = $result['status'] ?? 'pending';
                                    $statusClass = $status === 'released' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-gray-100 text-gray-800');
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusClass ?>">
                                        <?= esc(ucfirst($status)) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if (isset($result['is_critical']) && $result['is_critical']): ?>
                                        <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle"></i> Critical
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

