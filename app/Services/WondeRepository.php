<?php

namespace App\Services;

use App\Exceptions\EmployeeNotFoundException;
use App\Exceptions\EmployeeNotSetException;
use App\Exceptions\SchoolNotFoundException;
use App\Exceptions\SchoolNotSetException;
use App\Services\Helpers\ObjectToArray;
use GuzzleHttp\Exception\ClientException;
use Wonde\Client;
use Wonde\Endpoints\Schools;

class WondeRepository
{
    private array $classes = [];
    private ?object $employee;
    private ?Schools $school;
    private ?string $schoolId;

    public function __construct(
        private Client $client,
        private ObjectToArray $objectToArray
    ) {
    }

    public function setSchool(string $schoolId): static
    {
        try {
            $this->schoolId = $schoolId;
            $this->school = $this->client->school($schoolId);

            $this->employee = null;
            $this->classes = [];
        } catch (\Exception $exception) {
            throw new SchoolNotFoundException($schoolId . ' not found');
        }

        return $this;
    }

    /*
     * I couldn't see a parameter to pass to the endpoint to filter the data.
     */
    public function setEmployee(string $employeeId): static
    {
        throw_if(!$this->school, SchoolNotSetException::class);

        try {
            foreach ($this->school->employees->all(['classes'], ['has_class' => true]) as $searchedEmployee) {
                if ($searchedEmployee->id === $employeeId) {
                    $this->employee = $searchedEmployee;

                    break;
                }
            }

            $this->classes = [];
        } catch (ClientException $exception) {
            throw new EmployeeNotFoundException(sprintf('Employee %s not found for school %s', $employeeId, $this->schoolId));
        }

        throw_if(
            !$this->employee,
            new EmployeeNotFoundException(
                sprintf('Employee %s not found for school %s', $employeeId, $this->schoolId)
            )
        );

        return $this;
    }

    public function setClasses(): static
    {
        throw_if(!$this->school, SchoolNotSetException::class);
        throw_if(!$this->employee, EmployeeNotSetException::class);

        try {
            $classes = [];
            foreach ($this->employee->classes->data as $class) {
                $searchedClasses = $this->school->classes
                    ->all(['students'], ['has_lessons' => true, 'class_name' => $class->name]);

                foreach ($searchedClasses as $searchedClass) {
                    $classes[] = $searchedClass;
                }
            }

            $this->classes = $classes;
        } catch (ClientException $exception) {
            throw new EmployeeNotSetException('A valid employee needs to be set before getting classes');
        }

        throw_if(
            !$this->employee,
            new EmployeeNotSetException('A valid employee needs to be set before getting classes')
        );

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
