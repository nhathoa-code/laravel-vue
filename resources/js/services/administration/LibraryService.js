import axios from "axios";
import Service from "@root/services/Service";

class LibraryServiceClass extends Service {
    base = "api/libraries";

    async getList(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }

    async insert(data, callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.post(this.base, data.values, { params: params });
        this.handleResponse(res, callback, data?.actions);
    }

    async update(libraryId, data, callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.put(this.base + `/${libraryId}`, data.values, {
            params: params,
        });
        this.handleResponse(res, callback, data?.actions);
    }

    async delete(libraryId, callback = null, params = {}) {
        this.deleting.value = true;
        const res = axios.delete(this.base + `/${libraryId}`, {
            params: params,
        });
        this.handleResponse(res, callback);
    }
}

export const LibraryService = new LibraryServiceClass();
