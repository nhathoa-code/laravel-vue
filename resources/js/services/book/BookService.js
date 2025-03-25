import axios from "axios";
import Service from "@root/services/Service";

class BookServiceClass extends Service {
    base = "api/books";

    async getList(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }

    async getById(id, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${id}`, { params: params });
        this.handleResponse(res, callback);
    }

    async insertItem(bookId, data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(this.base + `/${bookId}/items`, data.values);
        this.handleResponse(res, callback, data.actions);
    }

    async updateItem(bookId, ItemId, data, callback = null) {
        this.submitting.value = true;
        const res = axios.put(
            this.base + `/${bookId}/items/${ItemId}`,
            data.values
        );
        this.handleResponse(res, callback, data.actions);
    }

    async deleteItem(bookId, ItemId, callback = null) {
        this.loading.value = true;
        const res = axios.delete(this.base + `/${bookId}/items/${ItemId}`);
        this.handleResponse(res, callback);
    }

    async search(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/search`, { params: params });
        this.handleResponse(res, callback);
    }

    async searchItems(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/searchitems`, { params: params });
        this.handleResponse(res, callback);
    }
}

export const BookService = new BookServiceClass();
