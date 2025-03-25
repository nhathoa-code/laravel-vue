import axios from "axios";
import Service from "@root/services/Service";
import { ref } from "vue";

class UserServiceClass extends Service {
    base = "api/users";
    logging_out = ref(false);

    async getList(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }

    async search(callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.get(this.base + "/search", { params: params });
        this.handleResponse(res, callback);
    }

    async getById(id, callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + `/${id}`, { params: params });
        this.handleResponse(res, callback);
    }

    async getByCard(data, callback = null) {
        this.loading.value = true;
        const res = axios.get(this.base + "/card", { params: data.values });
        this.handleResponse(res, callback, data.actions);
    }

    async update(userId, data, callback = null) {
        this.submitting.value = true;
        const res = axios.put(this.base + `/${userId}`, data.values);
        this.handleResponse(res, callback, data?.actions);
    }

    async reserve(userId, bookId, data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(
            this.base + `/${userId}/books/${bookId}/reserve`,
            data.values
        );
        this.handleResponse(res, callback);
    }

    async changePassword(userId, data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(
            this.base + `/${userId}/changepassword`,
            data.values
        );
        this.handleResponse(res, callback, data?.actions);
    }

    async getUser() {
        this.loading.value = true;
        const res = await axios.get("api/user");
        return res;
    }

    async login(data, callback = null) {
        this.submitting.value = true;
        const res = axios.post("login", data.values);
        this.handleResponse(res, callback, data?.actions);
    }

    async logout(callback = null) {
        this.logging_out.value = true;
        const res = axios.post("logout");
        this.handleResponse(res, callback);
    }
}

export const UserService = new UserServiceClass();
