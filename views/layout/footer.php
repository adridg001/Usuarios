<?php
$rutaJS= GenerarRutaJs($vista);
echo (file_exists($rutaJS))?"<script src=($rutaJS)></script>":"";
?>

<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
</body>
</html>