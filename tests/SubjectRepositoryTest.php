<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\SubjectRepository;
use App\Subject;
use App\Task;

class SubjectRepositoryTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    protected $subjectRepository;
    public function __construct()
    {
        $this->subjectRepository = new SubjectRepository;
    }
    public function testallSubjectsWithPaginate()
    {
        $subjectrepository = new SubjectRepository;
        $countSubject = count(Subject::all());
        $subject = factory(Subject::class)->create([
            'name' => 'subject-1',
            'description' => 'asd'
        ]);
        $subjects = $subjectrepository->allSubjectsWithPaginate();
        $this->seeInDatabase('subjects', ['name' => $subjects[$countSubject]->name]);
        $this->assertEquals($countSubject + 1, count($subjects));
    }
    public function testsubjectWithTasks()
    {
        $subjectId = count(Subject::all()) + 1;
        $subject = factory(Subject::class)->create([
            'id' => $subjectId,
            'name' => 'subject-1',
            'description' => 'asd'
        ]);   
        $subject = factory(Task::class)->create([
            'subject_id' => $subjectId,
            'name' => 'Task-1',
            'description' => 'as'
        ]);
        $subjectWithTasks = $this->subjectRepository->subjectWithTasks($subjectId);
        $count = count($subjectWithTasks) - 1;
        $this->seeInDatabase('subjects', ['name' => $subjectWithTasks->name]);
        $this->seeInDatabase('tasks', ['name' => $subjectWithTasks->tasks[$count]->name]);
        $this->seeInDatabase('tasks', ['subject_id' => $subjectWithTasks->tasks[$count]->subject_id]);
    }
    public function testfindSubject()
    {
        $id = count(Subject::all()) + 1;
        $singleSubject = factory(Subject::class)->create([
            'id' => $id
        ]);
        $subject = $this->subjectRepository->findSubject($id);
        $this->seeInDatabase('subjects', ['id' => $subject->id]);
        $secondId = count(Subject::all()) + 10;
        $secondSubject = $this->subjectRepository->findSubject($secondId);
        $this->assertNull($secondSubject);
    }
    public function testcreateSubject()
    {
        $this->dontseeInDatabase('subjects', ['name' => 'test']);
        $input = ['name' => 'test', 'description' => 'asd'];
        $this->subjectRepository->createSubject($input);
        $this->seeInDatabase('subjects', ['name' => 'test']);
    }
}
