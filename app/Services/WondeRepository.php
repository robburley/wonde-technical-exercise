<?php

namespace App\Services;

use App\Exceptions\EmployeeNotFoundException;
use App\Exceptions\EmployeeNotSetException;
use App\Exceptions\SchoolNotFoundException;
use App\Exceptions\SchoolNotSetException;
use App\Services\Helpers\ObjectToArray;
use Wonde\Client;
use Wonde\Endpoints\Schools;

class WondeRepository
{
    private ?Schools $school;
    private ?object $employee;
    private array $classes = [];

    public function __construct(
        private Client $client,
        private ObjectToArray $objectToArray
    ) {
    }

    public function setSchool($schoolId): static
    {
        $this->school = $this->client->school($schoolId);

        $this->employee = null;
        $this->classes = [];

        throw_if(!$this->school, SchoolNotFoundException::class);

        return $this;
    }

    public function setEmployee($employeeId): static
    {
        throw_if(!$this->school, SchoolNotSetException::class);

        foreach ($this->school->employees->all(['classes'], ['has_class' => true]) as $current) {
            if ($current->id === $employeeId) {
                $this->employee = $current;

                break;
            }
        }

        $this->classes = [];

        throw_if(!$this->employee, EmployeeNotFoundException::class);

        return $this;
    }

    public function setClasses(): static
    {
        throw_if(!$this->school, SchoolNotSetException::class);
        throw_if(!$this->employee, EmployeeNotSetException::class);

        $classes = [];
        foreach ($this->employee->classes->data as $class) {
            foreach ($this->school->classes->all(['students'], ['class_name' => $class->name]) as $current) {
                $classes[] = $current;
            }
        }

        $this->classes = $classes;

        return $this;
    }

    public function getSchool(): Schools
    {
        return $this->school;
    }

    public function getEmployee(): ?object
    {
        return $this->employee;
    }

    public function getClasses(): array
    {
        return $this->classes;
    }

    public function mergeClassesWithEmployee(): array
    {
        throw_if(!$this->employee, EmployeeNotSetException::class);

        $employee = $this->objectToArray->handle($this->getEmployee());

        $employee['classes'] = $this->objectToArray->handle($this->getClasses());

        return $employee;
    }
}
