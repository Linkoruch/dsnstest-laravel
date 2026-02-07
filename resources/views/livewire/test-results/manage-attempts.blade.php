<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Управління спробами</h2>
                            <p class="text-gray-600 mt-1">Тест: <strong>{{ $test->name }}</strong></p>
                            @if($test->attempts_limit)
                                <p class="text-sm text-gray-500 mt-1">Базовий ліміт: {{ $test->attempts_limit }} спроб</p>
                            @else
                                <p class="text-sm text-gray-500 mt-1">Необмежена кількість спроб</p>
                            @endif
                        </div>
                        <a href="{{ route('tests.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">Назад</a>
                    </div>

                    @if (session()->has('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Користувач</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Використано</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Залишилось</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Дії</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    @php
                                        $attempt = $test->getUserAttempt($user->id);
                                        $remaining = $attempt->getRemainingAttempts();
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm">{{ $attempt->attempts_used }}</td>
                                        <td class="px-6 py-4">
                                            @if($test->attempts_limit === null || $remaining === PHP_INT_MAX)
                                                <span class="px-2 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Необмежено</span>
                                            @elseif($remaining > 0)
                                                <span class="px-2 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ $remaining }}</span>
                                            @else
                                                <span class="px-2 text-xs font-semibold rounded-full bg-red-100 text-red-800">Вичерпано</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button wire:click="openModal({{ $user->id }}, 'add')" class="text-green-600 hover:text-green-900 mr-3" title="Додати">+</button>
                                            <button wire:click="openModal({{ $user->id }}, 'reset')" class="text-blue-600 hover:text-blue-900" title="Обнулити">↻</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Користувачів не знайдено</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50" wire:click="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
                @php $user = \App\Models\User::find($selectedUserId); @endphp

                @if($actionType === 'reset')
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Обнулити спроби?</h3>
                    <p class="text-sm text-gray-500 mb-4">Обнулити спроби для <strong>{{ $user->name }}</strong>?</p>
                    <div class="flex gap-4">
                        <button wire:click="closeModal" class="flex-1 px-4 py-2 bg-gray-300 rounded-lg">Скасувати</button>
                        <button wire:click="resetAttempts" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg">Обнулити</button>
                    </div>
                @else
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Додати спроби</h3>
                    <p class="text-sm text-gray-500 mb-4">Користувач: <strong>{{ $user->name }}</strong></p>
                    <input type="number" wire:model="bonusAttempts" min="1" class="w-full px-4 py-2 border rounded-lg mb-4" placeholder="Кількість">
                    @error('bonusAttempts')<p class="text-sm text-red-600 mb-2">{{ $message }}</p>@enderror
                    <div class="flex gap-4">
                        <button wire:click="closeModal" class="flex-1 px-4 py-2 bg-gray-300 rounded-lg">Скасувати</button>
                        <button wire:click="addBonusAttempts" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg">Додати</button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

