<template>
    <div>
        <Dashboard />
    </div>
    <div>
        <v-row no-gutters>
            <v-col class="d-flex flex-column justify-center" cols="auto">
                <v-timeline direction="horizontal"  line-inset="12">
                    <v-timeline-item v-mode="confirm" v-if="confirm" dot-color="blue-darken-2" icon="fas fa-home" fill-dot
                        size="x-small">
                        <template v-slot:opposite>
                            <v-card class="mx-auto" elevation="0">
                                <v-card-text>
                                    <v-row fluid no-gutters>
                                        <v-col cols="auto" v-for="(image, index) in this.productImages" :key="index">
                                            <v-img v-if="index === 0" :src="`/storage/products/${image}`"
                                                :lazy-src="`/storage/products/${image}`" :alt="image" aspect-ratio="16/9"
                                                :width="200" cover>
                                                <v-row justify="end">
                                                    <v-col cols="6">
                                                        <v-chip class="ma-2 bg-white">
                                                            {{ product.quantity }}
                                                        </v-chip>
                                                    </v-col>
                                                </v-row>
                                            </v-img>
                                        </v-col>
                                        <v-col cols="auto" sm="6">
                                            <div>
                                                <strong>{{ product.name }}</strong>

                                            </div>
                                            <div>
                                                <v-card :color="product.color" v-if="product.color">
                                                    color
                                                </v-card>
                                            </div>
                                            <div>
                                                <p>
                                                    <strong>Price</strong>
                                                    {{ product.price }} - {{ product.price * product.discount_percentage }}
                                                </p>
                                                <p>
                                                    <strong v-if="product.coupon_name == 'welcome'">
                                                        With {{ product.coupon_name }} {{ product.discount_percentage
                                                            * 100 }}%
                                                    </strong>
                                                   R$ {{ parseFloat(product.price - (product.price * product.discount_percentage)).toFixed(2) }}

                                                </p>
                                                <p>
                                                <div v-for="item in shippment" :key="item.id">
                                                    <strong>Delivery:</strong>
                                                    {{ item.price }}

                                                    <p>
                                                        <strong>Total: </strong>
                                                        {{
                                                            parseFloat(parseFloat(item.price) +
                                                            parseFloat(product.price) -
                                                            (product.price * product.discount_percentage)).toFixed(2)
                                                        }}
                                                    </p>
                                                    <p>
                                                        <strong>Shippment: </strong> {{ item.name }}
                                                    </p>
                                                    <p>
                                                        <v-row fluid no-gutters>
                                                            <v-col class="d-flex justify-center flex-column">
                                                                <v-text-field v-model="postal_code" v-maska:[options]
                                                                    label="postal code" :placeholder="zip_code"
                                                                    :value="zip_code" v-if="zip_code">
                                                                </v-text-field>
                                                                <v-text-field v-model="postal_code" v-maska:[options]
                                                                    label="postal code" :placeholder="zip_code"  v-else>
                                                                </v-text-field>
                                                                
                                                            </v-col>
                                                            <v-col class="d-flex justify-start flex-column">
                                                                <v-btn size="x-small" variant="text" color="primary"
                                                                    @click="calculateDelivery">calculate</v-btn>
                                                                <v-btn size="x-small" variant="text" color="warning"
                                                                    @click="itemCart.cep = ''">Clear</v-btn>
                                                                <v-btn size="x-small" variant="text" color="blue"
                                                                    @click="searchToAddress">Buscar Cep</v-btn>


                                                            </v-col>
                                                            
                                                        </v-row>
                                                    </p>
                                                    <p>
                                                        <v-col  cols="auto" v-if="address.length >= 1">
                                                            <strong>Logradouro:</strong> {{ address[0].logradouro }},
                                                            <strong v-if="address[0].complemento.length >= 1">
                                                                endereco:
                                                            </strong>
                                                            {{ address[0].complemento }},
                                                            <div v-if="address[0].complemento == ''">
                                                                <strong v-if="address[0].complemento == ''">
                                                                    <v-btn class="mb-2" size="xs" variant="text"
                                                                        color="blue" @click="openAddressDialog">
                                                                        Click to add address
                                                                    </v-btn></strong>
                                                            </div>
                                                            <strong>Bairro:</strong> {{ address[0].bairro }},

                                                            <strong>CEP:</strong> {{ address[0].cep }} ,
                                                            <strong>Localidade:</strong> {{ address[0].localidade }},
                                                            <strong>UF:</strong> {{ address[0].uf }}
                                                            <strong>DDD:</strong> {{ address[0].ddd }}
                                                        </v-col>
                                                    </p>
                                                </div>
                                                </p>

                                            </div>
                                            <div>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn-group>
                                        <v-btn class="me-2" @click="confirmNext()">Confirmar</v-btn>
                                    </v-btn-group>
                                </v-card-actions>
                            </v-card>
                        </template>
                    </v-timeline-item>

                    <v-timeline-item dot-color="blue-darken-2" icon="fas fa-check" fill-dot size="x-small">
                        <template v-slot:opposite>
                            <v-card class="mx-auto" v-model="shippmentConfirm" v-if="shippmentConfirm" elevation="0">
                                <v-card-text>
                                    <v-row fluid>
                                        <v-col class="d-flex justify-center flex-column" cols="auto" md="8" sm="6">
                                            <div>
                                                <v-img :src="`/storage/products/${productImages[0]}`"
                                                    :lazy-src="`/storage/products/${productImages[0]}`" aspect-ratio="16/9"
                                                    :width="200" cover>

                                                    <template>
                                                        <div class="d-flex align-center justify-center fill-height">
                                                            <v-progress-circular color="grey-lighten-4">
                                                            </v-progress-circular>
                                                        </div>
                                                    </template>
                                                </v-img>

                                            </div>
                                        </v-col>

                                        <v-col>
                                            <div>
                                                <p class="text-subititle-1">
                                                    <strong>Customer name:</strong>
                                                    {{ product.first_name }} {{ product.last_name }}

                                                </p>

                                            </div>

                                            <!--  <div>
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

                                            </div> -->
                                            <div>
                                                <p class="text-subtitle-2">
                                                    <strong>Logradouro:</strong>
                                                    {{ address[0].logradouro }}
                                                </p>
                                                <p class="text-subtitle-2" v-if="address[0].complemento.length >= 1">
                                                    <strong>Endereço:</strong> {{ address[0].complemento }}
                                                </p>

                                                <!--  <p class="text-subtitle-2">

                                                    <strong>Complemento:</strong> {{ address[0].complemento }}
                                                </p> -->
                                                <p class="text-subtitle-2">
                                                    <strong>Bairro:</strong> {{ address[0].bairro }}
                                                </p>
                                                <p class="text-subtitle-2">
                                                    <strong>Cidade:</strong> {{ address[0].localidade }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-subtitle-2">
                                                    <strong>Zip Code</strong> {{ address[0].cep }}
                                                </p>
                                                <p class="text-subtitle-2">
                                                    <strong>UF:</strong> {{ address[0].uf }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-subtitle-2">
                                                    <strong>Delivery: </strong>
                                                    {{ shippment[0].currency }} {{ shippment[0].price }}

                                                </p>

                                            </div>

                                            <div>
                                                <p>
                                                    <strong>Total Value</strong>
                                                    {{ shippment[0].currency }}
                                                    {{ (parseFloat(shippment[0].price) +
                                                        parseFloat(product.price) -
                                                        ((product.price * product.discount_percentage)))

                                                    }}
                                                </p>

                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-card-text>

                                <v-card-actions>
                                    <v-btn-group>
                                        <v-btn @click="returnConfirm()">Voltar</v-btn>
                                        <v-btn @click="confirmFinish()">Confirmar</v-btn>
                                    </v-btn-group>
                                </v-card-actions>
                            </v-card>
                        </template>
                    </v-timeline-item>

                    <v-timeline-item dot-color="blue" icon="fas fa-truck" fill-dot size="x-small">
                        <template v-slot:opposite>
                            <v-card class="mx-auto" v-model="finishConfirm" v-if="finishConfirm" elevation="0">
                                <v-card-text>
                                    <v-row no-gutters fluid>
                                        <v-col class="d-flex justify-center flex-column" cols="auto" md="6" sm="3">
                                            <v-img :src="`/storage/products/${productImages[0]}`"
                                                :lazy-src="`/storage/products/${productImages[0]}`" aspect-ratio="16/9"
                                                :width="200" cover>

                                                <template>
                                                    <div class="d-flex align-center justify-center fill-height">
                                                        <v-progress-circular color="grey-lighten-4">
                                                        </v-progress-circular>
                                                    </div>
                                                </template>

                                            </v-img>
                                        </v-col>
                                        <v-col cols="auto" md="6">
                                            <p>
                                                <strong>{{ product.name }}</strong>
                                            </p>

                                            <div>
                                                <p>
                                                    <strong>Total Value</strong>
                                                    {{ shippment[0].currency }}
                                                    {{
                                                        parseFloat(parseFloat(shippment[0].price)
                                                            + parseFloat(product.price) -
                                                        ((product.price * product.discount_percentage))).toFixed(2)

                                                    }}
                                                </p>

                                            </div>

                                            <div v-if="product.coupon_id && product.is_used == 0 && product.coupon_name == 'welcome'">
                                                <strong>Cupom: {{ product.coupon_name }} {{ product.discount_percentage * 100 }}%</strong>
                                            </div>
                                            <div v-else>
                                                select cupons
                                            </div>
                                        </v-col>
                                    </v-row>
                                    <v-row no-gutters>
                                        <v-col class="d-flex justify-end d-flex" cols="auto" md="12" sm="6">
                                            <v-card class="mx-auto" elevation="0">
                                                <v-card-text>
                                                    <v-row fluid no-gutters>
                                                        <v-col class="d-flex justify-center flex-column" cols="auto" md="12" sm="12">
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
                                                </v-card-text>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                    <v-row no-gutters>
                                        <v-col>
                                            <div v-if="paymentType == 'debit'">
                                                <v-card class="mx-auto" elevation="0">
                                                    <DebitForm :paymentType="paymentType" :id="this.product.id"
                                                        :name="this.product.name"
                                                        :totalValue="
                                                                        (parseFloat(shippment[0].price) 
                                                                        + parseFloat(product.price) 
                                                                        - parseFloat(product.price * (product.discount_percentage))).toFixed(2)
                                                                    "
                                                        :quantity="this.product.quantity" :delivery="shippment"
                                                        :description="this.product.description" :image="this.product.images"
                                                        :color="this.product.color" :address="this.address"
                                                        @completed="completed" />
                                                </v-card>
                                            </div>
                                            <div v-if="paymentType == 'credit'">
                                                <v-card class="mx-auto" elevation="0">
                                                    <CreditForm :paymentType="paymentType" :name="this.product.name"
                                                        :totalValue="
                                                                        (parseFloat(shippment[0].price) 
                                                                        + parseFloat(product.price) 
                                                                        - parseFloat(product.price * (product.discount_percentage))).toFixed(2)
                                                                    "
                                                        :quantity="this.product.quantity" :delivery="shippment"
                                                        :description="this.product.description" :image="this.product.images"
                                                        :color="this.product.color" />
                                                </v-card>
                                            </div>
                                            <div v-if="paymentType == 'pix'">
                                                <v-card class="mx-auto" elevation="0">
                                                    <PixForm :paymentType="paymentType" :name="this.product.name"
                                                        :totalValue="
                                                                        (parseFloat(shippment[0].price) 
                                                                        + parseFloat(product.price) 
                                                                        - parseFloat(product.price * (product.discount_percentage))).toFixed(2)
                                                                    "
                                                        :quantity="this.product.quantity" :delivery="shippment"
                                                        :description="this.product.description"
                                                        :image="this.product.images">
                                                    </PixForm>
                                                </v-card>
                                            </div>
                                        </v-col>
                                    </v-row>

                                </v-card-text>

                                <v-card-actions>
                                    <v-btn-group>
                                        <v-btn @click="returnConfirmDatas()">Voltar</v-btn>
                                    </v-btn-group>
                                </v-card-actions>
                            </v-card>
                        </template>
                    </v-timeline-item>
                    <v-timeline-item dot-color="blue-darken-2" icon="fas fa-check" fill-dot size="xs">
                        <template v-slot:opposite>
                            <v-card v-if="completedConfirm" :width="500">
                                <v-card-title class="headline">Pedido Finalizado</v-card-title>
                                <v-card-text>
                                    <v-row>
                                        <v-col>
                                            <v-icon dot-color="green-darken-2" color="green" fill-dot icon="fas fa-check"
                                                size="x-large"> </v-icon>
                                        </v-col>
                                    </v-row>
                                    <!--
                                    <div>
                                        <v-img :src="`/storage/products/${productImages[0]}`"
                                            :lazy-src="`/storage/products/${productImages[0]}`" aspect-ratio="16/9"
                                            :width="200" cover
                                        >
                                          
                                            <template>
                                                <div class="d-flex align-center justify-center fill-height">
                                                    <v-progress-circular color="grey-lighten-4">
                                                    </v-progress-circular>
                                                </div>
                                            </template>
                                        </v-img>

                                    </div> -->

                                    <v-spacer></v-spacer>
                                    <v-btn class="me-2 bg-green" variant="text" @click="redirect()">
                                        Continuar comprando.
                                    </v-btn>
                                </v-card-text>
                            </v-card>
                        </template>
                    </v-timeline-item>
                </v-timeline>
            </v-col>
        </v-row>

        <div class="text-center">
            <v-snackbar v-model="snackbar" :timeout="3500" color="cyan-darken-3" vertical>

                <div class="text-subtitle-1 pb-2">
                    {{ message }}
                </div>
                <template v-slot:actions>
                    <v-btn-group>
                        <v-btn size="small" variant="plain" color="white" to="/">back</v-btn>
                        <v-btn size="small" variant="plain" color="white" :to="`/login`">Login</v-btn>
                    </v-btn-group>
                </template>

            </v-snackbar>

            <v-dialog v-model="address_dialog">
                <v-card class="mx-auto" max-width="800">
                    <v-toolbar>
                        <v-toolbar-title class="bg-transparent-accent">Address form</v-toolbar-title>
                        <template v-slot:append>
                            <v-btn icon variant="plain">
                                <v-icon icon="fas fa-close" @click="closeAddressDialog"></v-icon>
                            </v-btn>
                        </template>
                    </v-toolbar>
                    <v-card-text>
                        <v-row no-gutters>
                            <v-col cols="12">
                                <v-text-field v-model="addressToShippment" label="Address to shippment">

                                </v-text-field>
                            </v-col>
                            <v-col>
                                <v-btn variant="plain" color="primary" size="xs" @click="saveAddress">Salvar</v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Dashboard from '../Auth/Dashboard.vue';
import ZipCode from '../Layout/TextFields/ZipCode.vue';
import DebitForm from '../Payment/DebitForm.vue'
import CreditForm from '../Payment/CreditForm.vue'
import PixForm from '../Payment/PixForm.vue'

export default {
    props: ['shippment', 'zip_code', 'customer'],
    components: {
        Dashboard,
        ZipCode,
        DebitForm,
        CreditForm,
        PixForm,
    },
    emits: ['completed'],
    data: () => ({
        product: [],
        confirm: true,
        shippmentConfirm: false,
        finishConfirm: false,
        completedConfirm: false,
        addressToShippment: false,
        dataConfirm: true,
        finish: true,
        productImages: false,
        snackbar: false,
        message: '',
        postal_code: false,
        address: [],
        address_dialog: false,
        paymentType: false,
        //shippment: [],
    }),
    watch: {
        closeAddressDialog(val) {
            val || this.closeAddressDialog();
        }
    },
    methods: {
        getProducts() {
            axios.get(`/cartItem/buy`)
                .then((response) => {
                    if (!response.address) {
                        this.message = 'No address found...';
                        this.snackbar = true;
                    }
                    this.productImages = JSON.parse(response.data.images);
                    return this.product = response.data;
                })
                .catch((response) => {
                    this.snackbar = true;
                    this.message = response;
                    return false;
                });
        },
        confirmNext() {
            this.dataConfirm = true;
            return this.confirm = false;
        },
        confirmDatas() {
            this.dataConfirm = false;
            return this.finish = true;
        },
        completed() {
            this.finishConfirm = false;
            return this.completedConfirm = true;
        },
        searchToAddress() {
            const cepToFound = this.postal_code != false ? this.postal_code : this.zip_code;
            const data = {
                postal_code: cepToFound,
            };
            axios.get(`https://viacep.com.br/ws/${cepToFound}/json/`)
                .then((response) => {
                    return this.address.push(response.data);
                })
                .catch((response) => {
                    return alert('Error :', response);
                });
        },
        openAddressDialog() {
            this.address_dialog = true;
        },
        saveAddress() {

            this.closeAddressDialog();
            return this.address[0].complemento = this.addressToShippment;
        },
        confirmNext() {
            this.createAddress(
                this.address[0].cep,
                this.addressToShippment,
                this.address[0].bairro,
                this.address[0].localidade,
                this.address[0].uf,
                this.address[0].ibge,
                this.address[0].complemento,
            );
            this.shippmentConfirm = true;
            return this.confirm = false;
        },
        returnConfirm() {
            this.shippmentConfirm = false;
            return this.confirm = true;
        },
        returnConfirmDatas(){
            this.finishConfirm = false;
            return this.confirm = true;
        },
        createAddress(cep, endereco, bairro, city, uf, ibge, complemento) {

            const newData = {
                address: endereco,
                postal_code: cep,
                uf: uf,
                city: city,
                bairro: bairro,
                code_ibge: ibge,
                complemento: complemento ? 'null' : false,

            };

            axios.post('/saveSearchAddress', newData)
                .then((response) => {

                    return this.address = response.data;

                })
                .catch((response) => {
                    return alert('ERROR: ', response);
                });

            this.closeAddressDialog();
        },
        confirmFinish() {
            this.shippmentConfirm = false;
            return this.finishConfirm = true;
        },
        closeAddressDialog() {
            return this.address_dialog = false;
        },
        redirect() {

            return window.location.href = '/';
        }
    },
    created() {
        this.getProducts();


    }

}
</script>