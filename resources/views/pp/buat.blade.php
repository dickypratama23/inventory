@extends('layouts.semantic')
@section('title', 'Transaksi In')
@section('content')

<div class="ui breadcrumb">
	<a class="section">Home</a>
	<i class="right angle icon divider"></i>
	<a class="section">PP Cab/HO</a>
	<i class="right angle icon divider"></i>
  <div class="active section">Buat</div>
</div>
<div class="ui divider"></div>

<div class="ui  segments">
	<div class="ui segment">

		<form class="ui form" action="{{ route('pp.store') }}" method="post">
			{{ csrf_field() }}
			<div class="field">
				<label>No. PP / Memo</label>
				<input type="text" name="no_pp" value="{{ $NO_PP }}">
			</div>

			<div class="field">
				<label>Jenis PP/Memo</label>
				<select class="ui search dropdown" name="jenis_pp">
					<option value="">Pilih Jenis PP/Memo</option>
					<option value="cabang">Cabang</option>
					<option value="ho">HO</option>
				</select>
			</div>

			<table class="ui compact celled table">
				<thead>
					<tr>
							<th class="single line">No</th>
							<th class="eleven wide">Barang</th>
							<th class="two wide">Unit</th>
							
							<th class="two wide">Action</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
				<tfoot>
					<tr>
						<th></th>
						<th>
								<div class="field">
										<div class="ui fluid category search barang">
										<div class="ui icon input">
												<input class="prompt" type="text" placeholder="Pilih Barang">
												<i class="search icon"></i>
										</div>
										<div class="results"></div>
										</div>
										{{-- <input type="hidden" name="_method" value="PUT" class="form-control"> --}}
										<input type="hidden" name="barang_id" class="barang_id" readonly>
								</div>
						</th>

						<th>
								<div class="field">
										<input type="number" min="1" value="1" name="qty">
								</div>
						</th>

						

						<th>
								<button class="fluid ui teal button" type="submit">Tambah</button>
						</th>
					</tr>
				</tfoot>
			</table>

			

			{{-- <button class="ui blue button" type="submit">Next</button> --}}
		</form>

	</div>
</div>

@endsection