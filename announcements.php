<?php
/**
 * @author Cyanne Justin Vega
 * @copyright  Ilocos Sur Polytechnic State College 2023
 */
include_once 'init.php';

header('Content-Type: application/json; charset=utf-8');
$announcement = new Announcements();
echo $announcement->convertToJson($announcement->fetchAnnouncements());