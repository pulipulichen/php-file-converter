<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php 
        if (isset($page_title)) {
            echo $page_title;
            }
        else {
            $converter = $this->config->item("converter");
            echo $converter["name"]." - ".$this->lang->line("page_title");
        }
        ?></title>
        <script type="text/javascript" src="<?php echo base_url("js/jquery.js"); ?>"></script>
        <link type="text/css" rel="stylesheet" href="<?php echo base_url("css/php-file-converter.css") ?>">
</head>
<body>
<div id="container">