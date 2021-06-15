<?php
declare(strict_types=1);

require_once 'DataAccess/EventsRepository.php';
require_once 'Utils/ParametersValidator.php';
require_once 'config.php';

const INIT_COMMAND = 'init';
const CURRENCY = '$';

shell_exec(INIT_COMMAND);

$validator = new ParametersValidator();
$workerId = $validator->getInputParameters();
$validationResult = $validator->validate($workerId);

if (!$validationResult->success) {
    lineError($validationResult->message ?? 'Something is wrong');
    die();
}

$pdo =  new PDO(
    'sqlite:' . DB_NAME,
    '',
    '',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

$eventsRepository = new EventsRepository($pdo);
$services = $workerId === null ?
    $eventsRepository->getAll() :
    $eventsRepository->getByWorker((int)$workerId);

if (count($services) < 1) {
    echo 'No events for this worker yet';
    die();
}

foreach ($services as $service) {
    line();
    lineWithFields(
        '  ',
        $service->id,
        $service->firstName,
        $service->lastName,
        $service->patronymic,
        $service->serviceName,
        $service->endedAt ?? 'Not ended yet',
        $service->price . CURRENCY,
        $service->status,
        PHP_EOL
    );
}
line();

function line(): void
{
    echo '------------------------------------------------------------------------------------' . PHP_EOL;
}

function lineWithFields(string $glue, ...$parameters): void
{
    echo implode($glue, $parameters);
}

function lineError(string $message): void
{
    echo "Error: $message";
}
