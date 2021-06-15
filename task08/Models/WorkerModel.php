<?php


class WorkerModel
{
    public string $first_name;

    public string $last_name;

    public ?string $patronymic;

    public int $speciality_id;

    public string $date_of_birth;

    public int $earning_in_percents;

    public int $employee_status_id;

    public function __construct($first_name, $last_name, $patronymic, $speciality_id, $date_of_birth)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->patronymic = $patronymic;
        $this->speciality_id = $speciality_id;
        $this->date_of_birth = $date_of_birth;
        $this->earning_in_percents = 90;
        $this->employee_status_id = 1;

    }
}