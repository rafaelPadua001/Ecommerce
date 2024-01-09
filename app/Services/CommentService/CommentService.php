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
            ->leftJoin('profile_images', 'customers.id', '=', 'profile_images.customer_id')
            ->select(
                'comments.*', 
                'customers.first_name',
                'customers.last_name',
                'profile_images.name'
            )
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
    }
    public function destroy($id){
        try{
            $comment = $this->comment->findOrFail($id)->delete();
            
            return $comment;
        }
        catch(Exception $e){
            return $e;
        }
    }
    
    
}