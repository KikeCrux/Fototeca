<?php
// Script de cierre de sesion

session_start();
session_destroy();
header("Location: login.php");
