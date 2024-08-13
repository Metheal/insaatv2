$(document).ready(function () {
  $('[data-coreui-toggle="modal"]').on("click", function () {
    var target = $(this).data("coreui-target");
    $(target).modal("show");
  });

  window.loadPaymentTypes = () => {
    $.ajax({
      url: "ajax/handlePayments.php?action=readPaymentTypes",
      method: "GET",
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          var options = response.data
            .map(function (payment_type) {
              return `<option value="${payment_type.id}">${payment_type.name}</option>`;
            })
            .join("");
          $("#payment_type").html(
            "<option selected>Select Payment</option>" + options
          );
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  };

  window.loadWorksites = () => {
    $.ajax({
      url: "ajax/handleWorksites.php?action=read",
      method: "GET",
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          var options = response.data
            .map(function (worksite) {
              return `<option value="${worksite.id}">${worksite.id}. ${worksite.description}</option>`;
            })
            .join("");
          $("#worksite").html(
            "<option selected>Select Worksite</option>" + options
          );
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  };
  
  $("#addPaymentForm").on("submit", function (event) {
    event.preventDefault();
    var payment_type = $("#payment_type").val();
    var worksite_id = $("#worksite").val();
    var amount = $("#amount").val();

    $.ajax({
      url: "ajax/handlePayments.php?action=create",
      method: "POST",
      data: {
        payment_type: payment_type,
        worksite_id: worksite_id,
        amount: amount,
      },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          loadData();
          $("#paymentAddModal").modal("hide");
          $("#addPaymentForm").trigger("reset");
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

  window.editPayment = function (payment) {
    $("#editPaymentType")
      .empty()
      .append(
        $("<option></option>")
          .val(payment.payment_id)
          .text(payment.name)
          .prop("selected", true)
      );
    $("#editWorksite").val(payment.worksite_id);
    $("#editAmount").val(payment.amount);
    $("#editId").val(payment.id);
    $("#paymentEditModal").modal("show");
  };

  $("#editPaymentForm").on("submit", function (event) {
    event.preventDefault();
    var editId = $("#editId").val();
    var editAmount = $("#editAmount").val();

    $.ajax({
      url: "ajax/handlePayments.php?action=update",
      method: "POST",
      data: {
        editId: editId,
        editAmount: editAmount,
      },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          loadData();
          $("#paymentEditModal").modal("hide");
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

  window.deletePayment = function (id) {
    $("#confirmDeleteButton").data("id", id);
    $("#deleteModal").modal("show");
  };

  $("#confirmDeleteButton").on("click", function () {
    var id = $(this).data("id");

    $.ajax({
      url: "ajax/handlePayments.php?action=delete",
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
      url: "ajax/handlePayments.php?action=read",
      method: "GET",
      success: function (data) {
        var response = JSON.parse(data);
        if (response.success) {
          var rows = response.data
            .map(function (entry) {
              return `<tr>
                            <td>${entry.id}</td>
                            <td>${entry.worksite_id}</td>
                            <td>${entry.name}</td>
                            <td>${entry.amount}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editPayment(${JSON.stringify(
                                  entry
                                ).replaceAll('"', "'")})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm text-light" onclick="deletePayment(${
                                  entry.id
                                })">
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
  }

  loadData();
});
