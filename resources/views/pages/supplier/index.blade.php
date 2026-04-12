@extends('layouts.main')

@section('js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@include('partials.navsupp')

@section('content')

<div class="space-y-6">

    <!-- 📍 Breadcrumb -->

    <!-- 🧾 Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">Suppliers</h2>

        <button
            onclick="toggleModal(true)"
            class="rounded-md bg-green-800 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-green-700 focus:shadow-none active:bg-green-700 hover:bg-green-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
            Add Supplier
        </button>
    </div>
    <div class="flex justify-between">
        <div class="flex-item">
            {{-- search button --}}
            <div class="w-full bg-white max-w-sm min-w-[200px]">
                <div class="relative">
                    <input
                    class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md pl-3 pr-28 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow"
                    placeholder="UI Kits, Dashboards..."
                    />
                    <button
                    class="absolute top-1 right-1 flex items-center rounded bg-slate-800 py-1 px-2.5 border border-transparent text-center text-sm text-white transition-all shadow-sm hover:shadow focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                    type="button"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-2">
                        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                    </svg>

                    Search
                    </button>
                </div>
            </div>
        </div>
        {{-- export button --}}
        <div class="flex-item">
            <div class="mx-2">
                <button onclick="toggleExport(true)" class="btn btn-sm rounded-lg mx-2 p-2 bg-white"><span class="material-icons">upload</span>Export</button>
            </div>
        </div>
    </div>
    <!-- 📊 Table -->
    <div class="card bg-white shadow rounded-lg overflow-hidden border-gray-600">
        <div class="card-body">
          <table class="w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Total Layups</th>
                <th class="p-3 text-left">Created At</th>
                <th class="p-3 text-left">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($suppliers as $key => $t)
              <tr class="border-t hover:bg-slate-100">
                <td class="p-3">{{ $t['name'] }}</td>
                <td class="p-3">{{ $t['layups_count'] }}</td>
                <td class="p-3">{{ $t['created_at'] }}</td>
                <td class="p-3 space-x-2">
                  <a href="/supplier/{{ $t['id'] }}" class="btn btn-sm text-green-600 hover:underline">View</a>
                  <a href="/supplier/{{ $t['id'] }}/edit" class="btn btn-sm text-blue-600 hover:underline">Edit</a>
                  <button data-id="{{ $t['id'] }}" class="btn btn-sm text-red-600 hover:underline btnDelete">Delete</button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer flex items-center">
            {{ $suppliers->links() }}
        </div>

    </div>
    {{-- Export Modal --}}
    <div id="exportModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 relative">
            <!-- Close Button -->
            <button
                onclick="toggleExport(false)"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl"
            >
                ✕
            </button>

            <!-- Title -->
            <h2 class="text-xl font-semibold mb-4">Export Supplier</h2>
            <!-- Form -->
            <form action="{{ route('supplier.export') }}" method="GET" class="space-y-4">
                <div class="card card-body">
                    @csrf
                    <div class="w-full max-w-sm min-w-[200px]">
                        <div class="relative">
                            <h3 class="p-4 my-1">Choose Suppliers : </h3>
                            <select
                            name="mode"
                                class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                                <option value="0">Current</option>
                                <option value="1">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="max-w-sm min-w-sm w-fit my-2 p-2">
                        <div class="relative">
                            <button type="submit" class="btn p-3 w-full shadow-lg border-neutral-300 rounded-md"><span class="material-icons">upload</span>
                                Export</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('components.validator')
@include('pages.supplier.form')
<script>
function toggleModal(show) {
    const modal = document.getElementById('supplierModal')

    if (show) {
        modal.classList.remove('hidden')
        modal.classList.add('flex')
    } else {
        modal.classList.add('hidden')
        modal.classList.remove('flex')
    }
}
function toggleExport(show) {
    const modal = document.getElementById('exportModal')

    if (show) {
        modal.classList.remove('hidden')
        modal.classList.add('flex')
    } else {
        modal.classList.add('hidden')
        modal.classList.remove('flex')
    }
}
document.getElementById('supplierModal').addEventListener('click', function(e) {
    if (e.target === this) toggleModal(false)
});

document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) toggleExport(false)
});

$('.btnDelete').click(function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const id = $(this).data('id');
            $.ajax({
                type:"delete",
                url:"/supplier/"+id,
                data:{
                    _token: "{{ csrf_token() }}",
                },
                success:function(data){
                    Swal.fire({
                    icon: data.status,
                    title: data.title,
                    text: data.message,
                    timer: 1200
                    });
                    // table.draw();
                    setTimeout(() => {
                        window.location.href = window.location.href;
                    }, 300);
                },error:function(data){
                    var js = data.responseJSON;
                    Swal.fire({
                        icon: 'error',
                        title: js.exception,
                        text: js.message,
                        timer: 1200
                    });
                    setTimeout(() => {
                        window.location.href = window.location.href;
                    }, 300);
                }
            });
        }
    })
});

$('#exportBtn').click(function () {
    $.get('/api/suppliers', function (suppliers) {

        let html = '<form id="exportForm">';
        suppliers.forEach(s => {
            html += `
                <label class="block">
                    <input type="checkbox" name="supplier_ids[]" value="${s.id}">
                    ${s.name}
                </label>
            `;
        });
        html += '</form>';

        Swal.fire({
            title: 'Select Suppliers',
            html: html,
            showCancelButton: true,
            confirmButtonText: 'Export',
            preConfirm: () => {
                let data = $('#exportForm').serialize();
                window.location = '/suppliers/export?' + data;
            }
        });
    });
});
</script>
@endsection
