@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />

@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
        @include('layouts.message')
				<h6 class="mb-0 ">Petugas Umrah</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-2 mt-1">
								<div class="mb-4">
									<button onclick="allMonth()"  class="btn btn-sm btn-outline-secondary ml-1" title="Bulan">Tampilkan Semua</button>
									{{-- <button id="dates" class="datepicker filter" title="Bulan">Kalender</button> --}}

									{{-- <div class="dropdown">
									<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
										Bulan
									</button>
									<ul class="row dropdown-menu" aria-labelledby="dropdownMenuButton1">
										<li>
											<button id="dates" class="datepicker filter" title="Bulan">Kalender</button>
										</li>
										<li>
											<button onclick="allMonth()"  class="ml-1" title="Bulan">Semua</button>
										</li>
									</ul>
								</div>
								 --}}
							</div>
							</div>
							<div class="col-md-2 mt-1">
								<div class="mb-4">
									<input id="dates" class="col-md-12 btn btn-sm btn-outline-secondary datepicker filter" placeholder="Pilih Bulan"/>
							</div>
							</div>
							<div class="col-md-3">
									<select id="pembimbing" class="single-select pembimbing @error ('pembimbing') is-invalid @enderror" name="pembimbing"></select>
							</div>
							<div class="col-md-3">
								<select id="tourcode" class="single-select tourcode @error ('tourcode') is-invalid @enderror" name="tourcode"></select>
							</div>
						</div>
						<hr>
						<div class="table-responsive">
							<table id="data" class="table table-hover" style="width:100%">
								<thead>
									<tr>
                                        <th>Tourcode</th>
                                        <th>Pembimbing / Asisten</th>
                                        <th>Opsi</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
    </div>
</div>
@endsection
@push('scripts')

<script src="{{asset('/vendor/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('/js/activitas-umrah-petugas.js') }}"></script>
@endpush