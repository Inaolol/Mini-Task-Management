<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>New Task</title>
    <style>
        :root { --border:#e5e7eb; --muted:#6b7280; --bg:#f9fafb; --radius:12px; }
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; padding: 24px; background: var(--bg); }
        .container { max-width: 720px; margin: 0 auto; }
        .header { display:flex; align-items:center; justify-content: space-between; margin-bottom: 16px; }
        .back { color:#111; text-decoration:none; border:1px solid var(--border); padding:8px 12px; border-radius:8px; background:#fff; }
        .card { background:#fff; border:1px solid var(--border); border-radius: var(--radius); padding: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
        .title { margin:0 0 8px; }
        .subtitle { margin:0 0 16px; color: var(--muted); font-size: 14px; }
        .field { margin-bottom: 14px; }
        .label { display:block; font-size: 14px; color:#111; margin-bottom:6px; }
        .input, .textarea { width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:8px; font: inherit; background:#fff; }
        .textarea { min-height: 120px; resize: vertical; }
        .row { display:flex; align-items:center; gap:10px; }
        .actions { display:flex; gap:10px; margin-top: 8px; }
        .btn { padding:10px 14px; border-radius:8px; border:1px solid #111; background:#111; color:#fff; cursor:pointer; }
        .btn.secondary { background:#fff; color:#111; border-color: var(--border); }
        .error { color:#b91c1c; font-size: 12px; margin-top: 6px; }
    </style>
    <script>
        function setCompletedFromToggle(cb) {
            const hidden = document.getElementById('completed-hidden');
            hidden.disabled = cb.checked;
        }
    </script>
    </head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0;">New Task</h1>
            <a class="back" href="{{ route('tasks.index') }}">Back to Tasks</a>
        </div>
        <div class="card">
            <h2 class="title">Create a task</h2>
            <p class="subtitle">Add a title, an optional description, and whether it’s already completed.</p>
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <div class="field">
                    <label class="label">Title</label>
                    <input class="input" name="title" value="{{ old('title') }}" required>
                    @error('title') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label class="label">Description</label>
                    <textarea class="textarea" name="description">{{ old('description') }}</textarea>
                </div>
                <div class="field row">
                    <label class="row" style="gap:8px;">
                        <input type="checkbox" name="completed" value="1" {{ old('completed') ? 'checked' : '' }} onchange="setCompletedFromToggle(this)">
                        Completed
                    </label>
                    <input id="completed-hidden" type="hidden" name="completed" value="0" {{ old('completed') ? 'disabled' : '' }}>
                </div>
                <div class="actions">
                    <button type="submit" class="btn">Create Task</button>
                    <a class="btn secondary" href="{{ route('tasks.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>