<?php

namespace App\Http\Controllers\Subcategories;

use App\Models\Subcategory;
use App\Models\Categories;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;

class SubcategoriesController extends Controller
{
    //
    private $subcategory;

    public function __construct(Subcategory $subcategory){
        $this->subcategory = $subcategory;
    }
    public function index(){
        $subcategories = Subcategory::join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->join('users', 'subcategories.user_id', 'users.id')
            ->select([
                'subcategories.id',
                'subcategories.name as name',
                'subcategories.created_at',
                'subcategories.updated_at', 
                'categories.id as category_id',
                'categories.name as category_name',
                'users.id as user_id',
                'users.name as user_name'
            ])
            ->get();
            
        return response()->json($subcategories);
    }
    public function store(Request $request, $id){
        $category = Categories::where('id', $request->category_id)->first();
     
        try{
            $subcategory = Subcategory::create([
                'name' => $request->name,
                'category_id' => $category->id,
                'user_id' => $id
            ]);

            return response()->json($subcategory);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function update(Request $request, $id){
      
         try{
            $subcategory = Subcategory::where('id', (int) $id)->update($request->all());
            return response()->json($subcategory);
        }
        catch(Exception $e){
        
            return response()->json($e);
        }
    }
    public function destroy($id){
        $subcategory = Subcategory::where('id',$id);
        try{
            $subcategory->delete();
            return true;
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
