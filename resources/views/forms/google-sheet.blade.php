<form method="POST" action="{{ route('save-or-edit') }}">
    @csrf
    <div style="display: flex; flex-direction: column; max-width: 600px;">
        <div style="display: flex; gap: 8px;">
            <input placeholder="Google Sheet URL" name="sheet_url" type="url" value="{{ config('services.google_sheet_url') }}" required class="form-control" style="flex: 1;">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</form>
