<?php 
namespace App\Services\CategoryService;

use App\Models\Categories;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryService {
    protected $categories;
    public function __construct(Categories $categories){
        $this->categories = $categories;
    }
    public function index(){
        try{
            $categories = Categories::join('users', 'categories.user_id', '=', 'users.id')
                ->select('categories.*', 'users.name as user_name')
                ->get();
            return $categories;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
        return response()->json($categories);
    }
    public function getCategories(){
        try{
            $categories = Categories::all();
            return $categories;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function getCategory($id){
        try{
            $categories = Categories::where('categories.id', $id)
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->select([
                'categories.id as category_id',
                'products.*',
                'product_stocks.color_qty as color_quantity',
                'product_stocks.size_qty as size_quantity'
            ])
            ->first();
            
            return $categories;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function show(){
        $categories = Categories::all();
        return response()->json($categories);
    }
    
    public function uploadThumbnail($thumbnail){
        try {
            
            if ($thumbnail) {
               
                $randomName = Str::random(10) . '.webp';
              
             
                $upload = Storage::putFileAs('/public/Categories/Thumbnails', $thumbnail[0] ?? $thumbnail[1], $randomName);

                return $randomName;
               
            }
            
        } catch (Exception $e) {
           
            return $e->getMessage();
        }
    }
    public function removeThumbnail($thumbnail)
    {
        try{
            $removeThumbnail = Storage::delete('/public/Categories/Thumbnails/'.$thumbnail);
            return true;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function store(Request $request, $userId){
        $user = User::findOrFail($userId);
       
        $uploadThumbnail = $this->uploadThumbnail($request->file('thumbnail'));
        try{
            $category = Categories::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'icon' => $request->icon,
                'thumbnail' => $uploadThumbnail
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
            $removeThumbnail = $this->removeThumbnail($category->thumbnail);
           
            if($removeThumbnail){
                
                $thumbnail = $request->file('thumbnail');
              
                $uploadThumbnail = $this->uploadThumbnail($thumbnail);
                
                $category->update([
                    'user_id' => $request->user_id,
                     'name' => $request->name,
                     'icon' => $request->icon,
                     'thumbnail' => $uploadThumbnail
                 ]);
            }

           
            return $category;
        }
        catch(Exception $e){
            return $e->getMessage();
        };
    }
    public function remove($id){
        try{
            $category = Categories::findOrFail($id);
            
            if(!empty($category)){
                $removeThumbnail = $this->removeThumbnail($category->thumbnail);
                if($removeThumbnail){
                    $category->delete();
                }
               
                return response()->json($category);
            }
            else
            {
                return back()->with(['message' => 'Categoria não encontrada em nossos registros']);
            }
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
   
}