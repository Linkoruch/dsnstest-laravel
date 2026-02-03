<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Заголовок -->
                    <div class="mb-6 flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Мій результат</h2>
                            <p class="text-gray-600 mt-1">Детальний перегляд ваших відповідей</p>
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

                        <!-- Детальні відповіді -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Детальний розбір відповідей</h3>
                            <div class="space-y-4">
                                @if(isset($questions['questions']) && is_array($questions['questions']) && count($questions['questions']) > 0)
                                    @foreach($results['answers'] as $answer)
                                        @php
                                            $question = collect($questions['questions'])->firstWhere('question_id', $answer['question_id']);
                                        @endphp
                                        @if($question)
                                        <div class="border rounded-lg p-6
                                            @if($answer['is_correct'])
                                                bg-green-50 border-green-200
                                            @else
                                                bg-red-50 border-red-200
                                            @endif">
                                            <div class="flex items-start justify-between mb-3">
                                                <h4 class="font-semibold text-gray-800 flex-1">
                                                    <span class="text-blue-600">Питання {{ $answer['question_id'] }}:</span> {{ $question['question_text'] }}
                                                </h4>
                                                @if($answer['is_correct'])
                                                    <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap ml-4">
                                                        ✓ Правильно
                                                    </span>
                                                @else
                                                    <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap ml-4">
                                                        ✗ Неправильно
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                                                @foreach($question['options'] as $key => $option)
                                                    <div class="p-4 rounded-lg transition
                                                        @if($key === $question['correct_answer'])
                                                            bg-green-100 border-2 border-green-500
                                                        @elseif($key === $answer['given_answer'] && !$answer['is_correct'])
                                                            bg-red-100 border-2 border-red-500
                                                        @else
                                                            bg-white border border-gray-200
                                                        @endif">
                                                        <div class="flex items-start">
                                                            <span class="font-bold text-lg mr-3 text-gray-700">{{ $key }}.</span>
                                                            <div class="flex-1">
                                                                <span class="text-gray-800">{{ $option }}</span>
                                                                @if($key === $question['correct_answer'])
                                                                    <div class="text-xs text-green-700 font-semibold mt-1">
                                                                        ✓ Правильна відповідь
                                                                    </div>
                                                                @endif
                                                                @if($key === $answer['given_answer'] && !$answer['is_correct'])
                                                                    <div class="text-xs text-red-700 font-semibold mt-1">
                                                                        ✗ Ваша відповідь
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @else
                                    <div class="text-center py-8 bg-yellow-50 rounded-lg border border-yellow-200">
                                        <svg class="w-12 h-12 mx-auto text-yellow-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <p class="text-yellow-700 font-medium">Дані питань недоступні</p>
                                        <p class="text-yellow-600 text-sm mt-1">Можливо, файл з питаннями було видалено або пошкоджено</p>
                                    </div>
                                @endif
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
