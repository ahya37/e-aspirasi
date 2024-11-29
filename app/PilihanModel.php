<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PilihanModel extends Model
{
    protected $table = 'pilihan';
    protected $guarded = [];

    public function getKategoriByUmrahId($id)
    {
        $sql = "SELECT a.id,  a.nama as pilihan, COUNT(DISTINCT (b.id)) as jumlah  from kategori_pilihan_jawaban as a
                join pilihan as b on a.id = b.kategori_pilihan_jawaban_id
                left join jawaban_kuisioner_umrah as c on c.pilihan_id = b.id where c.umrah_id = $id
                group by a.id, a.nama";
                
        return DB::select($sql);

    }

    public function getPilihanJawabanByPertanyaanId($id){

        $sql = DB::table('pilihan as a')
               ->leftJoin('jawaban_kuisioner_umrah as b','b.pilihan_id','=','a.id')
               ->where('a.pertanyaan_id', $id)
               ->select('a.isi as jawaban', DB::raw('count(b.jawaban) as jumlah'))
               ->distinct()
               ->groupBy('a.isi')
               ->get();
        
        return $sql;

    }

    
}
