@props([
    'dataCul',
    'dataHar',
    'categories'
])

<div
    class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
    <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:justify-between">
        <div class="w-full">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Statistics
            </h3>
            <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                Target you’ve set for each month
            </p>
        </div>

    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <div id="chartThree" class="-ml-4 min-w-[700px] pl-2 xl:min-w-full"
            data-cultivate="{{ json_encode($dataCul) }}"
            data-harvest="{{ json_encode($dataHar) }}"
            data-categories="{{ json_encode($categories) }}" />
    </div>
</div>