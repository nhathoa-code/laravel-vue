<script setup>
import { onMounted, ref, computed } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { DebitTypeService } from "@root/services/pos/DebitTypeService";
import { CashRegisterService } from "@root/services/pos/CashRegisterService";
import { PosService } from "@root/services/pos/PosService";
import Loading from "@admin/common/Loading.vue";
import Spinner from "@admin/common/Spinner.vue";
import * as yup from "yup";
const cashRegisters = ref([]);
const debitTypes = ref([]);
const selectedItems = ref([]);
const amountTendered = ref(0);
const changeToGive = ref(0);
const schema = yup.object({
    amount_tendered: yup
        .number()
        .required("Field A is required")
        .min(
            yup.ref("amount_being_pay"),
            ({ min }) => `Please enter a value greater than or equal to ${min}`
        ),
    amount_being_pay: yup.number().required(),
});
onMounted(() => {
    DebitTypeService.getList((res) => {
        debitTypes.value = res.data;
    });
    CashRegisterService.getList((res) => {
        cashRegisters.value = res.data;
    });
});
function makeTransaction(values, actions) {
    const transactionItems = [];
    selectedItems.value.forEach((item) => {
        transactionItems.push({
            debit_type: item.description,
            price: item.cost,
            quantity: item.quantity,
        });
    });
    values.transaction_items = transactionItems;
    PosService.transaction({ values }, (res) => {
        cancel();
    });
}
const totalPayable = computed(() => {
    return selectedItems.value.reduce((initial, item) => {
        return initial + item.quantity * item.cost;
    }, 0);
});
function selectItem(item) {
    const selectedItem = item;
    selectedItem.quantity = 1;
    selectedItem.cost = selectedItem.default_amount;
    selectedItems.value.push(selectedItem);
}
function removeItem(index) {
    selectedItems.value.splice(index, 1);
}
function handleBlur() {
    if (amountTendered.value >= totalPayable.value) {
        changeToGive.value = amountTendered.value - totalPayable.value;
    }
}
function cancel() {
    selectedItems.value = [];
    amountTendered.value = 0;
    changeToGive.value = 0;
}
</script>
<template>
    <Loading
        :visible="
            DebitTypeService.loading.value || CashRegisterService.loading.value
        "
    />
    <h3>Point of sale</h3>
    <div class="row">
        <div class="col-6">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body p-2">
                        <h2>Items for purchase</h2>
                        <p>
                            Please select items from below to add to this
                            transaction:
                        </p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Cost</th>
                                        <th style="width: 20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="item in debitTypes"
                                        :key="item.id"
                                    >
                                        <td>
                                            {{ item.code }}
                                        </td>
                                        <td>{{ item.description }}</td>
                                        <td>
                                            {{
                                                new Intl.NumberFormat({
                                                    style: "currency",
                                                }).format(item.default_amount) +
                                                " đ"
                                            }}
                                        </td>
                                        <td>
                                            <button
                                                @click="selectItem(item)"
                                                type="button"
                                                class="btn btn-outline-light btn-fw action"
                                            >
                                                <i class="fa fa-plus"></i>
                                                Add
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body p-2">
                        <h2>This sale</h2>
                        <p>Click to edit item cost or quantities</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Cost</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(item, index) in selectedItems"
                                        :key="item.id"
                                    >
                                        <td>{{ item.description }}</td>
                                        <td>
                                            {{
                                                new Intl.NumberFormat({
                                                    style: "currency",
                                                }).format(item.cost) + " đ"
                                            }}
                                        </td>
                                        <td>{{ item.quantity }}</td>
                                        <td>
                                            {{
                                                new Intl.NumberFormat({
                                                    style: "currency",
                                                }).format(
                                                    item.cost * item.quantity
                                                ) + " đ"
                                            }}
                                        </td>
                                        <td>
                                            <button
                                                @click="removeItem(index)"
                                                type="button"
                                                class="btn btn-outline-light btn-fw action"
                                            >
                                                <i class="fa fa-trash-o"></i>
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td
                                            colspan="3"
                                            class="editable editable_int"
                                            rowspan="1"
                                        >
                                            Total payable:
                                        </td>
                                        <td rowspan="1" colspan="1">
                                            {{
                                                new Intl.NumberFormat({
                                                    style: "currency",
                                                }).format(totalPayable) + " đ"
                                            }}
                                        </td>
                                        <td rowspan="1" colspan="1"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body p-2">
                        <h2 class="mb-5">Collect payment</h2>
                        <Form
                            method="POST"
                            @submit="makeTransaction"
                            :validation-schema="schema"
                            class="forms-sample"
                        >
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"
                                    >Amount being paid:</label
                                >
                                <div class="col-sm-9">
                                    <p>
                                        {{
                                            new Intl.NumberFormat({
                                                style: "currency",
                                            }).format(totalPayable) + " đ"
                                        }}
                                    </p>
                                    <Field
                                        type="hidden"
                                        name="amount_being_pay"
                                        v-model="totalPayable"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label required"
                                    >Amount tendered:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        @blur="handleBlur"
                                        type="number"
                                        name="amount_tendered"
                                        class="form-control"
                                        v-model="amountTendered"
                                    />
                                    <ErrorMessage
                                        name="amount_tendered"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"
                                    >Change to give:</label
                                >
                                <div class="col-sm-9">
                                    <p>
                                        {{
                                            new Intl.NumberFormat({
                                                style: "currency",
                                            }).format(changeToGive) + " đ"
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label required"
                                    >Payment type:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        class="form-control"
                                        name="payment_type"
                                        as="select"
                                    >
                                        <option selected value="CASH">
                                            Cash
                                        </option>
                                        <option value="CC">Credit Card</option>
                                    </Field>
                                    <ErrorMessage
                                        name="payment_type"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label required"
                                    >Cash register:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        class="form-control"
                                        name="cash_register"
                                        as="select"
                                    >
                                        <option
                                            v-for="item in cashRegisters"
                                            :value="item.id"
                                        >
                                            {{ item.name }}
                                        </option>
                                    </Field>
                                    <ErrorMessage
                                        name="cash_register"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <fieldset class="action">
                                    <button
                                        :disabled="PosService.submitting.value"
                                        type="submit"
                                        style="color: white"
                                        class="btn btn-secondary m-0"
                                    >
                                        Confirm
                                    </button>
                                    <a
                                        @click.prevent="cancel"
                                        class="cancel"
                                        href=""
                                        >Cancel</a
                                    >
                                </fieldset>
                                <spinner
                                    :visible="PosService.submitting.value"
                                ></spinner>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
