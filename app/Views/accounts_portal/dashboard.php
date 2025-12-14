<?= $this->extend('templates/accounts_portal_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <span class="text-gray-600">Accountant</span>
        </div>
    </div>

    <!-- Welcome Banner -->
    <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <div class="flex items-center space-x-2 mb-2">
            <i class="fas fa-flag text-blue-600"></i>
            <h2 class="text-xl font-semibold text-gray-800">Accounts Dashboard</h2>
        </div>
        <p class="text-gray-600">Welcome back, Accountant. Here's your financial overview for today. • Date: <?= date('F d, Y') ?></p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Today's Revenue -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Today's Revenue</p>
                    <p class="text-3xl font-bold text-gray-800">₱<?= number_format($stats['today_revenue'] ?? 0, 2) ?></p>
                    <p class="text-sm text-green-600 mt-1">Today</p>
                </div>
                <i class="fas fa-dollar-sign text-green-500 text-3xl"></i>
            </div>
        </div>

        <!-- Pending Bills -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Bills</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['pending_bills'] ?? 0) ?></p>
                    <p class="text-sm text-red-600 mt-1">Requires attention</p>
                </div>
                <i class="fas fa-file-invoice text-red-500 text-3xl"></i>
            </div>
        </div>

        <!-- Insurance Claims -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Insurance Claims</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['insurance_claims'] ?? 0) ?></p>
                    <p class="text-sm text-green-600 mt-1">All claims</p>
                </div>
                <i class="fas fa-shield-alt text-blue-500 text-3xl"></i>
            </div>
        </div>

        <!-- Overdue Payments -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Overdue Payments</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['overdue_payments'] ?? 0) ?></p>
                    <p class="text-sm text-red-600 mt-1">Requires attention</p>
                </div>
                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Pending Bills Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Pending Bills</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Bill ID</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Service</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Amount</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Due Date</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pending_bills)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No pending bills
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pending_bills as $bill): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?= esc($bill['invoice_id'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($bill['patient_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4"><?= esc($bill['invoice_type'] ?? 'Service') ?></td>
                                <td class="py-3 px-4 font-semibold">₱<?= number_format($bill['amount'] ?? 0, 2) ?></td>
                                <td class="py-3 px-4">
                                    <?= isset($bill['due_date']) ? date('M d, Y', strtotime($bill['due_date'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
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

    <!-- Recent Payments Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Payments</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Payment ID</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Amount</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Method</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_payments)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No recent payments
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent_payments as $payment): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?= esc($payment['invoice_id'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($payment['patient_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4 font-semibold">₱<?= number_format($payment['amount'] ?? 0, 2) ?></td>
                                <td class="py-3 px-4"><?= esc(ucfirst($payment['payment_method'] ?? 'N/A')) ?></td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <?= isset($payment['payment_date']) ? date('M d, Y', strtotime($payment['payment_date'])) : 'N/A' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Insurance Claims Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Insurance Claims</h2>
        <div class="text-center py-8 text-gray-500">
            <?php if (empty($insurance_claims)): ?>
                No insurance claims available.
            <?php else: ?>
                <!-- Insurance claims list would go here -->
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

