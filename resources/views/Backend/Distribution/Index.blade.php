@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Distribution Records</h1>
        <a href="{{ route('distribution.create') }}" class="btn btn-primary mb-3">Add New Distribution</a>
        
        @if($distributions->isEmpty())
            <p>No distribution records found.</p>
        @else
        <form id="bulk-delete-form" action="{{ route('distribution.bulkDelete') }}" method="POST">
            @csrf
            @method('DELETE')
            
            <div id="bulk-delete-container" class="mb-3" style="display: none;">
                <button type="submit" class="btn btn-danger" id="bulk-delete-btn">Delete Selected</button>
            </div>

            <table id="myTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>ID</th>
                        <th>Distribution Type</th>
                        <th>Shift</th>
                        <th>Day</th>
                        <th>Time of Day</th>
                        <th>Menu</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($distributions as $distribution)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $distribution->id }}" class="select-item"></td>
                            <td>{{ $distribution->id }}</td>
                            <td>{{ ucfirst($distribution->distribution_type) }}</td>
                            <td>{{ strtoupper($distribution->shift) }}</td>
                            <td>{{ ucfirst($distribution->day) }}</td>
                            <td>{{ ucfirst($distribution->time_of_day) }}</td>
                            <td>{{ ucfirst($distribution->menu->name) }}</td>
                            <td>
                                <a href="{{ route('distribution.edit', $distribution->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('distribution.destroy', $distribution->id) }}" method="POST" style="display:inline-block;">
                                  @csrf
                                  @method('DELETE')
                                 <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        @endif
    </div>
@endsection

@section('scripts')
<script>
    // Select/Deselect all checkboxes
    document.getElementById('select-all').addEventListener('change', function() {
        let checked = this.checked;
        document.querySelectorAll('input[name="ids[]"]').forEach(checkbox => {
            checkbox.checked = checked;
        });
        toggleDeleteButton();
    });

    // Toggle delete button visibility based on checkbox selection
    function toggleDeleteButton() {
        let selected = document.querySelectorAll('input[name="ids[]"]:checked').length > 0;
        document.getElementById('bulk-delete-container').style.display = selected ? 'block' : 'none';
    }

    // Handle individual checkbox change
    document.querySelectorAll('input[name="ids[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', toggleDeleteButton);
    });

    // Confirm deletion of selected items
    document.getElementById('bulk-delete-form').addEventListener('submit', function(event) {
        let selected = document.querySelectorAll('input[name="ids[]"]:checked');
        if (selected.length === 0) {
            event.preventDefault();
            alert('Please select at least one record to delete.');
        } else {
            return confirm('Are you sure you want to delete the selected records?');
        }
    });
</script>
@endsection
