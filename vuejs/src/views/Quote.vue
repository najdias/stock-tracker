<script setup>
import { storeToRefs } from 'pinia';
import { useStockStore } from '@/stores';
import { ref } from 'vue';

const symbol = ref('');

const stockStore = useStockStore();
const { quote } = storeToRefs(stockStore);

function getQuote() {
    stockStore.getQuote(symbol.value);
}
</script>
<template>
    <Fluid>
        <div class="card flex flex-col gap-4">
            <div class="font-semibold text-xl">Request Quote</div>
            <div class="flex flex-wrap items-start gap-4">
                <div class="field">
                    <label for="symbol" class="sr-only">Symbol</label>
                    <InputText id="symbol" type="text" placeholder="Symbol" v-model="symbol" />
                </div>
                <Button @click="getQuote" label="Submit" :fluid="false"></Button>
            </div>
        </div>
        <div class="card flex flex-col gap-4">
            <div class="flex items-center justify-between mb-0">
                <div class="font-semibold text-xl mb-4">Stock</div>
            </div>
            <div v-if="quote.error" class="leading-normal m-0">
                <Message severity="error">{{ quote.error }}</Message>
            </div>
            <div v-else-if="quote.loading === true" class="leading-normal m-0">
                <Message severity="warning">Geting quote</Message>
            </div>
            <div v-else class="leading-normal m-0">
                <span class="font-bold">Symbol:</span> {{ quote.symbol }}<br />
                <span class="font-bold">Open:</span> {{ quote.open }}<br />
                <span class="font-bold">High:</span> {{ quote.high }}<br />
                <span class="font-bold">Low:</span> {{ quote.low }}<br />
                <span class="font-bold">Close:</span> {{ quote.close }}<br />
            </div>
        </div>
    </Fluid>
</template>
