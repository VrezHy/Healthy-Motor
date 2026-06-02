@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/motor.css') }}">
@endpush

@section('content')

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