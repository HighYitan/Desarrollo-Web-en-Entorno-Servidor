<?php
namespace models;
use config\Database;
class Department extends Model{
    protected static $table = "departments"; // Definir la taula associada a la classe
    public function __construct(
        private int $department_id,
        private ?string $department_name = null,
        private ?int $manager_id = null,
        private ?int $location_id = null,
    ){}
}
?>