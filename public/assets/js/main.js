const showSuccessModal = () => {
  $("#successModal").modal("show");
  setTimeout(() => {
    $("#successModal").modal("hide");
  }, 2000);
};
