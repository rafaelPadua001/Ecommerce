
<template>
    <div>
        <Dashboard />
    </div>

    <div>
        <v-timeline 
            direction="horizontal"
            side="start"
            align="start"
        >
            <v-timeline-item
                v-model="confirm"
                v-if="confirm"
                dot-color="blue-darken-2"
                icon="fas fa-home"
                fill-dot
                 size="x-small"
                
                >
                <template v-slot:opposite>
                    <v-card :width="450">
                        <v-card-text>
                            <v-row>
                                <v-col col="4" md="6">

                                    <v-img :src="`/storage/products/${productImages}`" :lazy-src="`/storage/products/${productImages}`">

                                    </v-img>
                                    <div v-for="(image, index) in itemCart.images" :key="index">

                                    </div>

                                </v-col>
                                <v-col col="4" md="6">
                                    <div>
                                        <p><strong>Name Product:</strong> {{ itemCart.name }}</p>
                                    </div>


                                    <div>
                                        <p><strong>Color:</strong> 
                                            <v-card
                                                :color="itemCart.color"
                                            >
                                                color
                                            </v-card>
                                        </p>

                                    </div>
                                    <div>
                                        <strong>Quantity:</strong> {{ itemCart.quantity }}
                                    </div>
                                    <div>

                                        <v-row>
                                            <v-col col="8" sm="8">
                                                <v-text-field v-model="zip_code" v-if="itemCart.cep" v-maska:[options]
                                                    label="postal code" :placeholder="itemCart.cep" :value="itemCart.cep">
                                                </v-text-field>
                                                <v-text-field v-model="zip_code" v-else v-maska:[options]
                                                    label="postal code" :placeholder="itemCart.cep">
                                                </v-text-field>
                                            </v-col>
                                            <v-col col="4" sm="4">

                                                <v-btn size="x-small" variant="text" color="primary"
                                                    @click="calculateDelivery">calculate</v-btn>
                                                <v-btn size="x-small" variant="text" color="warning"
                                                    @click="itemCart.cep = ''">Clear</v-btn>


                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col>

                                                <v-row>
                                                    <v-col col="12" sm="12">
                                                        <v-card class="mx-auto" max-width="650">
                                                            <v-list lines="three">
                                                                <v-list-item v-for="quotation in quotations"
                                                                    :key="quotation.id" :v-if="!quotation.error">

                                                                    <template v-slot:prepend>

                                                                        <v-avatar :width="550"
                                                                            color="transparent-lighten-1">
                                                                            <v-img :src="quotation.company.picture"
                                                                                :lazy-src="quotation.company.picture"
                                                                                :placeholder="quotation.company.name">
                                                                            </v-img>
                                                                        </v-avatar>
                                                                    </template>

                                                                    <div>
                                                                        <p class="text-body-2">
                                                                            Price:
                                                                            <strong>{{ quotation.currency }}
                                                                                {{ quotation.price }}</strong>
                                                                        </p>
                                                                        <!--<p class="text-body-2">
                                                                                    Discount:
                                                                                    <strong>{{quotation.discount}}</strong>
                                                                                </p>
                                                                             
                                                                                 <p class="text-body-2" v-if="!quotation.error && itemCart.price">
                                                                                    total Value
                                                                                    <strong>
                                                                                        {{ 
                                                                                            (
                                                                                                (parseFloat(selectedDelivery.price) || 0) + 
                                                                                                (parseFloat(itemCart.price) || 0)).toFixed(2) 
                                                                                        }}
                                                                                    </strong>
                                                                                </p> -->
                                                                        <p class="text-body-2">
                                                                            Prazo de entrega:
                                                                            <strong>
                                                                                {{ quotation.delivery_time }}
                                                                                dias úteis
                                                                            </strong>
                                                                        </p>
                                                                    </div>


                                                                    <template v-slot:append>
                                                                        <v-radio-group v-model="selectedDelivery">
                                                                            <v-radio :value="quotation"></v-radio>
                                                                        </v-radio-group>


                                                                    </template>


                                                                </v-list-item>
                                                            </v-list>
                                                        </v-card>

                                                    </v-col>
                                                </v-row>


                                            </v-col>
                                        </v-row>

                                    </div>
                                </v-col>
                            </v-row>
                        </v-card-text>

                        <v-card-actions>
                            <v-btn-group>
                                <v-btn @click="confirmNext()">Confirmar</v-btn>
                            </v-btn-group>
                        </v-card-actions>
                    </v-card>


                    <!-- {{ itemCart }} -->
                </template>
                <div>
                    <div>Confirmar pedido</div>
                    <p>
                        {{ itemCart.name }}
                    </p>
                </div>
            </v-timeline-item>

            <v-timeline-item
                dot-color="blue-darken-2"
                icon="fas fa-check"
                fill-dot
                size="x-small"
            >
                <template v-slot:opposite>
                    <v-card v-model="dataConfirm" v-if="dataConfirm" :width="450">
                        <v-card-text>
                            <v-row>
                                <v-col col="4" md="6">
                                    <div v-for="(image, index) in JSON.parse(itemCart.images)" :key="image.id">
                                        <v-img v-if="index === 0" :vid-id="images" class="align-end text-white" :width="250"
                                            max-width="250" height="200" aspect-ratio="16/9"
                                            :src="`../../storage/products/${image}`"
                                            :lazy-src="`../../storage/products/${image}`" cover>

                                            <template>
                                                <div class="d-flex align-center justify-center fill-height">
                                                    <v-progress-circular color="grey-lighten-4">
                                                    </v-progress-circular>
                                                </div>
                                            </template>

                                        </v-img>
                                    </div>

                                </v-col>
                                <v-col col="12" md="4">

                                    <div>

                                        <p class="text-subititle-1">
                                            <strong>Customer name:</strong>
                                            {{ customer.first_name }}


                                        </p>

                                    </div>
                                    <div>

                                        <p class="text-subititle-1">
                                            <strong>Customer lastname:</strong>
                                            {{ customer.last_name }}

                                        </p>

                                    </div>
                                    <div>
                                        <p class="text-subtitle-2">
                                            <strong>CPF:</strong>

                                        </p>

                                    </div>
                                    <div>
                                        <p class="text-subtitle-2">
                                            <strong>Contact:</strong>
                                            {{ address.telefone }}
                                        </p>

                                    </div>
                                    <div>
                                        <p class="text-subtitle-2"><strong>Celular:</strong> </p>

                                    </div>
                                    <div>
                                        <p class="text-subtitle-2">
                                            <strong>Endereço:</strong> {{ address.endereco }}
                                        </p>
                                        <p class="text-subtitle-2">
                                            <strong>Complemento:</strong> {{ address.complemento }}
                                        </p>
                                        <p class="text-subtitle-2">
                                            <strong>Bairro:</strong> {{ address.bairro }}
                                        </p>
                                        <p class="text-subtitle-2">
                                            <strong>Cidade:</strong> {{ address.cidade }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-subtitle-2">
                                            <strong>Zip Code</strong> {{ address.cep }}
                                        </p>
                                        <p class="text-subtitle-2">
                                            <strong>UF:</strong> {{ address.UF }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-subtitle-2">
                                            <strong>Delivery: </strong>
                                            {{ selectedDelivery.currency }} {{ selectedDelivery.price }}

                                        </p>

                                    </div>

                                    <div>
                                        <p>
                                            <strong>Total Value</strong>
                                            {{ selectedDelivery.currency }}
                                            {{ (parseFloat(selectedDelivery.price) + parseFloat(itemCart.price)).toFixed(2)
                                            }}
                                        </p>

                                    </div>

                                </v-col>
                            </v-row>

                        </v-card-text>

                        <v-card-actions>
                            <v-btn-group>
                                <v-btn @click="returnConfirm()">voltar</v-btn>
                                <v-btn @click="confirmDatas()">Confirmar</v-btn>

                            </v-btn-group>
                        </v-card-actions>
                    </v-card>
                </template>
                <div>
                    <div>Datas User and Delivery Confirm</div>
                    <p>
                        {{ itemCart.name }}
                    </p>

                </div>
            </v-timeline-item>

            <v-timeline-item
                dot-color="blue-darken-2"
                icon="fas fa-truck"
                fill-dot
                size="x-small"
            >
                <template v-slot:opposite>

                </template>
                <div>
                    <div class="text-h6">
                        <v-card v-model="finish" v-if="finish" :width="450">
                            <v-card-text>
                                <v-row>
                                    <v-col col="4" md="6">
                                        <div v-for="(image, index) in JSON.parse(itemCart.images)" :key="image.id">
                                            <v-img v-if="index === 0" :vid-id="image" class="align-end text-white"
                                                :width="250" max-width="250" height="200" aspect-ratio="16/9"
                                                :src="`../../storage/products/${image}`"
                                                :lazy-src="`../../storage/products/${images}`" cover>

                                                <template>
                                                    <div class="d-flex align-center justify-center fill-height">
                                                        <v-progress-circular color="grey-lighten-4">
                                                        </v-progress-circular>
                                                    </div>
                                                </template>

                                            </v-img>
                                        </div>

                                    </v-col>
                                    <v-col col="12" md="4">


                                        <div class="text-h6">
                                            {{ itemCart.name }}
                                        </div>
                                        <div>
                                            <p>
                                                <strong>Total Value</strong>
                                                {{ selectedDelivery.currency }}
                                                {{ (parseFloat(selectedDelivery.price) +
                                                    parseFloat(itemCart.price)).toFixed(2) }}
                                            </p>

                                        </div>

                                        <div>
                                            <strong>Cupom</strong> {{ itemCart.quantity }}
                                        </div>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col>
                                        <v-card>
                                            <v-card-text>
                                                <v-row>
                                                    <v-col col="4" sm="6">
                                                        <v-radio-group v-model="paymentType" inline>
                                                            <v-radio label="Debit" value="debit">

                                                            </v-radio>
                                                            <v-radio label="Credit" value="credit">

                                                            </v-radio>
                                                            <v-radio label="Pix" value="pix">

                                                            </v-radio>
                                                        </v-radio-group>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col>
                                                        <div v-if="paymentType == 'debit'">
                                                            <v-card>
                                                                <DebitForm
                                                                    :paymentType="paymentType"
                                                                    :name="this.itemCart.name"
                                                                    :totalValue="(parseFloat(selectedDelivery.price) + parseFloat(itemCart.price)).toFixed(2)"
                                                                    :quantity="this.itemCart.quantity"
                                                                    :delivery="selectedDelivery"
                                                                    :description="this.itemCart.description"
                                                                    :image="this.itemCart.images" />
                                                            </v-card>
                                                        </div>
                                                        <div v-if="paymentType == 'credit'">
                                                            <v-card>
                                                                <CreditForm :paymentType="paymentType"
                                                                    :name="this.itemCart.name"
                                                                    :totalValue="(parseFloat(selectedDelivery.price) + parseFloat(itemCart.price)).toFixed(2)"
                                                                    :quantity="this.itemCart.quantity"
                                                                    :delivery="selectedDelivery"
                                                                    :description="this.itemCart.description"
                                                                    :image="this.itemCart.images" />


                                                            </v-card>
                                                        </div>
                                                        <div v-if="paymentType == 'pix'">
                                                            <v-card>
                                                                formulario de pagamento por pix
                                                            </v-card>
                                                        </div>
                                                    </v-col>
                                                </v-row>
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn-group>
                                    <v-btn @click="returnConfirmDatas()">Voltar</v-btn>
                                  

                                </v-btn-group>
                            </v-card-actions>
                        </v-card>
                    </div>
                    <p>

                    </p>
                </div>
                <div>
                    <div>Finalizar</div>
                    <p>
                        {{ itemCart.name }}
                    </p>

                </div>
            </v-timeline-item>

        </v-timeline>


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
import Dashboard from '../Auth/Dashboard.vue'
import DebitForm from '../Payment/DebitForm.vue'
import CreditForm from '../Payment/CreditForm.vue'

export default {
    components: {
        Dashboard,
        DebitForm,
        CreditForm,
    },
    data: () => ({
        itemCart: [],
        quotations: [],
        customer: [],
        address: [],
        zip_code: false,
        selectedDelivery: false,
        confirm: true,
        dataConfirm: false,
        finish: false,
        paymentType: false,
        productImages: false,
        colors: false,
    }),
    methods: {
        getProducts() {
            let url = window.location.href;

            // Use uma expressão regular para encontrar o número no final da URL
            let itemId = url.match(/\d+$/);

            // Verifique se o número foi encontrado
            if (itemId !== null) {
                // Converta o número para um valor inteiro
                itemId = parseInt(itemId[0]);

                axios.get(`/cartItem/checkout/${itemId}`)
                    .then((response) => {
                        this.productImages = JSON.parse(response.data.images);
                        this.colors = JSON.parse(response.data.colors);

                        return this.itemCart = response.data;
                    })
                    .catch((response) => {
                        return alert('Error: ' + response);
                    })

            }

        },
        getCustomer() {
            axios.get('/customer')
                .then((response) => {
                    return this.customer = response.data;

                })
                .catch((response) => {
                    return alert('Error:' + response);
                });
        },
        getAddress() {
            axios.get('/address')
                .then((response) => {
                    return this.address = response.data;
                })
                .catch((response) => {
                    return alert('Error: ' + response)
                })
        },
        calculateDelivery() {


            const data = {
                postal_code: this.zip_code,
                height: this.itemCart.height,
                width: this.itemCart.width,
                length: this.itemCart.length,
                weight: this.itemCart.weight,
                price: this.itemCart.price,
                quantity: this.itemCart.quantity,

            }
            axios.post('/api/calculateDelivery', data)
                .then((response) => {
                    return this.quotations = response.data;
                })
                .catch((response) => {
                    alert('Error : ' + response);
                })
        },
        confirmNext() {

            this.dataConfirm = true;
            return this.confirm = false;
        },
        returnConfirm() {
            this.dataConfirm = false;
            return this.confirm = true;
        },
        confirmDatas() {
            this.dataConfirm = false;
            return this.finish = true;
        },
        returnConfirmDatas() {
            this.dataConfirm = true;
            this.finish = false;
        }

    },
    created() {
        this.getProducts();
        this.getCustomer();
        this.getAddress();
    }
}
</script>