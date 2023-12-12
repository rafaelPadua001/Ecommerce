<template>
    <div>
        <v-row no-gutters>
            <v-col class="d-flex flex-column justify-center">
                <v-timeline direction="horizontal" side="center" line-inset="12">
                    <v-timeline-item v-mode="confirm" v-if="confirm" dot-color="blue-darken-2" icon="fas fa-home" fill-dot
                        size="x-small">
                        <template v-slot="opposite">
                            <v-card :max-width="800" elevation="0">
                                <v-card-text>
                                    <v-row>
                                        <v-col v-for="(image, index) in this.productImages" :key="index">
                                            <v-img v-if="index === 0" :src="`/storage/products/${image}`"
                                                :lazy-src="`/storage/products/${image}`" :alt="image" aspect-ratio="16/9"
                                                :width="200" cover>
                                                <v-row justify="end">
                                                    <v-col cols="auto">
                                                        <v-chip class="ma-2 bg-white">
                                                            {{ product.quantity }}
                                                        </v-chip>
                                                    </v-col>
                                                </v-row>
                                            </v-img>
                                        </v-col>
                                        <v-col cols="auto">
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
                                                    <strong>Price</strong> {{ product.price }}

                                                <div v-for="item in shippment" :key="item.id">
                                                    <strong>Delivery:</strong>
                                                    {{ item.price }}

                                                    <p>
                                                        <strong>Total: </strong>

                                                        {{ parseFloat(item.price) + parseFloat(product.price) }}
                                                    </p>
                                                    <p>
                                                        <strong>Shippment: </strong> {{ item.name }}
                                                    </p>
                                                    <p>

                                                        <strong>Cep:</strong> {{ zip_code }}
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
                                        <v-btn @click="confirmNext()">Confirmar</v-btn>
                                    </v-btn-group>
                                </v-card-actions>
                            </v-card>
                        </template>
                    </v-timeline-item>
                </v-timeline>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import axios from 'axios';
import ZipCode from '../Layout/TextFields/ZipCode.vue';
export default {
    props: ['shippment', 'zip_code'],
    components: {
        ZipCode,
    },
    data: () => ({
        product: [],
        confirm: true,
        dataConfirm: true,
        finish: true,
        productImages: false,
        //shippment: [],
    }),
    methods: {
        getProducts() {
            axios.get(`/cartItem/buy`)
                .then((response) => {
                    this.productImages = JSON.parse(response.data.images);
                    return this.product = response.data;
                })
                .catch((response) => {
                    return alert('Error:' + response);
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
        returnConfirmDatas() {
            this.dataConfirm = true;
            this.finish = false;
        },

    },
    created() {
        this.getProducts();
        console.log(this.zip_code);

    }

}
</script>