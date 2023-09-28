<?php

namespace App\Http\Controllers\Categories;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;
use App\Models\User;
use App\Http\Controllers\Controller;



class CategoriesController extends Controller
{
    //
    public function index(){
      
        $categories = Categories::join('users', 'categories.user_id', '=', 'users.id')
        ->select('categories.*', 'users.name as user_name')
        ->get();
        
        
        return response()->json($categories);
    }
    public function show(){
        $categories = Categories::all();
        return response()->json($categories);
    }
    public function store(Request $request, $id){
        $data = $request;
        $user = User::findOrFail($id);
      
        try{
            $category = Categories::create([
                'user_id' => $user->id,
                'name' => $request->name,
            ]);
            return response()->json($category);
        }
        catch(Exception $e){
            return $e;
        }
       
    }
    public function update(Request $request, $id){
       
        try{
            $category = Categories::where('id', $id)->first();
           
            $category->update($request->all());
            return response()->json($category);
        }
        catch(Exception $e){
            return $e;
        };
    }
    public function destroy($id){
        try{
            $category = Categories::findOrFail($id);
            
            if(!empty($category)){
                $category->delete();
                return response()->json($category);
            }
            else
            {
                return back()->with(['message' => 'Categoria não encontrada em nossos registros']);
            }
        }
        catch(Exception $e){}
    }
}
