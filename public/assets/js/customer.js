$(document).ready(function () {
  
  $('[data-coreui-toggle="modal"]').on("click", function () {
    var target = $(this).data("coreui-target");
    $(target).modal("show");
  });

  
  $("#addCustomerForm").on("submit", function (event) {
    event.preventDefault();
    var fullname = $("#fullname").val();
    var gsm = $("#gsm").val();
    var email = $("#email").val();

    $.ajax({
      url: "ajax/handleCustomers.php?action=create",
      method: "POST",
      data: { fullname: fullname, gsm: gsm, email: email },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          loadData();
          $("#customerAddModal").modal("hide");
          $("#addCustomerForm").trigger("reset");
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

  
  $("#editCustomerForm").on("submit", function (event) {
    event.preventDefault();
    var id = $("#editId").val();
    var fullname = $("#editFullname").val();
    var gsm = $("#editGsm").val();
    var email = $("#editEmail").val();

    $.ajax({
      url: "ajax/handleCustomers.php?action=update",
      method: "POST",
      data: { id: id, fullname: fullname, gsm: gsm, email: email },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          loadData();
          $("#customerEditModal").modal("hide");
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

  
  window.editCustomer = function (id, fullname, gsm, email) {
    $("#editId").val(id);
    $("#editFullname").val(fullname);
    $("#editGsm").val(gsm);
    $("#editEmail").val(email);
    $("#customerEditModal").modal("show");
  };

  
  $("#confirmDeleteButton").on("click", function () {
    var id = $(this).data("id");

    $.ajax({
      url: "ajax/handleCustomers.php?action=delete",
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
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  
  window.deleteCustomer = function (id) {
    $("#confirmDeleteButton").data("id", id);
    $("#deleteModal").modal("show");
  };

  
  function loadData() {
    $.ajax({
      url: "ajax/handleCustomers.php?action=read",
      method: "GET",
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          var rows = response.data
            .map(function (customer) {
              return `
                            <tr>
                                <td>${customer.id}</td>
                                <td>${customer.fullname}</td>
                                <td>${customer.gsm}</td>
                                <td>${customer.email}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editCustomer(${customer.id}, '${customer.fullname}', '${customer.gsm}', '${customer.email}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm text-light" onclick="deleteCustomer(${customer.id})">
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

  
  loadData();

  
  function showSuccessModal() {
    $("#successModal").modal("show");
    setTimeout(function () {
      $("#successModal").modal("hide");
    }, 2000);
  }
});
