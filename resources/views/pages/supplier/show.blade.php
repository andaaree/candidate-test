@extends('layouts.main')

@include('partials.navsupp')

@section('content')

<div class="space-y-6">

    <!-- 📍 Breadcrumb -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ $pageName }}
        </h2>

        <nav>
            <ol class="flex items-center gap-1.5">
                @foreach ($items as $item)
                    @if($loop->first) <span>/</span> @endif
                    @if (!$loop->first)
                    <svg class="stroke-current mx-1" width="17" height="16" viewBox="0 0 17 16" fill="none">
                        <path
                        d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                        stroke-width="1.2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        />
                    </svg>
                    @endif
                    <li class="text-sm">
                    <a href="{{ $item['url'] }}" class="text-black hover:text-gray-600">
                        {{ $item['label'] }}
                    </a>
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>

    <!-- 🧾 Header -->
    <div class="card card-body bg-white shadow-lg rounded-lg border-gray-800">
        <div class="flex items-center justify-between overflow-hidden max-w-screen-xl p-4 py-5 mx-auto">
            <div class="p-2">
                <h1 class="font-bold h1">{{ $supplier->name }}</h1>
                <p>ID : {{ $supplier->id }}</p>
            </div>
            <div class="flex justify-between p-2 items-center">
                <a href="{{ route('supplier.edit',$supplier->id) }}" class="edit-supplier btn btn-sm">
                    <span class="material-icons">edit</span>
                    Edit Supplier
                </a>
            </div>
        </div>
    </div>

    <div class="my-4 flex items-center justify-between overflow-hidden max-w-screen-xl mx-auto">
        <div class="flex-item">
        <h1>Associated Layups</h1>
        </div>
        <div class="flex-item">
            <div class="flex flex-auto">
                <a href="{{ route('supplier.import',$supplier->id) }}" class="btn btn-sm rounded-lg mx-2 p-2 bg-white"><span class="material-icons">download</span>Import</a>
                <a href="{{ route('layup.export',$supplier->id) }}" id="btnExport" class="btn btn-sm rounded-lg mx-2 p-2 bg-white"><span class="material-icons">upload</span>Export</a>
                <a href="{{ route('layup.create',$supplier->id) }}" class="btn btn-sm rounded-lg mx-2 p-2 bg-green-800 text-white"><span class="material-icons">add</span> Add Layup</a>
            </div>
        </div>
    </div>
    <hr>
    <!-- Content -->
    <div class="card bg-white shadow rounded-lg overflow-hidden border-gray-600">
        <div class="card-body">
            <table class="table-fixed max-w-screen-xl mx-auto w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left">Layup ID</th>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Thickness</th>
                        <th class="p-3 text-left">Ply Count</th>
                        <th class="p-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplier->layups as $key => $lp)
                    <tr class="border-2 hover:bg-slate-100">
                        <td class="p-3">{{ $lp['id'] }}</td>
                        <td class="p-3">{{ $lp['name'] }}</td>
                        <td class="p-3">{{ $lp['layers_sum_thickness'] ?? 0 }}mm</td>
                        <td class="p-3">{{ $lp['total_layers'] }}</td>
                        <td class="p-3">
                            <a href="{{ route('layup.show',[$supplier->id,$lp['id']]) }}" class="rounded-lg span material-icons">info</a>
                            <a href="{{ route('layup.edit',[$supplier->id,$lp['id']]) }}" class="rounded-lg span material-icons">edit</a>
                            <button class="rounded-lg span material-icons">trash</button>
                        </td>
                    @endforeach
                </tbody>
        </div>
        <div class="card-footer flex items-center">

        </div>

    </div>

</div>
@include('components.validator')
<script>

</script>
@endsection
