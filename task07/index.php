<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php

require_once 'DataAccess/EventsRepository.php';
require_once 'Utils/ParametersValidator.php';
require_once 'config.php';

const INIT_COMMAND = 'init';
const CURRENCY = '$';

shell_exec(INIT_COMMAND);

$pdo =  new PDO(
    'sqlite:' . DB_NAME,
    '',
    '',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

$eventRepository = new EventsRepository($pdo);
$services = $eventRepository->getAll();
$workersIds = $eventRepository->getWorkersIds();

?>
<h1>Select worker's ID</h1>
<form action="" method="POST">
    <label>
        <select style="width: 200px;" name="worker_id">
            <option value=<?= null ?>>

            </option>
            <?php foreach($workersIds as $key => $idObject): ?>
                <option value=<?= $idObject->worker_id ?>>
                    <?= $idObject->worker_id ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Search by ID</button>
</form>
<?php
$validator = new ParametersValidator();
$worker_id = null;
if(isset($_POST['worker_id'])){
    $worker_id = (int)$_POST['worker_id'];
    $validationResult = $validator->validate($worker_id);
}

$services = $worker_id === null || $worker_id === 0 ?
    $eventRepository->getAll() :
    $eventRepository->getByWorker($worker_id);

?>
<table class="workers-table">
    <tr class="table-header">
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Patronymic</th>
        <th>Service name</th>
        <th>Status</th>
        <th>Ended at</th>
        <th>Price</th>
    </tr>
    <?php foreach($services as $service): ?>
        <tr>
            <td><?= $service->id ?></td>
            <td><?= $service->firstName ?></td>
            <td><?= $service->lastName ?></td>
            <td><?= $service->patronymic ?></td>
            <td><?= $service->serviceName ?></td>
            <td><?= $service->status ?></td>
            <td><?= $service->endedAt ?? 'Not ended yet' ?></td>
            <td><?= $service->price . CURRENCY ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>