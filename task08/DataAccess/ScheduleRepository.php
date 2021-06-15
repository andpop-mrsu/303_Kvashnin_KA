<?php

require_once '../Models/ScheduleModel.php';
class ScheduleRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return ScheduleModel []
     */
    public function getAll(): array
    {
        $statement = $this->connection->query(
            "
select w.id as master_id,
       m.mon_start,
       m.mon_end,
       m.tue_start,
       m.tue_end,
       m.wed_start,
       m.wed_end,
       m.thu_start,
       m.thu_end,
       m.fri_start,
       m.fri_end,
       m.sat_start,
       m.sat_end,
       m.sun_start,
       m.sun_end
from masters_schedule as m
         join workers as w on m.id = w.id
         "
        );

        return $statement->fetchAll(PDO::FETCH_CLASS, ScheduleModel::class);
    }

    public function getById($id): array
    {
        $statement = $this->connection->prepare(
            "
select w.id as master_id,
       m.mon_start,
       m.mon_end,
       m.tue_start,
       m.tue_end,
       m.wed_start,
       m.wed_end,
       m.thu_start,
       m.thu_end,
       m.fri_start,
       m.fri_end,
       m.sat_start,
       m.sat_end,
       m.sun_start,
       m.sun_end
from masters_schedule as m
         join workers as w on m.id = w.id
         where w.id = ?
         "
        );
        $statement->execute([$id]);
        return $statement->fetchAll(PDO::FETCH_CLASS, ScheduleModel::class);
    }

    public function addSchedule($master_id, $mon_start, $mon_end, $tue_start, $tue_end,
                                $wed_start, $wed_end, $thu_start, $thu_end, $fri_start, $fri_end,
                                $sat_start, $sat_end, $sun_start, $sun_end): void
    {
        $statement = $this->connection->prepare('insert into masters_schedule
values (:master_id, :mon_start, :mon_end, :tue_start, :tue_end,
                                :wed_start, :wed_end, :thu_start, :thu_end, :fri_start, :fri_end,
                                :sat_start, :sat_end, :sun_start, :sun_end)');
        $statement->bindValue(":master_id", $master_id, PDO::PARAM_INT);
        $statement->bindValue(":mon_start", $mon_start, PDO::PARAM_STR);
        $statement->bindValue(":mon_end", $mon_end, PDO::PARAM_STR);
        $statement->bindValue(":tue_start", $tue_start, PDO::PARAM_STR);
        $statement->bindValue(":tue_end", $tue_end, PDO::PARAM_STR);
        $statement->bindValue(":wed_start", $wed_start, PDO::PARAM_STR);
        $statement->bindValue(":wed_end", $wed_end, PDO::PARAM_STR);
        $statement->bindValue(":thu_start", $thu_start, PDO::PARAM_STR);
        $statement->bindValue(":thu_end", $thu_end, PDO::PARAM_STR);
        $statement->bindValue(":fri_start", $fri_start, PDO::PARAM_STR);
        $statement->bindValue(":fri_end", $fri_end, PDO::PARAM_STR);
        $statement->bindValue(":sat_start", $sat_start, PDO::PARAM_STR);
        $statement->bindValue(":sat_end", $sat_end, PDO::PARAM_STR);
        $statement->bindValue(":sun_start", $sun_start, PDO::PARAM_STR);
        $statement->bindValue(":sun_end", $sun_end, PDO::PARAM_STR);
        $statement->execute();
    }
}