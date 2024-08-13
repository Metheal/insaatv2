<!DOCTYPE html>
<html lang="en">

<head>
  <base href="./">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title><?php echo $title ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/simplebar/dist/simplebar.css">
  <link rel="stylesheet" href="assets/css/simplebar/vendors/simplebar.css">
  <link rel="stylesheet" href="assets/css/coreui/dist/style.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <script src="assets/js/jquery-3.7.1.min.js"></script>

<body>
  <?php include "sidebar.php" ?>
  <div class="wrapper d-flex flex-column min-vh-100">
    <?php include "header.php" ?>
    <main class="body flex-grow-1 container-fluid px-4 pt-2">
      <?php include "./pages/{$page}.php"; ?>
    </main>
    <?php include "footer.php" ?>

  </div>

  <!-- CoreUI and necessary plugins-->
  <script src="assets/js/coreui/coreui.bundle.min.js"></script>
  <script src="assets/js/simplebar/simplebar.min.js"></script>
  <script>
    const header = document.querySelector('header.header');

    document.addEventListener('scroll', () => {
      if (header) {
        header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
      }
    });
  </script>
  <!-- Plugins and scripts required by this view-->
  <script src="assets/js/main.js"></script>
  <script>
  </script>
</body>

</html>