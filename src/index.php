<?php
    $modal_size = "xg";
    $code_activitie = "DASHBOARD";
    include "components/main.php";
?>

<div>Página inicial.</div>

<script type="text/javascript"> 
    page_data = <?= json_encode($page); ?>;
</script>

<?php require "components/template/footer.php"; ?>