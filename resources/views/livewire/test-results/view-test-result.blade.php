<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Заголовок -->
                    <div class="mb-6 flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Деталі результату тесту</h2>
                            <p class="text-gray-600 mt-1">Детальний перегляд відповідей користувача</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('results.index') }}"
                               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Назад до списку
                            </a>
                            <button
                                wire:click="$dispatch('confirmDelete', { id: {{ $testResult->id }} })"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Видалити
                            </button>
                        </div>
                    </div>

                    <!-- Інформація про тест -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Інформація про користувача</h3>
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium">Ім'я:</span> {{ $testResult->user->name }}
                                </p>
                                <p class="text-sm"><span
                                        class="font-medium">Email:</span> {{ $testResult->user->email }}</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Інформація про тест</h3>
                            <div class="space-y-2">
                                <p class="text-sm"><span
                                        class="font-medium">Назва тесту:</span> {{ $testResult->test->name }}</p>
                                <p class="text-sm"><span
                                        class="font-medium">Дата проходження:</span> {{ $testResult->completed_at->format('d.m.Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Загальний результат -->
                    @if($results)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Загальний результат</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-green-600">{{ $results['correct_answers'] }}</p>
                                    <p class="text-sm text-gray-600 mt-1">Правильних відповідей</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-red-600">{{ $results['total_questions'] - $results['correct_answers'] }}</p>
                                    <p class="text-sm text-gray-600 mt-1">Неправильних відповідей</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-blue-600">{{ $testResult->getScorePercentage() }}
                                        %</p>
                                    <p class="text-sm text-gray-600 mt-1">Загальний бал</p>
                                </div>
                            </div>
                        </div>

                        <!-- Детальні відповіді -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Детальні відповіді</h3>
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
                                                    <h4 class="font-semibold text-gray-800">
                                                        Питання #{{ $answer['question_id'] }}
                                                        : {{ $question['question_text'] }}
                                                    </h4>
                                                    @if($answer['is_correct'])
                                                        <span
                                                            class="bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                                        Правильно
                                                    </span>
                                                    @else
                                                        <span
                                                            class="bg-red-600 text-white px-3 py-1 rounded-full text-sm">
                                                        Неправильно
                                                    </span>
                                                    @endif
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                                                    @foreach($question['options'] as $key => $option)
                                                        <div class="p-3 rounded
                                                        @if($key === $question['correct_answer'])
                                                            bg-green-100 border-2 border-green-500
                                                        @elseif($key === $answer['given_answer'] && !$answer['is_correct'])
                                                            bg-red-100 border-2 border-red-500
                                                        @else
                                                            bg-white border border-gray-200
                                                        @endif">
                                                            <div class="flex items-center">
                                                                <span class="font-bold mr-2">{{ $key }}.</span>
                                                                <span>{{ $option }}</span>
                                                            </div>
                                                            @if($key === $question['correct_answer'])
                                                                <span class="text-xs text-green-700 ml-6">✓ Правильна відповідь</span>
                                                            @endif
                                                            @if($key === $answer['given_answer'] && !$answer['is_correct'])
                                                                <span
                                                                    class="text-xs text-red-700 ml-6">✗ Ваша відповідь</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="text-center py-8 bg-yellow-50 rounded-lg border border-yellow-200">
                                        <svg class="w-12 h-12 mx-auto text-yellow-500 mb-3" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <p class="text-yellow-700 font-medium">Дані питань недоступні</p>
                                        <p class="text-yellow-600 text-sm mt-1">Можливо, файл з питаннями було видалено
                                            або пошкоджено</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500">Не вдалося завантажити результати тесту</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Видалити результат?</h3>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 mb-6">
                        Ви впевнені, що хочете видалити цей результат тесту? Ця дія незворотна і всі дані будуть видалені назавжди.
                    </p>

                    <div class="flex space-x-3">
                        <button
                            wire:click="deleteResult"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                            Так, видалити
                        </button>
                        <button
                            wire:click="cancelDelete"
                            class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition font-medium">
                            Скасувати
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
