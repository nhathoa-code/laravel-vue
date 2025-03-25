<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { Field, Form, ErrorMessage } from "vee-validate";
import { CashRegisterService } from "@root/services/pos/CashRegisterService";
import { PosService } from "@root/services/pos/PosService";
import { printTransaction, printCashupSumary } from "@root/functions";
import Loading from "@admin/common/Loading.vue";
import Spinner from "@admin/common/Spinner.vue";
import Modal from "@admin/common/Modal.vue";
import * as yup from "yup";
const cashRegister = ref(null);
const route = useRoute();
const openModal = ref(false);
const openRefundModal = ref(false);
const refundedItem = ref(null);
const totalIncome = ref(0);
const sumaryToShow = ref(null);
const schema = yup.object({
    refund: yup
        .number()
        .required("Please enter refund amount")
        .test(
            "max-check",
            function () {
                return `Please enter a value less than or equal to 'amount paid'`;
            },
            function (value) {
                const { amount_paid } = this.parent;
                return !(value > amount_paid);
            }
        ),
    payment_type: yup.string().required("please choose payment type"),
});
function getCashRegister() {
    CashRegisterService.getById(route.query.registerId, (res) => {
        console.log(res);
        cashRegister.value = res.data;
        cashRegister.value.transactions.forEach((item) => {
            if (item.transaction_type == "increase") {
                totalIncome.value += item.total;
            } else {
                totalIncome.value -= item.total;
            }
        });
    });
}
onMounted(() => {
    getCashRegister();
});
function cashup() {
    PosService.cashup(cashRegister.value.id, (res) => {
        getCashRegister();
    });
}
function showSumary(sumary) {
    openModal.value = true;
    sumaryToShow.value = sumary;
}
function issueRefund(values, actions) {
    values.debit_type = refundedItem.value.debit_type;
    PosService.refund(refundedItem.value.id, { values, actions }, () => {
        openRefundModal.value = false;
        getCashRegister();
    });
}
function printTran(library, tran) {
    printTransaction(library, tran);
}
function printSumary(register, sumary) {
    printCashupSumary(register, sumary);
}
</script>
<template>
    <Loading :visible="CashRegisterService.loading.value" />
    <template v-if="cashRegister">
        <div id="toolbar" class="btn-toolbar">
            <button @click="cashup" type="button" class="btn btn-default">
                <i class="fa fa-money"></i> Record cashup
            </button>
        </div>
        <div v-if="PosService.submitting.value" class="d-flex gap-3 mb-3">
            <spinner :visible="true"></spinner>
            <p>cashing up, please wait...</p>
        </div>
        <h3>Transaction history for {{ cashRegister.name }}</h3>
        <h2>Summary</h2>
        <ul>
            <li>
                Last cashup:
                {{
                    cashRegister.last_cashup
                        ? cashRegister.last_cashup
                        : "No last cashup"
                }}
            </li>
            <li>
                Float:
                {{
                    new Intl.NumberFormat({
                        style: "currency",
                    }).format(cashRegister.initial_amount) + " đ"
                }}
            </li>
            <li>
                Total income (cash):
                {{
                    new Intl.NumberFormat({
                        style: "currency",
                    }).format(cashRegister.total_income) + " đ"
                }}
                (
                {{
                    new Intl.NumberFormat({
                        style: "currency",
                    }).format(cashRegister.total_income_cash) + " đ"
                }})
            </li>
            <li>
                Total outgoing (cash):
                {{
                    new Intl.NumberFormat({
                        style: "currency",
                    }).format(cashRegister.total_outgoing) + " đ"
                }}
                (
                {{
                    new Intl.NumberFormat({
                        style: "currency",
                    }).format(cashRegister.total_outgoing_cash) + " đ"
                }})
            </li>
            <li>
                Total bankable:
                {{
                    new Intl.NumberFormat({
                        style: "currency",
                    }).format(cashRegister.total_bankable) + " đ"
                }}
            </li>
        </ul>
        <h2>Transactions to date</h2>
        <div class="row">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Transaction</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th style="width: 10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template
                                        v-for="tran in cashRegister.transactions"
                                        :key="tran.id"
                                    >
                                        <tr
                                            class="dtrg-group dtrg-level-0"
                                            :class="
                                                tran.transaction_type ==
                                                'increase'
                                                    ? 'credit'
                                                    : 'debit'
                                            "
                                        >
                                            <td>
                                                {{ tran.date }} ({{ tran.id }})
                                            </td>
                                            <td>
                                                {{
                                                    tran.transaction_type ==
                                                    "increase"
                                                        ? "Purchase"
                                                        : "Payment from library to patron"
                                                }}
                                                ({{ tran.payment_type }})
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                {{
                                                    tran.transaction_type ==
                                                    "decrease"
                                                        ? "-"
                                                        : ""
                                                }}
                                                {{
                                                    new Intl.NumberFormat({
                                                        style: "currency",
                                                    }).format(tran.total) + " đ"
                                                }}
                                            </td>
                                            <td>
                                                <button
                                                    @click="
                                                        printTran(
                                                            cashRegister.to_library,
                                                            tran
                                                        )
                                                    "
                                                    class="printReceipt btn btn-default btn-xs action"
                                                >
                                                    <i class="fa fa-print"></i>
                                                    Print receipt
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-for="item in tran.details">
                                            <td></td>
                                            <td>
                                                {{
                                                    tran.transaction_type ==
                                                    "increase"
                                                        ? item.debit_type
                                                        : "refund"
                                                }}
                                                {{
                                                    item.refunded
                                                        ? "(refunded)"
                                                        : ""
                                                }}
                                            </td>
                                            <td>
                                                {{
                                                    tran.transaction_type ==
                                                    "increase"
                                                        ? item.quantity
                                                        : ""
                                                }}
                                            </td>
                                            <td>
                                                {{
                                                    tran.transaction_type ==
                                                    "decrease"
                                                        ? "-"
                                                        : ""
                                                }}
                                                {{
                                                    new Intl.NumberFormat({
                                                        style: "currency",
                                                    }).format(item.price) + " đ"
                                                }}
                                            </td>
                                            <td></td>
                                            <td>
                                                <button
                                                    v-if="
                                                        !item.refunded &&
                                                        tran.transaction_type ==
                                                            'increase'
                                                    "
                                                    @click="
                                                        openRefundModal = true;
                                                        refundedItem = item;
                                                    "
                                                    type="button"
                                                    class="btn btn-default btn-xs pos_refund action"
                                                >
                                                    <i class="fa fa-money"></i>
                                                    Issue refund
                                                </button>
                                                <button
                                                    v-if="
                                                        tran.transaction_type ==
                                                        'decrease'
                                                    "
                                                    class="btn btn-default btn-xs pos_refund action"
                                                >
                                                    Details
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" rowspan="1">
                                            Total income:
                                        </td>
                                        <td rowspan="1" colspan="1">
                                            {{
                                                new Intl.NumberFormat({
                                                    style: "currency",
                                                }).format(totalIncome) + " đ"
                                            }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h2>Cashup history</h2>
        <div class="row">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Cashier</th>
                                        <th>Amount</th>
                                        <th style="width: 10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="item in cashRegister.cashup_histories"
                                    >
                                        <td>
                                            {{ item.to }}
                                        </td>
                                        <td>{{ item.operator.name }}</td>
                                        <td>
                                            {{
                                                new Intl.NumberFormat({
                                                    style: "currency",
                                                }).format(
                                                    item.cashup_sumary.reduce(
                                                        (initial, item) => {
                                                            return (
                                                                initial +
                                                                item.total
                                                            );
                                                        },
                                                        0
                                                    )
                                                ) + " đ"
                                            }}
                                        </td>
                                        <td>
                                            <button
                                                @click="showSumary(item)"
                                                type="button"
                                                class="btn btn-default btn-xs pos_refund action"
                                            >
                                                <i class="fa fa-pencil"></i>
                                                Summary
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
        <Modal @close="openModal = false" :open="openModal">
            <template v-slot:header>
                <h4 class="modal-title">Cashup summary</h4>
            </template>
            <template v-slot:body>
                <template v-if="sumaryToShow">
                    <ul>
                        <li>
                            Cash register:
                            <span>{{ cashRegister.name }}</span>
                        </li>
                        <li>
                            Period:
                            <span id="from_date">{{ sumaryToShow.from }}</span>
                            to
                            <span id="to_date">{{ sumaryToShow.to }}</span>
                        </li>
                    </ul>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in sumaryToShow.cashup_sumary"
                                :key="item.id"
                            >
                                <td>{{ item.debit_type }}</td>
                                <td>
                                    {{
                                        new Intl.NumberFormat({
                                            style: "currency",
                                        }).format(item.total) + " đ"
                                    }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td>
                                    {{
                                        new Intl.NumberFormat({
                                            style: "currency",
                                        }).format(
                                            sumaryToShow.cashup_sumary.reduce(
                                                (initial, item) => {
                                                    return initial + item.total;
                                                },
                                                0
                                            )
                                        ) + " đ"
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td>Cash</td>
                                <td>
                                    {{
                                        new Intl.NumberFormat({
                                            style: "currency",
                                        }).format(sumaryToShow.cash) + " đ"
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td>Cash out</td>
                                <td>
                                    {{
                                        new Intl.NumberFormat({
                                            style: "currency",
                                        }).format(sumaryToShow.cash_out) + " đ"
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td>Others</td>
                                <td>
                                    {{
                                        new Intl.NumberFormat({
                                            style: "currency",
                                        }).format(sumaryToShow.others) + " đ"
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td>Others out</td>
                                <td>
                                    {{
                                        new Intl.NumberFormat({
                                            style: "currency",
                                        }).format(sumaryToShow.others_out) +
                                        " đ"
                                    }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </template>
            </template>
            <template v-slot:footer>
                <button
                    @click="openModal = false"
                    type="button"
                    class="btn btn-default m-0"
                    data-dismiss="modal"
                >
                    Close
                </button>
                <button
                    @click="printSumary(cashRegister, sumaryToShow)"
                    type="button"
                    class="printModal btn btn-secondary m-0 ms-2"
                >
                    <i class="fa fa-print"></i> Print
                </button>
            </template>
        </Modal>
        <Form
            method="POST"
            @submit="issueRefund"
            :validation-schema="schema"
            class="forms-sample"
        >
            <Modal @close="openRefundModal = false" :open="openRefundModal">
                <template v-slot:header>
                    <h4 class="modal-title">
                        Issue refund from {{ cashRegister.name }}
                    </h4>
                </template>
                <template v-slot:body>
                    <template v-if="refundedItem">
                        <div class="form-group row mb-0">
                            <label class="col-sm-3 col-form-label mb-0"
                                >Item:</label
                            >
                            <div class="col-sm-9">
                                <p>{{ refundedItem.debit_type }}</p>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-sm-3 col-form-label mb-0"
                                >Amount paid:</label
                            >
                            <div class="col-sm-9">
                                <p>
                                    {{
                                        refundedItem.quantity *
                                        refundedItem.price
                                    }}
                                </p>
                            </div>
                            <Field
                                type="hidden"
                                name="amount_paid"
                                :value="
                                    refundedItem.quantity * refundedItem.price
                                "
                            />
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-sm-3 col-form-label mb-0 required"
                                >Returned to patron:</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    type="number"
                                    name="refund"
                                    class="form-control"
                                />
                                <ErrorMessage
                                    name="refund"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label mb-0 required"
                                >Transaction type:</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    class="form-control"
                                    name="payment_type"
                                    as="select"
                                >
                                    <option selected value="CASH">Cash</option>
                                    <option value="CC">Credit Card</option>
                                </Field>
                                <ErrorMessage
                                    name="payment_type"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                        </div>
                    </template>
                </template>
                <template v-slot:footer>
                    <spinner :visible="PosService.submitting.value"></spinner>
                    <button
                        :disabled="PosService.submitting.value"
                        type="submit"
                        class="btn btn-secondary m-0"
                        data-dismiss="modal"
                    >
                        Confirm
                    </button>
                    <button
                        @click="openRefundModal = false"
                        type="button"
                        class="btn btn-default m-0 ms-2"
                    >
                        Cancel
                    </button>
                </template>
            </Modal>
        </Form>
    </template>
</template>
<style scoped>
.credit td {
    color: #690;
}
.debit td {
    color: #c00;
}
table tr.dtrg-group.dtrg-level-0 td {
    font-weight: bold;
}
</style>
