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
                        <v-combobox v-model="installments" :items="installmentOptions"
                            label="Installments"></v-combobox>
                        <v-select v-model="cardBrand" :items="cardBrands" label="Marca do Cartão" required></v-select>
                        <v-btn :loading="loading" class="flex-grow-1" variant="tonal" color="primary"
                            @click="load">Pagar</v-btn>
                    </v-form>
                </v-col>
            </v-row>
        </v-sheet>
    </v-container>
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
export default {
    props: [
        'paymentType',
        'carts',
        'billing_address',
        'shippment'
    ],
    data: () => ({
        loading: false,
        document: null,
        telefone: null,
        cardHolder: '',
        cardNumber: '',
        cardBrand: null,
        cardBrands: [
            'Visa',
            'Master',
            'Maestro',
            'Elo',
        ],
        expiryDate: '',
        cvv: '',
        bank: '',
        installments: 1, // Valor padrão é 1 (sem parcelamento)
        installmentOptions: [
            1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
        ],
        paymentResponse: false,
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
            }, 500);
        },
        payment() {
            const data = {
                document: this.document,
                cardHolder: this.cardHolder,
                cardNumber: this.cardNumber,
                expiryMonth: this.expiryDate.split('/')[0],
                expiryYear: this.expiryDate.split('/')[1],
                cvv: this.cvv,
                installments: this.installments,
                paymentType: this.paymentType,
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

            };
            axios.post(`/payment`, data)
                .then((response) => {
                    this.loading = false;
                    this.paymentResponse = response;
                    return this.completed(this.paymentResponse);

                })
                .catch((response) => {
                    this.paymentResponse = response;
                    return this.completed(this.paymentResponse);
                   
                });
        },
        completed(response) {
            return this.$emit('updateCompleted', response);
        }
    }
}
</script>