<?php

require_once '../Models/WorkerViewModel.php';
require_once '../Models/WorkerModel.php';

class WorkersRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return WorkerViewModel []
     */
    public function getAll(): array
    {
        $statement = $this->connection->query(
            "
select
    w.id,
    w.first_name as firstName,
    w.last_name as lastName,
    w.patronymic,
    s.title as specialty
from workers as w
join specialties as s on w.speciality_id = s.id
order by lastName
         "
        );

        return $statement->fetchAll(PDO::FETCH_CLASS, WorkerViewModel::class);
    }

    public function addWorker(WorkerModel $worker): void
    {
        $statement = $this->connection->prepare('insert into workers (first_name, last_name, patronymic, date_of_birth, speciality_id, earning_in_percents, employee_status_id) 
values (:first_name, :last_name, :patronymic, :date_of_birth, :speciality_id, :earning_in_percents, :employee_status_id)');
        $statement->bindValue(":first_name", $worker->first_name, PDO::PARAM_STR);
        $statement->bindValue(":last_name", $worker->last_name, PDO::PARAM_STR);
        $statement->bindValue(":patronymic", $worker->patronymic, PDO::PARAM_STR);
        $statement->bindValue(":date_of_birth", $worker->date_of_birth, PDO::PARAM_STR);
        $statement->bindValue(":speciality_id", $worker->speciality_id, PDO::PARAM_INT);
        $statement->bindValue(":earning_in_percents", $worker->earning_in_percents, PDO::PARAM_INT);
        $statement->bindValue(":employee_status_id", $worker->employee_status_id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function getWorker(int $id)
    {
        $statement = $this->connection->prepare(
            "
select
    id,
    first_name,
    last_name,
    patronymic,
    date_of_birth,
    speciality_id,
    earning_in_percents,
    employee_status_id
from workers
where id = ?;
         "
        );
        $statement->execute([$id]);

        return $statement->fetchAll(PDO::FETCH_CLASS, WorkerModel::class);
    }

    public function removeWorkerById(int $id)
    {
        $statement = $this->connection->prepare("delete from workers where id = ?");

        $statement->execute([$id]);
    }

    public function updateWorker(WorkerModel $worker): void
    {
        $statement = $this->connection->prepare("UPDATE workers
SET first_name = ?,
    last_name = ?,
    patronymic = ?,
    date_of_birth = ?,
    speciality_id = ?,
    earning_in_percents = ?,
    employee_status_id = ?
WHERE id = ?");

        $statement->execute([$worker->first_name, $worker->last_name, $worker->patronymic, $worker->date_of_birth,
            $worker->speciality_id, $worker->earning_in_percents, $worker->employee_status_id, $worker->id]);
    }
}