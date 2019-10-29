<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Resources\QuestionResource as QuestionResource;
use App\User;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::get();
        $qns = QuestionResource::collection($questions);
        
        return response()->json([
            "status" => true,
            "message" => "data retrived successfully",
            "data" => $qns,
        ])->setStatusCode(200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $request = $req->input();
        $validator = Validator::make($req->all(), [
                "title" => "required|unique:questions",
                "question" => "required"
            ]
        );
        
        if($validator->fails()){
            return response()->json([
                "status" => false,
                "message" => $validator->messages()
            ])->setStatusCode(400);
        }

        $user = User::get()->where('email', $request['email'])->first();
        if(is_null($user) || $user->usertype !== 'admin'){
            return response()->json([
                "status" => false,
                "message" => "Only admins can access this resource"
            ])->setStatusCode(401);
        }
        $request['title'] = str_replace(' ', '', $request['title']);
        Question::create($request);
        
        return response()->json([
            "status" => true,
            "message" => "question added successfully",
            "data" => $request
        ])->setStatusCode(200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $req)
    {
        $user = User::get()->where('email', $req['email'])->first();
        if(is_null($user) || $user->usertype !== 'admin'){
            return response()->json([
                "status" => false,
                "message" => "Only admins can edit this rescource"
            ])->setStatusCode(401);
        }

        $question = Question::get()->where('question_id', $req['id'])->first();
        if(is_null($question)){
            return response()->json([
                "status" => false,
                "message" => "Question not found"
            ])->setStatusCode(404);
        }

        $question->title = $req['title'];
        $question->question = $req['question'];
        $question->save();

        return response()->json([
            "status" => true,
            "message" => "Question updated successfully",
            "data" => QuestionResource::collection(Question::get())
        ])->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        $user = User::get()->where('email', $req['email'])->first();
        if(is_null($user) || $user->usertype !== 'admin'){
            return response()->json([
                "status" => false,
                "message" => "Only admin can delete this resource"
            ])->setStatusCode(401);
        }

        $qns = Question::get()->where('question_id', $req['id'])->first();
        $qns->delete();
        return response()->json([
            "status" => true,
            "message" => "question successfully deleted"
        ])->setStatusCode(200);
    }
}
