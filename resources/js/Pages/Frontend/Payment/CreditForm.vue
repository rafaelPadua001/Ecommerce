<template>
    <v-container>
        <!-- <v-form @submit.prevent="submitForm">
            <v-text-field v-model="cardholderName" label="Nome do Titular do Cartão" required></v-text-field>
            <v-text-field v-model="cardNumber" label="Número do Cartão" required></v-text-field>
            <v-text-field v-model="expiryDate" label="Data de Expiração (MM/AA)" required></v-text-field>
            <v-text-field v-model="cvv" label="CVV" required></v-text-field>
            <v-text-field v-model="bank" label="Banco" required></v-text-field>
            <v-select v-model="installments" :items="installmentOptions" label="Parcelas" required></v-select> 
            
        </v-form>
    -->
    <p>Você será redirecionado ao mercado pago</p>
    <v-btn :loading="loading" class="flex-grow-1" variant="tonal" size="small" type="submit" color="blue-darken-2" @click="load">
        <v-img src="../../../../../storage/app/public/Logos/mercado-pago.png" max-height="20" />
        Pagar com Mercado Pago
  </v-btn>
    </v-container>
</template>



<script>
import axios from 'axios';
import { loadMercadoPago } from "@mercadopago/sdk-js";
import { resolveDirective } from 'vue';

await loadMercadoPago();

const mp = new MercadoPago("TEST-48a8cf41-8570-4ec7-975d-25a6114c9677");

export default {
    props: [
        'paymentType',
        'name',
        'quantity',
        'totalValue',
        'delivery',
        'description',
        'image'
    ],
    data: () => ({
        loading: false,
        paymentSelected: 'mercadoPago',
        cardholderName: '',
        cardNumber: '',
        expiryDate: '',
        cvv: '',
        bank: '',
        installments: 1, // Valor padrão é 1 (sem parcelamento)
        installmentOptions: [
            1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
        ]
    }),
    methods: {
        load(){
                this.loading = true;
                setTimeout(() => {
                    this.payment();
                }, 500);
            },
        payment() {
            const data = {
                cardHolderName: this.cardholderName,
                cardNumber: this.cardNumber,
                expriryMonth: this.expiryDate.split('/')[0],
                expriryYear: '20' + this.expiryDate.split('/')[1],
                cvv: this.cvv,
                bank: this.bank,
                installments: this.installments,
                installmentOptions: this.installmentOptions,
                paymentType: this.paymentType,
                totalValue: this.totalValue,
                delivery: this.delivery,
                payment: this.paymentSelected,
                description: this.description,
                name: this.name,
                quantity: this.quantity,
                image: this.image
            };
            axios.post(`/payment`, data)
                .then((response) => {
                    this.loading = false;
                    window.location.href = response.data.original.sandbox_init_point;
                })
                .catch((response) => {
                    alert('Error:' + response);
                });

               // this.checkout();
        },
        

    }
}
</script>