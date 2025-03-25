import axios from "axios";
import Service from "@root/services/Service";

class CashRegisterServiceClass extends Service {
    base = "api/cash-registers";

    async getList(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }

    async getById(registerId, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${registerId}`, { params: params });
        this.handleResponse(res, callback);
    }

    async create(data, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.post(this.base, data.values, { params: params });
        this.handleResponse(res, callback, data?.actions);
    }
}

export const CashRegisterService = new CashRegisterServiceClass();
