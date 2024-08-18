<script setup>
import { storeToRefs } from 'pinia';

import { useStockStore } from '@/stores';

const stockStore = useStockStore();
const { history } = storeToRefs(stockStore);

stockStore.getHistory();
</script>
<template>
    <div class="card">
        <div class="font-semibold text-xl mb-4">Stock History</div>
        <DataTable :value="history" scrollable scrollHeight="400px" class="mt-6">
            <Column field="date" header="Date" style="min-width: 200px" frozen class="font-bold"></Column>
            <Column field="symbol" header="Symbol" style="min-width: 200px" frozen class="font-bold"></Column>
            <Column field="open" header="Open" style="min-width: 100px"></Column>
            <Column field="high" header="High" style="min-width: 200px"></Column>
            <Column field="low" header="Low" style="min-width: 200px"></Column>
            <Column field="close" header="Close" style="min-width: 200px"></Column>
        </DataTable>
    </div>
</template>
