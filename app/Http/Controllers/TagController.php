<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Transformers\TagTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Symfony\Component\Console\Input\Input;

class TagController extends Controller
{
    /**
     * TaskController constructor.
     */
    public function __construct(TagTransformer $tagTransformer)
    {
        $this->TagTransformer = $tagTransformer;
        $this->middleware('auth.basic', ['only' => 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tags = Tag::all();


        $tag = Tag::all();
        return $this->respond([
            'data' => $this->tagTransformer->transformCollection($tag->all())
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
                ->respondWithError('Parameters failed validation for a tag.');
        }
        Tag::create(Input::all());
        return $this->respondCreated('Tag successfully created.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $tag = Tag::find($id);
        // $task = Task::where('id',$id)->first();

        if (! $tag){
            return Response::json([

                'error' => [
                    'message' => 'La tag no existeix',
                    'code'
                ]
            ], 404);
        }
        return Response::json([
            $this->tagTransfomer->transform($tag)
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
        $tag =  Tag::findorFail($id);
        $this->saveTag($request, $tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tag::destroy($id);
    }

    /**
     * @param Request $request
     * @param $tag
     */
    public function saveTag(Request $request, $tag)
    {
        $tag->name = $request->name;
        $tag->priority = $request->priority;
        $tag->done = $request->done;
        $tag->save();
    }

}
