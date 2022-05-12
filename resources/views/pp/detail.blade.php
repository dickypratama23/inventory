@extends('layouts.semantic')
@section('title', 'Detail Permintaan Pembelian')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">PP Cab/HO</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Detail Permintaan Pembelian</div>
</div>
<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>STATUS PERMINTAAN PEMBELIAN</h2>
  </div>

  <div class="ui green segment">
    <table id="example" class="ui celled table responsive nowrap unstackable" style="width:100%">
      
      <thead class="full-width">
        <tr>
          <th class="center aligned">#</th>
          <th>Barang</th>
          <th>Qty</th>
          <th>Minus</th>
          <th>Note</th>
          <th>Updated</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
      @php $no = 1 @endphp
      @foreach ($det_pp as $row)
        <tr>
          <td class="center aligned collapsing">{{ $no++ }}</td>
          <td class="collapsing">[{{ $row->barang->kode }}] {{ $row->barang->name }}</td>
          <td class="center aligned collapsing">{{ $row->qty }}</td>
          <td class="center aligned collapsing">{{ $row->minus }}</td>
          <td>{{ $row->note }}</td>
          <td class="collapsing">{{ $row->updated_at->format('Y-m-d') }}</td>
          <td class="center aligned collapsing">
              <button data-nomor_pp="{{ $row->nomor_pp }}" data-barang_id="{{ $row->barang_id }}" data-nama_barang="{{ $row->barang->name }}" class="ui teal icon button upd_status" data-content="Update Status" data-position="top center">
                <i class="cogs icon"></i>
              </button>
          </td>
        </tr>
      @endforeach
      </tbody>

    </table>
  </div>
</div>


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