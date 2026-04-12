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

    <div class="my-4 flex items-center max-w-screen-xl overflow-hidden mx-auto justify-between">
        <div class="flex-item w-2/3 mx-auto">
            <div class="card p-6 bg-white shadow-lg rounded-lg border-gray-600">
                <div class="card-header my-2"><h1 class="card-title text-xl">Add New Layer</h1></div>
                <div class="card-body my-4">
                    <form action="{{ route('layer.update',[$layup->id,$layer->id]) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="layup_id" value="{{ $layup->id }}">
                    <input type="hidden" name="last_uri" value="{{ $last_uri }}">
                    <div class="w-full max-w-sm min-w-[200px]">
                        <h1 class="text-xl">Layer Order</h1>
                        <input type="number" min="1" name="layer_order" value="{{ old('',$layer->layer_order) }}" class="w-full bg-transparent border @error('layer_order') placeholder:text-red-400 text-red-700 border-red-600 focus:border-red-700 hover:border-red-400 @enderror text-sm  rounded-md px-3 py-2 transition duration-300 ease focus:outline-none shadow-sm focus:shadow" placeholder="Layer Order" />
                    </div>
                    @error('layer_order')
                    <p class="flex items-center mt-2 text-xs text-slate-400">
                        {{ $message }}
                    </p>
                    @enderror
                    <div class="w-full max-w-sm min-w-[200px]">
                        <h1 class="text-xl">Thickness</h1>
                        <input type="number" min="0.01" step="0.01" name="thickness" value="{{ old('',$layer->thickness) }}" class="w-full bg-transparent border @error('thickness') placeholder:text-red-400 text-red-700 border-red-600 focus:border-red-700 hover:border-red-400 @enderror text-sm  rounded-md px-3 py-2 transition duration-300 ease focus:outline-none shadow-sm focus:shadow" placeholder="Thickness" />
                    </div>
                    @error('thickness')
                    <p class="flex items-center mt-2 text-xs text-slate-400">
                        {{ $message }}
                    </p>
                    @enderror
                    <div class="w-full max-w-sm min-w-[200px]">
                        <h1 class="text-xl">Width</h1>
                        <input type="number" min="0.01" step="0.01" name="width" value="{{ old('',$layer->width) }}" class="w-full bg-transparent border @error('width') placeholder:text-red-400 text-red-700 border-red-600 focus:border-red-700 hover:border-red-400 @enderror text-sm  rounded-md px-3 py-2 transition duration-300 ease focus:outline-none shadow-sm focus:shadow" placeholder="Width" />
                    </div>
                    @error('width')
                    <p class="flex items-center mt-2 text-xs text-slate-400">
                        {{ $message }}
                    </p>
                    @enderror
                    <div class="w-full max-w-sm min-w-[200px]">
                        <h1 class="text-xl">Angle</h1>
                        <select name="angle" class="w-full bg-transparent border @error('angle') placeholder:text-red-400 text-red-700 border-red-600 focus:border-red-700 hover:border-red-400 @enderror text-sm  rounded-md px-3 py-2 transition duration-300 ease focus:outline-none shadow-sm focus:shadow" appearance-none cursor-pointer placeholder="Angle">
                            <option value="0.00" @if($layer->angle == 0.00) selected @endif >0</option>
                            <option value="90.00" @if($layer->angle == 90.00) selected @endif>90</option>
                        </select>
                    </div>
                    @error('angle')
                    <p class="flex items-center mt-2 text-xs text-slate-400">
                        {{ $message }}
                    </p>
                    @enderror
                    <div class="max-w-sm min-w-sm w-fit my-2 p-2">
                        <div class="relative">
                            <button
                            {{-- onclick="submitImport()" --}}
                                type="submit" class="btn p-3 w-full shadow-lg border-neutral-300 rounded-md"><span class="material-icons">download</span>
                                Save
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="flex-item w-1/3 max-w-screen-lg mx-auto rounded-lg shadow-lg bg-gray-100 p-6">
            <h1 class="text-xl">Used Layer Orders :</h1>
            <table class="table-fixed bg-white">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Thickness</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exist as $e)
                    <tr class="hover:bg-gray-200 text-md">
                        <td>{{ $e['layer_order'] }}</td>
                        <td>{{ $e['thickness'] }}mm</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
</script>
@endsection
