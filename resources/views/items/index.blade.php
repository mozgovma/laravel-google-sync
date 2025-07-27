<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Список</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

  <style>
    .badge {
      font-size: 0.85rem;
      padding: 0.35em 0.65em;
      border-radius: 0.5rem;
      font-weight: 600;
    }
    thead th {
      padding: 12px 16px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
  </style>
</head>
<body>

  <div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="btn-group m-3 d-flex justify-content-center">
        @include('forms.google-sheet')
        <div class="d-flex ms-3" style="gap: 0.5rem;">
          <form action="{{ route('items.generate') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">📄</button>
          </form>
          <a href="{{ route('items.clear') }}" class="btn btn-danger">🗑️</a>
        </div>
      </div>
    </div>

    <table class="table table-bordered table-hover table-striped align-middle text-nowrap">
      <thead class="table-primary text-center">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Status</th>
          <th>Discriptions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
          <tr>
            <td class="text-center">{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td class="text-center">
              <span class="badge {{ $item->status == 'Allowed' ? 'bg-primary' : 'bg-danger' }}">
                {{ $item->status }}
              </span>
            </td>
            <td>{{ $item->discription ?: '-' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="d-flex justify-content-center">
      {{ $items->links('pagination::bootstrap-5') }}
    </div>
  </div>

</body>
</html>
