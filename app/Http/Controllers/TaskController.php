<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Transformers\TaskTransformer;


class TaskController extends Controller
{
    /**
     * TaskController constructor.
     */
    public function __construct(TaskTransformer $taskTransformer)
    {
        $this->TaskTransformer = $taskTransformer;
        $this->middleware('auth.basic', ['only' => 'store']);
        $this->middleware('auth:api');
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tasks = Task::all();


        $task = Task::all();
        return $this->respond([
            'data' => $this->taskTransformer->transformCollection($task->all())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        if (! Input::get('name') or ! Input::get('done') or ! Input::get('priority'))
        {
            return $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY)
                ->respondWithError('Parameters failed validation for a task.');
        }
        Task::create(Input::all());
        return $this->respondCreated('Task successfully created.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $task = Task::find($id);
       // $task = Task::where('id',$id)->first();

        if (! $task){
            return Response::json([

                'error' => [
                    'message' => 'La tasca no existeix',
                    'code'
                ]
            ], 404);
        }
        return Response::json([
             $this->taskTransfomer->transform($task)
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task =  Task::findorFail($id);
        $this->saveTask($request, $task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::destroy($id);
    }

    /**
     * @param Request $request
     * @param $task
     */
    public function saveTask(Request $request, $task)
    {
        $task->name = $request->name;
        $task->priority = $request->priority;
        $task->done = $request->done;
        $task->save();
    }


}
