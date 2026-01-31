<div>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
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
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
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
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                placeholder="Введіть опис тесту"></textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Файл з питаннями -->
                        <div class="mb-6">
                            <label for="questionsFile" class="block text-sm font-medium text-gray-700 mb-2">
                                Файл з питаннями (JSON) <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg @error('questionsFile') border-red-500 @enderror">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="questionsFile" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Завантажити файл</span>
                                            <input id="questionsFile" type="file" wire:model="questionsFile" class="sr-only" accept=".json">
                                        </label>
                                        <p class="pl-1">або перетягніть сюди</p>
                                    </div>
                                    <p class="text-xs text-gray-500">JSON до 2MB</p>
                                </div>
                            </div>

                            @if ($questionsFile)
                                <div class="mt-2 flex items-center text-sm text-green-600">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Файл вибрано: {{ $questionsFile->getClientOriginalName() }}
                                </div>
                            @endif

                            @error('questionsFile')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Підказка про формат -->
                            <div class="mt-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-800 font-medium mb-2">Формат JSON файлу:</p>
                                <pre class="text-xs text-blue-700 overflow-x-auto">{
  "questions": [
    {
      "question_id": 1,
      "question_text": "What is 2+2?",
      "options": {
        "A": "3",
        "B": "4",
        "C": "5",
        "D": "6"
      },
      "correct_answer": "B"
    }
  ]
}</pre>
                            </div>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex justify-end gap-3">
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
