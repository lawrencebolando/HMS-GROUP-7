<?= $this->extend('templates/lab_portal_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <span class="text-gray-600">Lab</span>
        </div>
    </div>

    <!-- Laboratory Dashboard Section -->
    <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <div class="flex items-center space-x-2 mb-2">
            <i class="fas fa-check-circle text-green-600"></i>
            <h2 class="text-xl font-semibold text-gray-800">Laboratory Dashboard</h2>
        </div>
        <p class="text-gray-600">Laboratory Staff (Manage test requests, enter results) • Date: <?= date('F d, Y') ?></p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Pending Tests -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Tests</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['pending_tests'] ?? 0) ?></p>
                    <p class="text-sm text-orange-600 mt-1">Requires attention</p>
                </div>
                <i class="fas fa-clipboard-list text-orange-500 text-3xl"></i>
            </div>
        </div>

        <!-- Completed Today -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Completed Today</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['completed_today'] ?? 0) ?></p>
                    <p class="text-sm text-green-600 mt-1">Today</p>
                </div>
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
        </div>

        <!-- Urgent Tests -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Urgent Tests</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['urgent_tests'] ?? 0) ?></p>
                    <p class="text-sm text-red-600 mt-1">High priority</p>
                </div>
                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
            </div>
        </div>

        <!-- Critical Tests -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Critical Tests</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['critical_tests'] ?? 0) ?></p>
                    <p class="text-sm text-red-600 mt-1">Urgent</p>
                </div>
                <i class="fas fa-exclamation-circle text-red-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Pending Test Requests -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Pending Test Requests</h2>
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">REQUEST ID</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">PATIENT</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">TEST TYPE</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">DOCTOR</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">PRIORITY</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">REQUESTED</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pending_requests)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No pending test requests.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pending_requests as $request): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?= esc($request['lab_request_id'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($request['patient_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4"><?= esc($request['test_type'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($request['doctor_name'] ?? 'Unknown') ?></td>
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
                                    <?= isset($request['requested_date']) ? date('M d, Y', strtotime($request['requested_date'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Test Results -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Recent Test Results</h2>
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">RESULT ID</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">PATIENT</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">TEST TYPE</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">RESULT SUMMARY</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">STATUS</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">COMPLETED</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_results)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No recent test results.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent_results as $result): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?= esc($result['lab_result_id'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($result['patient_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4"><?= esc($result['test_type'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4">
                                    <span class="text-gray-700"><?= esc(substr($result['result_summary'] ?? 'N/A', 0, 50)) ?><?= strlen($result['result_summary'] ?? '') > 50 ? '...' : '' ?></span>
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
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

