import { useToast } from "primevue/usetoast";

export const formatCurrency = (value) => {
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
    }).format(value);
};

export function formattedDate(date) {
    const [day, month, year] = date.split("-");
    return `${year}-${month}-${day}`;
}

export function useMessage() {
    const toast = useToast();
    function message(message = "", type = "success") {
        toast.add({
            severity: type,
            summary: type,
            detail: message,
            life: 3000,
        });
    }
    return message;
}

export function getTotal(array, key) {
    return formatCurrency(
        array.reduce((prev, item) => {
            return prev + (typeof key === "function" ? key(item) : item[key]);
        }, 0)
    );
}

export function printTransaction(library, tran) {
    let printWindow = window.open("", "_blank");
    printWindow.document.write(`
        <html>
        <head>
            <title>CashRegisterSumary</title>
            <style>
                table{
                    width:100%;
                    border-collapse: collapse;
                }
                th{
                    text-align:left
                }
                th, td {
                    border: 1px solid black;
                    padding: 8px;
                    text-align: left;
                }
                .center{
                    text-align:center
                }
            </style>
        </head>
        <body>
           <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="center" colspan="2">${library.name}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="center" colspan="2">${tran.date}</td>
                    </tr>
                    <tr>
                        <td style="width:60%">Transaction ID:</td>
                        <td style="width:40%">${tran.id}</td>
                    </tr>
                    <tr>
                        <td style="width:60%">Operator ID:</td>
                        <td style="width:40%">${tran.operator}</td>
                    </tr>
                    <tr>
                        <td style="width:60%">Payment type:</td>
                        <td style="width:40%">${tran.payment_type}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="center" colspan="2">Free receipt</th>
                    </tr>
                    <tr>
                        <th style="width:60%">Description of charges</th>
                        <th style="width:40%">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    ${tran.details
                        .map((item) => {
                            return `<tr>
                                        <td style="width:60%">${
                                            item.debit_type
                                        }</td>
                                        <td style="width:40%">${formatCurrency(
                                            item.price
                                        )}</td>
                                    </tr>`;
                        })
                        .join("")}
                    <tr style="font-weight:bold">
                        <td style="width:60%">Total</td>
                        <td style="width:40%">${formatCurrency(tran.total)}</td>
                    </tr>
                    <tr>
                        <td style="width:60%">Amount tendered</td>
                        <td style="width:40%">${formatCurrency(
                            tran.amount_tendered
                        )}</td>
                    </tr>
                    <tr>
                        <td style="width:60%">Change</td>
                        <td style="width:40%">${formatCurrency(
                            tran.amount_tendered - tran.total
                        )}</td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.onload = () => {
        printWindow.print();
        printWindow.close();
    };
}

export function printCashupSumary(register, sumary) {
    let printWindow = window.open("", "_blank");
    printWindow.document.write(`
        <html>
        <head>
            <title>CashRegisterSumary</title>
            <style>
                table{
                    width:100%;
                    border-collapse: collapse;
                }
                th{
                    text-align:left
                }
                th, td {
                    border: 1px solid black;
                    padding: 8px;
                    text-align: left;
                }
                .center{
                    text-align:center
                }
            </style>
        </head>
        <body>
            <ul>
                <li>
                    Cash register:
                    <span>${register.name}</span>
                </li>
                <li>
                    Period:
                    <span id="from_date">${sumary.from}</span>
                    to
                    <span id="to_date">${sumary.to}</span>
                </li>
            </ul>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:75%" class="center">Type</th>
                        <th style="width:25%" class="center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    ${sumary.cashup_sumary
                        .map((item) => {
                            return `<tr>
                                        <td style="width:60%">${
                                            item.debit_type
                                        }</td>
                                        <td style="width:40%">${formatCurrency(
                                            item.total
                                        )}</td>
                                    </tr>`;
                        })
                        .join("")}
                    <tr style="font-weight:bold">
                        <td>Total</td>
                        <td>${formatCurrency(
                            sumary.cashup_sumary.reduce((total, item) => {
                                return total + item.total;
                            }, 0)
                        )}</td>
                    </tr>
                    <tr style="font-weight:bold">
                        <td>Cash</td>
                        <td>${formatCurrency(sumary.cash)}</td>
                    </tr>
                    <tr style="font-weight:bold">
                        <td>Cash out</td>
                        <td>${formatCurrency(sumary.cash_out)}</td>
                    </tr>
                    <tr style="font-weight:bold">
                        <td>Others</td>
                        <td>${formatCurrency(sumary.others)}</td>
                    </tr>
                    <tr style="font-weight:bold">
                        <td>Others out</td>
                        <td>${formatCurrency(sumary.others_out)}</td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.onload = () => {
        printWindow.print();
        printWindow.close();
    };
}
