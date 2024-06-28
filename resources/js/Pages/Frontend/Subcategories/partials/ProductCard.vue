<template>
    <v-sheet class="py-2 px-2">

        <v-hover v-slot="{ isHovering, props }">
            <v-card class="mx-auto elevation-1">
                <v-card-text>
                    <v-card class="mx-auto elevation-0" v-bind="props">
                        <v-toolbar class="bg-transparent">

                            <template v-slot:append>
                                <v-btn-group class="float-end">
                                    <v-btn icon size="x-small">
                                        <v-icon icon="fa-regular fa-heart fa-2xs" v-if="likes.length == 0"
                                            @click="like(product)"></v-icon>
                                        <v-icon v-else color="red-darken-4" icon="fa-solid fa-heart fa-2xs"
                                            @click="dislike(product)"></v-icon>
                                    </v-btn>
                                    <v-btn icon size="x-small">
                                        <v-icon icon="fa-solid fa-share-nodes fa-2xs"></v-icon>

                                    </v-btn>
                                </v-btn-group>
                            </template>
                        </v-toolbar>

                        <div v-for="(image, index) in JSON.parse(product.images)" :key="index">

                            <v-img v-if="index == 0" :vid-id="image" aspect-ratio="1/1"
                                :src="`./storage/products/${image}`" :lazy-src="`./storage/products/${image}`"
                                :height="300" :width="300" cover>
                                <div v-if="product.discount_id" class="d-flex justify-end text-center">
                                    <v-chip class="ma-2" label :color="cardDiscountColor ?? 'orange-darken-4'"
                                        variant="elevated">
                                        - {{ product.discount_percentage * 100 }}%
                                    </v-chip>

                                </div>

                                <div v-if="product.discount_id" class="d-flex justify-end text-center">
                                    <v-chip class="ma-2" label :color="cardDiscountColor ?? 'orange-darken-4'"
                                        variant="elevated">
                                        - {{ product.discount_percentage * 100 }}%
                                    </v-chip>

                                </div>

                                <template>
                                    <div class="d-flex align-center justify-center fill-height">
                                        <v-progress-circular color="grey-lighten-4">
                                        </v-progress-circular>
                                    </div>
                                </template>
                            </v-img>
                        </div>


                        <v-card-text>
                            <v-row fluid>
                                <v-col cols="auto">
                                    <p class="text-h5">{{ product.name }}</p>

                                </v-col>
                            </v-row>
                            <v-row fluid>
                                <!-- <v-col cols="auto">
                                                        <p>{{ product.description }}</p>
                                                    </v-col> -->
                            </v-row>
                            <v-row no-gutters>
                                <v-btn-group>
                                    <v-btn class="me-2" size="x-small" variant="outlined" color="orange"
                                        v-if="product.slug">
                                        {{ product.slug }}
                                    </v-btn>
                                    <v-btn v-if="product.discount_id" class="me-2" size="x-small" variant="outlined"
                                        color="green">
                                        {{ product.discount_percentage * 100 }}% off
                                    </v-btn>
                                </v-btn-group>
                            </v-row>

                            <v-row no-gutters>
                                <v-col cols="auto" md="6" sm="4">
                                    <div v-if="!product.discount_id">
                                        <p>
                                            <strong>R$:</strong>
                                            {{ product.price }}
                                        </p>
                                    </div>
                                    <div v-else>
                                        <div>
                                            <div>
                                                <p>
                                                    <strong>R$:</strong>
                                                    {{ (product.price - (product.price *
            product.discount_percentage)).toFixed(2)
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </v-col>
                                <v-col col="auto" md="6" sm="6">
                                    <p color="red" v-if="product.stock_quantity >= 1">
                                        <strong>Qtd:</strong> {{ product.stock_quantity }}
                                    </p>
                                    <p color="red" v-if="product.stock_quantity === 0">
                                        <strong> Fora de Estoque </strong>
                                    </p>
                                </v-col>
                                <!--  <v-col col="auto" md="5" sm="4">
                          <strong>Solds:</strong> 100
                        </v-col> -->
                            </v-row>
                        </v-card-text>

                        <v-expand-transition>
                            <div v-if="isHovering"
                                class="d-flex transition-fast-in-fast-out bg-grey-darken-3 v-card-menu--reveal text-h2">
                                <v-card-actions>
                                    <v-btn @click="openBuy(product)" block>
                                        <v-icon icon="fas fa-eye"></v-icon>
                                        <v-tooltip activator="parent" location="end">preview</v-tooltip>
                                    </v-btn>
                                </v-card-actions>
                            </div>
                        </v-expand-transition>
                    </v-card>
                </v-card-text>
            </v-card>
        </v-hover>
    </v-sheet>
</template>

<script>
export default {
    name: 'ProductCard',
    props:['product', 'likes'],
    methods: {
        openBuy(product){
            this.$emit('open-buy-dialog', product);
        },
        like(product){
            this.$emit('like-product', product);
        },
        dislike(product){
            this.$emit('dislike-product', product);
        }
        
    }
 }
</script>