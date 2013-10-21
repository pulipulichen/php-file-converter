<?php
if (isset($message)) {
    echo "<p class='message'>".$message."</p>";
}
?>
<p class='message'><a href="<?php echo base_url(); ?>">Return to homepage</a></p>
<p class='message'><img src="<?php echo base_url("images/no_entry.png"); ?>" /></p>