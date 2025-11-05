$(document).ready(function() {
  $("#loginForm").on("submit", function(e) {
    e.preventDefault();

    $.ajax({
      url: "/app/controllers/ctrauth.php",
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function(response) {
        if (response.success) {
          Swal.fire({
            icon: "success",
            title: "Bienvenido",
            text: response.message,
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            window.location.href = "/dashboard";
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: response.message
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: "error",
          title: "Error de conexión",
          text: "Intenta de nuevo más tarde."
        });
      }
    });
  });
});