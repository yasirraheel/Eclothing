@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="page-title" style="margin-bottom: 0;">Edit Page: {{ $page->title }}</h1>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Pages</a>
    </div>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title">Page Details</h2>
        </div>
        <div style="padding: 1.5rem;">
            <form action="{{ route('admin.pages.update', $page) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="title" class="form-label">Page Title *</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}">
                    @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">Page Content *</label>
                    <textarea id="content" name="content" class="form-control" rows="10" required>{{ old('content', $page->content) }}</textarea>
                    <small class="text-muted">You can use basic HTML here for formatting.</small>
                    @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="margin-top: 1.5rem; margin-bottom: 0;">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update Page</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
