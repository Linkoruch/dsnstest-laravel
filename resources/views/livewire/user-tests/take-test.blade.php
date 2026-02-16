<div>
    @if($test->duration_minutes && !$isCompleted)
        <script>
            // Реєструємо Alpine.js компонент ДО його використання
            if (typeof Alpine !== 'undefined') {
                Alpine.data('testTimer', (initialSeconds) => ({
                    timeRemaining: initialSeconds,
                    interval: null,

                    startTimer() {
                        console.log('Timer started with', this.timeRemaining, 'seconds');
                        // Запускаємо інтервал який оновлюється кожну секунду
                        this.interval = setInterval(() => {
                            this.timeRemaining--;
                            console.log('Time remaining:', this.timeRemaining);

                            // Коли час вийшов - автоматично завершуємо тест
                            if (this.timeRemaining <= 0) {
                                clearInterval(this.interval);
                                this.timeExpired();
                            }
                        }, 1000); // Оновлюється кожну секунду
                    },

                    formatTime() {
                        // Форматуємо час у вигляді ХХ:ХХ
                        const minutes = Math.floor(this.timeRemaining / 60);
                        const seconds = this.timeRemaining % 60;
                        return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                    },

                    timeExpired() {
                        console.log('Time expired! Auto-submitting test...');
                        // Викликаємо Livewire метод для автоматичного завершення тесту
                        @this.call('autoSubmitTest');
                    }
                }));
            }
        </script>
    @endif

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(!$isCompleted)
                <!-- Заголовок тесту -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-800">{{ $test->name }}</h2>
                            @if($test->description)
                                <p class="text-gray-600 mt-2">{{ $test->description }}</p>
                            @endif
                            <div class="mt-4 flex items-center text-sm text-gray-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Питання: {{ $currentQuestionIndex + 1 }} з {{ count($questions) }}</span>
                            </div>
                        </div>

                        <!-- Таймер -->
                        @if($test->duration_minutes)
                            <div class="ml-6" x-data="testTimer({{ $test->duration_minutes * 60 }})" x-init="startTimer()">
                                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 min-w-[180px]">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-gray-700">Залишилось часу</span>
                                    </div>
                                    <div class="text-center">
                                        <div
                                            class="text-3xl font-bold"
                                            :class="timeRemaining <= 60 ? 'text-red-600' : (timeRemaining <= 300 ? 'text-orange-600' : 'text-blue-600')">
                                            <span x-text="formatTime()"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Повідомлення про помилки -->
                @if (session()->has('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Поточне питання -->
                @if(isset($questions[$currentQuestionIndex]))
                    @php $question = $questions[$currentQuestionIndex]; @endphp
                    <div class="bg-white rounded-lg shadow-md p-8 mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">
                            {{ $question['question_text'] }}
                        </h3>

                        <!-- Варіанти відповідей -->
                        <div class="space-y-3">
                            @foreach($question['options'] as $key => $option)
                                <label
                                    for="q{{ $question['question_id'] }}_{{ $key }}"
                                    class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition
                                    @if(isset($answers[$question['question_id']]) && $answers[$question['question_id']] === $key)
                                        border-blue-500 bg-blue-50
                                    @else
                                        border-gray-200 hover:border-blue-300 hover:bg-gray-50
                                    @endif">
                                    <input
                                        type="radio"
                                        id="q{{ $question['question_id'] }}_{{ $key }}"
                                        name="question_{{ $question['question_id'] }}"
                                        value="{{ $key }}"
                                        wire:model="answers.{{ $question['question_id'] }}"
                                        @if(isset($answers[$question['question_id']]) && $answers[$question['question_id']] === $key) checked @endif
                                        class="w-5 h-5 text-blue-600">
                                    <span class="ml-3 text-gray-700 font-medium">{{ $key }}.</span>
                                    <span class="ml-2 text-gray-700">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Навігація між питаннями -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <button
                            wire:click="previousQuestion"
                            @if($currentQuestionIndex === 0) disabled @endif
                            class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                            ← Попереднє
                        </button>

                        <span class="text-gray-600 font-medium">
                            Питання {{ $currentQuestionIndex + 1 }} / {{ count($questions) }}
                        </span>

                        <button
                            wire:click="nextQuestion"
                            @if($currentQuestionIndex === count($questions) - 1) disabled @endif
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Наступне →
                        </button>
                    </div>

                    <!-- Мінікарта питань -->
                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-600 mb-3">Швидка навігація:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($questions as $index => $q)
                                <button
                                    wire:click="goToQuestion({{ $index }})"
                                    class="w-10 h-10 rounded-lg font-semibold transition
                                        @if($index === $currentQuestionIndex)
                                            bg-blue-600 text-white
                                        @elseif(!empty($answers[$q['question_id']]))
                                            bg-green-100 text-green-800 border-2 border-green-500
                                        @else
                                            bg-gray-100 text-gray-600 border-2 border-gray-300 hover:border-blue-300
                                        @endif">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Кнопка завершення тесту -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <button
                        wire:click="submitTest"
                        class="w-full px-6 py-4 bg-green-600 hover:bg-green-700 text-white font-bold text-lg rounded-lg transition">
                        Завершити тест і отримати результат
                    </button>
                </div>

            @else
                <!-- Результат тесту -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="mb-6">
                        <svg class="w-20 h-20 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Тест завершено!</h2>
                    <p class="text-gray-600 mb-8">Ваші результати успішно збережено</p>

                    <div class="flex gap-4 justify-center">
                        <a href="{{ route('user.tests') }}"
                           class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                            Повернутися до тестів
                        </a>
                        <a href="{{ route('user.results') }}"
                           class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                            Переглянути мої результати
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
