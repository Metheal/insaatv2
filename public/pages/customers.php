<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Customers</h2>
        <button type="button" class="btn btn-primary ms-auto" data-coreui-toggle="modal" data-coreui-target="#customerAddModal">
            <i class="fas fa-plus-circle me-2"></i>Add New Customer
        </button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">GSM</th>
                    <th scope="col">Email</th>
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

<!-- Add Customer Modal -->
<div class="modal fade" id="customerAddModal" tabindex="-1" aria-labelledby="customerAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerAddModalLabel">Add Customer</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCustomerForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input name="fullname" id="fullname" type="text" placeholder="Full Name of Customer" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="gsm" class="form-label">GSM</label>
                        <input name="gsm" id="gsm" type="text" placeholder="GSM of Customer" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input name="email" id="email" type="text" placeholder="Email of Customer" class="form-control">
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

<!-- Edit Customer Modal -->
<div class="modal fade" id="customerEditModal" tabindex="-1" aria-labelledby="customerEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerEditModalLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCustomerForm">
                <input type="hidden" id="editId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editFullname" class="form-label">Full Name</label>
                        <input name="fullname" id="editFullname" type="text" placeholder="Full Name of Customer" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="editGsm" class="form-label">GSM</label>
                        <input name="gsm" id="editGsm" type="text" placeholder="GSM of Customer" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email address</label>
                        <input name="email" id="editEmail" type="text" placeholder="Email of Customer" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Customer Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this customer?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger text-light" id="confirmDeleteButton"><i class="fas fa-trash-alt"></i> Delete</button>
            </div>
        </div>
    </div>
</div>

<?php include_once "../views/success_modal.php" ?>

<script src="assets/js/customer.js"></script>