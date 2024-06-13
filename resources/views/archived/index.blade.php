@php 
    use Carbon\Carbon;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archived') }}
        </h2>
    </x-slot>

    <div class="py-12">
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
                                <th>Archived</th>
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
                                            @php
                                                $date = Carbon::parse($todo->updated_at);
                                            @endphp
                                            {{ $date->diffForHumans(Carbon::now()) }}
                                        </td>
                                        <td>
                                            <button data-id="{{ $todo->id }}" class="btn text-lg del-btn">🗑️</button>
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
        $('.del-btn').click(function() {
            let id = $(this).data('id');
            // confirm modal
            Swal.fire({
                title: "Are you sure you want to delete?",
                showDenyButton: true,
                showCancelButton: true,
                showConfirmButton: false,
                denyButtonText: `Delete`
            }).then(async (result) => {
                if (result.isDenied) {
                    await permaDelete(id);
                }
            });
        });

        async function permaDelete(id){
            if(id){
                await axios.post('/todo/delete', { id: id }).then(res => {
                if(res.status == 200) {
                    Swal.fire("Deleted.", "", "info").then(() => {
                        location.reload();
                    })
                } else {
                    oops();
                }
            });
            } else {
                return;
            }
        }

        function oops(){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong! Please try again.',
            });
        }
    </script>
</x-app-layout>
