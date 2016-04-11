<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
/**
 * Class TasksAPITest
 */
class TasksAPITest extends TestCase
{
    use DatabaseMigrations;
    /**
     * Test tasks is an api then returns JSON
     *
     * @return void
     */
    public function testTasksUseJson()
    {
        $this->get('/task')->seeJson()->seeStatusCode(200);
    }
    /**
     * Test tasks in database are listed by API
     *
     * @return void
     */
    public function testTasksInDatabaseAreListedByAPI()
    {
        $this->createFakeTasks();
        $this->get('/task')
            ->seeJsonStructure([
                '*' => [
                    'name', 'done','priority'
                ]
            ])->seeStatusCode(200);
    }
    /**
     * Test task in database is shown by API
     *
     * @return void
     */
    public function testTaskInDatabaseAreShownByAPI()
    {
        $task = $this->createFakeTask();
        $this->get('/task/' . $task->id)
            ->seeJsonContains(['name' => $task->name, 'done' => $task->done, 'priority' => $task->priority ])
            ->seeStatusCode(200);
    }
    /**
     * Create fake task
     *
     * @return \App\Task
     */
    private function createFakeTask() {
        $faker = Faker\Factory::create();
        $task = new \App\Task();
        $task->name = $faker->sentence;
        $task->done = $faker->boolean;
        $task->priority = $faker->randomDigit;
        $task->save();
        return $task;
    }
    /**
     * Create fake tasks
     *
     * @param int $count
     * @return \App\Task
     */
    private function createFakeTasks($count = 10) {
        foreach (range(0,$count) as $number) {
            $this->createFakeTask();
        }
    }
    /**
     * Test tasks can be posted and saved to database
     *
     * @return void
     */
    public function testTasksCanBePostedAndSavedIntoDatabase()
    {
        $data = ['name' => 'Foobar', 'done' => true, 'priority' => 1];
        $this->post('/task',$data)->seeInDatabase('tasks',$data);
        $this->get('/task')->seeJsonContains($data)->seeStatusCode(200);
    }
    /**
     * Test tasks can be update and see changes on database
     *
     * @return void
     */
    public function testTasksCanBeUpdatedAndSeeChangesInDatabase()
    {
        $task = $this->createFakeTask();
        $data = [ 'name' => 'Learn Laravel', 'done' => false , 'priority' => 3];
        $this->put('/task/' . $task->id, $data)->seeInDatabase('tasks',$data);
        $this->get('/task')->seeJsonContains($data)->seeStatusCode(200);
    }
    /**
     * Test tasks can be deleted and not see on database
     *
     * @return void
     */
    public function testTasksCanBeDeletedAndNotSeenOnDatabase()
    {
        $task = $this->createFakeTask();
        $data = [ 'name' => $task->name, 'done' => $task->done , 'priority' => $task->priority];
        $this->delete('/task/' . $task->id)->notSeeInDatabase('tasks',$data);
        $this->get('/task')->dontSeeJson($data)->seeStatusCode(200);
    }

    /**
     * @group failed
     */
    public function testProtectedUrl(){

        $this->get('/task')->assertRedirectedTo('/auth/login');


    }
}