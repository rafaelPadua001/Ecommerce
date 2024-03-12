<?php

namespace App\Http\Controllers\Categories;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;
use App\Services\CategoryService\CategoryService;
use App\Models\User;
use App\Http\Controllers\Controller;



class CategoriesController extends Controller
{
    //
    protected $categoryService;

    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }
    public function index(){
      try{
        $categories = $this->categoryService->index();
        return response()->json($categories);
      }
      catch(Exception $e){
        return response()->json($e->getMessage());
      }
    }
    public function show(){
        $categories = Categories::all();
        return response()->json($categories);
    }
    public function create(Request $request, $id){
        try{
            $userId = $id;
            $create = $this->categoryService->store($request, $userId);
            return response()->json($create);
        }
        catch(Exception $e){
            return response()->json($e->getMessage());
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
