<?php

require_once '../Utils/PdoFactory.php';
require_once '../DataAccess/ScheduleRepository.php';
require_once '../DataAccess/EventsRepository.php';

$pdo = PdoFactory::createPDO();
$eventsRepository = new EventsRepository($pdo);
$workersIds = $eventsRepository->getWorkersIds();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $master_id = $_POST['master_id'];
    $mon_start = $_POST['mon_start'];
    $mon_end = $_POST['mon_end'];
    $tue_start = $_POST['tue_start'];
    $tue_end = $_POST['tue_end'];
    $wed_start = $_POST['wed_start'];
    $wed_end = $_POST['wed_end'];
    $thu_start = $_POST['thu_start'];
    $thu_end = $_POST['thu_end'];
    $fri_start = $_POST['fri_start'];
    $fri_end = $_POST['fri_end'];
    $sat_start = $_POST['sat_start'];
    $sat_end = $_POST['sat_end'];
    $sun_start = $_POST['sun_start'];
    $sun_end = $_POST['sun_end'];

    $scheduleRepository = new ScheduleRepository($pdo);
    $scheduleRepository->addSchedule(
            $master_id, $mon_start, $mon_end, $tue_start, $tue_end,
        $wed_start, $wed_end, $thu_start, $thu_end, $fri_start, $fri_end,
        $sat_start, $sat_end, $sun_start, $sun_end
    );

    header('Location: index.php', true, 303);
    exit();
}
?>
<h1>Add info</h1>
<form name="add-schedule" method="POST" action="add-schedule.php">
    <label>Master id:
        <select name="master_id">
            <?php foreach($workersIds as $key => $id): ?>
                <option value=<?= $id->worker_id ?>>
                    <?= $id->worker_id ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>
    <label>Monday start: <input type="text" name="mon_start"></label><br><br>
    <label>Monday end: <input type="text" name="mon_end"></label><br><br>
    <label>Tuesday start: <input type="text" name="tue_start"></label><br><br>
    <label>Tuesday end: <input type="text" name="tue_end"></label><br><br>
    <label>Wednesday start: <input type="text" name="wed_start"></label><br><br>
    <label>Wednesday end: <input type="text" name="wed_end"></label><br><br>
    <label>Thursday start: <input type="text" name="thu_start"></label><br><br>
    <label>Thursday end: <input type="text" name="thu_end"></label><br><br>
    <label>Friday start: <input type="text" name="fri_start"></label><br><br>
    <label>Friday start: <input type="text" name="fri_end"></label><br><br>
    <label>Saturday start: <input type="text" name="sat_start"></label><br><br>
    <label>Saturday end: <input type="text" name="sat_end"></label><br><br>
    <label>Sunday start: <input type="text" name="sun_start"></label><br><br>
    <label>Sunday end: <input type="text" name="sun_end"></label><br><br>

    <input type="submit" value="Add">
</form>