<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Resources\QuestionResource;
use App\Question;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request){  
        $user = User::get()->where('email', $request['email'])->first();
        $question = Question::get();
        $qns = QuestionResource::collection($question);
        $results_array = [];
        if(!is_null($request->file())){
            for($i = 0; $i < count($qns); ++$i){
                $image = $request->file($qns[$i]->title)->store('uploads');
                $results_array[$qns[$i]->title] = $image;
            }
        }
        Answer::create([
            "user_id" => $user['user_id'],
            "uploads" => serialize($results_array),
            "results" => null
        ]);

        return response()->json([
            "status" => true,
            "message" => "answer created successfully",
            "request" => $user
        ])->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show($email)
    {
        $user = User::get()->where('email', $email)->first();
        $answer = Answer::get()->where('user_id', $user['user_id'])->last();
        
        if(is_null($answer) || is_null($answer->results)){
            return response()->json([
                "status" => false,
                "message" => "Your solution is still under review"
            ])->setStatusCode(404);
        }

        return response()->json([
            "status" => true,
            "message" => "answer retrieved successfully",
            "answer" => $answer
        ])->setStatusCode(200);
    }

    public function getsingleanwer($email)
    {
        $user = User::get()->where('email', $email)->first();
        $answer = Answer::get()->where('user_id', $user['user_id'])->last();

        return response()->json([
            "status" => true,
            "message" => "answer retrieved successfully",
            "answer" => $answer
        ])->setStatusCode(200);
    }

    public function getanswers(){
        $answer = DB::table('users')
            ->join('answers', 'users.user_id', '=', 'answers.user_id')
            ->distinct('answer.user_id')
            ->select('fullname', 'uploads', 'users.user_id as user_id', 'results', 'answer_id')
            ->get();
        if(is_null($answer)){
            return response()->json([
                "status" => false,
                "message" => "No answered solution found"
            ])->setStatusCode(404);
        }
        foreach($answer as $ans){
            $ans->uploads = unserialize($ans->uploads);
        }
        
        return response()->json([
            "status" => true,
            "message" => "answers successfully retrieved",
            "answers" => $answer
        ])->setStatusCode(200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $req)
    {
        $answer = Answer::get()->where('answer_id', $req['id'])->last();
        $answer->results = $req['results'];
        $answer->save();

        return response()->json([
            "status" => true,
            "message" => "marks saved successfully"
        ])->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        //
    }
}
