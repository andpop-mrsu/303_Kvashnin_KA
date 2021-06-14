<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php

require_once '../DataAccess/EventsRepository.php';
require_once '../DataAccess/WorkersRepository.php';
require_once '../DataAccess/ScheduleRepository.php';
require_once '../Utils/PdoFactory.php';

$pdo = PdoFactory::createPDO();

$workerRepository = new WorkersRepository($pdo);
$workers = $workerRepository->getAll();

$schedulerRepository = new ScheduleRepository($pdo);
$schedule = $schedulerRepository->getAll();
?>
<h1>Workers</h1>
<table class="workers-table">
    <tr class="table-header">
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Patronymic</th>
        <th>Service name</th>
    </tr>
    <?php foreach($workers as $key => $worker): ?>
        <tr>
            <td><?= $worker->id ?></td>
            <td><?= $worker->firstName ?></td>
            <td><?= $worker->lastName ?></td>
            <td><?= $worker->patronymic ?></td>
            <td><?= $worker->specialty ?></td>
        </tr>
    <?php endforeach; ?>
    <a href="add-worker.php">Add new worker</a>
</table>
<h1>Sign up</h1>
<a href="sign-up.php">Sign up for the service</a>
<h1>Schedule</h1>
<table class="workers-schedule">
    <tr class="table-header">
        <th>Master id</th>
        <th>Monday</th>
        <th>Tuesday</th>
        <th>Wednesday</th>
        <th>Thursday </th>
        <th>Friday </th>
        <th>Saturday </th>
        <th>Sunday</th>
    </tr>
    <?php foreach($schedule as $key => $item): ?>
        <tr>
            <td><?= $item->master_id?></td>
            <td><?= $item->mon_start.' - '.$item->mon_end ?></td>
            <td><?= $item->tue_start.' - '.$item->tue_end?></td>
            <td><?= $item->wed_start.' - '.$item->wed_end ?></td>
            <td><?= $item->thu_start.' - '.$item->thu_end ?></td>
            <td><?= $item->fri_start.' - '.$item->fri_end ?></td>
            <td><?= $item->sat_start.' - '.$item->sat_end ?></td>
            <td><?= $item->sun_start.' - '.$item->sun_end ?></td>
        </tr>
    <?php endforeach; ?>
    <a href="add-schedule.php">Add master in schedule</a>
</table><br><br>
</body>
</html>