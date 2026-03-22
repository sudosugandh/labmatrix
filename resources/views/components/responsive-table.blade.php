@props(['headers'])

<div class="overflow-x-auto -mx-4 sm:mx-0">
    <div class="inline-block min-w-full align-middle">
        <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @foreach($headers as $header)
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>
</div>
