<script setup>
import { ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { CirculationService } from "@root/services/circulation/CirculationService";
import { useMessage } from "@root/functions";
import Spinner from "@admin/common/Spinner.vue";
import Toast from "primevue/toast";
const circulation = ref(null);
const message = useMessage();
CirculationService.setError((error) => {
    if (error.status === 400) {
        message(error.response.data.message, "error");
    }
});
function checkin(values) {
    CirculationService.checkin({ values }, (res) => {
        circulation.value = res.data.circulation;
    });
}
</script>
<template>
    <Toast />
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <Form @submit="checkin" method="POST" class="forms-sample">
                    <div class="row">
                        <div class="form-group col-8 mb-0">
                            <Field
                                type="text"
                                name="barcode"
                                class="form-control p-1"
                                placeholder="Enter item barcode"
                                style="height: auto !important"
                            />
                            <ErrorMessage
                                name="name"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="d-flex align-items-center gap-3 col-4 p-0">
                            <button
                                :disabled="CirculationService.submitting.value"
                                type="submit"
                                style="color: white"
                                class="btn btn-secondary m-0"
                            >
                                check in
                            </button>
                            <spinner
                                :visible="CirculationService.submitting.value"
                            ></spinner>
                        </div>
                    </div>
                </Form>
            </div>
        </div>
    </div>
    <div v-if="circulation" class="lastchecked col-md-6 mb-2">
        <p class="mb-0">
            <strong>Checked in: </strong>
            {{ circulation.book_item.book.title }} ({{
                circulation.book_item.barcode
            }}). Due on {{ circulation.due_date }}
        </p>
        <p class="mb-0">
            <strong>Date acquired: </strong>
            {{ circulation.book_item.date_acquired }}
        </p>
        <p class="mb-0">
            <strong>Patron: </strong>
            {{ circulation.user.name }} ({{
                circulation.user.user_meta.card_number
            }})
        </p>
    </div>
</template>
