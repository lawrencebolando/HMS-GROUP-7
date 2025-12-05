<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Pharmacy Management</h1>
            <p class="text-gray-600 mt-1">Manage medication inventory, prescriptions, and pharmacy operations.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="btn-secondary flex items-center">
                <i class="fas fa-file-export mr-2"></i> Export Report
            </button>
            <button class="btn-primary flex items-center">
                <i class="fas fa-plus mr-2"></i> Add Medication
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500">Total Medications</span>
                <span class="text-blue-500"><i class="fas fa-cubes text-lg"></i></span>
            </div>
            <div class="text-3xl font-bold text-gray-900"><?= esc($summary['total_medications']) ?></div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500">Low Stock Items</span>
                <span class="text-red-500"><i class="fas fa-arrow-trend-down text-lg"></i></span>
            </div>
            <div class="text-3xl font-bold text-orange-500"><?= esc($summary['low_stock_items']) ?></div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500">Pending Prescriptions</span>
                <span class="text-yellow-500"><i class="fas fa-clock text-lg"></i></span>
            </div>
            <div class="text-3xl font-bold text-amber-500"><?= esc($summary['pending_prescriptions']) ?></div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500">Total Dispensed</span>
                <span class="text-green-500"><i class="fas fa-check-circle text-lg"></i></span>
            </div>
            <div class="text-3xl font-bold text-emerald-500"><?= esc($summary['total_dispensed']) ?></div>
        </div>
    </div>

    <!-- Tabs and Filters -->
    <div class="bg-white rounded-xl shadow border border-gray-100">
        <div class="border-b border-gray-100 px-6 pt-4 pb-3 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <button class="px-4 py-2 rounded-full text-sm font-medium text-white bg-blue-600">
                    Inventory
                </button>
                <button class="px-4 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-100">
                    Prescriptions
                </button>
                <button class="px-4 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-100">
                    Procurement
                </button>
                <button class="px-4 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-100">
                    Reports
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Search -->
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        type="text"
                        placeholder="Search medications..."
                        class="pl-9 pr-3 py-2 w-60 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Category Filter -->
                <select class="border border-gray-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option>All Categories</option>
                    <option>Antibiotic</option>
                    <option>Analgesic</option>
                    <option>Antipyretic</option>
                </select>

                <!-- Stock Status Filter -->
                <select class="border border-gray-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option>All Stock Status</option>
                    <option>In Stock</option>
                    <option>Low Stock</option>
                    <option>Out of Stock</option>
                </select>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="px-6 py-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="pb-3 pr-4">Medication</th>
                        <th class="pb-3 pr-4">Category</th>
                        <th class="pb-3 pr-4 w-48">Stock Level</th>
                        <th class="pb-3 pr-4">Unit Price</th>
                        <th class="pb-3 pr-4">Expiry Date</th>
                        <th class="pb-3 pr-4">Status</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($medications as $med): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 pr-4">
                                <div class="font-medium text-gray-900"><?= esc($med['name']) ?></div>
                                <div class="text-xs text-gray-500"><?= esc($med['generic']) ?></div>
                            </td>
                            <td class="py-3 pr-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                    <?= esc($med['category']) ?>
                                </span>
                            </td>
                            <td class="py-3 pr-4">
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                    <span><?= esc($med['stock_level']) ?> units</span>
                                    <span>Min: <?= esc($med['min_stock']) ?></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <?php
                                        $ratio = $med['min_stock'] > 0 ? min(100, intval(($med['stock_level'] / $med['min_stock']) * 100)) : 0;
                                        $barClass = 'bg-blue-500';
                                        if ($med['status'] === 'low_stock') {
                                            $barClass = 'bg-yellow-500';
                                        } elseif ($med['status'] === 'out_of_stock') {
                                            $barClass = 'bg-red-500';
                                        }
                                    ?>
                                    <div class="<?= $barClass ?> h-2 rounded-full" style="width: <?= $ratio ?>%"></div>
                                </div>
                            </td>
                            <td class="py-3 pr-4">
                                <span class="font-medium text-gray-900">$<?= number_format($med['unit_price'], 2) ?></span>
                            </td>
                            <td class="py-3 pr-4">
                                <div class="flex items-center text-gray-700 text-sm">
                                    <i class="far fa-calendar mr-2 text-gray-400"></i>
                                    <?= esc(date('m/d/Y', strtotime($med['expiry_date']))) ?>
                                </div>
                            </td>
                            <td class="py-3 pr-4">
                                <?php if ($med['status'] === 'in_stock'): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">
                                        In Stock
                                    </span>
                                <?php elseif ($med['status'] === 'low_stock'): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-medium">
                                        Low Stock
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-medium">
                                        Out of Stock
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-right">
                                <button class="text-blue-600 hover:text-blue-800 text-xs font-medium mr-3">Edit</button>
                                <button class="text-gray-500 hover:text-gray-700 text-xs font-medium">View</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Placeholder sections for usage analytics, procurement, and alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Usage & Top Consumed -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Medicine Usage & Consumption</h2>
                <select class="border border-gray-200 rounded-lg text-sm px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>This Year</option>
                </select>
            </div>
            <p class="text-sm text-gray-500 mb-2">
                This section will show daily, weekly, monthly, and yearly usage trends with charts and top consumed medicines.
            </p>
            <div class="h-40 flex items-center justify-center text-gray-400 text-sm border border-dashed border-gray-200 rounded-lg">
                Analytics chart placeholder
            </div>
        </div>

        <!-- Notifications & Alerts -->
        <div class="bg-white rounded-xl shadow border border-gray-100 p-5 space-y-3">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Notifications & Alerts</h2>
            <div class="flex items-start text-sm">
                <span class="mt-1 mr-2 text-amber-500"><i class="fas fa-triangle-exclamation"></i></span>
                <div>
                    <div class="font-medium text-amber-700">Low stock alerts</div>
                    <p class="text-gray-500">Get notified when medications reach their reorder level.</p>
                </div>
            </div>
            <div class="flex items-start text-sm">
                <span class="mt-1 mr-2 text-red-500"><i class="fas fa-calendar-xmark"></i></span>
                <div>
                    <div class="font-medium text-red-700">Expiry alerts</div>
                    <p class="text-gray-500">Highlight medicines that are near or past expiry dates.</p>
                </div>
            </div>
            <div class="flex items-start text-sm">
                <span class="mt-1 mr-2 text-blue-500"><i class="fas fa-file-invoice-dollar"></i></span>
                <div>
                    <div class="font-medium text-blue-700">Procurement alerts</div>
                    <p class="text-gray-500">Track pending procurement orders and history.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


