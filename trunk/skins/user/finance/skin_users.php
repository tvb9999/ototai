<?php
class skin_users{
	function emailHtmlForgot($obj, $password) {
		$BWHTML .= <<<EOF
			<script language="javascript" type="text/javascript">
				$('#repassword').keypress(function(e){
				function checkMail(mail){
				function validMail(){
					if($("#userEmail").val().lastIndexOf(".")==$("#userEmail").val().length-1){
					if($("#userEmail").val().indexOf("@")!=$("#userEmail").val().lastIndexOf("@")){
					if(!checkMail($("#userEmail").val())){
				function validMail2(){
				function validPass1(){
					if($("#userPassword").val().length < 6){
				function validPass2(){
					if($("#repassword").val() != $("#userPassword").val()){
				$(document).ready(function(){
	function changePasswordForm($message = '') {
			<script language="javascript" type="text/javascript">
	function userInforForm( $message  = '') {
		<script language="javascript" type="text/javascript">
			function changDate(){
			function checkMail(mail){
			$('#submit').click(function(){
	function forgotPasswordForm($message = '') {
			<script language="javascript" type="text/javascript">
?>