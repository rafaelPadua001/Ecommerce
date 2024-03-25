<?php
namespace App\Services\AppBarService;

use App\Models\AppBar;
use Exception;
use Illuminate\Support\Facades\Auth;

class AppBarService{
    protected $appBar;
    public function __construct(AppBar $appBar){
        $this->appBar = $appBar;
    }
    public function getAppBar($id){
        try{
            $appBar = $this->appBar->where('app_bars.store_id', '=', $id)
            ->join('banners', 'banners.store_id', '=', 'app_bars.store_id')
            ->join('cards', 'cards.store_id', '=', 'banners.store_id')
            ->join('carrousels', 'carrousels.store_id', '=', 'cards.store_id')
            ->join('footers', 'footers.store_id', '=', 'carrousels.store_id')
            ->select(
                'app_bars.*',
                'banners.image as banner_image',
                'app_bars.id as appBar_id',
                'cards.chip_color',
                'carrousels.images',
                'footers.id as footer_id',
                'footers.links',
                'footers.icons',
                'footers.text',
                'footers.color'
            )
            ->first();
          
            
            return response()->json($appBar);
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function getAppBarAttributes(){
        $appBar = $this->appBar->first();
        return $appBar;
    }
    public function store($request, $id){
        try{
            $user = Auth::user();
            $store = $this->appBar->where('store_id', '=', $id)->create([
                'user_id' => $user->id,
                'store_id' => $id,
                'colors' => json_encode($request),
            ]);
            return $store;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function update($request, $id){
        try{
            $user = Auth::user();
            $storeUpdate = $this->appBar->where('id', '=', $request->appBar_id)->update([
                'colors' => $request->colors,
                'user_id' => $user->id,
                'store_id' => $request->storeId
            ]);
            
            return $request->colors;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function delete($storeId){
        try{
            $delete = $this->appBar->where('store_id', '=', $storeId)->delete();
            return true;
        }
        catch(Exception $e){
            return $e;
        }
    }
}