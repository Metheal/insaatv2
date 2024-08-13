<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Payments</h2>
        <button onclick="loadPaymentTypes();loadWorksites()" type="button" class="btn btn-primary ms-auto" data-coreui-toggle="modal" data-coreui-target="#paymentAddModal">
            <i class="fas fa-plus-circle me-2"></i>Add New Payment
        </button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Worksite Id</th>
                    <th scope="col">Payment Type</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table rows will be dynamically inserted here -->
            </tbody>
        </table>
    </div>
    <div class="card-footer text-body-secondary">
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="paymentAddModal" tabindex="-1" aria-labelledby="paymentAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentAddModalLabel">Add Payment</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPaymentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_type" class="form-label">Payment Type</label>
                        <select id="payment_type" name="payment_type" class="form-select" aria-label="Select Payment Type">
                            <option value="" selected>Select Payment Type</option>
                            <!-- Options will be dynamically loaded here -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="worksite" class="form-label">Worksite Id</label>
                        <select id="worksite" name="worksite" class="form-select" aria-label="Select Worksite">
                            <option value="" selected>Select Worksite</option>
                            <!-- Options will be dynamically loaded here -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input name="amount" id="amount" type="number" placeholder="Amount" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Payment Modal -->
<div class="modal fade" id="paymentEditModal" tabindex="-1" aria-labelledby="paymentEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentEditModalLabel">Edit Payment</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPaymentForm">
                <input type="hidden" id="editId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editPaymentType" class="form-label">Payment Type</label>
                        <select disabled id="editPaymentType" name="editPaymentType" class="form-select" aria-label="Select Payment Type">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editWorksite" class="form-label">Worksite Id</label>
                        <input disabled id="editWorksite" name="editWorksite" class="form-select" aria-label="Select Worksite">
                        </input>
                    </div>
                    <div class="mb-3">
                        <label for="editAmount" class="form-label">Amount</label>
                        <input name="editAmount" id="editAmount" type="number" placeholder="Amount" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Payment Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this payment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger text-light" id="confirmDeleteButton"><i class="fas fa-trash-alt"></i> Delete</button>
            </div>
        </div>
    </div>
</div>

<?php include_once "../views/success_modal.php" ?>

<script src="assets/js/payment.js"></script>