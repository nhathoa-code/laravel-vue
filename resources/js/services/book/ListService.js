import axios from "axios";
import Service from "@root/services/Service";

class ListServiceClass extends Service {
    base = "api/lists";

    async getList(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }

    async create(data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(this.base, data.values);
        this.handleResponse(res, callback, data?.actions);
    }

    async getById(id, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${id}`, { params: params });
        this.handleResponse(res, callback);
    }

    async addItem(listId, data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(this.base + `/${listId}/items`, data.values);
        this.handleResponse(res, callback, data?.actions);
    }

    async removeItem(listId, itemId, callback = null) {
        this.loading.value = true;
        const res = axios.delete(this.base + `/${listId}/items/${itemId}`);
        this.handleResponse(res, callback);
    }
}

export const ListService = new ListServiceClass();
