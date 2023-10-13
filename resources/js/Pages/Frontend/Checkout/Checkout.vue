
<template>
    <div>
        <Dashboard />
    </div>

    <div>
        <v-timeline direction="horizontal">
            <v-timeline-item v-model="confirm" v-if="confirm">
                <template v-slot:opposite>
                    <v-card :width="500">
                        <v-card-text>
                            <v-row>
                                <v-col col="4" md="6">
                                    <div v-for="(image, index) in JSON.parse(itemCart.images)" :key="image.id">
                                        <v-img v-if="index === 0" :vid-id="image" class="align-end text-white" :width="250"
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
                                <v-col col="4" md="4">
                                    <div>
                                        <p><strong>Name Product:</strong> {{ itemCart.name }}</p>
                                    </div>


                                    <div v-for="colors in JSON.parse(itemCart.colors)">
                                        <p><strong>Colors:</strong> {{ colors }}</p>

                                    <div>
                                        <p><strong>Price:</strong> {{ itemCart.price }}</p>

                                    </div>
                                    <div>
                                        <p><strong>Color:</strong> {{ itemCart.color }}</p>


                                    </div>
                                    <div>
                                        <strong>Quantity:</strong> {{ itemCart.quantity }}
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
                    <div class="text-h6">Confirmar pedido</div>
                    <p>
                        {{ itemCart.name }}
                    </p>
                </div>
            </v-timeline-item>

            <v-timeline-item>
                <template v-slot:opposite>
                    <v-card v-model="dataConfirm" v-if="dataConfirm">
                        <v-card-text>
                            <v-row>
                                <v-col col="4" md="6">
                                    <div v-for="(image, index) in JSON.parse(itemCart.images)" :key="image.id">
                                        <v-img v-if="index === 0" :vid-id="image" class="align-end text-white" :width="250"
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
                                <v-col col="4" md="4">

                                    <div>

                                        <p><strong>Customer name:</strong> {{ itemCart.name }}</p>

                                    </div>

                                    <div>
                                        <p><strong>CPF:</strong> {{ colors }}</p>

                                    </div>
                                    <div>
                                        <p><strong>Telefone:</strong> {{ colors }}</p>

                                    </div>
                                    <div>
                                        <p><strong>Celular:</strong> {{ colors }}</p>

                                    </div>
                                    <div>
                                        <strong>Endereço:</strong> {{ itemCart.quantity }}
                                    </div>

                                    <div>
                                        <strong>Zip Code</strong> {{ itemCart.quantity }}
                                    </div>
                                    <div>
                                        <strong>Delivery Value</strong> {{ itemCart.quantity }}
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
                    <div class="text-h6">Datas User and Delivery Confirm</div>
                    <p>
                        {{ itemCart.name }}
                    </p>
                  
                </div>
            </v-timeline-item>

            <v-timeline-item>
                <template v-slot:opposite>

                </template>
                <div>
                    <div class="text-h6">
                        <v-card v-model="finish" v-if="finish">
                            <v-card-text>
                                <v-row>
                                    <v-col col="6" md="6">
                                        <div v-for="(image, index) in JSON.parse(itemCart.images)" :key="image.id">
                                            <v-img v-if="index === 0" :vid-id="image" class="align-end text-white"
                                                :width="250" max-width="250" height="200" aspect-ratio="16/9"
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
                                    <v-col col="4" md="4">
                                        <div>Meios de Pagamento, credito,depito e pix</div>

                                        <div class="text-h6">
                                            {{ itemCart.name }}
                                        </div>

                                        <div>
                        <strong>Cupom</strong> {{ itemCart.quantity }}
                    </div>
                                      <div>
                                            <p><strong>Product:</strong> {{ itemCart.name }}</p>
                                        </div>
                                        <div>
                                        <p><strong>product price:</strong> R$ {{ itemCart.price }}</p>
                                    </div>

                                    <div>
                                        <p><strong>Discount</strong> Discount value</p>

                                    </div>
                                    <div>
                                        <p><strong>Delivery</strong> Delivery value</p>

                                    </div>
                                    <div>
                                        <p><strong>Total price:</strong> total value</p>

                                   </div>
                                  

                                        <div>
                                            <strong>Cupom</strong> {{ itemCart.quantity }}
                                        </div>

                                    </v-col>
                                </v-row>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn-group>
                                    <v-btn @click="returnConfirmDatas()">Voltar</v-btn>
                                    <v-btn @click="confirmDatas()">Confirmar Pedido</v-btn>

                                </v-btn-group>
                            </v-card-actions>
                        </v-card>
                    </div>
                    <p>

                    </p>
                </div>
                <div>
                    <div class="text-h6">Finalizar</div>
                    <p>
                        {{ itemCart.name }}
                    </p>
                  
                </div>
            </v-timeline-item>

        </v-timeline>


    </div>
</template>

<script>
import Dashboard from '../Auth/Dashboard.vue'

export default {
    components: {
        Dashboard,
    },
    data: () => ({
        itemCart: [],
        confirm: true,
        dataConfirm: false,
        finish: false,
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

                axios.get(`/cartsItem/checkout/${itemId}`)
                    .then((response) => {
                        return this.itemCart = response.data;
                    })
                    .catch((response) => {
                        return alert('Error: ' + response);
                    })

            }

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
    }
}
</script>