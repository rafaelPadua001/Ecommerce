<template>
    <v-container>
        <v-sheet class="px-2 py-2">
            <v-row fluid justify="center">
                <v-col>
                    
                    <v-form>
                        <v-text-field v-model="document" label="CPF do titular" required></v-text-field>
                        <v-text-field v-model="telefone" label="Telefone" outlined
                            v-maska:[phoneOptions]></v-text-field>


                        <v-text-field v-model="cardHolder" label="Nome do titular do cartão" required></v-text-field>
                        <v-text-field v-model="cardNumber" label="Número do Cartão" required></v-text-field>
                        <v-text-field v-model="expiryDate" label="Data de Expiração (MM/AA)" required></v-text-field>
                        <v-text-field v-model="cvv" label="CVV" required></v-text-field>
                        <v-select v-model="cardBrand" :items="cardBrands" label="Marca do Cartão" required></v-select>
                        <v-btn :loading="loading" class="flex-grow-1" variant="tonal" color="primary"
                            @click="load">Pagar</v-btn>
                    </v-form>
                </v-col>
            </v-row>
        </v-sheet>
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

<script setup>
import { ref } from "vue";
import { vMaska } from "maska";

const options = { mask: '#####-####' };
const myValue = ref('');

const phoneOptions = { mask: '55+ (##) #####-####' };
const phoneMask = ref('');
</script>

<script>
import axios from 'axios';

export default {
    emits: ['updateCompleted'],
    props: [
        'carts',
        'billing_address',
        'shippment',
    ],
    data: () => ({
        loading: false,
        paymentType: 'DebitCard',
        paymentSelected: 'cielo',
        paymentResponse: false,
        cardHolder: null,
        telefone: null,
        cardNumber: null,
        document: null,
        expiryDate: null,
        cvv: null,
        cardBrand: null,
        cardBrands: [
            'Visa',
            'Master',
            'Maestro',
            'Elo',
        ],
        snackbar: false,
        message: false,
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
            console.log(this.shippment);
            const data = {
                paymentType: this.paymentType,
                cardHolder: this.cardHolder,
                telefone: this.telefone,
                cardNumber: this.cardNumber,
                document: this.document,
                expiryDate: this.expiryDate,
                cvv: this.cvv,
                cardBrand: this.cardBrand,
                totalValue: this.formatedFinalValue.slice(3).replace(/[.,]/g, ''),
                cartItem: this.carts,
                quantity: this.sumQuantity,
                shippment: {
                    company: this.shippment.company,
                    package: this.shippment.packages,
                    additional_services: this.shippment.additional_services,
                    company_id: this.shippment.id,
                    price: this.shippment.price,
                    delivery_time: this.shippment.delivery_time,

                },
                // delivery: this.delivery,
                // payment: this.paymentSelected,
                // description: this.description,
                // name: this.name,
                // quantity: this.sumQuantity,
                // image: this.image,
                // color: this.color,
                // product_id: this.product_id,
                address: this.billing_address,
                // coupon_id: this.coupon_id,
                // cartItem_id: this.item_id,
                // cart_id: this.cart_id,
            };

         
            axios.post('/payment', data)
                .then((response) => {
                    if (response.data.original.error) {
                       this.snackbar = true;
                        this.message = response.data.original.error;
                        this.loading = false;
                        return false;
                    }
                    this.loading = false;
                    this.paymentResponse = response;
                    return this.completed(this.paymentResponse);

                })
                .catch((response) => {
                    this.loading = false;
                    return alert('Error: ' + response);
                });
        },
        snackbarClose() {
            this.snackbar = false;
        },
        completed(response) {
            return this.$emit('updateCompleted', response);
        }

    }


}
</script>