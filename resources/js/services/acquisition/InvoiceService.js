import axios from "axios";
import Service from "@root/services/Service";

class InvoiceServiceClass extends Service {
    base = "api/invoices";

    async getList(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }

    async getById(invoiceId, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${invoiceId}`, { params: params });
        this.handleResponse(res, callback);
    }
}

export const InvoiceService = new InvoiceServiceClass();
