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

                        <!-- Питання -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-sm font-medium text-gray-700">
                                    Питання <span class="text-red-500">*</span>
                                </label>
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

                            @error('questions')
                                <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Список питань -->
                            <div class="space-y-6">
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
</div>
