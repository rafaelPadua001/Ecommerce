<template>
    <v-list :items="carts" item-props :width="270" :max-height="625" lines="three"
        style="margin: 0.6rem auto; margin-right: -15%; padding: 0%">
        <v-list-item v-for="item in carts" :key="item.id" :value="item.id">
            <div v-if="item && item.cart_item_status == 1">
                <v-row no-gutters justify="center">
                    <v-col cols="auto">
                        <v-card class="mx-auto" elevation="0">
                            <template v-slot:append>
                                <v-btn-group>
                                    <v-btn v-bind="props" icon size="x-small" variant="plain">
                                        <v-icon icon="fas fa-close fa-2xs" @click="removeItem(item)"></v-icon>
                                    </v-btn>
                                </v-btn-group>
                            </template>

                            <v-card-text>
                                <v-row no-gutters>
                                    <v-col cols="auto">
                                        <div v-for="(image, index) in parsedImages(item.images)" :key="index">
                                            <v-avatar rounded="0" v-if="index === 0">
                                                <v-img :vid-id="image" class="align-end text-white" aspect-ratio="1"
                                                    :src="`./storage/products/${image}`"
                                                    :lazy-src="`./storage/products/${image}`">

                                                </v-img>
                                            </v-avatar>
                                        </div>
                                    </v-col>

                                    <div>
                                        <v-col cols="auto">
                                            <v-avatar v-for="(color, index) in parsedColors(item.cart_item_colors)"
                                                :key="index" v-bind="props" :color="color">
                                                <template v-slot:append>

                                                </template>
                                            </v-avatar>

                                        </v-col>
                                    </div>
                                    <div class="d-flex justify-space-around">
                                        <v-col cols="auto" v-for="(size, index) in parsedSizes(item.cart_item_size)"
                                            :key="index">
                                            <v-avatar v-bind="props" color="grey">
                                                <span>{{ size }}</span>
                                            </v-avatar>
                                        </v-col>
                                    </div>
                                    <v-col cols="12">
                                        <div>
                                            <p class="text-body-1">
                                                <span><strong>{{ item.name }}</strong></span>
                                            </p>

                                        </div>
                                    </v-col>

                                    <v-spacer></v-spacer>
                                    <v-col cols="12">
                                        <p class="text-body-2">
                                            <span><strong>Price:</strong></span>
                                            R$ {{ item.cart_item_price }}
                                        </p>
                                    </v-col>


                                    <v-col cols="8">
                                        <!-- <p class="text-body-2">
                                          <strong>Quantity:</strong> {{ item.shippment_quantity }} 
                                        </p> -->

                                    </v-col>
                                    <v-col cols="8">
                                        <p class="text-body-2">
                                            <span><strong>Total:</strong> R$ {{ item.total_price }}</span>
                                        </p>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="auto" sm="2">
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>

                <v-divider></v-divider>
            </div>

        </v-list-item>

        <v-row justify="center" no-gutters>
            <v-col cols="auto">
                <p class="text-body-2"><span><strong>Total:</strong> R$ {{ totalPrice }}</span></p>
            </v-col>
        </v-row>

        <v-row fluid>
            <v-col>
                <v-btn :color="this.appBarColor ?? 'trasparent'" variant="tonal" block
                    @click="redirectToCheckout(this.carts)">Checkout</v-btn>
            </v-col>
        </v-row>


    </v-list>
</template>

<script>
import cartStorage from '@/Services/CartStorage/CartStorage';
export default {
    name: 'cartList',
    props: {
        carts: {
            type: Array,
            required: true
        }
    },
    data: () => {
        totalPrice: 0;
    },
    watch: {
        carts: {
            handler: 'calculateTotalPrice',
            deep: true,
        }
    },
    computed: {
        // Propriedade computada que retorna uma função
        parsedImages() {
            return imagesString => JSON.parse(imagesString);
        },
        parsedColors() {
            return colorsString => JSON.parse(colorsString);
        },
        parsedSizes() {
            return sizesString => JSON.parse(sizesString);
        },
        totalPrice() {
            return this.carts
                .filter(cart => cart.cart_item_status) // Filtra apenas os itens que estão ativos
                .reduce((sum, cart) => {
                    const price = parseFloat(cart.total_price) || 0;
                    return sum + price;
                }, 0)
                .toFixed(2);
        }
    },
    methods: {
        calculateTotalPrice() {
            this.totalPrice = this.carts.reduce((sum, cart) => {
                const price = parseFloat(cart.total_price) || 0;
                return sum + price;
            }, 0).toFixed(2);
        },
        removeItem(item) {
            const cartItemId = item.cart_item_id;
            axios.delete(`/cartItem/delete/${cartItemId}`)
                .then((response) => {
                    return this.carts.splice(this.carts.indexOf(item), 1);
                })
                .catch((response) => {
                    return alert('Error :' + response);
                });
        },
        redirectToCheckout(carts) {
            console.log(carts);
            cartStorage.setCart(carts);
            this.$router.push({ name: 'checkout' });
        }
    },
    mounted() {
        this.calculateTotalPrice();
    }
}
</script>