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
    public function uploadThumbnail($thumbnail){
        try {
            
            if ($thumbnail) {
                
                $randomName = Str::random(10) . '.webp';
              
              
                $upload = Storage::putFileAs('/public/Categories/Thumbnails', $thumbnail[0], $randomName);
                return $randomName;
               
            }
            
        } catch (Exception $e) {
           
            return $e->getMessage();
        }
    }
}