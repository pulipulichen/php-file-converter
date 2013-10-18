<p>
    <span style="font-weight: bold;">
<?php
if (isset($message)) {
    echo $message;
}
?>
    </span>
    <span id="status">
        is converting now. Please Wait. <br />
        <img src="<?php echo base_url("images/ajax-loader.gif"); ?>" />
    </span>
</p>
<script type="text/javascript">
    
//setTimeout(function () {
//    location.href = '<?php echo $wait_uri ?>';
//}, <?php echo $wait_reload_interval * 1000; ?>);

var _status_uri = "<?php echo $status_uri ?>";
var _callback = function (_data) {
    var _href = "<?php echo $wait_uri; ?>";
    if (_data == "deleted") {
        _href = "<?php echo $deleted_uri; ?>";
    }
    else if (_data != "wait") {
        _href = "<?php echo $download_uri; ?>/" + _data;
        $("#status").html(" is converted successful. <br /> Download link: "
            + '<a href="'+_href+'" tartget="download">'+_href+'</a> <br />'
            + ' <img src="<?php echo base_url("images/checkmark.png"); ?>" />');
        //$.get(_href);
        setTimeout(function () {
            location.href = _href;
        }, 1000);
        return;
    }
    location.href = _href;
}

var _wait = <?php echo $wait_reload_interval * 1000; ?>;

setTimeout(function () {
    $.get(_status_uri, _callback);
}, _wait);

var _start_convert_url = "<?php echo $start_convert_uri; ?>";
//window.open(_start_convert_url, "_blank");
$.get(_start_convert_url, "_blank");

</script>
<p><a href="<?php echo base_url(); ?>">Return to homepage</a>.</p>