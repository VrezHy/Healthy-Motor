@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/kerusakan.css') }}">
@endpush

@section('content')


{{-- Alert sukses --}}
@if(session('success'))
<div class="alert-success">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
  </svg>
  {{ session('success') }}
</div>
@endif

{{-- Tombol tambah + --}}
<div class="mb-3">
  <x-shared.button id="btnAddOpen" title="Tambah Data Solusi" variant="primary" >+</x-shared.button>
  <!-- <button class="btn-add" id="btnAddOpen" title="Tambah Data Solusi">+</button> -->
</div>

{{-- Tabel --}}
<div class="rounded-2xl overflow-hidden shadow-sm">
  <div class="table-header px-5 py-3">
    <span class="font-bold text-sm">Tabel Data Penyakit</span>
  </div>
  <div class="bg-white overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr style="background:#f0f1fa;">
          <th class="text-left px-5 py-3 font-bold text-gray-700 w-16">ID</th>
          <th class="text-left px-5 py-3 font-bold text-gray-700">Data Kerusakan</th>
          <th class="text-right px-5 py-3 font-bold text-gray-700 w-44">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kerusakans as $item)
        <tr>
          <td class="px-5 py-3 font-semibold text-gray-700">{{ $loop->iteration }}</td>
          <td class="px-5 py-3 text-gray-700">{{ $item->nama_kerusakan }}</td>
          <td class="px-5 py-3 text-right flex items-end shrink-0justify-center flex-col gap-2">
            {{-- Tombol Hapus --}}
            <form action="{{ route('admin.kerusakan.destroy', $item->id) }}" method="POST"
              class="inline-block"
              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-hapus">Hapus</button>
            </form>

            {{-- Tombol Ubah --}}
            <button type="button" class="btn-ubah"
              data-id="{{ $item->id }}"
              data-nama="{{ $item->nama_kerusakan }}"
              onclick="openEditModal(this.dataset.id, this.dataset.nama)">
              Ubah
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="3" class="px-5 py-8 text-center text-gray-400 font-medium">
            Belum ada data kerusakan. Klik <strong>+</strong> untuk menambahkan.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>


{{-- ===== MODAL TAMBAH ===== --}}
<div class="modal-overlay" id="modalTambah">
  <div class="modal-box">
    <button class="btn-close-modal" onclick="closeModal('modalTambah')">✕</button>
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-8">
      Masukkan Kerusakan pada Kendaraan?
    </h2>

    <form action="{{ route('admin.kerusakan.store') }}" method="POST" id="formTambah">
      @csrf
      <div>
        <input
          type="text"
          name="nama_kerusakan"
          id="inputTambah"
          class="modal-input {{ $errors->has('nama_kerusakan') && !session('edit_id') ? 'is-invalid' : '' }}"
          value="{{ old('nama_kerusakan') }}"
          placeholder="Nama kerusakan..."
          autocomplete="off" />
        @error('nama_kerusakan')
        @if(!session('edit_id'))
        <p class="text-red-500 text-sm font-bold mt-2">{{ $message }}</p>
        @endif
        @enderror
      </div>

      <div class="flex justify-end mt-6">
        <button type="submit" class="btn-save">save</button>
      </div>
    </form>
  </div>
</div>


{{-- ===== MODAL UBAH ===== --}}
<div class="modal-overlay" id="modalUbah">
  <div class="modal-box">
    <button class="btn-close-modal" onclick="closeModal('modalUbah')">✕</button>
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-8">
      Ubah Kerusakan pada Kendaraan?
    </h2>

    <form id="formUbah" method="POST">
      @csrf
      @method('PUT')
      <div>
        <input
          type="text"
          name="nama_kerusakan"
          id="inputUbah"
          class="modal-input {{ $errors->has('nama_kerusakan') && session('edit_id') ? 'is-invalid' : '' }}"
          value="{{ session('edit_id') ? old('nama_kerusakan') : '' }}"
          placeholder="Nama kerusakan..."
          autocomplete="off" />
        @error('nama_kerusakan')
        @if(session('edit_id'))
        <p class="text-red-500 text-sm font-bold mt-2">{{ $message }}</p>
        @endif
        @enderror
      </div>

      <div class="flex justify-end mt-6">
        <button type="submit" class="btn-save">save</button>
      </div>
    </form>
  </div>
</div>

@endsection


@push('scripts')
<script>
  // ===== MODAL HELPERS =====
  function openModal(id) {
    const overlay = document.getElementById(id);
    overlay.classList.add('active');
    setTimeout(() => {
      const input = overlay.querySelector('input[name="nama_kerusakan"]');
      if (input) input.focus();
    }, 200);
  }

  function closeModal(id) {
    document.getElementById(id).classList.remove('active');
  }

  // Klik di luar box → tutup
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
      if (e.target === this) {
        this.classList.remove('active');
      }
    });
  });

  // ESC → tutup semua modal
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
    }
  });

  // ===== TOMBOL + → buka modal tambah =====
  document.getElementById('btnAddOpen').addEventListener('click', function() {
    openModal('modalTambah');
  });

  // ===== UBAH → isi form ubah lalu buka modal =====
  function openEditModal(id, nama) {
    const form = document.getElementById('formUbah');
    const input = document.getElementById('inputUbah');

    // Set action URL dengan ID
    form.action = '/admin/kerusakan/' + id;
    input.value = nama;

    openModal('modalUbah');
  }

  // ===== AUTO-BUKA MODAL jika ada error validasi =====
  @if($errors -> has('nama_kerusakan') && !session('edit_id'))
  openModal('modalTambah');
  @endif

  @if($errors -> has('nama_kerusakan') && session('edit_id'))
  openEditModal({
    {
      session('edit_id')
    }
  }, @json(old('nama_kerusakan')));
  @endif
</script>
@endpush