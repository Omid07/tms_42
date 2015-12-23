<?php

namespace App\Repositories;

use App\Subject;
use App\Course;
use App\User;

class SubjectRepository
{
    public function allSubjectsWithPaginate()
    {
        return Subject::orderBy('id')->paginate(\Config::get('paginate.paginate_no'));
    }
    public function subjectWithTasks($id)
    {
        return Subject::with('tasks')->find($id);
    }
    public function findSubject($id)
    {
        return Subject::find($id);
    }
    public function createSubject($input)
    {
        return Subject::create($input)->id;
    }
}
