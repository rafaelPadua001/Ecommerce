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
        $categories = $this->categoryService->show();
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
            $category = $this->categoryService->update($request, $id);
           return response()->json($category);
        }
        catch(Exception $e){
            return response()->json($e);
        };
    }
    public function destroy($id){
        try{
            $removeCategory = $this->categoryService->remove($id);
            return response()->json($removeCategory);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
