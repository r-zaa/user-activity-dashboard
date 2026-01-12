<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-10">

        <!-- Header -->
        <h2 class="text-3xl font-extrabold text-blue-700 mb-6 flex items-center gap-3">
            Activity Dashboard
        </h2>

        <!-- Filter Form -->
        <form method="GET"
            class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-2xl shadow-lg flex flex-wrap gap-6 items-end">
            <div class="flex flex-col">
                <label class="text-sm font-medium text-blue-800 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 w-full">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-blue-800 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 w-full">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-blue-800 mb-1">Action</label>
                <select name="action"
                    class="border border-blue-300 rounded px-5 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 w-full">
                    <option value="">All Actions</option>
                    <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                    <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                    <option value="create_post" {{ request('action') == 'create_post' ? 'selected' : '' }}>Create Post
                    </option>
                </select>
            </div>

            <button type="submit"
                class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-xl shadow-md hover:bg-blue-700 transition duration-200">
                Filter
            </button>
        </form>

        <!-- Activity Per Day -->
        <div class="bg-gradient-to-b from-white to-blue-50 p-6 rounded-2xl shadow-xl">
            <h3 class="font-semibold text-xl text-blue-700 mb-4 flex items-center gap-2">
                Total Activity per Day
            </h3>

            <div class="mb-6">
                <canvas id="activityChart" height="100"></canvas>
            </div>

            <table class="w-full text-sm rounded-xl overflow-hidden shadow-md">
                <thead class="bg-blue-100 text-blue-800">
                    <tr>
                        <th class="border-b border-blue-200 p-3 text-left">Tanggal</th>
                        <th class="border-b border-blue-200 p-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activityPerDay as $row)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="border-b border-blue-100 p-3">{{ $row->date }}</td>
                            <td class="border-b border-blue-100 p-3 text-right font-medium">{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-4 text-center text-blue-400">Tidak Ada data tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Top 5 Users -->
        <div class="bg-gradient-to-b from-white to-green-50 p-6 rounded-2xl shadow-xl">
            <h3 class="font-semibold text-xl text-green-700 mb-4 flex items-center gap-2">
                <span>👤</span> Top 5 Most Active Users
            </h3>

            <div class="mb-6">
                <canvas id="userChart" height="100"></canvas>
            </div>

            <table class="w-full text-sm rounded-xl overflow-hidden shadow-md">
                <thead class="bg-green-100 text-green-800">
                    <tr>
                        <th class="border-b border-green-200 p-3 text-left">User</th>
                        <th class="border-b border-green-200 p-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topUsers as $row)
                        <tr class="hover:bg-green-50 transition">
                            <td class="border-b border-green-100 p-3">{{ $row->user->name }}</td>
                            <td class="border-b border-green-100 p-3 text-right font-medium">{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-4 text-center text-blue-400">Tidak Ada data tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Activity Per Action -->
        <div class="bg-gradient-to-b from-white to-yellow-50 p-6 rounded-2xl shadow-xl">
            <h3 class="font-semibold text-xl text-yellow-700 mb-4 flex items-center gap-2">
                <span>⚡</span> Activity by Action
            </h3>

            <div class="mb-6 max-w-md">
                <canvas id="actionChart"></canvas>
            </div>

            <table class="w-full text-sm rounded-xl overflow-hidden shadow-md">
                <thead class="bg-yellow-100 text-yellow-800">
                    <tr>
                        <th class="border-b border-yellow-200 p-3 text-left">Action</th>
                        <th class="border-b border-yellow-200 p-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activityPerAction as $row)
                        <tr class="hover:bg-yellow-50 transition">
                            <td class="border-b border-yellow-100 p-3">{{ $row->action }}</td>
                            <td class="border-b border-yellow-100 p-3 text-right font-medium">{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-4 text-center text-blue-400">Tidak Ada data tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    @push('scripts')
        <script>
            const activityData = @json($activityPerDay);
            const userData = @json($topUsers);
            const actionData = @json($activityPerAction);

            // Activity Chart - Line
            new Chart(document.getElementById('activityChart'), {
                type: 'line',
                data: {
                    labels: activityData.map(i => i.date),
                    datasets: [{
                        label: 'Activity',
                        data: activityData.map(i => i.total),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59,130,246,0.2)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Top Users - Bar
            new Chart(document.getElementById('userChart'), {
                type: 'bar',
                data: {
                    labels: userData.map(i => i.user.name),
                    datasets: [{
                        label: 'Total',
                        data: userData.map(i => i.total),
                        backgroundColor: 'rgba(16,185,129,0.7)',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Actions - Pie
            new Chart(document.getElementById('actionChart'), {
                type: 'pie',
                data: {
                    labels: actionData.map(i => i.action),
                    datasets: [{
                        data: actionData.map(i => i.total),
                        backgroundColor: [
                            '#3B82F6', '#EF4444', '#F59E0B', '#10B981', '#8B5CF6'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        </script>
    @endpush
</x-app-layout>
