@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="page-title" style="margin-bottom: 0;">Pages</h1>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Page</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">All Pages</h2>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Slug / URL</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->id }}</td>
                            <td>{{ $page->title }}</td>
                            <td><a href="{{ url('page/' . $page->slug) }}" target="_blank" style="color:var(--primary);">/page/{{ $page->slug }}</a></td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pen"></i> Edit</a>
                                    @if(!$page->is_fixed)
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Delete this page?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem;">No pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
