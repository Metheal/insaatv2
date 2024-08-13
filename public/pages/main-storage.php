<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Main Storage</h2>
        <button type="button" class="btn btn-primary ms-auto" data-coreui-toggle="modal" data-coreui-target="#materialAddModal">
            <i class="fas fa-plus-circle me-2"></i>Add New Item
        </button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Cost by Piece</th>
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

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Main Storage Average</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Total Quantity</th>
                    <th scope="col">Average Cost by Piece</th>
                </tr>
            </thead>
            <tbody id="table-avg-body">
                <!-- Table rows will be dynamically inserted here -->
            </tbody>
        </table>
    </div>
    <div class="card-footer text-body-secondary">
    </div>
</div>

<!-- Add Material Modal -->
<div class="modal fade" id="materialAddModal" tabindex="-1" aria-labelledby="materialAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="materialAddModalLabel">Add Material</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addMaterialForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="materials" class="form-label">Material</label>
                        <select id="materials" name="materials" class="form-select" aria-label="Select Material">
                            <option value="" selected>Select Material</option>
                            <!-- Options will be dynamically loaded here -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input name="quantity" id="quantity" type="number" placeholder="Quantity of Material" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="cost_by_piece" class="form-label">Cost By Piece</label>
                        <input name="cost_by_piece" id="cost_by_piece" type="number" placeholder="Cost of Material" class="form-control" required>
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

<!-- Edit Material Modal -->
<div class="modal fade" id="materialEditModal" tabindex="-1" aria-labelledby="materialEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="materialEditModalLabel">Edit Material</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMaterialForm">
                <input type="hidden" id="editId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editMaterial" class="form-label">Material</label>
                        <input disabled name="editMaterial" id="editMaterial" type="text" placeholder="Name of Material" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editQuantity" class="form-label">Quantity</label>
                        <input name="editQuantity" id="editQuantity" type="number" placeholder="Quantity of Material" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="cost_by_piece" class="form-label">Cost By Piece</label>
                        <input name="editCostByPiece" id="editCostByPiece" type="number" placeholder="Cost of Material" class="form-control" required>
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

<!-- Delete Material Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this material?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger text-light" id="confirmDeleteButton"><i class="fas fa-trash-alt"></i> Delete</button>
            </div>
        </div>
    </div>
</div>

<?php include_once "../views/success_modal.php" ?>

<script src="assets/js/main-storage.js"></script>