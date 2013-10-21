<?php 
$converter = $this->config->item("converter");
?>
<script type="text/javascript">
    $(function () {
        $("#bitstream").change(function () {
                if (this.value != '') {
                    $("#submit").slideDown();
                }
        });
    });
</script>     
        <link rel="icon" href="<?php echo base_url("/favicon.ico"); ?>" type="image/x-icon">

	<h1>
            <?php
        if (isset($page_title)) {
            echo $page_title;
            }
        else {
            $converter = $this->config->item("converter");
            echo $this->lang->line("page_title") . ' - ' . $converter["name"];
        }
            ?>
        </h1>

        <?php
        if (count($chmod_messages) > 0) {
            echo '<div class="warning message">';
            echo "<p class='message'>";
            
            echo $this->lang->line("check_permissions_error");
            
            echo "<ul>";
            foreach($chmod_messages AS $c) {
                echo "<li>".$c."</li>";
            }
            echo "</ul>";
            
            
            echo "</p>";
            echo "</div>";
        }
        ?>
        
        <form id="body" action="<?php echo base_url("converter/"); ?>" method="post" enctype="multipart/form-data" tartget="_blank">
        
<?php
if (isset($error)) {
    echo $error;
}
?>

                <p>
                    Converter: <strong><?php echo $converter["name"]; ?></strong> <br />
                    Max file size limit: <strong><?php 
                    $size = $this->config->item("max_file_size");
                    if ($size == 0) {
                        echo "UNLIMIT";
                    }
                    else {
                        echo $size."MB";
                    }
                    ?></strong> <br />
                    Allow file type: <strong><?php echo $this->config->item("allowed_types"); ?></strong>
                </p>

                <?php
                if (is_array($parameters)) {
                    echo "<p><table>";
                    echo "<caption>".$this->lang->line("parameters_caption")."</caption>";
                    echo "<tbody>";
                    
                    foreach ($parameters AS $key => $parameter) {
                        $default_value = "";
                        if (isset($parameter["default_value"])) {
                            $default_value = $parameter["default_value"];
                        }
                        
                        $label = "PARAM ".$key;
                        if (isset($parameter["label"])) {
                            $label = $parameter["label"];
                        }
                        
                        $hint = NULL;
                        if (isset($parameter["hint"])) {
                            $hint = $parameter["hint"];
                        }
                        
                        ?>
                <tr>
                    <td valign="top"><?php echo $label; ?></td>
                    <td>
                        <input type="text" value="<?php echo $default_value; ?>" name="params_<?php echo $key; ?>" />
                        <?php
                        if (!is_null($hint)) {
                            echo "<br />".$hint;
                        }
                        ?>
                    </td>
                </tr>
                        <?php
                    }
                    
                    echo "</tbody></table></p>";
                }
                ?>
                
            <p>Upload your file and wait for download.</p>
            
                <input type="file" name="bitstream" id="bitstream" class="input file" />
                <div><button type="submit" id="submit" class="input submit">SUBMIT</button></div>
                
                
	</form>

                <hr />
                
                <h2>Converter setting</h2>
                <p class="message">
                    <a href="<?php echo base_url("phpliteadmin/phpliteadmin.php"); ?>" target="phpliteadmin">SQLite Database</a> 
                        (<a href="<?php echo base_url("phpliteadmin/phpliteadmin.php?table=bitstream&action=row_view&sort=bitstream_id&order=DESC"); ?>" target="phpliteadmin">bitstream</a> 
                        | 
                        <a href="<?php echo base_url("phpliteadmin/phpliteadmin.php?table=log&action=row_view&sort=log_id&order=DESC"); ?>" target="phpliteadmin">log</a> )
                    |
                    <a href="<?php echo base_url("converter/reset") ?>">RESET</a> 
                    |
                    <a href="<?php echo base_url("converter/unlock") ?>">UNLOCK</a> 
                </p>
                <p class="message">
                    Development reference: <a href="user_guide/" target="user_guide">User Guide</a> | <a href="user_guide_zh_tw/CodeIgniter%202.1.4/www.codeigniter.org.tw/user_guide/index.html" target="user_guide">Traditional Chinese</a>.
                
                </p>
                
                
                <hr/>
                <h2>Readme</h2>
                
                <?php
                if (isset($readme)) {
                    echo $readme;
                }
                ?>
		<p class="message">
                    <strong>PHP File Converter</strong> is based on CodeIgniter 2.1.4. 
                </p>
        
                 <hr/>
                <h2>License</h2>
                
                <pre class="message">
                <?php
                if (isset($license)) {
                    echo $license;
                }
                ?>
                </pre>
        
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>