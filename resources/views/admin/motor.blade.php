@extends('layouts.app')

@section('content')

<style>
  .table-header {
    background: #7c84d0;
    color: white;
  }

  .data-motor-table th,
  .data-motor-table td {
    padding: 14px 18px;
    text-align: left;
    vertical-align: middle;
  }

  .data-motor-table th {
    font-weight: 800;
    color: #111827;
    background: #f0f1fa;
  }

  .data-motor-table tbody tr:nth-child(even) td {
    background: #f3f4fc;
  }

  .data-motor-table tbody tr:nth-child(odd) td {
    background: white;
  }

  .badge-pending {
    background: #fef3c7;
    color: #92400e;
    border-radius: 999px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 800;
    display: inline-block;
  }

  .badge-proses {
    background: #dbeafe;
    color: #1e40af;
    border-radius: 999px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 800;
    display: inline-block;
  }

  .badge-selesai {
    background: #d1fae5;
    color: #065f46;
    border-radius: 999px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 800;
    display: inline-block;
  }

  .info-banner {
    background: #e0e3f8;
    border: 1.5px solid #a5aee8;
    border-radius: 12px;
    padding: 12px 18px;
    font-size: 13px;
    font-weight: 600;
    color: #3730a3;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
</style>

<div class="info-banner">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z" />
  </svg>
  Data motor di bawah bersumber dari <strong>log riwayat mekanik</strong>. Untuk menambah / mengubah data, gunakan
  <a href="{{ route('mekanik.riwayat') }}" class="underline ml-1">Dashboard Mekanik</a>.
</div>

<div class="rounded-2xl overflow-hidden shadow-sm">
  <div class="table-header px-5 py-3">
    <span class="font-bold text-sm">Data Motor</span>
  </div>

  <div class="bg-white overflow-x-auto">
    @if($motors->isEmpty())
    <x-shared.empty-state />
    @else
    <table class="data-motor-table w-full text-sm">
      <thead>
        <tr>
          <th style="width: 70px;">No</th>
          <th>Nama Pelanggan</th>
          <th>Nomor Polisi</th>
          <th>Kerusakan</th>
          <th style="width: 140px;">Status</th>
        </tr>
      </thead>

      <tbody>

        @foreach($motors as $item)
        <tr>
          <td class="font-semibold text-gray-500">{{ $loop->iteration }}</td>

          <td class="font-semibold text-gray-800">
            {{ $item->nama_pelanggan ?? '-' }}
          </td>

          <td class="font-mono text-gray-700">
            {{ $item->nomor_polisi ?? '-' }}
          </td>

          <td class="text-gray-700">
            {{ $item->nama_kerusakan ?? '-' }}
          </td>

          <td>
            @if($item->status === 'pending')
            <span class="badge-pending">Pending</span>
            @elseif($item->status === 'proses')
            <span class="badge-proses">Proses</span>
            @elseif($item->status === 'selesai')
            <span class="badge-selesai">Selesai</span>
            @else
            <span class="badge-pending">{{ ucfirst($item->status ?? 'Pending') }}</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

  </div>

  @endif
</div>

@endsection