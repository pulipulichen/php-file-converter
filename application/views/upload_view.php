<script type="text/javascript">
    $(function () {
        $("#bitstream").change(function () {
                if (this.value != '') {
                    $("#submit").slideDown();
                }
        });
    });
</script>   
<?php 
if ($this->config->item("debug") > 0) {
?>
        <script type="text/javascript">
            // 20131015
            // Only for test
            $(function () {
                setTimeout(function () {
                    var _file_path = "D:\\xampp\\htdocs\\php-file-converter\\README.md";
                    $("#bitstream").attr("value",_file_path).change();
                    $("#submit").slideDown();
                }, 500);
            });
        </script>
<?php 
}
?>   
        <link rel="icon" href="<?php echo base_url("/favicon.ico"); ?>" type="image/x-icon">
<div id="container">
	<h1>PHP File Converter</h1>

        
        <form id="body" action="<?php echo base_url("converter/"); ?>" method="post" enctype="multipart/form-data" tartget="_blank">
        
<?php
if (isset($error)) {
    echo $error;
}
?>

            <p>Upload your file and wait for download.</p>
                <p>Max file size limit: <?php echo $this->config->item("max_file_size") ?>MB</p>
                
                <input type="file" name="bitstream" id="bitstream" class="input file" />
                <div><button type="submit" id="submit" class="input submit">SUBMIT</button></div>
		
		<p>
                    PHP File Converter is based on CodeIgniter 2.1.4. If you are exploring CodeIgniter for the very first time, you should start by reading the 
                    <a href="user_guide/" target="user_guide">User Guide</a> (<a href="user_guide_zh_tw/CodeIgniter%202.1.4/www.codeigniter.org.tw/user_guide/index.html" target="user_guide">Traditional Chinese</a>).
                </p>
                
                <p>Checking system detail in <a href="phpliteadmin/phpliteadmin.php" target="phpliteadmin">SQLite Database</a>.</p>
                <p>Reset this system with <a href="<?php echo base_url("converter/reset") ?>" target="reset">RESET</a>.</p>
	</form>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>
<?php 
//$this->jquery->fadeIn("p:last");
?>