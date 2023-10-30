<template>
    <v-container>
        <v-form @submit.prevent="submitForm">
            <v-text-field v-model="cardHolder" label="Nome do titular do cartão" required></v-text-field>
            <v-text-field v-model="cardNumber" label="Número do Cartão" required></v-text-field>
            <v-text-field v-model="expiryDate" label="Data de Expiração (MM/AA)" required></v-text-field>
            <v-text-field v-model="cvv" label="CVV" required></v-text-field>
            <v-select
                v-model="cardBrand"
                :items="cardBrands"
                label="Marca do Cartão"
                required
            ></v-select>
            <v-btn type="submit" color="primary" @click="payments">Pagar</v-btn>
        </v-form>
    </v-container>
</template>

<script>
import axios from 'axios';

    export default{
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
            paymentType: 'debit',
            paymentSelected: 'mercadoPago',
            cardHolder: null,
            cardNumber: null,
            expiryDate: null,
            cvv: null,
            cardBrand: null,
            cardBrands: [
                'Visa',
                'MasterCard',
                'Maestro',
                'Elo',
            ],
        }),
        methods:{
            payments(){
                const data = {
                    paymentType: this.paymentType,
                    cardHolder: this.cardHolder,
                    cardNumber: this.cardNumber,
                    expiryDate: this.expiryDate,
                    cvv: this.cvv,
                    cardBrand: this.cardBrand,
                    totalValue: this.totalValue,
                    delivery: this.delivery,
                    payment: this.paymentSelected,
                    description: this.description,
                    name: this.name,
                    quantity: this.quantity,
                    image: this.image
                };
                axios.post('/payment', data)
                .then((response) => {
                    console.log(response);
                })
                .catch((response) => {
                    return alert('Error: ', response);
                })
                console.log(data);
            }
        }


    }
</script>