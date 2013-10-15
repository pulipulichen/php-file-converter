<?php
if (isset($message)) {
    echo $message;
}
?>
<script type="text/javascript">
setTimeout(function () {
    location.href = '<?php $wait_uri ?>';
}, <?php echo $wait_reload_interval * 1000; ?>);
</script>