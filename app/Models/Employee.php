<?php

class Employee {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $statement = $this->pdo->query("
        select 
        e.id, e.first_name, e.last_name, e.phone, e.email, e.department_id, e.position, e.salary,
        e.hire_date, e.status, d.name as department_name
        from employees e
        left join departments d on e.department_id = d.id
        order by e.id");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {
        $statement = $this->pdo->prepare("
        select e.id, e.first_name, e.last_name, e.phone, e.email, e.department_id, e.position, e.salary,
        e.hire_date, e.status, d.name as department_name
        from employees e 
        left join departments d on e.department_id = d.id
        where e.id = :id");
        $statement->execute(['id' => $id]);
        $employee = $statement->fetch(PDO::FETCH_ASSOC);
        return $employee ?: null;
    }

    public function create(string $firstName, string $lastName, string $phone, string $email, int $departmentId, 
                           string $position, string $salary, string $hireDate, string $status): bool {
        $statement = $this->pdo->prepare("
        insert into employees (first_name, last_name, phone, email, department_id, position, salary, hire_date, status)
        values (:first_name, :last_name, :phone, :email, :department_id, :position, :salary, TO_DATE(:hire_date, 'YYYY-MM-DD'), :status)");
        return $statement->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'email' => $email,
            'department_id' => $departmentId,
            'position' => $position,
            'salary' => $salary,
            'hire_date' => $hireDate,
            'status' => $status
        ]);
    }

    public function update(int $id, string $firstName, string $lastName, string $phone, string $email, int $departmentId, 
                           string $position, string $salary, string $hireDate, string $status): bool {
        $statement = $this->pdo->prepare("
        update employees set first_name = :first_name, last_name = :last_name, phone = :phone, email = :email, 
            department_id = :department_id, position = :position, salary = :salary, 
            hire_date = TO_DATE(:hire_date, 'YYYY-MM-DD'), status = :status, updated_at = CURRENT_TIMESTAMP
        where id = :id");
        return $statement->execute([
            'id' => $id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'email' => $email,
            'department_id' => $departmentId,
            'position' => $position,
            'salary' => $salary,
            'hire_date' => $hireDate,
            'status' => $status
        ]);
    }

    public function delete(int $id): bool {
        $statement = $this->pdo->prepare("delete from employees where id = :id");
        return $statement->execute(['id' => $id]);
    }
}