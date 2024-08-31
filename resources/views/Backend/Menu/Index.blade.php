@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Menus</h1>
        <a href="{{ route('menu.create') }}" class="btn btn-primary mb-3">Create New Menu</a>

        @if($menus->isEmpty())
            <p>No menus found.</p>
        @else
            <table id="myTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Menu Type</th>
                        <th>Menu</th>
                        <th>Quantity Person</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                        <tr>
                            <td>{{ $menu->id }}</td>
                            <td>{{ ucfirst($menu->type) }}</td>
                            <td>{{ ucfirst($menu->name) }}</td>
                            <td>
                                @if($menu->unit == 'kg')
                                    {{ $menu->quantity_per_person / 1000 }} kg
                                @else
                                    {{ $menu->quantity_per_person }} Pcs
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this menu?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
