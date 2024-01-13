<template>
    <v-dialog fullscreen :scrim="true" transition="dialog-bottom-transition">
        <v-row fluid justify="center">
            <v-col class="d-flex justify-center flex-column" md="6">
                <v-sheet>
                    <v-card class="mx-auto elevation-0">
                        <v-card-title>
                            <v-toolbar color="transparent">
                                <v-toolbar-title>Order</v-toolbar-title>

                                <template v-slot:append>
                                    <v-btn class="me-2" icon variant="plain" size="small" @click="closeDialog()">
                                        <v-icon icon="fas fa-close"></v-icon>
                                    </v-btn>

                                </template>
                            </v-toolbar>
                        </v-card-title>

                        <v-card-text v-if="order.order_id">
                            <v-row>
                                <v-col>
                                    <p><strong>OrderId</strong>: {{ order.id }}</p>
                                </v-col>
                                <v-col>
                                    <p><strong>Client:</strong> {{ order.client }}</p>
                                </v-col>
                                <v-col>
                                    <p><strong>Value: </strong>{{ order.value }}</p>
                                </v-col>
                                <v-col>
                                    <p><strong>status:</strong> {{ order.status }}</p>
                                </v-col>
                            </v-row>

                            <v-row fluid v-if="Object.keys(orderInfo).length >= 1">

                                <v-col>
                                    ReasonCode : {{ orderInfo.original.ReasonCode }}

                                </v-col>
                                <v-col>

                                    ReasonMessage: {{ orderInfo.original.ReasonMessage }}

                                </v-col>
                                <v-col>

                                    PaymentId: {{ orderInfo.original.Payments[0].PaymentId }}

                                </v-col>
                                <v-col>


                                    ReceveidData: {{ orderInfo.original.Payments[0].ReceveidDate }}
                                </v-col>


                            </v-row>

                            <v-row v-if="Object.keys(transaction).length >= 1">
                                <v-col>
                                    <v-sheet>
                                        <v-card class="mx-auto">
                                            <v-card-text>
                                                <v-row>
                                                    Order
                                                    <v-divider></v-divider>
                                                    <v-col>
                                                        MerchantOrderId: {{ transaction[0]['MerchantOrderId'] }}
                                                    </v-col>
                                                    <v-col>
                                                        Customer Name: {{ transaction[0]['Customer']['Name'] }}
                                                    </v-col>
                                                    <v-col>
                                                        Customer Email: {{ transaction[0]['Customer']['Email'] }}
                                                    </v-col>

                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        Payment
                                                        <v-divider></v-divider>
                                                        <v-row>
                                                            <v-col>
                                                                Card Number: {{
                                                                    transaction[0]['Payment']['DebitCard']['CardNumber'] }}
                                                            </v-col>
                                                            <v-col>
                                                                Card Holder: {{
                                                                    transaction[0]['Payment']['DebitCard']['Holder'] }}
                                                            </v-col>
                                                            <v-col v-if="transaction[0]['Payment']['Status'] <= 4">
                                                                <strong>Status:</strong>
                                                                <v-chip class="me-2 bg-success">
                                                                    Aproved
                                                                </v-chip>

                                                            </v-col>
                                                            <v-col v-if="transaction[0]['Payment']['Status'] >= 4">
                                                                <strong>Status:</strong>
                                                                <v-chip class="me-2 bg-orange">
                                                                    Pendent
                                                                </v-chip>

                                                            </v-col>
                                                        </v-row>

                                                        <v-row>
                                                            <v-col>
                                                                Expiration Date: {{
                                                                    transaction[0]['Payment']['DebitCard']['ExpirationDate'] }}
                                                            </v-col>
                                                            <v-col>
                                                                Brand: {{ transaction[0]['Payment']['DebitCard']['Brand'] }}
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                Type: {{ transaction[0]['Payment']['Type'] }}
                                                            </v-col>
                                                            <v-col>
                                                                Amount: {{ transaction[0]['Payment']['Amount'] }}
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                Received Date: {{ transaction[0]['Payment']['ReceivedDate']
                                                                }}
                                                            </v-col>
                                                            <v-col>
                                                                Capture Date: {{ transaction[0]['Payment']['CapturedDate']
                                                                }}
                                                            </v-col>

                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                Currency: {{ transaction[0]['Payment']['Currency'] }}
                                                            </v-col>
                                                            <v-col>
                                                                County: {{ transaction[0]['Payment']['Country'] }}
                                                            </v-col>
                                                            <v-col>
                                                                Provider: {{ transaction[0]['Payment']['Provider'] }}
                                                            </v-col>
                                                        </v-row>
                                                    </v-col>
                                                </v-row>
                                            </v-card-text>
                                        </v-card>
                                    </v-sheet>

                                </v-col>
                            </v-row>

                            <v-row v-if="Object.keys(orderInfo).length >= 1">
                                <v-col>
                                    <v-btn-group>
                                        <v-btn class="me-2 elevation-0" variant="flat" color="primary"
                                            @click="getTransaction(orderInfo)">
                                            <v-icon icon="fas fa-magnifying-glass"> </v-icon>
                                        </v-btn>
                                        <v-btn class="me-2 elevation-0" variant="flat" color="primary"
                                            v-if="transaction.length >= 1">
                                            <v-icon icon="fas fa-file-pdf"> </v-icon>
                                        </v-btn>
                                        <v-btn class="me-2 elevation-0" variant="flat" color="primary"
                                            v-if="transaction.length >= 1" @click="refundTransaction()">
                                            Refund
                                        </v-btn>
                                        <v-btn class="me-2 elevation-0" variant="flat" color="primary"
                                            @click="doneOrder(orderInfo)">Done</v-btn>
                                    </v-btn-group>

                                </v-col>
                            </v-row>

                        </v-card-text>

                        <v-card-text v-else>
                            Id não encontrada
                        </v-card-text>

                        <v-card-actions>
                            <v-btn class="me-2" color="primary" variant="flat" @click="search()" block>search</v-btn>
                        </v-card-actions>
                    </v-card>

                    <div>
                        <RefundDialog v-model="refundDialogResult" :message="this.message" @close-dialog="refundDialogResult = false"></RefundDialog>
                    </div>
                </v-sheet>
            </v-col>
        </v-row>



    </v-dialog>
</template>

<script>
import axios from 'axios';
import RefundDialog from '../partials/RefundDialog.vue'

export default {
    props: ['order'],
    components: {
        RefundDialog
    },
    data: () => ({
        orderId: false,
        orderInfo: [],
        transaction: [],
        refund: [],
        refundDialogResult: false,
        message: false,
    }),
    watch: {
        close(val) {
            val || this.closeDialog();
        }
    },
    methods: {
        search() {
            axios.get(`/orders/getOrder/${this.order.id}`)
                .then((response) => {
                    return this.orderInfo = response.data;
                })
                .catch((response) => {
                    console.log('error: ' + response);
                });
        },
        closeDialog() {
            return this.$emit('close-dialog');
        },
        getTransaction() {
            axios.get(`/orders/getTransaction/${this.orderInfo.original.Payments[0].PaymentId}`)
                .then((response) => {
                    return this.transaction.push(response.data);
                })
                .catch((response) => {
                    alert('Error :' + response);
                });
        },
        refundTransaction(){
            let token = document.head.querySelector('meta[name="csrf-token"]').content;
            const amount = {'amount': this.transaction[0]['Payment']['Amount']};
            axios.put(`/orders/refundTransaction/${this.orderInfo.original.Payments[0].PaymentId}`, amount, {
                headers: {
                    'X-CSRF-TOKEN': token,
                }
            })
            .then((response) => {
                console.log(response);
                this.refundDialogResult = true;
                this.message = response;
                return this.refund.push(response);
            })  
            .catch((response) => {
                this.refundDialogResult = true;
                this.message = response;
                return alert('Error:' + response);
            })
        },
        doneOrder() {
            console.log(this.order);
            console.log(this.orderInfo);
            alert('estamos aqui');
        },

    }
}
</script>