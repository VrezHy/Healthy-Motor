@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kerusakan.css') }}">
@endpush

@section('content')

<div class="flex-1 min-h-0 flex flex-col">

  @if(session('success'))
  <div class="alert-success flex-shrink-0">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
    {{ session('success') }}
  </div>
  @endif

  <div class="mb-3 flex-shrink-0 flex items-center justify-between">
    <x-shared.button id="btnAddOpen" title="Tambah Data Solusi" variant="primary">+</x-shared.button>
     <div>
      <x-shared.search />
     </div>
  </div>

  <div class="rounded-2xl overflow-hidden shadow-sm flex-1 min-h-0 flex flex-col bg-white">

    <div class="table-header px-5 py-3 flex-shrink-0">
      <span class="font-bold text-sm">Tabel Data Penyakit</span>
    </div>

    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0">
      @if($kerusakans->isEmpty())
      <x-shared.empty-state-plus namaHalaman="kerusakan" />
      @else
      <table class="w-full text-sm border-collapse">
        <thead class="sticky top-0 bg-[#f0f1fa] z-10 shadow-[inset_0_-1px_0_rgba(0,0,0,0.05)]">
          <tr>
            <th class="text-left px-5 py-3 font-bold text-gray-700 w-16">ID</th>
            <th class="text-left px-5 py-3 font-bold text-gray-700">Data Kerusakan</th>
            <th class="text-right px-5 py-3 font-bold text-gray-700 w-44">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($kerusakans as $item)
          <tr class="border-b border-gray-100 hover:bg-gray-50/50">
            <td class="px-5 py-3 font-semibold text-gray-700">{{ $loop->iteration }}</td>
            <td class="px-5 py-3 text-gray-700">{{ $item->nama_kerusakan }}</td>
            <td class="px-5 py-3 text-right flex items-end justify-center flex-col gap-2">
              <form action="{{ route('admin.kerusakan.destroy', $item->id) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-hapus" onclick="bukaModalHapus(this)">Hapus</button>
              </form>
              <button type="button" class="btn-ubah"
                data-id="{{ $item->id }}"
                data-nama="{{ $item->nama_kerusakan }}"
                onclick="openEditModal(this.dataset.id, this.dataset.nama)">
                Ubah
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @endif
    </div>
  </div>
</div>


<div class="modal-overlay" id="modalTambah" data-show-modal="{{ $errors->has('nama_kerusakan') && !session('edit_id') ? 'true' : 'false' }}">
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


<div class="modal-overlay" id="modalUbah" data-show-modal="{{ $errors->has('nama_kerusakan') && session('edit_id') ? 'true' : 'false' }}"
  data-edit-id="{{ session('edit_id') }}"
  data-edit-nama="{{ old('nama_kerusakan') }}">
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

<div class="custom-modal" id="hapusModal">
  <div class="custom-modal-box">
    <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 20px;">Hapus Data Kerusakan Ini?</h2>
    <div class="custom-modal-actions">
      <button type="button" class="custom-btn" onclick="tutupModalHapus()">batal</button>
      <button type="button" class="custom-btn" id="confirmHapus" style="background-color: #dc2626;color: white;">hapus</button>
    </div>
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
  const modalTambah = document.getElementById('modalTambah');
  const modalUbah = document.getElementById('modalUbah');
  if (modalTambah.dataset.showModal === 'true') {
    openModal('modalTambah');
  }

  if (modalUbah.dataset.showModal === 'true') {
    const editId = modalUbah.dataset.editId;
    const editNama = modalUbah.dataset.editNama;
    openEditModal(editId, editNama);
  }

  let formHapus = null;

  function bukaModalHapus(button) {
    formHapus = button.closest('form');
    document.getElementById('hapusModal').style.display = 'flex';
  }

  function tutupModalHapus() {
    document.getElementById('hapusModal').style.display = 'none';
    formHapus = null;
  }

  document.getElementById('confirmHapus').addEventListener('click', function() {
    if (formHapus) {
      formHapus.submit();
    }
  });
</script>
@endpush