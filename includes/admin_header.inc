<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="css/simple.css">
    </head>
    <body>
        <div class="content">
            <div class="nav">
                <a href="dash.php" class="bt<?php is_current('dash'); ?>">Dashboard</a>
                <a href="add.php" class="bt<?php is_current('add'); ?>">Add Site</a>
                <a href="settings.php" class="bt<?php is_current('settings'); ?>">Settings</a>
            </div>
        <div class="box">
            <div class="description box_body">
                