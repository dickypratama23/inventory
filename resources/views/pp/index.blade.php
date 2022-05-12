@extends('layouts.semantic')
@section('title', 'Permintaan Pembelian')
@section('content')

<div class="ui breadcrumb">
	<a class="section">Home</a>
	<i class="right angle icon divider"></i>
	<a class="section">PP Cab/HO</a>
	<i class="right angle icon divider"></i>
	<div class="active section">Status</div>
</div>
<div class="ui divider"></div>

<div class="ui stacked segments">
	<div class="ui right aligned segment">
		<h2>STATUS PERMINTAAN PEMBELIAN PER BARANG</h2>
	</div>

	<div class="ui green segment">
		<table id="example" class="ui celled table responsive nowrap unstackable lppExport" style="width:100%">

			<thead class="full-width">
				<tr>
					<th class="center aligned">#</th>
					<th>No. PP</th>
					<th>Barang</th>
					<th>Qty</th>
					<th>Minus</th>
					<th>Note</th>
					<th>Update</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>
				@php $no = 1 @endphp
				@foreach ($pps as $detail)
				<tr>
					<td class="center aligned collapsing">{{ $no++ }}</td>
					<td class="center aligned collapsing">
						<div
							class="ui @if($detail->realisasi == '0000-00-00 00:00:00') orange @else @if($detail->minus > 0) orange @else green @endif @endif  horizontal label">
							{{ $detail->nomor_pp }}</div>
					</td>
					<td class="collapsing">{{ $detail->barang->name }}</td>
					<td class="center aligned collapsing">{{ $detail->qty }}</td>
					<td class="center aligned collapsing">{{ $detail->minus }}</td>
					<td class="aligned">{{ $detail->note }}</td>
					<td class="center aligned collapsing">{{ $detail->updated_at->format('Y-m-d') }}</td>
					<td class="center aligned collapsing">
						<button data-nomor_pp="{{ $detail->nomor_pp }}" data-barang_id="{{ $detail->barang_id }}"
							data-nama_barang="{{ $detail->barang->name }}" class="ui teal icon button upd_status"
							data-content="Update Status" data-position="top center">
							<i class="cogs icon"></i>
						</button>
					</td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

<form action="{{ route('exp.baru') }}" method="POST">
	<button type="submit" class="ui teal icon button">
		Export Ke Yang Baru
	</button>
</form>



<div class="ui stacked segments">
	<div class="ui right aligned segment">
		<h2>STATUS PERMINTAAN PEMBELIAN PER NOMOR PP/MEMO</h2>
	</div>

	<div class="ui green segment">
		<table class="ui celled table responsive nowrap unstackable lppExport" style="width:100%">

			<thead class="full-width">
				<tr>
					<th class="center aligned">#</th>
					<th>Status</th>
					<th data-field="item">No. PP/MEMO</th>
					<th data-field="item">Item</th>
					<th>Buat</th>
					<th data-field="price">BM/DBM</th>
					<th data-field="price">GA</th>
					<th data-field="price">Realisasi</th>
				</tr>
			</thead>

			<tbody>
				@php $no = 1 @endphp
				@foreach ($pp as $detail)
				<tr class="@if($detail->terpenuhi == $detail->total && $detail->ttl_minus == 0) positive @else warning @endif">
					<td class="center aligned collapsing">{{ $no++ }}</td>
					<td class="collapsing">
						@if($detail->terpenuhi == $detail->total && $detail->ttl_minus == 0)
						<i class="check circle icon"></i> Fulfilled
						@else
						<i class="attention icon"></i> Progress
						@endif
					</td>
					<td class="center aligned collapsing">
						<a class="item" href="{{ url('/pp/detail/' . $detail->nomor_pp) }}">
							<div
								class="ui @if($detail->terpenuhi == $detail->total && $detail->ttl_minus == 0) green @else orange @endif horizontal label">
								{{ $detail->nomor_pp }}</div>
						</a>
					</td>
					<td> {{ $detail->terpenuhi }}/{{ $detail->total }} Item</td>
					<td class="center aligned collapsing">{{ $detail->created_at->format('Y-m-d') }}</td>
					<td class="center aligned collapsing">{{ $detail->serah1 }}</td>
					<td class="center aligned collapsing">{{ $detail->serah2 }}</td>
					<td class="center aligned collapsing">{{ $detail->realisasi }}</td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>
<br>


<div class="ui small modal">
	<i class="close icon"></i>
	<div class="header">
		Update Status PP/Memo
	</div>
	<div class="content">
		<div class="description">
			<form class="ui form" action="{{ url('/pp/update') }}" method="post">
				{{ csrf_field() }}
				<div class="field">
					<div class="ui right labeled left icon input">
						<i class="clipboard icon"></i>
						<input type="text" id="nomor_pp" name="nomor_pp" readonly>
						<a class="ui tag label">
							Nomor PP/Memo
						</a>
					</div>
				</div>

				<div class="field">
					<div class="ui right labeled left icon input">
						<i class="hashtag icon"></i>
						<input type="hidden" id="barang_id" name="barang_id" readonly>
						<input type="text" id="nama_barang" readonly>
						<a class="ui tag label">
							Nama Barang
						</a>
					</div>
				</div>

				<div class="field">
					<div class="ui right labeled left icon input">
						<i class="minus icon"></i>
						<input type="number" min="0" value="0" name="minus">
						<a class="ui tag label">
							Minus
						</a>
					</div>
				</div>

				<div class="field">
					<div class="ui right labeled left icon input">
						<i class="sticky note icon"></i>
						<input type="text" name="note">
						<a class="ui tag label">
							Note
						</a>
					</div>
				</div>

		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Cancel
		</div>
		<button class="ui positive right labeled icon button" type="submit"><i class="checkmark icon"></i>Submit</button>
	</div>
	</form>
</div>
@endsection