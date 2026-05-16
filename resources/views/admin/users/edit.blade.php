@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="page-title" style="margin-bottom: 0;">Edit User</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="background-color: #fef2f2; border: 1px solid #fca5a5; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">User Information</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label" for="name">Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email <span style="color: red;">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password (Leave blank to keep current)</label>
                    <input type="password" name="password" id="password" class="form-control" minlength="8">
                </div>

                <div class="form-group">
                    <label class="form-label" for="whatsapp_number">WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $user->whatsapp_number) }}" placeholder="+1234567890">
                </div>

                <div class="form-group">
                    <label class="form-label" for="address">Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                    <input type="hidden" name="is_admin" value="0">
                    <input type="checkbox" name="is_admin" id="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }} style="width: 1.2rem; height: 1.2rem;">
                    <label for="is_admin" style="font-weight: 500; cursor: pointer;">Is Administrator</label>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update User</button>
            </form>
        </div>
    </div>
@endsection
