<div>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Заголовок -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Редагувати користувача</h2>
                        <p class="text-gray-600 mt-1">Оновіть дані користувача</p>
                    </div>

                    <!-- Повідомлення про успіх -->
                    @if ($successMessage)
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">{{ $successMessage }}</span>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="update">
                        <!-- Ім'я -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Ім'я <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                wire:model="name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="Введіть ім'я користувача">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="email"
                                wire:model="email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                placeholder="email@example.com">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Новий пароль (опціонально) -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Новий пароль <span class="text-gray-500">(залиште порожнім, якщо не хочете змінювати)</span>
                            </label>
                            <input
                                type="password"
                                id="password"
                                wire:model.defer="password"
                                autocomplete="new-password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                placeholder="Мінімум 8 символів">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Підтвердження пароля -->
                        <div class="mb-6" x-data="{ showConfirmation: false }" x-init="$watch('$wire.password', value => showConfirmation = value && value.length > 0)">
                            <div x-show="showConfirmation">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Підтвердження пароля <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    wire:model.defer="password_confirmation"
                                    autocomplete="new-password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Повторіть пароль">
                            </div>
                        </div>

                        <!-- Роль -->
                        <div class="mb-6">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                Роль <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="role"
                                wire:model="role"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror">
                                <option value="user">Користувач</option>
                                <option value="admin">Адміністратор</option>
                            </select>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Рівень ризику -->
                        <div class="mb-6">
                            <label for="risk_level" class="block text-sm font-medium text-gray-700 mb-2">
                                Рівень ризику
                            </label>
                            <select
                                id="risk_level"
                                wire:model="risk_level"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('risk_level') border-red-500 @enderror">
                                <option value="">Не вказано</option>
                                <option value="high">Високий ризик</option>
                                <option value="low">Низький ризик</option>
                            </select>
                            @error('risk_level')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Кнопки -->
                        <div class="flex justify-end gap-3 mt-6">
                            <a href="{{ route('users.index') }}"
                               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                Скасувати
                            </a>
                            <button
                                type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>Оновити дані</span>
                                <span wire:loading>Оновлення...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
