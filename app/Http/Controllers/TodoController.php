<?php

namespace App\Http\Controllers;
use App\Models\Todo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TodoController extends Controller
{
    public function index(){
        $set = [
            'user' => auth()->user(),
            'todos' => $this->getAllTodos(),
        ];

        return view('dashboard', $set);
    }

    public function getAllTodos(){
        $user_id = auth()->user()->id;
        $todos = Todo::where('user_id', $user_id)
                    ->where('archived', 0)
                    ->orderBy('completed', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->get();
        return $todos;
    }

    public function todo_archived(){
        $user_id = auth()->user()->id;
        $todos = Todo::where('user_id', $user_id)
            ->where('archived', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $set = [
            'user' => auth()->user(),
            'todos' => $todos,
        ];

        return view('archived.index', $set);
    }

    public function todo_detail($id){
        $todo = Todo::where('id', $id)->first();
        if(!$todo){
            return redirect("/dashboard");
        }

        $set = [
            'user' => auth()->user(),
            'todo' => $todo,
        ];

        return view('detail.index', $set);
    }

    public function new(){
        if(request()->isMethod('post')){
            $data = request()->validate([
                'title' => 'required',
                'description' => 'required',
            ]);
    
            $data['user_id'] = auth()->user()->id;
            Todo::create($data);
        }
        return redirect("/dashboard");
    }

    public function update(){
        if(request()->isMethod('post')){
            $data = request()->validate([
                'id' => 'required',
                'title' => 'required',
                'description' => 'required',
            ]);
    
            $todo = Todo::where('id', $data['id'])->first();
            if(!$todo){
                return redirect("/dashboard");
            }
    
            $todo->title = $data['title'];
            $todo->description = $data['description'];
            $todo->save();
        }
        return redirect("/dashboard");
    }

    public function done(){
        if(request()->isMethod('post')){
            $status = 200;
            $message = '';

            $data = request()->all();
            if(isset($data['id']) && !empty($data['id'])){
                $todo = Todo::where('id', $data['id'])->first();
                if(!$todo){
                    $message = "Todo not found";
                    $status = 404;
                } else {
                    $todo->completed = 1;
                    $todo->save();
                    $message = "Todo completed";
                }
            }
        }
        return Response::json(['message' => $message], $status);
    }

    public function archive(){
        if(request()->isMethod('post')){
            $status = 200;
            $message = '';

            $data = request()->all();
            if(isset($data['id']) && !empty($data['id'])){
                $todo = Todo::where('id', $data['id'])->first();
                if(!$todo){
                    $message = "Todo not found";
                    $status = 404;
                } else {
                    try{
                        $todo->archived = 1;
                        $todo->save();
                        $message = "Todo archived";
                    } catch (\Exception $e){
                        $message = "Error archiving todo: ".$e->getMessage();
                        $this->log($e->getMessage(), __METHOD__, __LINE__);
                        $status = 500;
                    }
                }
            }
        }
        return Response::json(['message' => $message], $status);
    }

    public function permaDelete(){
        if(request()->isMethod('post')){
            $status = 200;
            $message = '';

            $data = request()->all();
            if(isset($data['id']) && !empty($data['id'])){
                $todo = Todo::where('id', $data['id'])->first();
                if(!$todo){
                    $message = "Todo not found";
                    $status = 404;
                } else {
                    try{
                        $todo->delete();
                        $message = "Todo deleted";
                    } catch (\Exception $e){
                        $message = "Error deleting todo: ".$e->getMessage();
                        $this->log($e->getMessage(), __METHOD__, __LINE__);
                        $status = 500;
                    }
                }
            }
            return Response::json(['message' => $message], $status);
        }
    }
}
