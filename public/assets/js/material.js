$(document).ready(function () {
  
  $('[data-coreui-toggle="modal"]').on("click", function () {
    var target = $(this).data("coreui-target");
    $(target).modal("show");
  });

  
  $("#addMaterialForm").on("submit", function (event) {
    event.preventDefault();
    var name = $("#name").val();

    $.ajax({
      url: "ajax/handleMaterials.php?action=create",
      method: "POST",
      data: { name: name },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          loadMaterials();
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

  
  window.editMaterial = function (id, name) {
    $("#editId").val(id);
    $("#editName").val(name);
    $("#materialEditModal").modal("show");
  };

  
  $("#editMaterialForm").on("submit", function (event) {
    event.preventDefault();
    var id = $("#editId").val();
    var name = $("#editName").val();

    $.ajax({
      url: "ajax/handleMaterials.php?action=update",
      method: "POST",
      data: { id: id, name: name },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          loadMaterials();
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

  
  window.deleteMaterial = function (id) {
    $("#confirmDeleteButton").data("id", id);
    $("#deleteModal").modal("show");
  };

  
  $("#confirmDeleteButton").on("click", function () {
    var id = $(this).data("id");

    $.ajax({
      url: "ajax/handleMaterials.php?action=delete",
      method: "POST",
      data: { id: id },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          showSuccessModal();
          loadMaterials();
        } else {
          alert(response.message);
        }
        $("#deleteModal").modal("hide");
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  function loadMaterials() {
    $.ajax({
      url: "ajax/handleMaterials.php?action=read",
      method: "GET",
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          var rows = response.data
            .map(function (material) {
              return `
                                <tr>
                                    <td>${material.id}</td>
                                    <td>${material.name}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editMaterial(${material.id}, '${material.name}')">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm text-light" onclick="deleteMaterial(${material.id})">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            `;
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
  }

  loadMaterials(); 
});
