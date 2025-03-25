import { defineStore } from "pinia";
export const useCartStore = defineStore("cart", {
    state: () => ({ items: [] }),
    actions: {
        addItem(item) {
            this.items.push(item);
        },
        removeItem(item) {
            this.items = this.items.filter((Item) => Item.id !== item.id);
        },
        inCart(item) {
            return this.items.find((Item) => Item.id === item.id);
        },
    },
});
