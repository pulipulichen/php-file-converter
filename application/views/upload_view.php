<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>PHP File Converter</title>
	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
        form .input.submit {
                -moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
                -webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
                box-shadow:inset 0px 1px 0px 0px #ffffff;
                background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf) );
                background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
                filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
                background-color:#ededed;
                -webkit-border-top-left-radius:6px;
                -moz-border-radius-topleft:6px;
                border-top-left-radius:6px;
                -webkit-border-top-right-radius:6px;
                -moz-border-radius-topright:6px;
                border-top-right-radius:6px;
                -webkit-border-bottom-right-radius:6px;
                -moz-border-radius-bottomright:6px;
                border-bottom-right-radius:6px;
                -webkit-border-bottom-left-radius:6px;
                -moz-border-radius-bottomleft:6px;
                border-bottom-left-radius:6px;
                text-indent:0;
                border:1px solid #dcdcdc;
                display:none;
                color:#777777;
                font-family:arial;
                font-size:15px;
                font-weight:bold;
                font-style:normal;
                height:50px;
                line-height:50px;
                width:100px;
                text-decoration:none;
                text-align:center;
                text-shadow:1px 1px 0px #ffffff;
                margin: 0.5em 0;
        }
        form .input.submit:hover {
                background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed) );
                background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% );
                filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
                background-color:#dfdfdf;
        }
        form .input.submit:active {
                position:relative;
                top:1px;
        }
        
	</style>
</head>
<body>

        <link rel="icon" href="/php-file-converter/favicon.ico" type="image/x-icon">
<div id="container">
	<h1>PHP File Converter</h1>

	<form id="body">
		<p>Upload your file and wait for download.</p>
                <p>Converter:</p>
                
                <input type="file" name="bitstream" class="input file" />
                <button type="submit" class="input submit">SUBMIT</button>
		
		<p>
                    PHP File Converter is based on CodeIgniter 2.1.4. If you are exploring CodeIgniter for the very first time, you should start by reading the 
                    <a href="user_guide/" target="user_guide">User Guide</a> (<a href="user_guide_zh_tw/CodeIgniter%202.1.4/www.codeigniter.org.tw/user_guide/index.html" target="user_guide">Traditional Chinese</a>).
                </p>
                
                <p>Checking system detail in <a href="phpliteadmin.php" target="phpliteadmin">SQLite Database</a>.</p>
	</form>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

</body>
</html>