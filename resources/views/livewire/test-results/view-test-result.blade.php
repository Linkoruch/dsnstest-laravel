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
                        <a href="{{ route('results.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                            Назад до списку
                        </a>
                    </div>

                    <!-- Інформація про тест -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Інформація про користувача</h3>
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium">Ім'я:</span> {{ $testResult->user->name }}</p>
                                <p class="text-sm"><span class="font-medium">Email:</span> {{ $testResult->user->email }}</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Інформація про тест</h3>
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium">Назва тесту:</span> {{ $testResult->test->name }}</p>
                                <p class="text-sm"><span class="font-medium">Дата проходження:</span> {{ $testResult->completed_at->format('d.m.Y H:i') }}</p>
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
                                    <p class="text-3xl font-bold text-blue-600">{{ $testResult->getScorePercentage() }}%</p>
                                    <p class="text-sm text-gray-600 mt-1">Загальний бал</p>
                                </div>
                            </div>
                        </div>

                        <!-- Детальні відповіді -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Детальні відповіді</h3>
                            <div class="space-y-4">
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
                                                    Питання #{{ $answer['question_id'] }}: {{ $question['question_text'] }}
                                                </h4>
                                                @if($answer['is_correct'])
                                                    <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                                        Правильно
                                                    </span>
                                                @else
                                                    <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm">
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
                                                            <span class="text-xs text-red-700 ml-6">✗ Ваша відповідь</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
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
</div>
