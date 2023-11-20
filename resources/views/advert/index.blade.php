<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Adverts') }}
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

            <a href="{{ route('advert.create') }}" type="button" class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add Advert</a>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400" x-data="table()">
                        <tr>

                            <th scope="col" class="px-6 py-3 column-sortable cursor-pointer position-relative {{ request()->sortBy == 'title' ? request()->sortType : '' }}" @click="sort('title', '{{ request()->sortType == 'desc' ? 'asc' : 'desc' }}')">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3 column-sortable cursor-pointer position-relative {{ request()->sortBy == 'category' ? request()->sortType : '' }}" @click="sort('category', '{{ request()->sortType == 'desc' ? 'asc' : 'desc' }}')">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-3 column-sortable cursor-pointer position-relative {{ request()->sortBy == 'price' ? request()->sortType : '' }}" @click="sort('price', '{{ request()->sortType == 'desc' ? 'asc' : 'desc' }}')">
                                Price
                            </th>
                            @role(App\Enums\Roles::ADMIN->name())
                            <th scope="col" class="px-6 py-3 column-sortable cursor-pointer position-relative {{ request()->sortBy == 'userName' ? request()->sortType : '' }}" @click="sort('userName', '{{ request()->sortType == 'desc' ? 'asc' : 'desc' }}')">
                                User
                            </th>
                            @endrole
                            <th scope="col" class="px-6 py-3">
                                Actions
                            </th>
                        
                        </tr>
                    </thead>
                    <tbody>

                        @if(count($data) > 0)

                            @foreach($data as $row)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $row->title }}</td>
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $row->category }}</td>
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $defaultCurrency.number_format($row->price, 2) }}</td>
                                @role(App\Enums\Roles::ADMIN->name())
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $row->userName }}</td>
                                @endrole
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                
                                    <div class="inline-flex rounded-md shadow-sm" role="group" x-data="table()">
                                        <a href="{{ route('advert.edit', ['advert' => $row->id]) }}" type="button" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-s-lg text-sm dark:focus:ring-yellow-900 px-4 py-2">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <button type="button" class="focus:outline-none text-white bg-red-400 hover:bg-red-500 focus:ring-4 focus:ring-red-300 font-medium rounded-e-lg text-sm dark:focus:ring-red-900 px-4 py-2" @click="deleteElement('{{ route('advert.destroy', ['advert' => $row->id]) }}')">
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
