<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemModel;
use App\ItemBundleModel;
use App\ItemBundleDetailModel;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseFormatter;
use Auth;
use Str;
use DB;

class BundleController extends Controller
{
    public function bundle(){

        return view('inventori.bundle.index');

    }

    public function createBundle(){

        return view('inventori.bundle.create');

    }

    public function storeBundle(Request $request){

        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(),[
                'iditem' => 'required',
                'name' => 'required',
            ]);

            
            $count['qty'] = $request->qty; 
            $iditem       = $request->iditem;
            
            #cek jika tidak ada item dipilih
            if(!$iditem) return redirect()->route('bundle-create')->with(['error' => 'Pilih setidaknya 1 Item!']);

            #cek jika input stok kosong
            $request_qty =  array_values(array_filter($request->qty));
            if(empty($request_qty)) return redirect()->route('bundle-create')->with(['error' => 'Qty tidak boleh kosong!']);
            $count['qty'] =  $request_qty;         

            #save ke tb rb_item_bundle
            $bundle = ItemBundleModel::create([
                'ib_idx' => Str::random(30),
                'ib_name' => $request->name,
                'ib_note' => $request->note,
                'ib_create' => date('Y-m-d H:i:s'),
                'ib_useridx' => Auth::user()->id
            ]);


            #save detail bundle berisi item
            foreach ($iditem as $key => $value) {
                
                #buat bundle jika  qty terisi
                if (isset( $count['qty'][$key])) {
                    $itemBundleDetail = new ItemBundleDetailModel();
                    $itemBundleDetail->ibd_ibidx = $bundle->ib_idx;
                    $itemBundleDetail->ibd_itidx = $value;
                    $itemBundleDetail->ibd_count = $count['qty'][$key];
                    $itemBundleDetail->ibd_create = date('Y-m-d H:i:s');
                    $itemBundleDetail->save();
                }
            }
            
            DB::commit();
            return redirect()->route('bundle')->with(['success' => 'Bundel telah disimpan!']);

        } catch (\Exception $e) {
            DB::rollback();
            // return $e->getMessage();
            return redirect()->route('bundle')->with(['error' => 'Gagal disimpan!']);
        }

    }

    public function getListDataBundle(Request $request){

        $orderBy = 'a.ib_name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.ib_name';
                break;
        }

        $data =  DB::table('rb_item_bundle as a')
                ->join('rb_item_bundle_detail as b','a.ib_idx','=','b.ibd_ibidx')
                ->select('a.ib_name','a.ib_note','a.ib_create','a.ib_idx', DB::raw('count(if(b.is_delete = 0, 1, NULL)) as count_item'))
                ->groupBy('a.ib_name','a.ib_note','a.ib_create','a.ib_idx')
                ->where('a.is_delete',0);

        if($request->input('search.value')!=null){
                    $data = $data->where(function($q)use($request){
                        $q->whereRaw('LOWER(a.ib_name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                    });
        }

        $recordsFiltered = $data->get()->count();
        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();

        $recordsTotal = $data->count();

        $results = [];
        $no      = 1;
        foreach ($data as $value) {

            $results[] = [
                'no' => $no++,
                'id' => $value->ib_idx,
                'name' => $value->ib_name,
                'qty' => $value->count_item,
                'note' => $value->ib_note ?? '',
                'created_at' => date('d-m-Y', strtotime($value->ib_create)),
            ];
        }  
        
        return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $results
            ]);

    
    }

    public function editBundle($idx){


        $items = DB::table('rb_item as a')
                ->select('a.it_idx','a.it_name','a.it_image','a.it_update','b.ic_count')
                ->join('rb_item_count as b','a.it_idx','=','b.ic_itidx')
                ->where('a.is_delete',0)
                ->get();

        $results = [];
        foreach ($items as $value) {
            $bundle    = DB::table('rb_item_bundle_detail')
                        ->select('ibd_itidx','ibd_count')
                        ->where('ibd_itidx', $value->it_idx)
                        ->where('ibd_ibidx', $idx)
                        ->where('is_delete',0)
                        ->first();

            $results[] = [
                'id' => $value->it_idx,
                'name' => $value->it_name,
                'image' => $value->it_image,
                'stok' => $value->ic_count,
                'qty' => $bundle->ibd_count ?? '',
                'ibd_itidx' => $bundle->ibd_itidx ?? ''
            ];
        }


        $itemBundle = ItemBundleModel::select('ib_idx','ib_name','ib_note')->where('ib_idx', $idx)->first();


        return view('inventori.bundle.edit', compact('results','itemBundle'));

    }

    public function updateBundle(Request $request, $idx){

        try {

            $validator = Validator::make($request->all(),[
                'iditem' => 'required',
                'name' => 'required',
            ]);

            
            $count['qty'] = $request->qty; 
            $iditem       = $request->iditem;
            
            #cek jika tidak ada item dipilih
            if(!$iditem) return redirect()->route('bundle-create')->with(['error' => 'Pilih setidaknya 1 Item!']);

            #cek jika input stok kosong
            $request_qty =  array_values(array_filter($request->qty));
            if(empty($request_qty)) return redirect()->route('bundle-create')->with(['error' => 'Qty tidak boleh kosong!']);
            $count['qty'] =  $request_qty;

            #update bundle
            $bundle = DB::table('rb_item_bundle')->where('ib_idx', $idx)->update([
                'ib_name' => $request->name,
                'ib_note' => $request->note,
                'ib_update' => date('Y-m-d H:i:s'),
                'ib_useridx' => Auth::user()->id
            ]);

            #hapus detail bundle sebelumnya
            DB::table('rb_item_bundle_detail')->where('ibd_ibidx', $idx)->delete();

            #replace detail_bundle
            foreach ($iditem as $key => $value) {
                #buat bundle jika  qty terisi
                if (isset( $count['qty'][$key])) {
                    $itemBundleDetail = new ItemBundleDetailModel();
                    $itemBundleDetail->ibd_ibidx = $idx;
                    $itemBundleDetail->ibd_itidx = $value;
                    $itemBundleDetail->ibd_count = $count['qty'][$key];
                    $itemBundleDetail->ibd_create = date('Y-m-d H:i:s');
                    $itemBundleDetail->save();
                }
            }

            DB::commit();
            return redirect()->route('bundle')->with(['success' => 'Bundel telah disimpan!']);

        } catch (\Exception $e) {
            DB::rollback();
            // return $e->getMessage();
            return redirect()->route('bundle')->with(['error' => 'Gagal disimpan!']);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $id = $request->id;

            DB::table('rb_item_bundle')->where('ib_idx', $id)->update(['is_delete' => 1]);
            DB::table('rb_item_bundle_detail')->where('ibd_ibidx', $id)->update(['is_delete' => 1]);
            
            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus bundel'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }
}
