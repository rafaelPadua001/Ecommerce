<template>
    <v-container>

        <v-form @submit.prevent="submitForm">
            <v-text-field v-model="document" label="CPF do titular" required></v-text-field>
            <v-text-field v-model="cardHolder" label="Nome do titular do cartão" required></v-text-field>
            <v-text-field v-model="cardNumber" label="Número do Cartão" required></v-text-field>
            <v-text-field v-model="expiryDate" label="Data de Expiração (MM/AA)" required></v-text-field>
            <v-text-field v-model="cvv" label="CVV" required></v-text-field>
            <v-select v-model="cardBrand" :items="cardBrands" label="Marca do Cartão" required></v-select>
            <v-btn :loading="loading" class="flex-grow-1" variant="tonal" type="submit" color="primary"
                @click="load">Pagar</v-btn>
        </v-form>


    </v-container>
    <div>
        <v-alert v-model="snackbar" :timeout="1500" color="cyan-darken-3" vertical>
            <template v-slot:append>
                <v-btn icon class="me-6" variant="plain" size="xs">
                    <v-icon icon="fas fa-close" @click="snackbarClose()"></v-icon>
                </v-btn>
            </template>
            <div class="text-subtitle-1 pb-2">
                {{ message }}
            </div>
            <template v-slot:actions>
                <v-btn-group>
                    <v-btn size="small" variant="plain" color="white" to="/">back</v-btn>
                    <v-btn size="small" variant="plain" color="white" :to="`/login`">Login</v-btn>
                </v-btn-group>
            </template>
        </v-alert>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: [
        'paymentType',
        'id',
        'name',
        'quantity',
        'totalValue',
        'delivery',
        'description',
        'image',
        'color',
        'address',
    ],
    data: () => ({
        loading: false,
        paymentType: 'debit',
        paymentSelected: 'cielo',
        cardHolder: null,
        cardNumber: null,
        document: null,
        expiryDate: null,
        cvv: null,
        cardBrand: null,
        cardBrands: [
            'Visa',
            'MasterCard',
            'Maestro',
            'Elo',
        ],
        snackbar: false,
        message: false,
    }),
    watch: {
        snackbarClose(val) {
            val || this.snackbarClose();
        }
    },
    methods: {
        load() {
            this.loading = true;
            setTimeout(() => {
                this.payments();
            }, 500);
        },
        payments() {
            const data = {
                paymentType: this.paymentType,
                cardHolder: this.cardHolder,
                cardNumber: this.cardNumber,
                document: this.document,
                expiryDate: this.expiryDate,
                cvv: this.cvv,
                cardBrand: this.cardBrand,
                totalValue: this.totalValue,
                delivery: this.delivery,
                payment: this.paymentSelected,
                description: this.description,
                name: this.name,
                quantity: this.quantity,
                image: this.image,
                color: this.color,
                id: this.id,
                address: this.address,
            };
            axios.post('/payment', data)
                .then((response) => {
                    this.message = 'Seu pagamento foi recebido pela instituição',
                        this.snackbar = true;
                    this.loading = false;
                    return this.updateCompleted();

                })
                .catch((response) => {
                    this.loading = false;
                    return alert('Error: ', response);
                });
        },
        snackbarClose() {
            this.snackbar = false;
        },
        updateCompleted() {
            this.$emit('completed');
        }

    }


}
</script>