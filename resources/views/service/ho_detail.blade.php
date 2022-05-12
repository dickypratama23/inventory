@extends('layouts.semantic')
@section('title', 'Informasi Barang Service')
@section('content')


<div class="ui breadcrumb">
    <a class="section">Home</a>
    <i class="right angle icon divider"></i>
    <a class="section">Service</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Service HO</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
    <div class="ui right aligned segment">
        <h2>List Barang Service HO (Terbit Docno)</h2>
    </div>
    <div class="ui green segment">
        <table id="tableDocno" class="ui striped selectable celled table fold-table" style="width:100%">
            <thead class="full-width">
                <tr>
                <th data-field="item">Barang</th>
                <th data-field="item">Toko</th>
                <th data-field="price">Detail</th>
                <th data-field="price">Terima</th>
                <th data-field="price">Kirim HO</th>
                <th data-field="action">Action</th>
                </tr>
            </thead>
            <tbody>
            @php $no = 1 @endphp
            @forelse ($HO_DETAIL as $row)                    
                <tr class="view" style="cursor: pointer;">


                    <td class="collapsing">
                    <div class="ui list">
                        <div class="item">
                        <div class="header">{{ $row->barang->name }}</div>
                        S/N : {{ $row->Serial_number }}
                        </div>
                    </div>
                    </td>

                    <td class="collapsing">{{ $row->transaksi->department->kdtk }}</td>

                    <td>
                    <ul class="ui list">
                        <li>Masalah : {{ $row->note }}</li>
                        <li>Pembawa : {{ $row->transaksi->pic }}</li>
                        <li>Invoice : {{ $row->transaksi->invoice }}</li>
                    </ul>
                    </td>

                    <td class="collapsing">{{ $row->transaksi->created_at->diffForHumans() }}</td>
                    <td class="collapsing">{{ $row->updated_at->diffForHumans() }}</td>
                    <td class="center aligned collapsing">
                    <a href="{{ route('service.store', $row->id) }}" class="ui blue icon button" data-content="Selesai Service HO" data-position="top center">
                        <i class="eject icon"></i>
                    </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="center aligned" colspan="7">Tidak ada data</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>










@endsection