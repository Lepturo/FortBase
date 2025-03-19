<?php
ob_start();
//DO NOT REMOVE imports!!
require("./trainingService.php");
require("./dbService.php");
require("./themeService.php");
require("../constants.php");

function getAllUsers()
{
    $trainingService = new TrainingService();
    $participants = $trainingService->getAllUsers();
    $users = array();
    foreach ($participants as $participant) {
        array_push($users, ["p_username" => $participant["p_username"]]);
    }
    return $users;
}

$participants = getAllUsers();

header('Content-Type: application/json; charset=utf-8');

ob_end_clean();

echo json_encode($participants);
exit;
