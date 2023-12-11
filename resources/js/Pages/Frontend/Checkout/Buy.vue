<template>
    <div>
        <v-row no-gutters>
            <v-col class="d-flex flex-column justify-center">
                <v-timeline  direction="horizontal" side="center"  line-inset="12">
                    <v-timeline-item 
                        v-mode="confirm"
                        v-if="confirm"
                        dot-color="blue-darken-2"
                        icon="fas fa-home"
                        fill-dot
                        size="x-small"
                    >
                        <template v-slot="opposite">
                            <v-card :max-width="800" elevation="0">
                                <v-card-text>
                                    <v-row>
                                        <v-col v-for="(image, index) in this.productImages" :key="index" >
                                            <v-img 
                                                v-if="index === 0"
                                                :src="`/storage/products/${image}`"
                                                :lazy-src="`/storage/products/${image}`"
                                                :alt="image"
                                                aspect-ratio="16/9"
                                                :width="200"
                                                cover>
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
                                                    <strong>Quantidade: </strong>
                                                    {{ product.quantity }}
                                                </p>
                                            </div>

                                            <div class="d-flex flex-column">
                                               <ZipCode :selectProduct="product" :quantity="this.product.quantity"/>
                                            </div>
                                            <div>
                                                <v-row>
                                                    <v-col>

                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    Lista de transportadoras
                                                </v-row>
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
        components: {
            ZipCode,
        },
        data: () => ({
            product: [],
            confirm: true,
            productImages: false,
        }),
        methods: {
            getProducts(){
                axios.get(`/cartItem/buy`)
                .then((response) => {
                    this.productImages = JSON.parse(response.data.images);
                    return this.product = response.data;
                })
                .catch((response) => {
                    return alert('Error:' + response);
                });
            }
        },
        created() {
            this.getProducts();
        }

    }
</script>