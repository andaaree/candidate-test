@extends('layouts.main')

@include('partials.nav')

@section('content')

<div class="space-y-6">

    <!-- 📍 Breadcrumb -->
    <x-breadcrumb :items="[
        // ['label' => 'Dashboard', 'url' => '/dashboard'],
        // ['label' => 'Suppliers']
        $items
    ]" />

    <!-- 🧾 Header -->
    <div class="flex items-center justify-between">
        <div class="card card-body rounded-lg shadow-lg border-gray-600">
            {{ $supplier }}
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
                  <button class="text-green-600 hover:underline">View</button>
                  <button class="text-blue-600 hover:underline">Edit</button>
                  <button class="text-red-600 hover:underline">Delete</button>
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
@include('pages.supplier.form')
</div>
@include('components.validator')
@endsection
