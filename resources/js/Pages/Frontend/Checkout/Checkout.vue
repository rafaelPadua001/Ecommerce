
<template>
    <div>
        <Dashboard />
    </div>

    <div>
        <v-timeline direction="horizontal" side="start" align="start">
            <v-timeline-item v-model="confirm" v-if="confirm" dot-color="blue-darken-2" icon="fas fa-home" fill-dot
                size="x-small">
                <template v-slot:opposite>
                    <v-card class="d-flex align-center flex-column">
                        <v-card-text>
                            <v-row>
                                <v-col col="4" md="6">

                                    <v-img :src="`/storage/products/${productImages}`"
                                        :lazy-src="`/storage/products/${productImages}`">

                                    </v-img>
                                    <div v-for="(image, index) in itemCart.images" :key="index">

                                    </div>

                                </v-col>
                                <v-col col="12" md="8">
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
                                            <v-col col="8" sm="8">
                                                <v-text-field v-model="zip_code" v-if="itemCart.cep" v-maska:[options]
                                                    label="postal code" :placeholder="itemCart.cep" :value="itemCart.cep">
                                                </v-text-field>
                                                <v-text-field v-model="zip_code" v-else v-maska:[options]
                                                    label="postal code" :placeholder="itemCart.cep">
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
                                                <strong>Bairro:</strong> {{ respSearchAddress[0].bairro }},
                                                <strong>Logradouro:</strong> {{ respSearchAddress[0].logradouro }},
                                                <strong>CEP:</strong> {{ respSearchAddress[0].cep }} ,
                                                <strong>Localidade:</strong>  {{ respSearchAddress[0].localidade }},
                                                <strong>UF:</strong>  {{ respSearchAddress[0].uf }}
                                                <strong>DDD:</strong>  {{ respSearchAddress[0].ddd }}
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
                                                                <v-list-item v-for="quotation in quotations"
                                                                    :key="quotation.id" :v-if="!quotation.error">

                                                                    <template v-slot:prepend>

                                                                        <v-avatar :width="120"
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

            <v-timeline-item dot-color="blue-darken-2" icon="fas fa-check" fill-dot size="x-small">
                <template v-slot:opposite>
                    <v-card v-model="dataConfirm" class="d-flex align-center flex-column" v-if="dataConfirm" :width="500">
                        <v-card-text>
                            <v-row>
                                <v-col col="12" md="6">
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

            <v-timeline-item dot-color="blue-darken-2" icon="fas fa-truck" fill-dot size="x-small">
                <template v-slot:opposite>

                </template>
                <div>
                    <div class="text-h6">
                        <v-card v-model="finish" class="d-flex align-center flex-column" v-if="finish" :width="500">
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
                                    <v-col col="6" md="4">


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
                                        <v-card class="mx-auto" :width="450">
                                            <v-card-text>
                                                <v-row>
                                                    <v-col col="12" sm="8">
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
                                                                <PixForm :paymentType="paymentType"
                                                                    :name="this.itemCart.name"
                                                                    :totalValue="(parseFloat(selectedDelivery.price) + parseFloat(itemCart.price)).toFixed(2)"
                                                                    :quantity="this.itemCart.quantity"
                                                                    :delivery="selectedDelivery"
                                                                    :description="this.itemCart.description"
                                                                    :image="this.itemCart.images"></PixForm>
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
    <div>
        <v-dialog v-model="cepDialog" persistent width="1024">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Busca Cep</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            
                           <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="uf" label="UF"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="city" label="Cidade"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="bairro" label="Bairro"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="searchAddress" label="logradouro"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="complemento" label="Complemento"></v-text-field>
                            </v-col>
                            
                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                  
               
                    <div v-if="respSearchAddress.length >= 1">
                        <v-row>
                            <v-col>
                                CEP: {{ respSearchAddress[0].cep }} ,
                                Logradouro: {{ respSearchAddress[0].logradouro }},
                                Localidade:  {{ respSearchAddress[0].localidade }},
                                UF:  {{ respSearchAddress[0].uf }}
                                DDD:  {{ respSearchAddress[0].ddd }}
                            </v-col>
                        </v-row>
                      
                    </div>
                  
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="cepDialog = false">
                        Close
                    </v-btn>
                    <v-btn color="blue-darken-1" variant="text" @click="searchToAddress">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
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
import PixForm from '../Payment/PixForm.vue'
import axios from "axios";

export default {
    components: {
        Dashboard,
        DebitForm,
        CreditForm,
        PixForm,
    },
    data: () => ({
        itemCart: [],
        quotations: [],
        customer: [],
        address: [],
        respSearchAddress: [],
        zip_code: false,
        selectedDelivery: false,
        confirm: true,
        dataConfirm: false,
        finish: false,
        paymentType: false,
        productImages: false,
        colors: false,
        cepDialog: false,
        searchAddress: null,
        postal_code: null,
        uf: null,
        city: null,
        bairro: null,
        complemento: null
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
        searchToAddress() {
            const data = { 
                address: this.searchAddress,
                postal_code: this.postal_code,
                uf: this.uf,
                city: this.city,
                bairro: this.bairro,
                complemento: this.complemento
            };
           
            axios.get(`https://viacep.com.br/ws/${this.uf}/${this.city}/${this.searchAddress.replace(/\s/g, '+' )}/json/`)
                .then((response) => {
                    this.itemCart.cep = " ";
                    this.itemCart.cep = response.data[0].cep;
                    this.address.endereco = response.data[0].logradouro;
                    this.address.bairro = response.data[0].bairro;
                    this.address.cep = response.data[0].cep;
                    this.address.cidade = response.data[0].cidade;
                    this.address.UF = response.data[0].uf;
                    //this.address.complemento = response.data[0].complemento;
                    this.cepDialog = false;
                    this.saveAddress(
                        this.itemCart.cep,
                        this.address.endereco,
                        this.address.bairro,
                        this.city,
                        response.data[0].uf,
                        response.data[0].ibge,
                        this.complemento,
                    );

                    return this.respSearchAddress.push(response.data[0]);
                    
                })  
                .catch((response) => {
                    return alert('Error :', response);
                })
        },
        saveAddress(cep, endereco, bairro, city, uf, ibge, complemento){
           
          const newData = {
              address: endereco,
              postal_code: cep,
              uf:  uf,
              city: city,
              bairro: bairro,
              code_ibge: ibge,
              complemento: complemento,
              
          };
          console.log(newData);
             axios.post('/saveSearchAddress', newData)
             .then((response) => {
              
                return this.address = response.data;
                
             })
             .catch((response) => {
                 return alert('ERROR: ', response);
             });
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
        cepDialogOpen() {
            this.cepDialog = true;

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