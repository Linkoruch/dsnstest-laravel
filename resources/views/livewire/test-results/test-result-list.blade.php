<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Заголовок -->
                    <div class="mb-6 flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Результати тестів</h2>
                            <p class="text-gray-600 mt-1">Перегляд всіх результатів проходження тестів</p>
                        </div>
                    </div>

                    <!-- Повідомлення про успіх -->
                    @if (session()->has('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Пошук -->
                    <div class="mb-6">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Пошук за користувачем або тестом..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Таблиця результатів -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th wire:click="sortBy('id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                        <div class="flex items-center">
                                            ID
                                            @if ($sortField === 'id')
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Користувач
                                    </th>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $result->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $result->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $result->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $result->test->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $percentage = $result->getScorePercentage();
                                                $resultData = $result->getResults();
                                            @endphp
                                            <div class="flex items-center">
                                                <div class="flex-1">
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
                                            <a href="{{ route('results.view', $result->id) }}"
                                               class="text-blue-600 hover:text-blue-900 mr-3"
                                               title="Переглянути деталі">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <button
                                                wire:click="confirmDelete({{ $result->id }})"
                                                class="text-red-600 hover:text-red-900"
                                                title="Видалити">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Результатів не знайдено
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Пагінація -->
                    <div class="mt-6">
                        {{ $results->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальне вікно підтвердження видалення -->
    @if ($deleteId)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="cancelDelete">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">Видалити результат?</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Ви впевнені, що хочете видалити цей результат тесту? Цю дію не можна буде скасувати.
                        </p>
                    </div>
                    <div class="flex gap-4 px-4 py-3">
                        <button
                            wire:click="cancelDelete"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                            Скасувати
                        </button>
                        <button
                            wire:click="delete"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Видалити
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
