@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Manpower List</h1>

        @if($manpowers->isEmpty())
            <p>No manpower found.</p>
        @else
        <table id="myTable" class="table">
                            <thead>
                    <tr>
                        <th>ID</th>
                        <th>Shift</th>
                        <th>Member</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($manpowers as $manpower)
                        <tr>
                            <td>{{ $manpower->id }}</td>
                            <td>{{ ucfirst($manpower->shift) }}</td>
                            <td>{{ ucfirst($manpower->member) }}</td>
                            <td>
                                <a href="{{ route('manpower.edit', $manpower->id) }}" class="btn btn-warning">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
