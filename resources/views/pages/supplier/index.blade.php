@extends('layouts.main')

@include('partials.navsupp')

@section('content')

<div class="space-y-6">

    <!-- 📍 Breadcrumb -->
    {{-- <x-breadcrumb :items="[
        // ['label' => 'Dashboard', 'url' => '/dashboard'],
        // ['label' => 'Suppliers']
        $items
    ]" /> --}}

    <!-- 🧾 Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">Suppliers</h2>

        <button
            onclick="toggleModal(true)"
            class="rounded-md bg-green-800 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-green-700 focus:shadow-none active:bg-green-700 hover:bg-green-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
            Add Supplier
        </button>
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
                  <a href="s/{{ $t['id'] }}" class="btn btn-sm text-green-600 hover:underline">View</a>
                  <a href="s/{{ $t['id'] }}/edit" class="btn btn-sm text-blue-600 hover:underline">Edit</a>
                  <button onclick="showDelete()" data-id="{{ $t['id'] }}" class="btn btn-sm text-red-600 hover:underline">Delete</button>
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
document.getElementById('supplierModal').addEventListener('click', function(e) {
    if (e.target === this) toggleModal(false)
})

function showDelete(e) {
e.preventDefault();
const id = e.target.dataset.id;
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
        $.post("/s/"+id, data,
            function (data, textStatus, jqXHR) {

            },
            "dataType"
        );
    }
});
}
</script>
@endsection
