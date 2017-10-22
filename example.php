<?php
// ----------------------------------------------------------------------
// Copyright (C) 2007 by Abdul-Aziz Al-Oraij.
// http://aziz.oraij.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Class Name: FTP Folder Copy/Delete
// Filename:   example.php
// Original    Author(s): Abdul-Aziz Al-Oraij <aziz.oraij.com>
// Purpose:    xCopy/rm -R FTP folders
// ----------------------------------------------------------------------
// Bismillah..
session_start();
set_time_limit ( 1000 ) ;
require "ftp.class.php";
$server 
= $_SESSION["ftp_server"] ? $_SESSION["ftp_server"] : "localhost";
$ftp = new ftp($server);
$login_form = "<form method=\"post\"><label for=\"label0\">Server</label>\n<input name=\"server\" value=\"localhost\" type=\"text\" id=\"label0\" />\n<label for=\"label\">User</label>\n<input name=\"ftp_user_name\" type=\"text\" id=\"label\" />\n<label for=\"label2\">Pass</label>\n<input name=\"ftp_user_pass\" type=\"password\" id=\"label2\" /><input type=\"submit\" name=\"login\" value=\"Log in\" /></form>";

if($_POST[ftp_user_name] && $_POST[ftp_user_pass]){
	$_SESSION["ftp_user_name"] = $_POST[ftp_user_name];
	$_SESSION["ftp_user_pass"] = $_POST[ftp_user_pass];
	$_SESSION["ftp_server"] = $_POST[server];
}
if($_POST[logout]){
	unset($_SESSION);
}
$ftp_user_name	= $_SESSION[ftp_user_name];
$ftp_user_pass	= $_SESSION[ftp_user_pass];

// set up basic connection 
$list = $ftp->connect($ftp_user_name , $ftp_user_pass, $_GET['dir']);
if(is_array($list)){
	echo "<h2>Welcome $ftp_user_name</h2>";
	echo "<pre><form method=post><label for=\"del\">Delete: </label><input name=\"folder\" type=\"text\" id=\"del\" value=\"$_GET[dir]\" /><input type=\"submit\" name=\"delete\" value=\" X \" /><br /><label for=\"copy\">Copy </label><input name=\"dir\" type=\"text\" id=\"copy\" value=\"./fckeditor\" /><label for=\"label\"> -> </label><input name=\"remote\" type=\"text\" id=\"label\" value=\"/public_html/fckeditor\" /><input type=\"submit\" name=\"copy\" value=\"GO\" /><br /><input type=\"submit\" name=\"logout\" value=\"Logout\" /></form><a href=\"?\">&lt;root&gt;</a>\n";
	foreach ($list as $v) echo $v!="."&&$v!=".."?" <a href=\"?dir=$_GET[dir]/$v\">$v</a>\n":"";
}else{
	echo $login_form;
	exit;
}
if($_POST['delete']){
	echo "\n<a href=\"?dir=$_GET[dir]\">Done</a>\n";
	$ftp->rmAll($_POST['folder']);
}
if($_POST['copy']){
	echo "\n<a href=\"?dir=$_GET[dir]\">Done</a>\n";
	$ftp->copy($_POST['dir'], $_POST['remote']);
	$ftp->log("print");
}
$ftp->quit();

?>
