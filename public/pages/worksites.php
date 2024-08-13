<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Worksites</h2>
        <button onclick="loadCustomers()" type="button" class="btn btn-primary ms-auto" data-coreui-toggle="modal" data-coreui-target="#worksiteAddModal">
            <i class="fas fa-plus-circle me-2"></i>Add New Worksite
        </button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Description</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Address</th>
                    <th scope="col">Negotiated Price</th>
                    <th scope="col">Cost</th>
                    <th scope="col">In Progress?</th>
                    <th scope="col">Total Paid</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table rows will be dynamically inserted here -->
            </tbody>
        </table>
    </div>
    <div class="card-footer text-body-secondary">
        <!-- Optional footer content -->
    </div>
</div>

<!-- Add Worksite Modal -->
<div class="modal fade" id="worksiteAddModal" tabindex="-1" aria-labelledby="worksiteAddModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="worksiteAddModalLabel">Add New Worksite</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addWorksiteForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customer" class="form-label">Customer</label>
                        <select id="customer" name="customer_id" class="form-select" required>
                            <option value="" selected>Select Customer</option>
                            <!-- Options will be dynamically populated -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="initialPrice" class="form-label">Initial Price</label>
                        <input type="number" id="initialPrice" name="initialPrice" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Materials Used</label>
                        <div id="materialsContainer">
                            <!-- Materials will be dynamically added here -->
                        </div>
                        <button type="button" class="btn btn-success text-light" id="addMaterialButton">Add Material</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Worksite</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Worksite Modal -->
<div class="modal fade" id="worksiteUpdateModal" tabindex="-1" aria-labelledby="worksiteUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="worksiteUpdateModalLabel">Update Worksite</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateWorksiteForm">
                <div class="modal-body">
                    <input type="hidden" id="editWorksiteId" name="editWorksiteId">
                    <div class="mb-3">
                        <label for="editCustomer" class="form-label">Customer</label>
                        <input disabled id="editCustomer" name="editCustomer" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="editAddress" class="form-label">Address</label>
                        <textarea disabled id="editAddress" name="editAddress" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea disabled id="editDescription" name="editDescription" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editInitialPrice" class="form-label">Initial Price</label>
                        <input disabled type="number" id="editInitialPrice" name="editInitialPrice" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="editCost" class="form-label">Total Cost</label>
                        <input disabled type="number" id="editCost" name="editCost" class="form-control">
                    </div>
                    <div class="mb-3">
                        <input id="editInProgress" name="editInProgress" class="form-check-input" type="checkbox">
                        <label for="editInProgress" class="form-label">Still In Progress?</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expenses Average</label>
                        <table class="table table-striped table-hover" id="editExpensesAverageTable">

                        </table>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Materials Used</label>
                        <div id="editMaterialsContainer">
                            <!-- Existing materials will be dynamically populated here -->
                        </div>
                        <button type="button" class="btn btn-success text-light" id="addEditMaterialButton">Add Material</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Worksite Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this worksite?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger text-light" id="confirmDeleteButton"><i class="fas fa-trash-alt"></i> Delete</button>
            </div>
        </div>
    </div>
</div>

<?php include_once "../views/success_modal.php"; ?>

<script src="assets/js/worksite.js"></script>