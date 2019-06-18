<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = $message;
}
?>
<script type="text/javascript">
	$.notify({
        title: 'Failed',
        message: '<?= $message ?>'
    },{
        type: 'pastel-danger',
    });
</script>
