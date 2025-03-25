import axios from "axios";
import Service from "@root/services/Service";

class BasketServiceClass extends Service {
    base = "api/vendors";

    async addItem(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }
}

export const BasketService = new BasketServiceClass();
