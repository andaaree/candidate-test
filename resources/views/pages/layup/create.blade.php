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

    <div class="my-4 flex items-center justify-center overflow-hidden max-w-screen-xl mx-auto">
        <div class="card p-6 bg-white shadow-lg rounded-lg w-1/2 border-gray-600">
            <div class="card-header my-2"><h1 class="card-title text-xl">Add New Layup</h1></div>
            <div class="card-body my-4">
                <form action="{{ route('layup.store',$supplier->id) }}" method="POST">
                @csrf
                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                <div class="w-full max-w-sm min-w-[200px]">
                    <h1 class="text-xl">Layup Name</h1>
                    <input name="layup_name" value="{{ old('layup_name',$layup->name) }}" class="w-full bg-transparent border @error('layup_name') placeholder:text-red-400 text-red-700 border-red-600 focus:border-red-700 hover:border-red-400 @enderror text-sm  rounded-md px-3 py-2 transition duration-300 ease focus:outline-none shadow-sm focus:shadow" placeholder="Layup Name" />
                </div>
                @error('layup_name')
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
</div>
<script>
</script>
@endsection
