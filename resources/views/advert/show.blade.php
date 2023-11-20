<x-app-layout>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ redirect()->back()->getTargetUrl() }}" class="font-medium text-xl font-bold mb-5 text-blue-600 dark:text-blue-500 hover:underline block">{{ __('< Back') }}</a>

            <div class="flex justify-between">

                <h2 class="text-4xl text-gray-700 font-extrabold dark:text-white">{{ $advert->title }} <span class="text-2xl">({{ $advert->condition->name() }})</span></h2>

                <h2 class="text-4xl text-gray-700 font-extrabold dark:text-white">{{ $defaultCurrency.number_format($advert->price, 2) }}</h2>

            </div>

            <div class="flex flex-col lg:flex-row mt-5">

                <div class="w-full lg:w-1/3 mb-4 lg:mb-0">

                    <img src="{{ asset($advert->image) }}" alt="{{ $advert->title }}" class="w-full lg:h-60 object-fit-cover overflow-clip-margin-unset">

                    <div class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 mt-4">

                        <h5 class="mb-2 text-2xl font-bold tracking-tight dark:text-white text-gray-700">{{ __('Contact') }}</h5>

                        <p class="font-normal text-gray-700 dark:text-gray-400 mb-1">{{ $advert->city->name }}</p>

                        <a href="tel:{{ $advert->phone }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ $advert->phone }}</a>

                    </div>

                </div>

                <div class="w-full lg:w-2/3 lg:pl-4">
                
                    <div class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                        <h5 class="mb-2 text-2xl font-bold tracking-tight dark:text-white text-gray-700">{{ __('Description') }}</h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">{!! nl2br($advert->desc) !!}</p>
                        
                    </div>

                </div>

            </div>

        </div>
        
    </div>
</x-app-layout>
