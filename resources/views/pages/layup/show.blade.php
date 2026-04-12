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
                <li class="text-sm">
                    <a href="/supplier" class="text-black hover:text-gray-600">
                        Supplier
                    </a>
                </li>
            </ol>
        </nav>
    </div>

    <!-- 🧾 Header -->
    <div class="card card-body bg-white shadow-lg rounded-lg border-gray-800">
        <div class="flex items-center justify-between overflow-hidden max-w-screen-xl p-4 py-5 mx-auto">
            <div class="p-2">
                <h1 class="font-bold text-xl">Layup Specifications : {{ $layup->name }}</h1>
                <p class="text-gray-400">ID : {{ $layup->id }}</p>
            </div>
            <div class="flex justify-between p-2 items-center">
                <a href="{{ route('layup.edit',[$supplier->id, $layup->id]) }}" class="edit-layup btn btn-sm">
                    <span class="material-icons">edit</span>
                    Edit Layup
                </a>
            </div>
        </div>
    </div>

    <div class="my-2 flex items-center justify-between overflow-hidden max-w-screen-xl mx-auto">
        <div class="flex-item">
            <div class="grid grid-cols-3 gap-8 my-6">
                <h1 class="text-xl col-span-2 font-bold">Layer Composition</h1>
                <a href="{{ route('layer.create',$layup->id) }}" class="rounded-lg shadow-lg w-fit p-2 bg-white">
                    <span class="material-icons">add</span>
                    Add Layer
                </a>
            </div>
            <div class="flex flex-auto bg-white max-w-screen-lg w-1/2">
                <table class="table-fixed w-full mx-auto text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th>Order</th>
                            <th>Thickness</th>
                            <th>Width</th>
                            <th>Angle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($layup->layers as $key => $layer)
                        <tr class="border-2 hover:bg-slate-100">
                            <td class="p-3">{{ $layer->layer_order }}</td>
                            <td class="p-3">{{ $layer->thickness }}mm</td>
                            <td class="p-3">{{ $layer->width }}mm</td>
                            <td class="p-3">{{ $layer->angle }}°</td>
                            <td class="p-3">
                                <a href="{{ route('layer.edit',[$layup->id,$layer->id]) }}" class="rounded-lg span material-icons">edit</a>
                                <button class="rounded-lg span material-icons">delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex-item max-w-screen-xl w-2/3 justify-center mx-auto max-h-screen-xl h-1/2">
            <div class="flex-col">
                <h1 class="text-2xl font-bold m-2">Structure Virtualizer</h1>
                <div class="card bg-neutral-50 p-4 border-gray-600 shadow-lg rounded-lg overflow-y-hidden">
                    <div class="card-body bg-white ">
                        @php
                        use Illuminate\Support\Arr;
                        $array = ["bg-orange-200", "bg-orange-900", "bg-orange-300", "bg-orange-800"];
                        $random = Arr::random($array);
                        $random2 = Arr::random($array);
                        @endphp
                        @foreach($layup->layers as $key => $layer)
                            @php
                                $thickness = $layer->thickness / 4;
                            @endphp
                        <div class="{{ $loop->odd ? $random2 : $random }} rounded-lg m-1">
                            <div class="p-50 text-xl text-gray-800 font-bold text-center" style="padding: {{ $thickness }}rem">L{{ $layer->layer_order }}</div>
                        </div>
                        @endforeach
                    </div>
                    <div class="card-footer bg-gray-400"> This is preview only</div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <!-- Content -->
    <div class="card bg-white shadow rounded-lg overflow-hidden border-gray-600">
        <div class="card-body">

        </div>
    </div>
</div>
@include('components.validator')
<script>
    $('.btnExport').click(function(e){
        e.preventDefault();

    });
</script>
@endsection
