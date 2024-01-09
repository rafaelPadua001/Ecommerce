<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Services\CommentService\CommentService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    //
    protected $commentService;
    protected $customerService;
    
    public function __construct(
        CommentService $commentService,
   
    )
    {
        $this->commentService = $commentService;
        
    }
    public function index(){
        $comments = $this->commentService->getAll();
        return response()->json($comments);
    }
    public function create(Request $request){
        try{ 
            $comment = $this->commentService->store($request);
            return response()->json($comment);
        }
        catch(Exception $e){
            return response()->json($e);
        }
       
    }
    public function remove($id){
        try{
            $comment = $this->commentService->destroy($id);
            return response()->json($comment);
        }
        catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
