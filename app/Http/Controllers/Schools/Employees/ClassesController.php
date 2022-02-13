<?php

namespace App\Http\Controllers\Schools\Employees;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Services\WondeRepository;

class ClassesController extends Controller
{
    public function __construct(private WondeRepository $repository)
    {
    }

    public function index(string $schoolId, string $employeeId)
    {
        $data = $this->repository->setSchool($schoolId)
            ->setEmployee($employeeId)
            ->setClasses()
            ->mergeClassesWithEmployee();

        return new TeacherResource($data);
    }
}
