$(document).ready(function () {
  window.loadCustomers = () => {
    $.ajax({
      url: "ajax/handleCustomers.php?action=read",
      method: "GET",
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          var customerSelect = $("#customer");
          customerSelect.empty();
          customerSelect.append(
            '<option value="" selected>Select Customer</option>'
          );
          response.data.forEach(function (customer) {
            customerSelect.append(
              '<option value="' +
                customer.id +
                '">' +
                customer.fullname +
                "</option>"
            );
          });
        } else {
          alert("Failed to load customers.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  };

  $("#addWorksiteForm").on("submit", function (event) {
    event.preventDefault();

    var customer_id = $("#customer").val();
    var address = $("#address").val();
    var description = $("#description").val();
    var initialPrice = $("#initialPrice").val();

    var materials = [];

    $("#materialsContainer .material-entry").each(function () {
      var $entry = $(this);
      var materialId = $entry.find("select[name^='material_id']").val();
      var quantity = $entry.find("input[name^='quantity']").val();
      var costByPiece = $entry.find("input[name^='cost_by_piece']").val();
      var materialStorageId = $entry.find("option:selected").data("storage-id");

      var isFromStorage = $entry
        .find("option:selected")
        .hasClass("storage-option");

      if (materialId && quantity && costByPiece) {
        materials.push({
          id: isFromStorage ? materialStorageId : null,
          material_id: materialId,
          quantity: quantity,
          cost_by_piece: costByPiece,
          from_storage: isFromStorage,
        });
      }
    });

    $.ajax({
      url: "ajax/handleWorksites.php?action=create",
      method: "POST",
      data: {
        customer_id: customer_id,
        address: address,
        description: description,
        initialPrice: initialPrice,
        materials: materials,
      },
      success: function (response) {
        try {
          var data = JSON.parse(response);
          if (data.success) {
            $("#worksiteAddModal").modal("hide");
            showSuccessModal();
            $("#addWorksiteForm").trigger("reset");
          } else {
            alert(data.message);
          }
        } catch (e) {
          console.error("Error parsing response:", e);
        }
        loadData();
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
      },
    });
  });

  $("#addMaterialButton").on("click", async function () {
    const index = $("#materialsContainer .material-entry").length;
    let materialSelectHtml = `
        <select id="material-select-${index}" class="form-select mb-2" name="material_id_${index}">
            <option value="">Select Material</option>
        </select>
    `;

    try {
      const response = await $.ajax({
        url: "ajax/handleWorksites.php?action=getMaterialsAndMainStorage",
        method: "GET",
      });

      const data = JSON.parse(response);

      if (data.success) {
        data.materials.forEach((material) => {
          materialSelectHtml += `
                    <option value="${material.material_id}">${material.name}</option>
                `;
        });

        data.main_storage.forEach((storage) => {
          if (storage.quantity > 0)
            materialSelectHtml += `
                    <option value="${storage.material_id}" data-storage-id="${storage.id}" data-quantity="${storage.quantity}" data-cost="${storage.cost_by_piece}" class="storage-option">
                        (storage ${storage.id}) ${storage.material_name} - Qty: ${storage.quantity}, Cost: ${storage.cost_by_piece}
                    </option>
                `;
        });

        materialSelectHtml += "</select>";

        $("#materialsContainer").append(`
                <div class="col-md-12 mb-2 material-entry" data-index="${index}">
                    <div class="row">
                        <div class="col-md-3">${materialSelectHtml}</div>
                        <div class="col-md-3">
                            <input type="number" min="1" name="quantity_${index}" class="form-control" placeholder="Quantity">
                        </div>
                        <div class="col-md-3">
                            <input type="number" min="1" name="cost_by_piece_${index}" class="form-control" placeholder="Cost By Piece">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-danger text-light remove-material-button" data-index="${index}">Remove</button>
                        </div>
                    </div>
                </div>
            `);

        $(`#material-select-${index}`).on("change", function () {
          const selectedOption = $(this).find("option:selected");
          const cost = selectedOption.data("cost");
          const quantity = selectedOption.data("quantity");

          if (cost) {
            $(this)
              .closest(".material-entry")
              .find("input[name^='cost_by_piece']")
              .val(cost)
              .prop("disabled", true);
            $(this)
              .closest(".material-entry")
              .find("input[name^='quantity']")
              .val(quantity)
              .prop("max", quantity);
          } else {
            $(this)
              .closest(".material-entry")
              .find("input[name^='cost_by_piece']")
              .prop("disabled", false);
            $(this)
              .closest(".material-entry")
              .find("input[name^='quantity']")
              .prop("max", false);
          }
        });
      } else {
        alert("Failed to load materials.");
      }
    } catch (error) {
      console.error("Error:", error);
    }
  });

  $(document).on("click", ".remove-material-button", function () {
    var index = $(this).data("index");
    $(`.material-entry[data-index="${index}"]`).remove();

    $("#materialsContainer .material-entry").each(function (i) {
      $(this).attr("data-index", i);
      $(this)
        .find(".row .col-md-3")
        .each(function (j) {
          var name = $(this).find("input, select").attr("name");
          if (name) {
            $(this).find("input, select").attr("name", name.replace(/\d+/, i));
          }
        });
      $(this).find(".remove-material-button").data("index", i);
    });
  });

  window.editWorksite = function (worksite) {
    let materials = JSON.parse(worksite.materials.replaceAll("'", '"'));

    $("#editWorksiteId").val(worksite.id);
    $("#editAddress").val(worksite.address);
    $("#editCost").val(worksite.cost);
    $("#editInProgress").prop("checked", worksite.in_progress);
    $("#editDescription").val(worksite.description);
    $("#editCustomer").val(worksite.customer);
    $("#editInitialPrice").val(worksite.initial_price);

    const tableData = Object.values(
      materials.reduce(
        (acc, { material_id, material_name, quantity, cost_by_piece }) => {
          if (!acc[material_id]) {
            acc[material_id] = {
              material_name,
              total_quantity: 0,
              total_cost: 0,
            };
          }
          acc[material_id].total_quantity += quantity;
          acc[material_id].total_cost += quantity * cost_by_piece;
          return acc;
        },
        {}
      )
    ).map(({ material_name, total_quantity, total_cost }) => ({
      material_name,
      total_quantity,
      average_cost_by_piece: total_cost / total_quantity,
    }));

    const table = $("#editExpensesAverageTable");
    table.empty();

    let tableContent = `
      <thead>
          <tr>
              <th>Material Name</th>
              <th>Total Quantity</th>
              <th>Average Cost By Piece</th>
          </tr>
      </thead>
      <tbody id="editExpensesAverageTableBody">
    `;

    tableData.forEach((row) => {
      tableContent += `
        <tr>
          <td>${row.material_name}</td>
          <td>${row.total_quantity}</td>
          <td>${row.average_cost_by_piece.toFixed(2)}</td>
        </tr>
      `;
    });

    tableContent += "</tbody>";
    table.append(tableContent);

    $("#editMaterialsContainer").empty();

    materials.forEach(function (material, index) {
      addMaterialEntry(
        material.material_id,
        material.material_name,
        material.quantity,
        material.cost_by_piece,
        index
      );
    });

    $("#worksiteUpdateModal").modal("show");
  };

  function addMaterialEntry(
    materialId,
    materialName,
    quantity,
    costByPiece,
    index
  ) {
    var entryHtml = `
      <div class="col-md-12 mb-2 material-entry" data-index="${index}">
        <div class="row">
          <div class="col-md-3">
            <select disabled class="form-select mb-2" name="material_id_${index}">
              <option value="${materialId}">${materialName}</option>
            </select>
          </div>
          <div class="col-md-3">
            <input disabled type="number" name="quantity_${index}" class="form-control" placeholder="Quantity" value="${quantity}">
          </div>
          <div class="col-md-3">
            <input disabled type="number" name="cost_by_piece_${index}" class="form-control" placeholder="Cost By Piece" value="${costByPiece}">
          </div>
        </div>
      </div>
    `;
    $("#editMaterialsContainer").append(entryHtml);
  }

  $("#addEditMaterialButton").on("click", async function () {
    const index = $("#editMaterialsContainer .material-entry").length;
    let materialSelectHtml = `
        <select class="form-select mb-2" name="material_id_${index}" id="material-select-${index}">
            <option value="">Select Material</option>
    `;

    try {
      const response = await $.ajax({
        url: "ajax/handleWorksites.php?action=getMaterialsAndMainStorage",
        method: "GET",
      });

      const data = JSON.parse(response);

      if (data.success) {
        data.materials.forEach((material) => {
          materialSelectHtml += `
                    <option value="${material.material_id}">${material.name}</option>
                `;
        });

        data.main_storage.forEach((storage) => {
          if (storage.quantity > 0)
            materialSelectHtml += `
                    <option value="${storage.material_id}" data-storage-id="${storage.id}" data-quantity="${storage.quantity}" data-cost="${storage.cost_by_piece}" class="storage-option">
                        (storage ${storage.id}) ${storage.material_name} - Qty: ${storage.quantity}, Cost: ${storage.cost_by_piece}
                    </option>
                `;
        });

        materialSelectHtml += "</select>";

        $("#editMaterialsContainer").append(`
                <div class="col-md-12 mb-2 material-entry" data-index="${index}">
                    <div class="row">
                        <div class="col-md-3">${materialSelectHtml}</div>
                        <div class="col-md-3">
                            <input type="number" min="1" name="quantity_${index}" class="form-control" placeholder="Quantity">
                        </div>
                        <div class="col-md-3">
                            <input type="number" min="1" name="cost_by_piece_${index}" class="form-control" placeholder="Cost By Piece">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-danger text-light remove-material-button" data-index="${index}">Remove</button>
                        </div>
                    </div>
                </div>
            `);

        $(`#material-select-${index}`).on("change", function () {
          const selectedOption = $(this).find("option:selected");
          const cost = selectedOption.data("cost");
          const quantity = selectedOption.data("quantity");

          const $materialEntry = $(this).closest(".material-entry");

          if (cost) {
            $materialEntry
              .find("input[name^='cost_by_piece']")
              .val(cost)
              .prop("disabled", true);
            $materialEntry
              .find("input[name^='quantity']")
              .val(quantity)
              .prop("max", quantity);
          } else {
            $materialEntry
              .find("input[name^='cost_by_piece']")
              .prop("disabled", false);
            $materialEntry.find("input[name^='quantity']").prop("max", false);
          }
        });
      } else {
        alert("Failed to load materials.");
      }
    } catch (error) {
      console.error("Error:", error);
    }
  });

  $("#updateWorksiteForm").on("submit", function (event) {
    event.preventDefault();
    const worksite_id = $("#editWorksiteId").val();
    const in_progress = $("#editInProgress").prop("checked") ? 1 : 0;
    const total_paid = $("#editFinishedPrice").val();

    let materials = [];
    $("#editMaterialsContainer .material-entry").each(function () {
      var $entry = $(this);
      var materialId = $entry.find("select[name^='material_id']").val();
      var quantity = $entry.find("input[name^='quantity']").val();
      var costByPiece = $entry.find("input[name^='cost_by_piece']").val();
      var materialStorageId = $entry.find("option:selected").data("storage-id");

      var isFromStorage = $entry
        .find("option:selected")
        .hasClass("storage-option");

      if (materialId && quantity && costByPiece) {
        materials.push({
          id: isFromStorage ? materialStorageId : null,
          material_id: materialId,
          quantity: quantity,
          cost_by_piece: costByPiece,
          from_storage: isFromStorage,
        });
      }
    });

    $.ajax({
      url: "ajax/handleWorksites.php?action=update",
      method: "POST",
      data: {
        worksite_id: worksite_id,
        materials: materials,
        in_progress: in_progress,
        total_paid: total_paid,
      },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          $("#worksiteUpdateModal").modal("hide");
          showSuccessModal();
          $("#updateWorksiteForm").trigger("reset");
        } else {
          alert(response.message);
        }
        loadData();
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  function loadData() {
    $.ajax({
      url: "ajax/handleWorksites.php?action=read",
      method: "GET",
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          var rows = response.data
            .map(function (worksite) {
              return `
                <tr>
                  <td>${worksite.id}</td>
                  <td>${worksite.description}</td>
                  <td>${worksite.customer}</td>
                  <td>${worksite.address}</td>
                  <td>${worksite.initial_price} TL</td>
                  <td>${worksite.cost} TL</td>
                  <td>${worksite.in_progress ? "true" : "false"}</td>
                  <td>${worksite.total_paid} TL</td>
                  <td>
                    <button class="btn btn-warning btn-sm" onclick="editWorksite(${JSON.stringify(
                      worksite
                    ).replaceAll('"', "'")})">
                      <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-danger btn-sm text-light" onclick="deleteWorksite(${
                      worksite.id
                    })">
                      <i class="fas fa-trash-alt"></i> Delete
                    </button>
                  </td>
                </tr>
              `;
            })
            .join("");
          $("tbody").html(rows);
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }

  window.deleteWorksite = (id) => {
    $("#confirmDeleteButton").data("id", id);
    $("#deleteModal").modal("show");
  };

  $("#confirmDeleteButton").on("click", function () {
    var id = $(this).data("id");

    $.ajax({
      url: "ajax/handleWorksites.php?action=delete",
      method: "POST",
      data: { id: id },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          showSuccessModal();
          loadData();
        } else {
          alert(response.message);
        }
        $("#deleteModal").modal("hide");
      },
      error: (xhr, status, error) => {
        console.error("Error:", error);
      },
    });
  });

  loadData();
});
