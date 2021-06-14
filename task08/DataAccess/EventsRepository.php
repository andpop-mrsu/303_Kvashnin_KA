<?php
declare(strict_types=1);

require_once '../Models/EventViewModel.php';
require_once '../Models/IdViewModel.php';

class EventsRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    /**
     * @return EventViewModel []
     */
    public function getAll(): array
    {
        $statement = $this->connection->query(
            "
select distinct 
       w.id         as id,
       w.first_name as firstName,
       w.last_name  as lastName,
       w.patronymic as patronymic,
       e.ended_at   as endedAt,
       es.title     as status,
       s.title      as serviceName,
       s.price      as price
from events as e
         join workers as w on e.worker_id = w.id
         join services as s on e.service_id = s.id
         join event_statuses es on e.status_id = es.id
         "
        );

        return $statement->fetchAll(PDO::FETCH_CLASS, EventViewModel::class);
    }

    /**
     * @param int $workerId
     * @return EventViewModel []
     */
    public function getByWorker(int $workerId): array
    {
        $statement = $this->connection->prepare(
            "
select distinct 
       w.id         as id,
       w.first_name as firstName,
       w.last_name  as lastName,
       w.patronymic as patronymic,
       e.ended_at   as endedAt,
       es.title     as status,
       s.title      as serviceName,
       s.price      as price

from events as e
         join workers as w on e.worker_id = w.id
         join services as s on e.service_id = s.id
         join event_statuses es on e.status_id = es.id
where w.id = ? 
"

        );

        $statement->execute([$workerId]);

        return $statement->fetchAll(PDO::FETCH_CLASS, EventViewModel::class);
    }

    public function getWorkersIds(): array
    {
        return $this->connection->query(
                "
select distinct id as worker_id
from workers 
"
            )
            ->fetchAll(PDO::FETCH_CLASS, IdViewModel::class);
    }

    public function addEvent($worker_id, $client_id, $service_id, $scheduled_at, $is_completed, $status_id): void
    {
        $statement = $this->connection->prepare('insert into events (worker_id, client_id, service_id, scheduled_at, is_completed, status_id)
values (:worker_id, :client_id, :service_id, :scheduled_at, :is_completed, :status_id)');
        $statement->bindValue(":worker_id", $worker_id, PDO::PARAM_INT);
        $statement->bindValue(":client_id", $client_id, PDO::PARAM_INT);
        $statement->bindValue(":service_id", $service_id, PDO::PARAM_INT);
        $statement->bindValue(":scheduled_at", $scheduled_at, PDO::PARAM_STR);
        $statement->bindValue(":is_completed", $is_completed, PDO::PARAM_INT);
        $statement->bindValue(":status_id", $status_id, PDO::PARAM_INT);

        $statement->execute();
    }
}
