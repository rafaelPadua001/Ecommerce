<template>

    <v-text-field v-model="document" label="CPF" mask="###.###.###-##" hint="Informe seu CPF" persistent-hint outlined
        v-maska:[cpfOptions]></v-text-field>

    <v-btn :loading="loading" class="flex-grow-1" variant="tonal" color="primary" size="small" @click="load">Gerar Qr
        Code</v-btn>

    <div v-if="paymentResponse">
        <div id="status-screen-container"></div>
        <v-row>
            <v-col>

                <v-img
                    :src="'data:image/png;base64,' + paymentResponse.point_of_interaction.transaction_data.qr_code_base64"
                    :lazy-src="paymentResponse.point_of_interaction.transaction_data.qr_code_base64"
                    alt="QR Code"></v-img>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                Pix copia e cola
            </v-col>
            <v-col cols="12" md="12">
                <v-text-field v-model="paymentResponse.point_of_interaction.transaction_data.qr_code" label="Código PIX"
                    outlined variant="underline" append-inner-icon="fas fa-copy" @click:append-inner="copyKey">

                </v-text-field>
            </v-col>

        </v-row>

        <a :href="paymentResponse.point_of_interaction.transaction_data.ticket_url" target="_blank">Link para o
            ticket</a>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { vMaska } from "maska";
import axios from "axios";


const options = { mask: '#####-####' };
const myValue = ref('');

const phoneOptions = { mask: '55+ (##) #####-####' };
const phoneMask = ref('');

const cpfOptions = { mask: '###.###.###-##' };
const cpfMask = ref('');
</script>

<script>
export default {
    props: [
        'paymentType',
        'carts',
        'billing_address',
        'shippment',
        // 'delivery',
        // 'description',
        // 'image'
    ],
    data: () => ({
        document: null,
        paymentResponse: null,
        loading: false,
    }),
    computed: {
        sumQuantity() {
            return this.carts.reduce((total, item) => {
                return total + item.quantity;
            }, 0);
        },
        finalValue() {
            return this.carts.reduce((acc, item) => {
                const total_value = parseFloat(item.total_price) + parseFloat(this.shippment.price);
                return acc + (isNaN(total_value) ? 0 : total_value);
            }, 0);
        },
        formatedFinalValue() {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).format(this.finalValue);
        }
    },
    methods: {
        load() {
            this.loading = true;
            setTimeout(() => {
                this.payment();

            }, 30);
        },
        payment() {
            const data = {
                paymentType: 'Pix',
                document: this.document,
                cartItem: this.carts,
                quantity: this.sumQuantity,
                totalValue: this.formatedFinalValue.slice(3).replace(/[.,]/g, ''),
                shippment: {
                    company: this.shippment.company,
                    package: this.shippment.packages,
                    additional_services: this.shippment.additional_services,
                    company_id: this.shippment.id,
                    price: this.shippment.price,
                    delivery_time: this.shippment.delivery_time,

                },
                payment: this.paymentSelected,
                address: this.billing_address,
            };
            axios.post('/payment', data)
                .then((response) => {
                    this.loading = false;
                    this.paymentResponse = response.data;
                   
                    return this.paymentResponse = response.data;
                })
                .catch((response) => {
                    //   this.loading = false;
                    return alert('Error: ', response);
                })

        },
        copyKey() {
            const campoCodigo = this.paymentResponse.point_of_interaction.transaction_data.qr_code;

            if (campoCodigo) {
                document.execCommand("copy");
                navigator.clipboard.writeText(campoCodigo);
                navigator.clipboard.writeText(campoCodigo);
                alert('Codigo copiado com sucesso');
            } else {
                this.$toast.error("Erro ao copiar o código PIX.");
            }
        },
        
    },
    
    
}
</script>
