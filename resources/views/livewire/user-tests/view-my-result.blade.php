<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Заголовок -->
                    <div class="mb-6 flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Мій результат</h2>
                            <p class="text-gray-600 mt-1">Загальна статистика проходження тесту</p>
                        </div>
                        <a href="{{ route('user.results') }}"
                           wire:navigate
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Назад до моїх результатів
                        </a>
                    </div>

                    <!-- Інформація про тест -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-lg mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">{{ $testResult->test->name }}</h3>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Дата проходження: {{ $testResult->completed_at->format('d.m.Y о H:i') }}</span>
                        </div>
                    </div>

                    <!-- Загальний результат -->
                    @if($results)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">Ваш результат</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                    <p class="text-4xl font-bold text-green-600">{{ $results['correct_answers'] }}</p>
                                    <p class="text-sm text-gray-600 mt-2">Правильних відповідей</p>
                                </div>
                                <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                    <p class="text-4xl font-bold text-red-600">{{ $results['total_questions'] - $results['correct_answers'] }}</p>
                                    <p class="text-sm text-gray-600 mt-2">Неправильних відповідей</p>
                                </div>
                                <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                    <p class="text-4xl font-bold text-blue-600">{{ $testResult->getScorePercentage() }}%</p>
                                    <p class="text-sm text-gray-600 mt-2">Загальний бал</p>
                                </div>
                            </div>
                        </div>

                        <!-- Інформаційне повідомлення -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-2">Інформація про результати</h4>
                                    <p class="text-gray-600 text-sm">
                                        Ви успішно пройшли тест "{{ $testResult->test->name }}".
                                        Детальний розбір відповідей доступний тільки адміністратору.
                                        Ви можете переглянути вашу загальну статистику вище.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 text-lg">Не вдалося завантажити результати тесту</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
