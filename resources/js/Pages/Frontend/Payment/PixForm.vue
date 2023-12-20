<template>
   
    <v-text-field v-model="cpf" label="CPF" mask="###.###.###-##" hint="Informe seu CPF" persistent-hint outlined
        v-maska:[cpfOptions]></v-text-field>
    <v-btn :loading="loading" class="flex-grow-1" variant="tonal" color="primary" size="small" @click="load">Gerar Qr
        Code</v-btn>
    <div v-if="paymentResponse">
        <v-row>
            <v-col>

                <v-img
                    :src="'data:image/png;base64,' + paymentResponse.original.point_of_interaction.transaction_data.qr_code_base64"
                    :lazy-src="paymentResponse.original.point_of_interaction.transaction_data.qr_code_base64"
                    alt="QR Code"></v-img>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                Pix copia e cola
            </v-col>
            <v-col cols="12" md="12">

                <v-text-field v-model="paymentResponse.original.point_of_interaction.transaction_data.qr_code"
                    label="Código PIX" outlined variant="underline" append-inner-icon="fas fa-copy"
                    @click:append-inner="copyKey">

                </v-text-field>


            </v-col>

        </v-row>

        <a :href="paymentResponse.original.point_of_interaction.transaction_data.ticket_url" target="_blank">Link para o
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
        'name',
        'quantity',
        'totalValue',
        'delivery',
        'description',
        'image'
    ],
    data: () => ({
        cpf: null,
        paymentResponse: null,
        loading: false,
    }),
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
                totalValue: this.totalValue,
                delivery: this.delivery,
                payment: this.paymentSelected,
                description: this.description,
                name: this.name,
                quantity: this.quantity,
                image: this.image,
                cpfPayer: this.cpf
            };
            axios.post('/payment', data)
                .then((response) => {
                    this.loading = false;
                    
                    return this.paymentResponse = response.data;
                })
                .catch((response) => {
                    //   this.loading = false;
                    return alert('Error: ', response);
                })

        },
        copyKey() {
            const campoCodigo = this.paymentResponse.original.point_of_interaction.transaction_data.qr_code;

            if (campoCodigo) {
                document.execCommand("copy");
                navigator.clipboard.writeText(campoCodigo);
                navigator.clipboard.writeText(campoCodigo);
                alert('Codigo copiado com sucesso');
            } else {
                this.$toast.error("Erro ao copiar o código PIX.");
            }
        }
    }
}
</script>