@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />

@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
            @include('layouts.message')
         <div class="row">
                        <div class="col-xl-12 mx-auto">
                            <div class="card border-top border-0 border-4 border-primary">
                                <div class="card-body p-5">
                                    <div class="card-title d-flex align-items-center">
                                        <h5 class="mb-0 text-primary"> Tambah Umrah Baru</h5>
                                    </div>
                                    <hr>
                                    <form class="row g-3" action="{{ route('umrah.store') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        {{-- <div class="col-md-6">
                                            <label class="form-label">Tourcode</label>
                                            <input type="text" name="tourcode" class="form-control @error ('tourcode') is-invalid @enderror" required placeholder="tourcode here...">
                                            @error('tourcode')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div> --}}
                                        <div class="col-md-6">
                                            <label class="form-label">Tahun</label>
                                            <select id="tahun" name="tahun" class="single-select @error ('kuisioner') is-invalid @enderror" >
                                                <option>-Pilih tahun-</option>
                                                @foreach ($ArrTahun as $tahun)
                                                    <option value="{{$tahun}}">{{$tahun}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tourcode</label>
                                            <select id="tourcode" class="single-select @error ('kuisioner') is-invalid @enderror" name="tourcode" required >
                                            </select>
                                            @error('kuisioner')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tanggal</label>
                                            <input type="text" name="dates" class="form-control @error ('dates') is-invalid @enderror"  required>
                                            @error('dates')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Jumlah Keberangkatan Jamaah</label>
                                            <input type="number" name="count" class="form-control @error ('tourcode') is-invalid @enderror number" required>
                                            @error('count')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                       
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-sm btn-primary px-5">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> 
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/umrah-create.js') }}"></script>   
<script src="{{ asset('js/number-only.js') }}"></script>   
@endpush