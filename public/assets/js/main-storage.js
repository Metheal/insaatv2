$(document).ready(function () {
  $('[data-coreui-toggle="modal"]').on("click", function () {
    var target = $(this).data("coreui-target");
    $(target).modal("show");
  });

  function loadMaterials() {
    $.ajax({
      url: "ajax/handleMaterials.php?action=read",
      method: "GET",
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          var options = response.data
            .map(function (material) {
              return `<option value="${material.id}">${material.name}</option>`;
            })
            .join("");
          $("#materials").html(
            "<option selected>Select Material</option>" + options
          );
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }

  loadMaterials();

  $("#addMaterialForm").on("submit", function (event) {
    event.preventDefault();
    var material_id = $("#materials").val();
    var quantity = $("#quantity").val();
    var cost_by_piece = $("#cost_by_piece").val();

    $.ajax({
      url: "ajax/handleMainStorage.php?action=create",
      method: "POST",
      data: {
        material_id: material_id,
        quantity: quantity,
        cost_by_piece: cost_by_piece,
      },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          loadData();
          $("#materialAddModal").modal("hide");
          $("#addMaterialForm").trigger("reset");
          showSuccessModal();
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  window.editMainStorage = function (id, name, quantity, cost_by_piece) {
    $("#editId").val(id);
    $("#editMaterial").val(name);
    $("#editQuantity").val(quantity);
    $("#editCostByPiece").val(cost_by_piece);
    $("#materialEditModal").modal("show");
  };

  $("#editMaterialForm").on("submit", function (event) {
    event.preventDefault();
    var id = $("#editId").val();
    var quantity = $("#editQuantity").val();
    var cost_by_piece = $("#editCostByPiece").val();

    $.ajax({
      url: "ajax/handleMainStorage.php?action=update",
      method: "POST",
      data: { id: id, quantity: quantity, cost_by_piece: cost_by_piece },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          loadData();
          $("#materialEditModal").modal("hide");
          showSuccessModal();
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  window.deleteMainStorage = function (id) {
    $("#confirmDeleteButton").data("id", id);
    $("#deleteModal").modal("show");
  };

  $("#confirmDeleteButton").on("click", function () {
    var id = $(this).data("id");

    $.ajax({
      url: "ajax/handleMainStorage.php?action=delete",
      method: "POST",
      data: { id: id },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          loadData();
          $("#deleteModal").modal("hide");
          showSuccessModal();
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  function loadData() {
    $.ajax({
      url: "ajax/handleMainStorage.php?action=read",
      method: "GET",
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          var rows = response.data
            .map(function (entry) {
              return `<tr>
                          <td>${entry.id}</td>
                          <td>${entry.name}</td>
                          <td>${entry.quantity}</td>
                          <td>${entry.cost_by_piece} TL</td>
                          <td>
                              <button class="btn btn-warning btn-sm" onclick="editMainStorage(${entry.id}, '${entry.name}', ${entry.quantity}, ${entry.cost_by_piece})">
                                  <i class="fas fa-edit"></i> Edit
                              </button>
                              <button class="btn btn-danger btn-sm text-light" onclick="deleteMainStorage(${entry.id})">
                                  <i class="fas fa-trash-alt"></i> Delete
                              </button>
                          </td>
                      </tr>`;
            })
            .join("");
          $("#table-body").html(rows);
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
    $.ajax({
      url: "ajax/handleMainStorage.php?action=readAverage",
      method: "GET",
      success: function (data) {
        var response = JSON.parse(data);
        var index = 0;
        if (response.success) {
          var rows = response.data
            .map(function (entry) {
              return `<tr>
                          <td>${(index += 1)}</td>
                          <td>${entry.name}</td>
                          <td>${entry.total_quantity}</td>
                          <td>${parseFloat(entry.average_cost).toFixed(2)} TL</td>`;
            })
            .join("");
          $("#table-avg-body").html(rows);
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }

  loadData();
});
