<div>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Заголовок -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Створити новий тест</h2>
                        <p class="text-gray-600 mt-1">Заповніть форму для створення тесту</p>
                    </div>

                    <!-- Повідомлення про успіх -->
                    @if ($successMessage)
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">{{ $successMessage }}</span>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="save">
                        <!-- Назва тесту -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Назва тесту <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                wire:model="name"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @else border-gray-300 @enderror"
                                placeholder="Введіть назву тесту">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Опис тесту -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Опис тесту
                            </label>
                            <textarea
                                id="description"
                                wire:model="description"
                                rows="4"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @else border-gray-300 @enderror"
                                placeholder="Введіть опис тесту"></textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Призначення користувачів -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Доступ до тесту <span class="text-red-500">*</span>
                            </label>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <div class="flex items-start">
                                    <input
                                        type="checkbox"
                                        id="assignToAll"
                                        wire:model.live="assignToAll"
                                        class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="assignToAll" class="ml-2 block text-sm text-gray-700">
                                        <span class="font-semibold">Доступний для всіх користувачів</span>
                                        <p class="text-gray-600 text-xs mt-1">Якщо відмічено, всі користувачі зможуть проходити цей тест</p>
                                    </label>
                                </div>
                            </div>

                            @if(!$assignToAll)
                                <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto">
                                    <p class="text-sm text-gray-700 mb-3 font-medium">Оберіть користувачів:</p>
                                    @if(count($availableUsers) > 0)
                                        <div class="space-y-2">
                                            @foreach($availableUsers as $user)
                                                <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                                                    <input
                                                        type="checkbox"
                                                        wire:model="selectedUsers"
                                                        value="{{ $user['id'] }}"
                                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                    <span class="ml-2 text-sm text-gray-700">
                                                        {{ $user['name'] }}
                                                        <span class="text-gray-500">({{ $user['email'] }})</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">Немає доступних користувачів</p>
                                    @endif
                                </div>
                                @if(!$assignToAll && count($selectedUsers) === 0)
                                    <p class="mt-2 text-sm text-amber-600">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Оберіть хоча б одного користувача або виберіть "Доступний для всіх"
                                    </p>
                                @endif
                            @endif
                        </div>

                        <!-- Питання -->
                        <div class="mb-6">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">
                                    Питання <span class="text-red-500">*</span>
                                </label>
                            </div>

                            @error('questions')
                                <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Список питань -->
                            <div class="space-y-6 mb-4" id="questions-container">
                                @foreach($questions as $index => $question)
                                    <div class="border border-gray-300 rounded-lg p-6 bg-gray-50">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-semibold text-gray-800">Питання #{{ $index + 1 }}</h3>
                                            @if(count($questions) > 1)
                                                <button
                                                    type="button"
                                                    wire:click="removeQuestion({{ $index }})"
                                                    class="text-red-600 hover:text-red-800 transition duration-150"
                                                    title="Видалити питання">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Текст питання -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Текст питання <span class="text-red-500">*</span>
                                            </label>
                                            <textarea
                                                wire:model="questions.{{ $index }}.question_text"
                                                rows="2"
                                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('questions.'.$index.'.question_text') border-red-500 @else border-gray-300 @enderror"
                                                placeholder="Введіть текст питання"></textarea>
                                            @error('questions.'.$index.'.question_text')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Варіанти відповідей -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <!-- Варіант A -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Варіант A <span class="text-red-500">*</span>
                                                </label>
                                                <input
                                                    type="text"
                                                    wire:model="questions.{{ $index }}.option_a"
                                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('questions.'.$index.'.option_a') border-red-500 @else border-gray-300 @enderror"
                                                    placeholder="Варіант A">
                                                @error('questions.'.$index.'.option_a')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Варіант B -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Варіант B <span class="text-red-500">*</span>
                                                </label>
                                                <input
                                                    type="text"
                                                    wire:model="questions.{{ $index }}.option_b"
                                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('questions.'.$index.'.option_b') border-red-500 @else border-gray-300 @enderror"
                                                    placeholder="Варіант B">
                                                @error('questions.'.$index.'.option_b')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Варіант C -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Варіант C <span class="text-red-500">*</span>
                                                </label>
                                                <input
                                                    type="text"
                                                    wire:model="questions.{{ $index }}.option_c"
                                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('questions.'.$index.'.option_c') border-red-500 @else border-gray-300 @enderror"
                                                    placeholder="Варіант C">
                                                @error('questions.'.$index.'.option_c')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Варіант D -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Варіант D <span class="text-red-500">*</span>
                                                </label>
                                                <input
                                                    type="text"
                                                    wire:model="questions.{{ $index }}.option_d"
                                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('questions.'.$index.'.option_d') border-red-500 @else border-gray-300 @enderror"
                                                    placeholder="Варіант D">
                                                @error('questions.'.$index.'.option_d')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Правильна відповідь -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Правильна відповідь <span class="text-red-500">*</span>
                                            </label>
                                            <div class="flex gap-4">
                                                <label class="inline-flex items-center">
                                                    <input
                                                        type="radio"
                                                        wire:model="questions.{{ $index }}.correct_answer"
                                                        value="A"
                                                        class="form-radio text-blue-600">
                                                    <span class="ml-2">A</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input
                                                        type="radio"
                                                        wire:model="questions.{{ $index }}.correct_answer"
                                                        value="B"
                                                        class="form-radio text-blue-600">
                                                    <span class="ml-2">B</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input
                                                        type="radio"
                                                        wire:model="questions.{{ $index }}.correct_answer"
                                                        value="C"
                                                        class="form-radio text-blue-600">
                                                    <span class="ml-2">C</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input
                                                        type="radio"
                                                        wire:model="questions.{{ $index }}.correct_answer"
                                                        value="D"
                                                        class="form-radio text-blue-600">
                                                    <span class="ml-2">D</span>
                                                </label>
                                            </div>
                                            @error('questions.'.$index.'.correct_answer')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Кнопка додавання питання -->
                            <button
                                type="button"
                                wire:click="addQuestion"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-md transition duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Додати питання
                            </button>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex justify-end gap-3 mt-6">
                            <a href="{{ route('tests.index') }}"
                               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-150">
                                Скасувати
                            </a>
                            <button
                                type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>Створити тест</span>
                                <span wire:loading>Створення...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('questionAdded', () => {
            // Чекаємо, поки Livewire оновить DOM
            setTimeout(() => {
                const questionsContainer = document.getElementById('questions-container');
                if (questionsContainer) {
                    // Знаходимо кнопку "Додати питання" (наступний елемент після контейнера)
                    const addButton = questionsContainer.nextElementSibling;
                    if (addButton) {
                        // Плавно прокручуємо до кнопки
                        addButton.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center',
                            inline: 'nearest'
                        });
                    }
                }
            }, 100);
        });

        // Прокручування до повідомлення після створення тесту
        $wire.on('test-created', () => {
            setTimeout(() => {
                // Прокручуємо вгору до повідомлення
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }, 100);
        });
    </script>
    @endscript
</div>
