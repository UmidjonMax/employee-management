<?php

class EmployeeController {
    private Employee $employeeModel;

    public function __construct(Employee $employeeModel) {
        $this->employeeModel = $employeeModel;
    }

    public function index(): array {
        return $this->employeeModel->getAll();
    }

    public function delete(int $id): bool {
        return $this->employeeModel->delete($id);
    }

    
}