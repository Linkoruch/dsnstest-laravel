<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Мої результати</h2>
                <p class="text-gray-600 mt-2">Історія проходження тестів</p>
            </div>

            <!-- Пошук -->
            <div class="mb-6">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Пошук за назвою тесту..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Таблиця результатів -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Тест
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Результат
                                </th>
                                <th wire:click="sortBy('completed_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    <div class="flex items-center">
                                        Дата проходження
                                        @if ($sortField === 'completed_at')
                                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                @if ($sortDirection === 'asc')
                                                    <path d="M5 10l5-5 5 5H5z"/>
                                                @else
                                                    <path d="M15 10l-5 5-5-5h10z"/>
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Дії
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($results as $result)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $result->test->name }}</div>
                                        @if($result->test->description)
                                            <div class="text-sm text-gray-500 line-clamp-1">{{ $result->test->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $percentage = $result->getScorePercentage();
                                            $resultData = $result->getResults();
                                        @endphp
                                        <div class="flex items-center">
                                            <div class="flex-1 max-w-xs">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ $resultData['correct_answers'] ?? 0 }}/{{ $resultData['total_questions'] ?? 0 }}
                                                    </span>
                                                    <span class="text-sm font-medium
                                                        @if($percentage >= 80) text-green-600
                                                        @elseif($percentage >= 60) text-yellow-600
                                                        @else text-red-600
                                                        @endif">
                                                        {{ $percentage }}%
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="h-2 rounded-full
                                                        @if($percentage >= 80) bg-green-600
                                                        @elseif($percentage >= 60) bg-yellow-600
                                                        @else bg-red-600
                                                        @endif"
                                                        style="width: {{ $percentage }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $result->completed_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('user.result.view', $result->id) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Переглянути деталі">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Деталі
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-gray-500 text-lg mb-4">Ви ще не пройшли жодного тесту</p>
                                        <a href="{{ route('user.tests') }}"
                                           class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                            Перейти до тестів
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Пагінація -->
                @if($results->hasPages())
                    <div class="px-6 py-4 border-t">
                        {{ $results->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
