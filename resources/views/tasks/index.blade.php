<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tasks</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; padding: 24px; }
        .container { max-width: 720px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f5f5f5; text-align: left; }
        .status { font-size: 12px; color: #0a0; margin-bottom: 12px; }
        .actions { display: flex; gap: 8px; }
        a, button { cursor: pointer; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Tasks</h1>
        <a href="{{ route('tasks.create') }}" style="background:#111;color:#fff;padding:8px 12px;border-radius:8px;text-decoration:none;border:1px solid #111;">+ New Task</a>
    </div>

    <form method="GET" action="{{ route('tasks.index') }}" style="display:flex; gap:8px; margin-bottom: 12px;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search tasks..." style="flex:1; padding:8px; border:1px solid #ddd; border-radius:6px;">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <button type="submit" style="padding:8px 12px; border:1px solid #ddd; border-radius:6px; background:#fff;">Search</button>
        <a href="{{ route('tasks.index') }}" style="padding:8px 12px; border:1px solid #ddd; border-radius:6px; background:#fff; text-decoration:none;">Clear</a>
    </form>

    <div style="display:flex; gap:8px; margin-bottom: 16px;">
        <a href="{{ route('tasks.index', array_filter(['q' => request('q'), 'status' => null])) }}" style="padding:6px 10px; border-radius: 20px; border:1px solid #ddd; text-decoration:none; {{ request('status') === null ? 'background:#000;color:#fff;border-color:#000;' : '' }}">All</a>
        <a href="{{ route('tasks.index', array_filter(['q' => request('q'), 'status' => 'pending'])) }}" style="padding:6px 10px; border-radius: 20px; border:1px solid #ddd; text-decoration:none; {{ request('status') === 'pending' ? 'background:#000;color:#fff;border-color:#000;' : '' }}">Pending</a>
        <a href="{{ route('tasks.index', array_filter(['q' => request('q'), 'status' => 'completed'])) }}" style="padding:6px 10px; border-radius: 20px; border:1px solid #ddd; text-decoration:none; {{ request('status') === 'completed' ? 'background:#000;color:#fff;border-color:#000;' : '' }}">Completed</a>
    </div>

    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif
    @if (session('success'))
        <div class="status">{{ session('success') }}</div>
    @endif

    @if ($tasks->isEmpty())
        <p>No tasks found.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Completed</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($tasks as $task)
            <tr data-task-id="{{ $task->id }}">
                <td>
                    <span class="view-only">{{ $task->title }}</span>
                    <form class="inline-edit-form" method="POST" action="{{ route('tasks.update', $task) }}" style="display:none; gap:6px;">
                        @csrf
                        @method('PUT')
                        <input name="title" value="{{ $task->title }}" required style="width:100%; padding:6px;">
                    </form>
                </td>
                <td>
                    <span class="view-only">{{ $task->description }}</span>
                    <form class="inline-edit-form" method="POST" action="{{ route('tasks.update', $task) }}" style="display:none;">
                        @csrf
                        @method('PUT')
                        <textarea name="description" style="width:100%; padding:6px;">{{ $task->description }}</textarea>
                    </form>
                </td>
                <td>
                    <span class="view-only">{{ $task->completed ? 'Yes' : 'No' }}</span>
                    <form class="inline-edit-form" method="POST" action="{{ route('tasks.update', $task) }}" style="display:none;">
                        @csrf
                        @method('PUT')
                        <label style="display:flex; align-items:center; gap:6px;"><input type="checkbox" name="completed" value="1" {{ $task->completed ? 'checked' : '' }}> Completed</label>
                    </form>
                </td>
                <td class="actions">
                    <button class="edit-toggle" type="button">Edit</button>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                    <button class="save-inline" type="button" style="display:none;">Save</button>
                    <button class="cancel-inline" type="button" style="display:none;">Cancel</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('tr[data-task-id]');
    rows.forEach(function (row) {
        const editBtn = row.querySelector('.edit-toggle');
        const saveBtn = row.querySelector('.save-inline');
        const cancelBtn = row.querySelector('.cancel-inline');
        const viewEls = row.querySelectorAll('.view-only');
        const forms = row.querySelectorAll('.inline-edit-form');

        function setEditing(editing) {
            viewEls.forEach(el => el.style.display = editing ? 'none' : 'inline');
            forms.forEach(el => el.style.display = editing ? 'block' : 'none');
            editBtn.style.display = editing ? 'none' : 'inline-block';
            saveBtn.style.display = editing ? 'inline-block' : 'none';
            cancelBtn.style.display = editing ? 'inline-block' : 'none';
        }

        editBtn?.addEventListener('click', function () {
            setEditing(true);
        });

        cancelBtn?.addEventListener('click', function () {
            setEditing(false);
        });

        saveBtn?.addEventListener('click', function () {
            // Merge the three forms' data and submit via a hidden form
            const merged = new FormData();
            forms.forEach(function (form) {
                new FormData(form).forEach((value, key) => {
                    merged.set(key, value);
                });
            });

            // Ensure checkbox false case is captured
            if (!merged.has('completed')) {
                // leave absent to be treated as false by controller casting
            }

            const firstForm = forms[0];
            fetch(firstForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: (function () {
                    const payload = new FormData();
                    payload.append('_token', firstForm.querySelector('input[name="_token"]').value);
                    payload.append('_method', 'PUT');
                    merged.forEach((value, key) => payload.append(key, value));
                    return payload;
                })()
            }).then(function (resp) {
                if (resp.ok) {
                    window.location.reload();
                } else {
                    alert('Failed to save.');
                }
            }).catch(function () {
                alert('Failed to save.');
            });
        });
    });
});
</script>
</body>
</html>