<?php
namespace App\Services\CommentService;

use App\Models\Comment;
use Illuminate\Http\Client\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class CommentService{
    protected $comment;
    public function __construct(Comment $comment){
        $this->comment = $comment;
    }
    public function getAll(){
        $comments = $this->comment
            ->join('customers', 'comments.user_id', '=', 'customers.id')
            ->select('comments.*', 'customers.first_name', 'customers.last_name')
            ->get();
       
        return $comments;
    }
    public function store($request){
        try{
            
            $storeComment = $this->comment->create([
              'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'message' => $request->message
            ]);
            
            return $storeComment;
        }
        catch(Exception $e){
            return $e;
        }
        dd($request);
    }
    
    
}