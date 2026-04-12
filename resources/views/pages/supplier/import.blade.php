@extends('layouts.main')

@include('partials.navsupp')
@section('js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<style>
        .card { border-radius: 16px; }
        .dropzone { border: 2px dashed #adb5bd; border-radius: 16px; background: #fff; }
        .dropzone .dz-message { margin: 3rem 0; }
        .small-muted { font-size: 12px; color: #6c757d; }
        .session-pill { font-size: 12px; }
        .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
    </style>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
@endsection
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

    <div class="my-4 flex items-center justify-center overflow-hidden max-w-screen-xl mx-auto">
        <div class="card p-6 bg-white shadow-lg rounded-lg w-1/2 border-gray-600">
            <div class="card-header my-2"><h1 class="card-title text-xl">Import Layup Data</h1></div>
            <div class="card-body my-4">
                {{-- <form id="fileInput" action="/supplier/import/file" class="dropzone border-gray-500 rounded-lg" enctype="multipart/form-data" method="post">
                    @csrf
                </form> --}}
                <form action="/supplier/{{ $supplier->id }}/import" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="file_token">

                <label class="block mb-2.5 text-sm font-medium text-heading" for="file_input">Upload file</label>
                <input name="imported" class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body" id="file_input" type="file">

                <div class="w-full max-w-sm min-w-[200px]">
                    <div class="relative">
                        <h3 class="p-4 my-1">Conflict Resolution Strategy : </h3>
                        <select
                        name="mode"
                            class="w-100 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                            <option value="accept_incoming" selected>Skip Conflicts (Default)</option>
                            <option value="review">Review</option>
                        </select>
                    </div>
                </div>
                <div class="max-w-sm min-w-sm w-fit my-2 p-2">
                    <div class="relative">
                        <button
                        {{-- onclick="submitImport()" --}}
                            type="submit" class="btn p-3 w-full shadow-lg border-neutral-300 rounded-md"><span class="material-icons">download</span>
                            Import
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
let fileToken = null;

document.getElementById('fileInput').addEventListener('change', async function () {
    const file = this.files[0];

    const formData = new FormData();
    formData.append('file', file);

    const res = await fetch('/supplier/import/file', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    });

    const data = await res.json();
    fileToken = data.file_token;

    alert("File uploaded successfully");
});

async function submitImport() {

    if (!fileToken) {
        alert("Upload file first");
        return;
    }

    const res = await fetch('/suppliers/import/submit', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            file_token: fileToken
        })
    });

    // follow redirect manually
    if (res.redirected) {
        window.location.href = res.url;
    }
}
</script>
@endsection
