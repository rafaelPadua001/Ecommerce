<template>
    <!-- <div>
        <Dashboard />
    </div> -->
    <v-container>
        <v-sheet class="py-2 px-2">
            <div v-if="!products">
                <v-row justify="center" no-gutters>
                    <v-col class="d-flex flex-column" cols="auto">
                        <v-timeline direction="horizontal" line-inset="12">
                            <v-timeline-item dot-color="blue-darken-2" icon="fas fa-home" fill-dot size="x-small">
                                <template v-slot:opposite>
                                    <v-row no-gutters v-model="confirm" v-if="confirm">
                                        <v-col cols="auto">
                                            <v-card class="mx-auto">
                                                <v-card-text>

                                                    <v-row fluid>
                                                        <v-col>
                                                            <Contact :customer="this.customer" :carts="this.carts"
                                                                :address="this.address" :confirm="this.confirm" 
                                                                :quantity="this.quantity"
                                                                @confirmNext="confirmNext"
                                                               />
                                                        </v-col>
                                                    </v-row>
                                                </v-card-text>
                                            </v-card>
                                        </v-col>
                                    </v-row>

                                </template>
                            </v-timeline-item>

                            <v-timeline-item v-model="dataConfirm" v-if="dataConfirm && !confirm"
                                dot-color="blue-darken-2" icon="fas fa-check" fill-dot size="small"
                                class="justify-start">
                                <template v-slot:opposite>

                                </template>
                                <DataConfirm 
                                    :carts="this.carts"
                                    :billing_address="this.billing_address"
                                    :address="this.address" :customer="this.customer"
                                    @confirmDatas="confirmDatas"
                                    @returnConfirm="returnConfirm"></DataConfirm>
                               
                               
                            </v-timeline-item>

                            <v-timeline-item v-model="finish" dot-color="blue-darken-2" icon="fas fa-truck" fill-dot
                                size="small" v-if="finish && !dataConfirm && !confirm">
                                <template v-slot:opposite>

                                </template>
                                <Finish
                                    :carts="this.carts"
                                    :billing_address="this.billing_address"
                                    :shippment="this.shippment"
                                    @returnConfirmDatas="returnConfirmDatas"
                                    @updateCompleted="updateCompleted"
                                />
                               
                            </v-timeline-item>
                            <v-timeline-item v-model="completed" dot-color="blue-darken-2" icon="fas fa-truck" fill-dot
                                size="small" v-if="completed && !finish && !dataConfirm && !confirm"
                            >
                                <Completed 
                                    @updateCompleted="updateCompleted"
                                />
                            </v-timeline-item>
                        </v-timeline>

                        <div>
                            <v-dialog v-model="removeDialog">
                                <v-card>
                                    <v-toolbar>
                                        <v-toolbar-title>{{ editedItem.name }}</v-toolbar-title>
                                    </v-toolbar>
                                    <v-card-text>
                                        Você deseja remover este Item ? {{ editedItem.cart_item_id }}
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-btn @click="closeRemoveItemDialog()">
                                            Close
                                        </v-btn>
                                        <v-btn color="error" @click="removeItemConfirm()">
                                            Confirm
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </div>
                    </v-col>
                </v-row>

            </div>
            <!-- <div v-else>
                CartItems: {{ carts }}
                <v-row align="center" justify="center">
                    <v-col cols="8" sm="8">
                        <v-timeline direction="horizontal" side="center" line-inset="12">
                            <v-timeline-item v-model="confirm" v-if="confirm" dot-color="blue-darken-2"
                                icon="fas fa-home" fill-dot size="x-small">
                                <template v-slot:opposite>
                                    <v-card class="d-flex justify-center flex-column" :max-width="500">
                                        <v-card-text>
                                            <v-row>
                                                <v-col class="d-flex child-flex">
                                                    <v-img :src="`/storage/products/${productImages}`"
                                                        :lazy-src="`/storage/products/${productImages}`"
                                                        aspect-ratio="16/9" :width="200" cover>

                                                    </v-img>


                                                </v-col>
                                                <v-col>
                                                    <div>
                                                        <p><strong>Name Product:</strong> {{ itemCart.name }}</p>
                                                    </div>


                                                    <div>
                                                        <p><strong>Color:</strong>
                                                            <v-card :color="itemCart.color">
                                                                color
                                                            </v-card>
                                                        </p>

                                                    </div>
                                                    <div>
                                                        <strong>Quantity:</strong> {{ itemCart.quantity }}
                                                    </div>
                                                    <div>
                                                        <v-row fluid>
                                                            <v-col>
                                                                <v-text-field v-model="zip_code" v-if="itemCart.cep"
                                                                    v-maska:[options] label="postal code"
                                                                    :placeholder="itemCart.cep" :value="itemCart.cep">
                                                                </v-text-field>
                                                                <v-text-field v-model="billind_address.zip_code" v-else
                                                                    v-maska:[options] label="postal code"
                                                                    :placeholder="itemCart.cep">
                                                                </v-text-field>
                                                            </v-col>
                                                            <v-col col="8" sm="4">

                                                                <v-btn size="x-small" variant="text" color="primary"
                                                                    @click="calculateDelivery">calculate</v-btn>
                                                                <v-btn size="x-small" variant="text" color="warning"
                                                                    @click="itemCart.cep = ''">Clear</v-btn>
                                                                <v-btn size="x-small" variant="text" color="blue"
                                                                    @click="cepDialogOpen">Buscar Cep</v-btn>


                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col v-if="respSearchAddress.length >= 1">
                                                                <strong>endereco:</strong> {{ address.complemento }},
                                                                <strong>Bairro:</strong> {{ respSearchAddress[0].bairro
                                                                }},
                                                                <strong>Logradouro:</strong> {{
                respSearchAddress[0].logradouro
            }},
                                                                <strong>CEP:</strong> {{ respSearchAddress[0].cep }} ,
                                                                <strong>Localidade:</strong> {{
                respSearchAddress[0].localidade
            }},
                                                                <strong>UF:</strong> {{ respSearchAddress[0].uf }}
                                                                <strong>DDD:</strong> {{ respSearchAddress[0].ddd }}
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>

                                                        </v-row>
                                                        <v-row>
                                                            <v-col col="12" sm="12">
                                                                <v-row>
                                                                    <v-col col="12" sm="12">
                                                                        <v-card class="mx-auto" :width="255">
                                                                            <v-list lines="three">
                                                                                <v-list-item
                                                                                    v-for="quotation in quotations"
                                                                                    :key="quotation.id"
                                                                                    :v-if="!quotation.error">

                                                                                    <template v-slot:prepend>

                                                                                        <v-avatar :width="120"
                                                                                            color="transparent-lighten-1">
                                                                                            <v-img
                                                                                                :src="quotation.company.picture"
                                                                                                :lazy-src="quotation.company.picture"
                                                                                                :placeholder="quotation.company.name">
                                                                                            </v-img>
                                                                                        </v-avatar>
                                                                                    </template>

                                                                                    <div>
                                                                                        <p class="text-body-2">
                                                                                            Price:
                                                                                            <strong>{{
                quotation.currency }}
                                                                                                {{ quotation.price
                                                                                                }}</strong>
                                                                                        </p>
                                                                                        <p class="text-body-2">
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
                                                                                </p> 
                                                                                        <p class="text-body-2">
                                                                                            Prazo de entrega:
                                                                                            <strong>
                                                                                                {{
                quotation.delivery_time
            }}
                                                                                                dias úteis
                                                                                            </strong>
                                                                                        </p>
                                                                                    </div>


                                                                                    <template v-slot:append>
                                                                                        <v-radio-group
                                                                                            v-model="selectedDelivery">
                                                                                            <v-radio
                                                                                                :value="quotation"></v-radio>
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


                                    
                                </template>
                                <div>
                                    <div>Confirmar pedido</div>
                                    <p>
                                        {{ itemCart.name }}
                                    </p>
                                </div>
                            </v-timeline-item>

                            <v-timeline-item dot-color="blue-darken-2" icon="fas fa-check" fill-dot size="x-small">
                                <template v-slot:opposite>
                                    <v-card v-model="dataConfirm" class="d-flex align-center flex-column"
                                        v-if="dataConfirm" :width="500">
                                        <v-card-text>
                                            <v-row>
                                                <v-col class="d-flex child-flex" cols="6">
                                                    <div>
                                                        <v-img :src="`/storage/products/${productImages}`"
                                                            :lazy-src="`/storage/products/${productImages}`"
                                                            aspect-ratio="16/9" :width="200" cover>


                                                            <template>
                                                                <div
                                                                    class="d-flex align-center justify-center fill-height">
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
                                                            {{ (parseFloat(selectedDelivery.price) +
                parseFloat(itemCart.price)).toFixed(2)
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

                            <v-timeline-item dot-color="blue-darken-2" icon="fas fa-truck" fill-dot size="x-small">
                                <template v-slot:opposite>

                                </template>
                                <div>
                                    <div class="text-h6">
                                        <v-card v-model="finish" class="d-flex align-center flex-column" v-if="finish"
                                            :width="500">
                                            <v-card-text>
                                                <v-row>
                                                    <v-col class="d-flex child-flex" cols="6">
                                                        <div>

                                                            <v-img :src="`/storage/products/${productImages}`"
                                                                :lazy-src="`/storage/products/${productImages}`"
                                                                aspect-ratio="16/9" :width="200" cover>

                                                                <template>
                                                                    <div
                                                                        class="d-flex align-center justify-center fill-height">
                                                                        <v-progress-circular color="grey-lighten-4">
                                                                        </v-progress-circular>
                                                                    </div>
                                                                </template>

                                                            </v-img>

                                                        </div>
                                                    </v-col>
                                                    <v-col cols="6" md="4">
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
                                                    <v-col cols="8" sm="6">
                                                        <v-card class="mx-auto" :width="450">
                                                            <v-card-text>
                                                                <v-row>
                                                                    <v-col cols="8" sm="8">
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
                                                                                <DebitForm :paymentType="paymentType"
                                                                                    :id="this.itemCart.id"
                                                                                    :name="this.itemCart.name"
                                                                                    :totalValue="(parseFloat(selectedDelivery.price) + parseFloat(itemCart.price)).toFixed(2)"
                                                                                    :quantity="this.itemCart.quantity"
                                                                                    :delivery="selectedDelivery"
                                                                                    :description="this.itemCart.description"
                                                                                    :image="this.itemCart.images"
                                                                                    :color="this.itemCart.color"
                                                                                    :address="this.address" />
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
                                                                                    :image="this.itemCart.images"
                                                                                    :color="this.itemCart.color" />


                                                                            </v-card>
                                                                        </div>
                                                                        <div v-if="paymentType == 'pix'">
                                                                            <v-card>
                                                                                <PixForm :paymentType="paymentType"
                                                                                    :name="this.itemCart.name"
                                                                                    :totalValue="(parseFloat(selectedDelivery.price) + parseFloat(itemCart.price)).toFixed(2)"
                                                                                    :quantity="this.itemCart.quantity"
                                                                                    :delivery="selectedDelivery"
                                                                                    :description="this.itemCart.description"
                                                                                    :image="this.itemCart.images">
                                                                                </PixForm>
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
                    </v-col>
                </v-row>
            </div> -->
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
import Dashboard from '../Auth/Dashboard.vue'
import DebitForm from '../Payment/DebitForm.vue'
import CreditForm from '../Payment/CreditForm.vue'
import PixForm from '../Payment/PixForm.vue'
import cartStorage from '@/Services/CartStorage/CartStorage';
import Contact from './partials/Contact.vue';
import DataConfirm from './partials/DataConfirm.vue';
import Finish from './partials/Finish.vue';
import Completed from './partials/Completed.vue';
import axios from "axios";

export default {
    name: 'Checkout',
    components: {
        Dashboard,
        Contact,
        DataConfirm,
        Finish,
        Completed,
        DebitForm,
        CreditForm,
        PixForm,
    },

    data: () => ({
        customer: [],
        address: [],
        paymentType: false,
        confirm: true,
        dataConfirm: false,
        finish: false,
        completed: false,
        productImages: false,
        colors: false,
        postal_code: null,
        uf: null,
        city: null,
        bairro: null,
        complemento: null,
        carts: [],
        billing_address: {
            shippment_address: '',
            shippment_complement: '',
            shippment_city: '',
            select_uf: { state: 'Distrito Federal', uf: 'DF' },
            zip_code: false,
            city: null,
            bairro: null,
        },
        email: '',
        emailRules: [
            v => !!v || 'O email é obrigatório',
            v => /.+@.+\..+/.test(v) || 'Email inválido',
        ],
        ufs: [
            { state: 'Distrito Federal', uf: 'DF' },
            { state: 'Goiás', uf: 'GO' },
            { state: 'Maranhão', uf: 'MA' },
        ],
        index: -1,
        editedItem: {},
        removeDialog: false,
        shippment: [],
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
        confirmNext(data) {
            console.log(data);
            this.billing_address = data.billing_address;
            this.shippment = data.shippment;
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
        },
        couponApply() {
            alert('Working this...');
        },
        removeItemDialog(item) {
            this.index = this.carts.findIndex(cartItem => cartItem.cart_item_id === item.cart_item_id);
            this.editedItem = Object.assign({}, item);
            this.removeDialog = true;
        },
        removeItemConfirm() {

            this.carts.splice(this.index, 1);
            this.closeRemoveItemDialog();

        },
        closeRemoveItemDialog() {
            this.removeDialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, '');
                this.index = -1;
            });
        },
        updateCompleted(){
            
            this.completed = true;
            return this.finish = false;
        }
    },
    created() {
        this.getProducts();
        this.getCustomer();
        this.getAddress();
        this.carts = cartStorage.getCart();
        if (!this.carts) {
            this.$router.push({ name: 'cart' });
        }
    }
}
</script>

<style scoped>
.avatar-stack {
    position: absolute;
    transition: transform 0.3s ease;

}

.avatar-stack:hover {
    transform: translateY(-10px);
}

.container-relative {
    position: relative;
    height: 100%;

}

.container-absolute {
    position: relative;
    margin-left: 50%;
}

.text-caption {
    display: flex;
    margin-left: 32%;
    /* Espaço entre nome e preço */
}
</style>