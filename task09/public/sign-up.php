<?php

require_once '../DataAccess/ServicesRepository.php';
require_once '../DataAccess/WorkersRepository.php';
require_once '../DataAccess/ScheduleRepository.php';
require_once '../DataAccess/EventsRepository.php';
require_once '../Utils/PdoFactory.php';

$pdo = PdoFactory::createPDO();
$servicesRepository = new ServicesRepository($pdo);
$services = $servicesRepository->getServices();
$workersRepository = new WorkersRepository($pdo);
$workers = $workersRepository->getAll();
$scheduleRepository = new ScheduleRepository($pdo);
$schedule = $scheduleRepository->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'];
    $worker_id = $_POST['worker_id'];
    $client_id = 1;
    $is_completed = 0;
    $status_id = 1;
    $scheduled_at = $_POST['scheduled_at'];

    $eventRepository = new EventsRepository($pdo);
    $eventRepository->addEvent($worker_id, $client_id, $service_id, $scheduled_at, $is_completed, $status_id);

    header('Location: index.php', true, 303);
    exit();
}
?>
<h1>Sign up</h1>
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
    <?php foreach($schedule as $key => $time): ?>
        <tr>
            <td><?= $time->id?></td>
            <td><?= $time->mon_start.' - '.$time->mon_end ?></td>
            <td><?= $time->tue_start.' - '.$time->tue_end ?></td>
            <td><?= $time->wed_start.' - '.$time->wed_end ?></td>
            <td><?= $time->thu_start.' - '.$time->thu_end ?></td>
            <td><?= $time->fri_start.' - '.$time->fri_end ?></td>
            <td><?= $time->sat_start.' - '.$time->sat_end ?></td>
            <td><?= $time->sun_start.' - '.$time->sun_end ?></td>
        </tr>
    <?php endforeach; ?>

</table><br><br>

<form name="sign-up" method="POST" action="sign-up.php">
    <label>Service:
        <select name="service_id">
            <?php foreach($services as $key => $service): ?>
                <option value=<?= $service['id'] ?>>
                    <?= $service['service'].'('.$service['title'].')'?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>
    <label>Worker:
        <select name="worker_id">
            <?php foreach($workers as $key => $worker): ?>
                <option value=<?= $worker->id ?>>
                    <?= $worker->firstName.' '.$worker->lastName.' '.$worker->patronymic ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>
    <label>Time: <input type="date" name="scheduled_at"></label><br><br>

    <input type="submit" value="Choose time">
</form>