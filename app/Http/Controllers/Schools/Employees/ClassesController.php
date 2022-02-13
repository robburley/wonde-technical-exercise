<?php

namespace App\Http\Controllers\Schools\Employees;

use App\Http\Controllers\Controller;
use Wonde\Client;

class ClassesController extends Controller
{
    public function __construct(private Client $client)
    {
    }

    public function index(string $schoolId, string $employeeId)
    {
//        $userId = 'A921160679';
//        $schoolId = 'A1930499544';

        $school = $this->client->school($schoolId);

        foreach ($school->employees->all(['classes'], ['has_class' => true]) as $current) {
            if ($current->id === $employeeId) {
                $employee = $current;

                break;
            }
        }

        if (!$employee) {
            dd('nope');
        }

        $classes = [];
        foreach ($employee->classes->data as $class) {
            foreach ($school->classes->all(['students'], ['class_name' => $class->name]) as $current) {
                $classes[] = $current;
            }
        }

        dd($employee, $classes);
    }
}
