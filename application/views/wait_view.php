<p class="message">
    <strong>
<?php
if (isset($message)) {
    echo $message;
}
?>
    </strong>
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
        var _location = location.href;
        _location = _location.substr(0, _location.indexOf("php-file-convert")-1);
        $("#status").html(" is converted successful. <br /> Download link: "
            + '<a href="'+_href+'" tartget="download">'+_location+_href+'</a> <br />'
            + ' <img src="<?php echo base_url("images/checkmark.png"); ?>" />');
        //$.get(_href);
        setTimeout(function () {
            location.href = _href;
        }, 1000);
        return;
    }
    //location.href = _href;
    _call_status();
}

var _wait = <?php echo $wait_reload_interval * 1000; ?>;

var _call_status = function () {
    setTimeout(function () {
        //$.get(_status_uri, _callback);
        $.ajax({
            url: _status_uri,
            success: _callback,
            timeout: _wait,
            error: function() {
                _call_status();
            },
            dataType: "text"
        });
    }, _wait);
};
_call_status();

var _start_convert_url = "<?php echo $start_convert_uri; ?>";
//window.open(_start_convert_url, "_blank");
//$.get(_start_convert_url, "_blank");
$.ajax({
    url: _start_convert_url,
    timeout: 500
});

</script>
<p class="message"><a href="<?php echo base_url(); ?>">Return to homepage</a>.</p>