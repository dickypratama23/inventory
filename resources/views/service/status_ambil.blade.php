@extends('layouts.semantic')
@section('title', 'Status Pengambilan')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Service</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Status Pengambilan</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>BELUM AMBIL</h2>
  </div>
  <div class="ui green segment">
    <table id="example" class="ui striped selectable celled table fold-table serviceExport" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="no">No</th>
          <th data-field="item">Barang</th>
          {{-- <th data-field="item">Serial Number</th> --}}
          <th data-field="item">Toko</th>
          <th data-field="price">Penggantian</th>
          <th data-field="price">Masuk</th>
          <th data-field="price">Selesai</th>
          <th data-field="price">Lama Service</th>
          <th data-field="price">Do</th>
        </tr>
      </thead>
      <tbody>

        @php $no = 1 @endphp
        @forelse ($service as $row)
        @foreach ($service_in as $row_in)
        @if($row_in->invoice == $row->inv_relation)
        <tr class="view" style="cursor: pointer;">
          <td class="center aligned">{{ $no++ }}</>
          <td>
            <ul class="ui list">
              <li> <strong>{{ $row->barang->name }} ({{ $row->id }}) @foreach ($row_in->detail as $detail)
                  @if($detail->cads == 1) <div class="ui blue horizontal label">Cad</div> @endif @endforeach</strong>
                @if($row->department->kdtk == 'EDPS')
                @else
                <ul class="ui list">
                  <li>@foreach ($row_in->detail as $detail) @if($detail->barang_id == $row->barang_id)
                    {{ $detail->Serial_number }} <strong>(TOKO)</strong> @endif @endforeach</li>
                  <li>@foreach ($row_in->detail as $detail) @foreach ($cad as $x) @if($detail->barang->kode ==
                    substr($x->kode,4,7) && $row_in->department_id == $x->department_id ) {{ $x->mac }} <strong>(PINJAM
                      TOKO)</strong> @endif @endforeach &nbsp; @endforeach</li>
                </ul>
                @endif
              </li>
            </ul>




          </td>
          {{-- <td>@foreach ($row_in->detail as $detail) @if($detail->barang_id == $row->barang_id) {{ $detail->Serial_number }}
          @endif @endforeach </td> --}}
          {{-- <td>@foreach ($row_in->detail as $detail) {{ $detail }} @endforeach </td> --}}
          <td>
            {{ $row->department->kdtk }}
          </td>
          <td class="">
            @if($row->detail->count() > 0)
            @foreach ($row->detail as $detail)
            <ul class="ui list">
              <li>{{ $detail->barang->name }} ({{ $detail->qty }}X)</li>
            </ul>
            @endforeach
            @endif


          </td>
          <td>{{ $row_in->created_at->diffForHumans() }}</td>
          <td>{{ $row->created_at->diffForHumans() }}</td>
          <td class="center aligned">
            {{ str_replace('after', '', $row->created_at->diffForHumans($row_in->created_at)) }}</td>
          <td class="center aligned">
            <button data-id="{{ $row->id }}" class="ui blue icon button ambil" data-content="Ambil"
              data-position="top center">
              <i class="dolly icon"></i>
            </button>
          </td>
        </tr>
        @endif
        @endforeach
        @empty
        <tr>
          <td class="center aligned" colspan="9">Tidak ada data</td>
        </tr>

        @endforelse



      </tbody>

    </table>
  </div>
</div>










<div class="ui small modal">
  <i class="close icon"></i>
  <div class="header">
    Data Pengambil Barang
  </div>
  <div class="content">
    <div class="description">
      <form class="ui form" action="{{ url('/service/ambil/selesai') }}" method="post">
        {{ csrf_field() }}
        <div class="field">
          <input type="hidden" id="invoice" name="invoice">
          <label>Nama</label>
          <div class="ui karyawan search selection dropdown">
            <input type="hidden" name="personil">
            <i class="dropdown icon"></i>
            <div class="default text">Personil Toko</div>
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