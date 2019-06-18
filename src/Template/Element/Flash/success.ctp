<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = $message;
}
?>

<script type="text/javascript">
	$.notify({
        title: 'Success',
        message: '<?= $message ?>'
    },{
        type: 'pastel-info',
    });
</script>
