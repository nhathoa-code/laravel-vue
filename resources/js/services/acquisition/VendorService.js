import axios from "axios";
import Service from "@root/services/Service";

class VendorServiceClass extends Service {
    base = "api/vendors";

    async getList(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }

    async getById(vendorId, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${vendorId}`, { params: params });
        this.handleResponse(res, callback);
    }

    async insert(data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(this.base, data.values);
        this.handleResponse(res, callback, data.actions);
    }

    async update(vendorId, data, callback = null) {
        this.submitting.value = true;
        const res = axios.put(this.base + `/${vendorId}`, data.values);
        this.handleResponse(res, callback, data.actions);
    }

    async delete(id, callback = null) {
        this.submitting.value = true;
        const res = axios.delete(this.base + `/${id}`);
        this.handleResponse(res, callback);
    }

    async getBasket(vendorId, basketId, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${vendorId}/baskets/${basketId}`, {
            params: params,
        });
        this.handleResponse(res, callback);
    }

    async getBaskets(vendorId, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${vendorId}/baskets`, {
            params: params,
        });
        this.handleResponse(res, callback);
    }

    async addBasket(vendorId, data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(this.base + `/${vendorId}/baskets`, data.values);
        this.handleResponse(res, callback, data.actions);
    }

    async updateBasket(vendorId, basketId, data, callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.put(
            this.base + `/${vendorId}/baskets/${basketId}`,
            data.values,
            { params: params }
        );
        this.handleResponse(res, callback, data?.actions);
    }

    async deleteBasket(vendorId, basketId, callback = null) {
        this.submitting.value = true;
        const res = axios.delete(
            this.base + `/${vendorId}/baskets/${basketId}`
        );
        this.handleResponse(res, callback);
    }

    async addItem(vendorId, basketId, data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(
            this.base + `/${vendorId}/baskets/${basketId}/additem`,
            data.values
        );
        this.handleResponse(res, callback, data?.actions);
    }

    async deleteItem(vendorId, basketId, data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(
            this.base + `/${vendorId}/baskets/${basketId}/deleteitem`,
            data
        );
        this.handleResponse(res, callback);
    }

    async getSpendingOrders(vendorId, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${vendorId}/spending-orders`, {
            params: params,
        });
        this.handleResponse(res, callback);
    }

    async getItemsFromSpendingBaskets(vendorId, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${vendorId}/basket-items`, {
            params: params,
        });
        this.handleResponse(res, callback);
    }

    async invoice(vendorId, data, callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.post(this.base + `/${vendorId}/invoices`, data, {
            params: params,
        });
        this.handleResponse(res, callback);
    }

    async getInvoices(callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.post(`api/invoices`, {
            params: params,
        });
        this.handleResponse(res, callback);
    }
}

export const VendorService = new VendorServiceClass();
