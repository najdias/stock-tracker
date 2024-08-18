import { defineStore } from 'pinia';

import { fetchWrapper } from '@/helpers';

const baseUrl = `${import.meta.env.VITE_API_URL}/stock`;

export const useStockStore = defineStore({
    id: 'stock',
    state: () => ({
        history: [],
        quote: {}
    }),
    actions: {
        async getHistory() {
            this.history = { loading: true };
            fetchWrapper
                .get(`${baseUrl}/history`)
                .then((history) => (this.history = history))
                .catch((error) => (this.history = { error }));
        },
        async getQuote(symbol) {
            this.quote = { loading: true };
            fetchWrapper
                .get(`${baseUrl}?q=${symbol}`)
                .then((quote) => (this.quote = quote))
                .catch((error) => (this.quote = { error }));
        }
    }
});
