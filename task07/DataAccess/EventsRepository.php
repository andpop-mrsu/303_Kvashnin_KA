<?php
declare(strict_types=1);

require_once 'Models/EventViewModel.php';
require_once 'Models/IdViewModel.php';

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
where w.id = ? "
        );

        $statement->execute([$workerId]);

        return $statement->fetchAll(PDO::FETCH_CLASS, EventViewModel::class);
    }

    public function getWorkersIds(): array
    {
        return $this->connection->query(
                "
select distinct worker_id
from events 
"
            )
            ->fetchAll(PDO::FETCH_CLASS, IdViewModel::class);
    }
}
