<script setup>
import { ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { CirculationService } from "@root/services/circulation/CirculationService";
import Spinner from "@admin/common/Spinner.vue";
const circulation = ref(null);
function renew(values) {
    CirculationService.renew({ values }, (res) => {
        circulation.value = res.data.circulation;
    });
}
</script>
<template>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <Form @submit="renew" method="POST" class="forms-sample">
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
                                renew
                            </button>
                            <spinner
                                :visible="CirculationService.submitting.value"
                            ></spinner>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="form-group col-8 mb-0">
                            <label>Specify due date (optional) :</label>
                            <Field
                                type="date"
                                name="due_date"
                                class="form-control p-1"
                                style="height: auto !important"
                            />
                        </div>
                    </div>
                </Form>
            </div>
        </div>
    </div>
    <div v-if="circulation" class="lastchecked col-md-6 mb-2">
        <strong>Item renewed: </strong>
        <p class="mb-0">
            {{ circulation.book_item.book.title }} ({{
                circulation.book_item.barcode
            }}) renewed for {{ circulation.user.name }} (
            {{ circulation.user.user_meta.card_number }} ). Now due on
            {{ circulation.due_date }}
        </p>
    </div>
</template>
