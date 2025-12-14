<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Billing & Payments</h1>
    </div>

    <!-- Billing & Payments Overview -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 mb-2">
            <i class="fas fa-money-bag text-blue-600"></i>
            <h2 class="text-xl font-semibold text-gray-800">Billing & Payments</h2>
        </div>
        <p class="text-gray-600">Manage invoices, payments, and financial records • Date: <?= date('F d, Y') ?></p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-800">₱<?= number_format($stats['total_revenue'], 2) ?></p>
                    <p class="text-sm text-green-600 mt-1">All time</p>
                </div>
                <i class="fas fa-dollar-sign text-blue-500 text-3xl"></i>
            </div>
        </div>

        <!-- Pending Invoices -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Invoices</p>
                    <p class="text-3xl font-bold text-gray-800">₱<?= number_format($stats['pending_invoices'], 2) ?></p>
                    <p class="text-sm text-orange-600 mt-1">Awaiting payment</p>
                </div>
                <i class="fas fa-file-invoice text-orange-500 text-3xl"></i>
            </div>
        </div>

        <!-- Overdue Payments -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Overdue Payments</p>
                    <p class="text-3xl font-bold text-gray-800">₱<?= number_format($stats['overdue_payments'], 2) ?></p>
                    <p class="text-sm text-red-600 mt-1">Requires attention</p>
                </div>
                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">This Month</p>
                    <p class="text-3xl font-bold text-gray-800">₱<?= number_format($stats['this_month'], 2) ?></p>
                    <p class="text-sm text-green-600 mt-1">Current month</p>
                </div>
                <i class="fas fa-calendar-alt text-green-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Recent Invoices Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Recent Invoices</h2>
            <div class="flex space-x-3">
                <button onclick="createBillsForPrescriptions()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                    Create Bills for Completed Prescriptions
                </button>
                <button onclick="exportInvoices()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors font-medium text-sm">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Invoice ID</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Type</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Amount</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Payment</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($invoices)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                No invoices found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($invoices as $invoice): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?= esc($invoice['invoice_id'] ?? $invoice['id']) ?></td>
                                <td class="py-3 px-4"><?= esc($invoice['patient_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4"><?= esc($invoice['invoice_type'] ?? 'Service') ?></td>
                                <td class="py-3 px-4 font-semibold">₱<?= number_format($invoice['amount'] ?? 0, 2) ?></td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $status = $invoice['status'] ?? 'pending';
                                    $statusClass = $status === 'paid' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'overdue' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800');
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusClass ?>">
                                        <?= esc(ucfirst($status)) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <?= isset($invoice['invoice_date']) ? date('M d, Y', strtotime($invoice['invoice_date'])) : date('M d, Y', strtotime($invoice['created_at'])) ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if (isset($invoice['payment_method'])): ?>
                                        <span class="text-gray-600"><?= esc(ucfirst($invoice['payment_method'])) ?></span>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-800" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-purple-600 hover:text-purple-800" title="Print">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Hidden form for CSRF token -->
<form id="createBillsForm" style="display: none;">
    <?= csrf_field() ?>
</form>

<script>
    function createBillsForPrescriptions() {
        if (confirm('Create bills for all completed prescriptions that don\'t have bills yet?')) {
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            
            // Get CSRF token from hidden form
            const form = document.getElementById('createBillsForm');
            const csrfToken = form.querySelector('input[name="<?= csrf_token() ?>"]').value;
            
            // Create FormData with CSRF token
            const formData = new FormData();
            formData.append('<?= csrf_token() ?>', csrfToken);
            
            fetch('<?= base_url('billing/create-bills') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                button.disabled = false;
                button.innerHTML = originalText;
                
                if (data.success) {
                    alert(`Created ${data.created} bills, skipped ${data.skipped} (already have bills)`);
                    location.reload();
                } else {
                    alert(data.message || 'Error creating bills');
                }
            })
            .catch(error => {
                button.disabled = false;
                button.innerHTML = originalText;
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    }

    function exportInvoices() {
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
        
        // Create a form to submit
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '<?= base_url('billing/export') ?>';
        document.body.appendChild(form);
        form.submit();
        
        // Re-enable button after a short delay
        setTimeout(() => {
            button.disabled = false;
            button.innerHTML = originalText;
            document.body.removeChild(form);
        }, 1000);
    }
</script>
<?= $this->endSection() ?>

