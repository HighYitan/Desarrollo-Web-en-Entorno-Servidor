<?php
namespace models;
use config\Database;
class Job extends Model{
    protected static $table = "jobs"; // Definir la taula associada a la classe
    public function __construct(
        private string $job_id,
        private ?string $job_title = null,
        private ?float $min_salary = null,
        private ?float $max_salary = null,
    ){}
    public function getJobId() : string{
        return $this->job_id;
    }
    public function getJobTitle() : ?string{
        return $this->job_title;
    }
    public function getMinSalary() : ?float{
        return $this->min_salary;
    }
    public function getMaxSalary() : ?float{
        return $this->max_salary;
    }
}
?>