<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customers') }}
        </h2>
    </x-slot>

    <div class="py-12">

    <div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex justify-between py-3">

            <div x-data="table()">
                <select name="limit" @change="limiterChanged($event.target.value)" class="block w-20 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="10" {{ request()->input('limit') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request()->input('limit') == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request()->input('limit') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400" x-data="table()">
                        <tr>

                            <th scope="col" class="px-6 py-3 column-sortable cursor-pointer position-relative {{ request()->sortBy == 'name' ? request()->sortType : '' }}" @click="sort('name', '{{ request()->sortType == 'desc' ? 'asc' : 'desc' }}')">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 column-sortable cursor-pointer position-relative {{ request()->sortBy == 'email' ? request()->sortType : '' }}" @click="sort('email', '{{ request()->sortType == 'desc' ? 'asc' : 'desc' }}')">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Ban & Unban / Delete
                            </th>
                        
                        </tr>
                    </thead>
                    <tbody>

                        @if(count($data) > 0)

                            @foreach($data as $row)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $row->name }}</td>
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $row->email }}</td>
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                
                                    <div class="inline-flex rounded-md shadow-sm" role="group" x-data="{table : table(), customer : customer()}">
                                        <button title="{{ $row->active ? 'Ban customer' : 'Unban customer' }}" type="button" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-s-lg text-sm dark:focus:ring-yellow-900 px-4 py-2" @click="customer.banCustomer({{ json_encode($row) }})">
                                        @if($row->active)
                                        <i class="fa-solid fa-ban"></i>
                                        @else
                                        <i class="fa-regular fa-circle-check"></i>
                                        @endif
                                        </button>
                                        <button type="button" title="Delete customer & all their adverts" class="focus:outline-none text-white bg-red-400 hover:bg-red-500 focus:ring-4 focus:ring-red-300 font-medium rounded-e-lg text-sm dark:focus:ring-red-900 px-4 py-2" @click="table.deleteElement('{{ route('customer.destroy', ['customer' => $row->id]) }}', 'Are you sure want to delete this customer? All their adverts will be permanently deleted.')">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>

                                </td>
                            </tr>
                            @endforeach

                        @else
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" colspan="3">No results found...</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="my-3">

            {{ $data->withQueryString()->links() }}

        </div>
       
    </div>
        
    </div>
</x-app-layout>
