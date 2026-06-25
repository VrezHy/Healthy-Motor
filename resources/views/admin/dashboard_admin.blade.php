@extends('layouts.app')

@section('content')
<div class="rounded-2xl overflow-hidden shadow-sm flex flex-col bg-white border border-gray-100">
    <div class="px-5 py-4 border-b border-gray-100 bg-white">
        <span class="font-bold text-gray-800 text-base">Tabel Data Motor (Selesai / Done)</span>
    </div>

    <div class="overflow-x-auto overflow-y-auto min-h-0">
        <table class="w-full text-sm border-collapse bg-white">
            <thead class="sticky top-0 bg-[#f0f1fa] z-10 shadow-[inset_0_-1px_0_rgba(0,0,0,0.05)]">
                <tr>
                    <th class="text-left px-5 py-3 font-bold text-gray-700 w-12">No</th>
                    <th class="text-left px-5 py-3 font-bold text-gray-700">Nama Pelanggan</th>
                    <th class="text-left px-5 py-3 font-bold text-gray-700">Nomor Polisi</th>
                    <th class="text-left px-5 py-3 font-bold text-gray-700">Kerusakan</th>
                    <th class="text-left px-5 py-3 font-bold text-gray-700">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($dataMotor as $index => $motor)
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                        <td class="px-5 py-3 font-semibold text-gray-700">{{ $index + 1 }}</td>
                        <td class="px-5 py-3 text-gray-700 font-medium">{{ $motor->nama_pelanggan ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-700 font-mono font-semibold">{{ $motor->nomor_polisi ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-700 font-medium">{{ $motor->nama_kerusakan ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full uppercase tracking-wider">
                                {{ $motor->status ?? 'Done' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-400 font-medium">
                            Belum ada motor dengan status Done.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
