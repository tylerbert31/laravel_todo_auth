<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 min-w-full">
        <div class="mx-auto sm:px-6 lg:px-8 flex flex-col gap-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 flex gap-2">
                    <form action="/todo/new" method="POST" class="flex gap-2">
                        @csrf
                        <input required type="text" name="title" placeholder="Title" class="input text-sm rounded-md" />
                        <input required type="text" name="description" placeholder="Description" class="input text-sm rounded-md" />
                        <button type="submit" class="btn">➕</button>
                    </form>
                    <a href="/todo/archived">
                        <button class="btn btn-neutral place-self-end">Go to Archived</button>
                    </a>
                </div>
        </div>
    </div>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Table Start -->
                    <div class="overflow-x-auto">
                        <table class="table">
                            <!-- head -->
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Detail</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- row 1 -->
                            @if (isset($todos) && count($todos))
                                @foreach($todos as $todo)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $todo->title }}</td>
                                        <td>{{ $todo->description }}</td>
                                        <td>
                                            @if ($todo->completed == 0)
                                                <div class="badge">Pending</div>
                                            @else
                                                <div class="badge badge-primary">Done</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($todo->completed == 0) 
                                            <a href="/todo/detail/{{ $todo->id }}">
                                                <button data-id="{{ $todo->id }}" class="btn text-lg edit-btn">📝</button>
                                            </a>
                                                <button data-id="{{ $todo->id }}" class="btn text-lg done-btn">✅</button>
                                            @elseif ($todo->completed == 1)
                                                <button data-id="{{ $todo->id }}" class="btn text-lg archive-btn">🗑️</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">No data found</td>
                                </tr>
                            @endif
                            <!-- row end -->
                            </tbody>
                        </table>
                    </div>
                    <!-- Table End -->
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.done-btn').click(async function() {
            let id = $(this).data('id');
            await axios.post('/todo/done', { id: id }).then(res => {
                if(res.status == 200) {
                    location.reload();
                } else {
                    oops();
                }
            });
        });

        $('.archive-btn').click(async function() {
            let id = $(this).data('id');
            await axios.post('/todo/archive', { id: id }).then(res => {
                if(res.status == 200) {
                    location.reload();
                } else {
                    oops();
                }
            });
        });

        function oops(){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong! Please try again.',
            });
        }
    </script>
</x-app-layout>
