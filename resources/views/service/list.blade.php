@extends('layouts.semantic')
@section('title', 'Informasi Barang Service')
@section('content')


<div class="ui breadcrumb">
    <a class="section">Home</a>
    <i class="right angle icon divider"></i>
    <a class="section">Service</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Informasi Barang Service</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
    <div class="ui right aligned segment">
        <h2>List Barang Service</h2>
    </div>
    <div class="ui green segment">
        <table id="example" class="ui striped selectable celled table fold-table nowrap serviceExport"
            style="width:100%">
            <thead class="full-width">
                <tr>
                    {{-- <th data-field="no">Status</th> --}}
                    <th data-field="action">No.</th>
                    <th data-field="item">Barang</th>
                    <th data-field="item">SN / MAC</th>
                    <th data-field="item">Toko</th>
                    <th style="display: none" data-field="item">Toko</th>
                    <th data-field="price">Detail</th>
                    <th data-field="price">Charged (Rp.)</th>
                    <th data-field="price">Note Servicer</th>
                    <th data-field="price">Terima</th>
                    <th data-field="action">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1 @endphp
                @forelse ($LIST as $row)
                <tr class="view" style="cursor: pointer;">
                    <td class="center aligned collapsing">{{ $no++ }}</td>
                    <td class="collapsing">
                        ({{ $row->id }}) {{ $row->barang->name }} @if($row->cads == 1) <div
                            class="ui blue horizontal label">Cad</div>
                        @endif</td>
                    <td class="collapsing">
                        <ul class="ui list">
                            <li>{{ $row->Serial_number }}</li>
                            @if($row->sn_assembly)
                            <li>{{ $row->sn_assembly }}</li>
                            @endif
                        </ul>
                    </td>
                    <td class="collapsing">{{ $row->transaksi->department->kdtk }}</td>
                    <td style="display: none">{{ $row->transaksi->department->name }}</td>
                    <td>
                        <ul class="ui list">
                            <li>Masalah : {{ $row->note }}</li>
                            <li>Pembawa : {{ $row->transaksi->pic }}</li>
                            <li>Invoice : {{ $row->transaksi->invoice }}</li>
                        </ul>
                    </td>
                    <td class="right aligned">

                        @foreach ($rupiah as $rp)
                        @if($rp['id'] == $row->id)
                        <div class="detBiaya" data-detail="{{ $rp['detail'] ? 1 : 0 }}">
                            {{ number_format($rp['rupiah']) }}
                        </div>
                        @endif
                        @endforeach

                    </td>
                    <td class="aligned collapsing">
                        @if($row->note_servicer)
                        {{ $row->note_servicer }}
                        @endif
                    </td>
                    <td class="collapsing">{{ $row->transaksi->created_at->diffForHumans() }}</td>
                    <td class="center aligned collapsing">
                        @if(session('role') != 4)
                        @if($row->approve == 0)
                        <button class="ui teal icon button note" data-id="{{ $row->id }}"
                            data-note="{{ $row->note_servicer }}" data-content="Beri Keterangan"
                            data-position="top center">
                            <i class="sticky note icon"></i>
                        </button>

                        <a href="{{ route('service.store', $row->id) }}" class="ui blue icon button"
                            data-content="Service" data-position="top center">
                            <i class="eject icon"></i>
                        </a>

                        <a href="{{ route('service.ho', $row->id) }}" class="ui violet icon button"
                            data-content="Kirim HO" data-position="top center">
                            <i class="truck icon"></i>
                        </a>
                        @elseif($row->approve == 1)
                        <a href="{{ route('service.selesai', $row->id) }}" class="ui teal icon button"
                            data-content="Ambil Toko" data-position="top center">
                            <i class="thumbs up icon"></i>
                        </a>
                        @endif
                        @endif
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


<div class="ui small modal">
    <i class="close icon"></i>
    <div class="header">
        Beri Keterangan
    </div>
    <div class="content">
        <form class="ui form" action="{{ route('service.note') }}" method="post">
            {{ csrf_field() }}
            <div class="ui form">
                <div class="field">
                    <input type="hidden" id="invoice" name="invoice">
                    <label>Keterangan</label>
                    <textarea id="note" name="note" rows="2"></textarea>
                </div>
            </div>
    </div>
    <div class="actions">
        <button type="submit" class="ui positive right labeled icon button">
            Simpan
            <i class="checkmark icon"></i>
        </button>
    </div>
    </form>
</div>

<div class="ui small modal modalDetBiaya">
    <i class="close icon"></i>
    <div class="header">
        Detail Biaya Perbaikan Sebelumnya
    </div>
    <div class="content">

    </div>
    <div class="actions">
        <div class="ui black deny button">
            Close
        </div>
    </div>
</div>


<script>
    window.addEventListener('load', function () {
        $(".detBiaya").click(function(){
            $('.ui.modal.modalDetBiaya').modal('show');
            
            alert($(this).data("detail"));
        });
    });
</script>





@endsection