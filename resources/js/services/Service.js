import { ref } from "vue";

export default class Service {
    loading = ref(false);
    submitting = ref(false);
    updating = ref(false);
    deleting = ref(false);

    error;
    finally;
    resetForm = true;

    setError(callback = null) {
        if (typeof callback === "function") {
            this.error = callback;
        }
        return this;
    }

    setFinally(callback = null) {
        if (typeof callback === "function") {
            this.finally = callback;
        }
        return this;
    }

    reset(bol) {
        this.resetForm = bol;
        return this;
    }

    handleResponse(axiosRes, callback, actions = null) {
        axiosRes
            .then((res) => {
                if (typeof callback === "function") {
                    callback(res);
                }
                if (actions && this.resetForm) {
                    actions.resetForm();
                }
            })
            .catch((error) => {
                console.log("Error:", error);
                if (actions) {
                    if (error.status == 422) {
                        let errors = error.response.data.errors;
                        for (let key in errors) {
                            actions.setFieldError(key, errors[key][0]);
                        }
                    }
                }
                if (this.error) {
                    this.error(error);
                }
            })
            .finally(() => {
                this.loading.value = false;
                this.submitting.value = false;
                this.updating.value = false;
                this.deleting.value = false;
                if (this.finally) {
                    this.finally();
                }
            });
    }
}
