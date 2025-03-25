import axios from "axios";
import Service from "@root/services/Service";

class BudgetServiceClass extends Service {
    base = "api/budgets";

    async getList(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }

    async getById(budgetId, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${budgetId}`, { params: params });
        this.handleResponse(res, callback);
    }

    async insert(data, callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.post(this.base, data.values, { params: params });
        this.handleResponse(res, callback, data?.actions);
    }

    async update(budgetId, data, callback = null, params = {}) {
        this.submitting.value = true;
        this.resetForm = false;
        const res = axios.put(this.base + `/${budgetId}`, data.values, {
            params: params,
        });
        this.handleResponse(res, callback, data?.actions);
    }

    async delete(budgetId, callback = null, params = {}) {
        this.deleting.value = true;
        const res = axios.delete(this.base + `/${budgetId}`, {
            params: params,
        });
        this.handleResponse(res, callback);
    }

    async addFund(budgetId, data, callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.post(this.base + `/${budgetId}/funds`, data.values, {
            params: params,
        });
        this.handleResponse(res, callback, data?.actions);
    }

    async getFund(budgetId, fundId, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${budgetId}/funds/${fundId}`, {
            params: params,
        });
        this.handleResponse(res, callback);
    }

    async updateFund(budgetId, fundId, data, callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.put(
            this.base + `/${budgetId}/funds/${fundId}`,
            data.values,
            {
                params: params,
            }
        );
        this.handleResponse(res, callback, data?.actions);
    }

    async deleteFund(budgetId, fundId, callback = null, params = {}) {
        this.deleting.value = true;
        const res = axios.delete(this.base + `/${budgetId}/funds/${fundId}`, {
            params: params,
        });
        this.handleResponse(res, callback);
    }
}

export const BudgetService = new BudgetServiceClass();
