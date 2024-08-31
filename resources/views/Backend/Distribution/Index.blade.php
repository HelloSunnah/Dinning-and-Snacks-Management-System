@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Distribution Records</h1>
        <a href="{{ route('distribution.create') }}" class="btn btn-primary mb-3">Add New Distribution </a>
        @if($distributions->isEmpty())
            <p>No distribution records found.</p>
        @else
        <table id="myTable" class="table table-bordered">
        <thead>
                    <tr>
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
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            var table = $('#distributionTable').DataTable({
                "pageLength": 10,
            });

            $('#filter_type').on('change', function () {
                var searchTerm = this.value;
                alert('Filtering by type: ' + searchTerm); 
                table.column(1).search(searchTerm).draw();
            });

            $('#filter_shift').on('change', function () {
                var searchTerm = this.value;
                alert('Filtering by shift: ' + searchTerm); 
                table.column(2).search(searchTerm).draw();
            });

            $('#filter_day').on('change', function () {
                var searchTerm = this.value;
                alert('Filtering by day: ' + searchTerm); 
                table.column(3).search(searchTerm).draw();
            });
        });
    </script>
@endsection
