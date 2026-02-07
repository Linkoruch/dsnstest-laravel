<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Заголовок з навігацією -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Доступні тести</h2>
                    <p class="text-gray-600 mt-2">Оберіть тест для проходження</p>
                </div>
                <a href="{{ route('user.results') }}"
                   wire:navigate
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Мої результати
                </a>
            </div>

            <!-- Пошук -->
            <div class="mb-6">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Пошук тестів..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Список тестів (Grid) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($tests as $test)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                        <!-- Заголовок тесту -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                            <h3 class="text-xl font-bold mb-2">{{ $test->name }}</h3>
                            <div class="flex items-center text-blue-100 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $test->created_at->format('d.m.Y') }}</span>
                            </div>
                        </div>

                        <!-- Опис тесту -->
                        <div class="p-6">
                            @if($test->description)
                                <p class="text-gray-600 mb-4 line-clamp-3">{{ $test->description }}</p>
                            @else
                                <p class="text-gray-400 mb-4 italic">Опис відсутній</p>
                            @endif

                            <!-- Статистика -->
                            <div class="flex items-center justify-between mb-4 text-sm">
                                <div class="flex items-center text-gray-500">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    @php
                                        $questions = $test->getQuestions();
                                        $questionCount = $questions && isset($questions['questions']) ? count($questions['questions']) : 0;
                                    @endphp
                                    <span>{{ $questionCount }} {{ $questionCount === 1 ? 'питання' : 'питань' }}</span>
                                </div>
                                <div class="flex items-center text-gray-500">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span>{{ $test->test_results_count }} проходжень</span>
                                </div>
                            </div>

                            <!-- Інформація про спроби -->
                            @php
                                $attempt = $test->getUserAttempt(auth()->id());
                                $remaining = $attempt->getRemainingAttempts();
                                $canTake = $test->canUserTakeTest(auth()->id());
                            @endphp

                            @if($test->attempts_limit !== null)
                                <div class="mb-4 p-3 rounded-lg {{ $remaining > 0 ? 'bg-blue-50 border border-blue-200' : 'bg-red-50 border border-red-200' }}">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="font-medium {{ $remaining > 0 ? 'text-blue-800' : 'text-red-800' }}">
                                            Залишилось спроб:
                                        </span>
                                        <span class="font-bold {{ $remaining > 0 ? 'text-blue-900' : 'text-red-900' }}">
                                            {{ $remaining }} / {{ $attempt->getTotalAvailableAttempts() }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <!-- Кнопка -->
                            @if($canTake)
                                <a href="{{ route('user.test.take', $test->id) }}"
                                   wire:navigate
                                   class="block w-full text-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                                    Розпочати тест
                                </a>
                            @else
                                <button disabled
                                        class="block w-full text-center px-4 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed opacity-60">
                                    Спроби вичерпано
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white rounded-lg shadow-md p-12 text-center">
                            <svg class="w-20 h-20 mx-auto text-blue-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Тести для проходження відсутні</h3>
                            <p class="text-gray-600 mb-6">На даний момент немає доступних тестів для проходження</p>

                            <!-- Посилання на історію -->
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('user.results') }}"
                                   wire:navigate
                                   class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Переглянути історію проходження
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Пагінація -->
            @if($tests->hasPages())
                <div class="mt-8">
                    {{ $tests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
