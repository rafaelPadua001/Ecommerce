<template>
    <v-dialog :max-width="1200" v-model="dialog">
        <v-card :max-width="1200">
            <v-card-title>
                <v-toolbar class="bg-transparent">
                    Buy
                    <template v-slot:append>
                        <v-btn-group>
                            <v-btn v-bind="props" icon size="small" @click="like">
                                <v-icon icon="fa-regular fa-heart fa-2xs" v-if="!likes"></v-icon>
                                <v-icon color="red-darken-4" icon="fa-solid fa-heart fa-2xs" v-else></v-icon>
                            </v-btn>
                            <v-btn v-bind="props" icon @click="" size="small">
                                <v-icon icon="fas fa-share-nodes fa-2xs"></v-icon>
                            </v-btn>
                            <v-btn v-bind="props" icon @click="closeBuy()" size="small">
                                <v-icon icon="fas fa-close fa-2xs"></v-icon>
                            </v-btn>
                        </v-btn-group>

                    </template>
                </v-toolbar>
            </v-card-title>

            <v-divider></v-divider>
            <v-spacer></v-spacer>

            <v-card-text>
                <v-row no-gutters>
                    <v-col col="auto" md="6" sm="2">
                        <v-sheet class="ma-2 pa-2">
                            <v-row>
                                <v-col v-for="(image, index) in JSON.parse(selectProduct.images)" cols="2" sm="3">
                                    <v-hover v-slot="{ isHovering, props }" open-delay="200">
                                        <v-card width="50" class="mx-auto" v-bind="props"
                                            :color="isHovering ? 'cyan-darken-4' : undefined" elevation="0">
                                            <template :class="{ 'on-hover': isHovering }" class="mx-auto" v-bind="props">

                                            </template>
                                            <div :key="index >= 1">
                                                <v-img cover :lazy-src="`./storage/products/${image}`"
                                                    :src="`./storage/products/${image}`" @click="alterImage(index)">
                                                    <template v-slot:placeholder>
                                                        <div class="d-flex align-center justify-center fill-height">
                                                            <v-progress-circular color="grey-lighten-4"
                                                                indeterminate></v-progress-circular>
                                                        </div>
                                                    </template>


                                                </v-img>
                                            </div>
                                        </v-card>
                                    </v-hover>


                                </v-col>
                                <v-col>
                                    <v-card :min-width="150" :max-width="1500" :height="400" elevation="0">
                                        <div v-for="(image, index) in JSON.parse(selectProduct.images)" :key="index"
                                            class="image-container">
                                            <v-img v-if="index === selectImageIndex"
                                                :lazy-src="`./storage/products/${image}`"
                                                :src="`./storage/products/${image}`" class="zoomable-image">

                                                <template v-slot:placeholder>
                                                    <div class="d-flex align-center justify-center fill-height">
                                                        <v-progress-circular color="grey-lighten-4"
                                                            indeterminate></v-progress-circular>
                                                    </div>
                                                </template>
                                            </v-img>
                                        </div>
                                    </v-card>

                                </v-col>

                            </v-row>
                            <v-spacer></v-spacer>
                            <v-divider></v-divider>

                        </v-sheet>
                    </v-col>

                    <v-col col="12" sm="6">
                        <p justify="start" class="bg-grey-lighten-4">
                            {{ selectProduct.name }}
                        </p>
                        <v-divider> </v-divider>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>

                        <p float="end" class="text-h5" color="red">
                            Price: R$ {{ selectProduct.price }}
                        </p>
                        <p v-for="item in shippment" :key="item.id">
                            Delivery: R$ {{ item.price }}
                        </p>
                        <p float="end" class="text-h6" color="red" v-if="selectProduct.stock_quantity >= 1">
                            {{ selectProduct.unity }}: {{ selectProduct.stock_quantity }}
                        </p>

                        <p color="red" v-else>
                            <strong> Fora de Estoque </strong>
                        </p>
                        <div v-if="selectProduct.availability == 1" justify="start">
                            <v-responsive class="mx-auto">
                                <v-rating v-model="rating" bg-color="orange-lighten-1" color="blue"
                                    size="x-small"></v-rating>
                            </v-responsive>

                            <!--   count availation: ({{selectProduct.stock_qua}})este 2 -->
                        </div>
                        <div v-if="selectProduct.colors">
                            <p>Colors:</p>
                            <v-row no-gutters>
                                <v-col cols="2" sm="2" md="2" v-for="(color, index) in JSON.parse(selectProduct.colors)"
                                    :key="index">
                                    <v-hover>
                                        <template v-slot:default="{ isHovering, props }">
                                            <v-card @click="getColors(color)" v-bind="props" :bg-color="color"
                                                :color="isHovering ? undefined : color" :width="60">
                                                <template v-slot:append>

                                                </template>
                                            </v-card>
                                        </template>
                                    </v-hover>

                                </v-col>
                            </v-row>
                        </div>

                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>

                        <div v-if="selectProduct.size >= 1">
                            <p>Size:</p>
                            <v-row>
                                <v-col cols="2" sm="2" md="2" v-for="(size, index) in JSON.parse(selectProduct.size)"
                                    :key="index">
                                    <v-card :color="color" :width="40">
                                        <template v-slot:append>
                                            {{ size }}
                                        </template>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </div>

                        <div justify="start">
                            <v-responsive width="150" max-width="165" justify="start">

                                <v-text-field v-model="quantity" label="Quantity" :placeholder="1">
                                    <template v-slot:append>
                                        <v-icon class="grey-lighten-4" size="x-small" @click="quantityIncrement">
                                            fas fa-plus fa-2xs
                                        </v-icon>
                                    </template>
                                    <template v-slot:prepend>
                                        <v-icon class="grey-lighten-4" size="x-small" @click="quantityDecrement">
                                            fas fa-minus
                                        </v-icon>
                                    </template>
                                </v-text-field>
                            </v-responsive>

                        </div>

                        <div justify="start">
                            <ZipCodeField :selectProduct="selectProduct" :quantity="this.quantity"
                                @updateShippment="updateShippment" />
                        </div>

                        <div>
                            <v-btn-group>
                                <v-btn class="mx-auto" v-for="icon in social_icons" :key="icon" icon variant="text">
                                    <v-icon :icon="icon"></v-icon>
                                </v-btn>
                            </v-btn-group>
                        </div>
                        <div>
                            <v-btn-group>
                                <v-btn variant="outlined" color="success" size="small" :loading="checkout_product"
                                    @click="checkout(selectProduct)">
                                    <v-icon icon="fa-solid fa-cart-shopping" size="large"></v-icon>
                                    Comprar
                                    <template v-slot:loader>
                                        <v-progress-circular indeterminate>Loading ...</v-progress-circular>

                                    </template>
                                </v-btn>
                                <v-btn variant="outlined" color="warning" size="small" :loading="add_cart"
                                    @click="addItem(selectProduct)">
                                    <v-icon icon="fas fa-cart-plus" size="large"></v-icon>Carrinho
                                    <template v-slot:loader>
                                        <v-progress-circular indeterminate text="teste"> Loading ...</v-progress-circular>

                                    </template>

                                </v-btn>
                            </v-btn-group>
                        </div>

                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-divider></v-divider>

                        <div>
                            <v-card :max-height="140">
                                <v-card-title class="text-h5">Description:</v-card-title>
                                <v-spacer></v-spacer>
                                <v-spacer></v-spacer>
                                <v-divider></v-divider>
                                <v-card-text>
                                    {{ selectProduct.description }}
                                </v-card-text>

                            </v-card>

                        </div>


                    </v-col>

                    <v-col>

                    </v-col>
                </v-row>
                <v-row>
                    <v-col col="6" sm="4"></v-col>

                    <v-col col="2" sm="4">

                    </v-col>
                </v-row>
                <v-row>

                    <v-col col="12" sm="12">
                        <div>
                            <v-card>
                                <v-card-title>Comments</v-card-title>
                                <v-spacer></v-spacer>
                                <v-spacer></v-spacer>
                                <v-divider></v-divider>
                                <v-card-title>
                                    List Coments Here...
                                </v-card-title>
                            </v-card>

                        </div>
                    </v-col>
                </v-row>
                <v-spacer></v-spacer>


            </v-card-text>


        </v-card>
    </v-dialog>
</template>

<script>
import ZipCodeField from '../Layout/TextFields/ZipCode.vue';

export default {
    props: ['selectProduct', 'buyDialog', 'customer'],
    components: {
        ZipCodeField
    },
    data: () => ({
        cart: [],
        colors: false,
        quantity: 1,
        selectImageIndex: 0,
        loading: false,
        checkoutProduct: false,
        add_cart: false,
        snackbar: false,
        liked: 0,
        likes: false,
        social_icons: [
            'fa-brands fa-facebook',
            'fa-brands fa-instagram',
            'fa-brands fa-whatsapp',
            'fa-brands fa-x-twitter',
            'fa-brands fa-telegram',
        ],
        shippment: [],
        zip_code: false,
    }),
    watch: {
        buyDialog(val) {
            val || this.closeBuy();
        },
        loading(val) {
            if (!val) return
            setTimeout(() => {
                this.loading = false
            }, 2000);

        },
        add_cart(val) {
            if (!val) return
            setTimeout(() => {
                this.add_cart = false
                this.closeBuy;
            }, 2000);
        },
        checkout_product(val) {
            if (!val) return
            setTimeout(() => {
                this.checkoutProduct = false;
                this.closeBuy();
            })
        }
    },
    methods: {
        alterImage(index) {
            return this.selectImageIndex = index;
        },
        addItem() {

            /* if (Object.keys(this.customer).length == 0) {
                 return this.snackbar = true;
             }
            */
            const data = {
                'product': this.selectProduct,
                'quantity': this.quantity,
                'color': this.colors,

            }
            axios.post(`/carts/add`, data)
                .then((response) => {
                    this.add_cart = false;
                    return this.cart.push(response.data);
                })
                .catch((response) => {
                    alert('Error :' + response);
                });
        },
        getColors(color) {
            this.colors = color;
        },
        closeBuy() {
            this.$emit('update:buyDialog', false);
        },
        quantityIncrement() {
            this.quantity++;
        },
        quantityDecrement() {
            if (this.quantity >= 1) {
                this.quantity--;
            }
            else {
                alert('apenas numeros inteiros são aceitos');
            }

        },
        updateShippment(selectedShippment, zip_code) {

            this.shippment.push(selectedShippment);
            this.zip_code = zip_code;
            return this.finalValue(selectedShippment);

        },
        finalValue(selectedShippment) {
            const sumValue = parseFloat(this.selectProduct.price) + parseFloat(selectedShippment.price);
            return sumValue
            //return this.selectProduct.price = sumValue.toFixed(2);

        },
        checkout() {
            this.addItem();
            this.$router.push({
                name: 'item.buy',
                query: { shippment: JSON.stringify(this.shippment), zip_code: this.zip_code }
            });
        }
    }
}
</script>

<style>
.zoomable-image {
    transition: transform 0.3s;
}

.image-container:hover .zoomable-image {
    transform: scale(1.2);
}
</style>