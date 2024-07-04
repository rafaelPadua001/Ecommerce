<template>
    <v-dialog v-model="dialog" fullscreen :scrim="false" transition="dialog-bottom-transition">
        <productSeo v-if="showProductSeo" :selectProduct="this.selectProduct" />
        <v-card>
            <v-card-title>
                <v-toolbar class="bg-transparent">
                    Buy
                    <template v-slot:append>
                        <v-btn-group>
                            <v-btn v-bind="props" icon size="small">
                                <v-icon icon="fa-regular fa-heart fa-2xs" v-if="Object.keys(likes).length == 0"
                                    @click="like()"></v-icon>
                                <v-icon v-else color="red-darken-4" icon="fa-solid fa-heart fa-2xs"
                                    @click="dislike()"></v-icon>
                            </v-btn>
                            <v-btn v-bind="props" icon @click="" size="small">
                                <v-icon icon="fas fa-share-nodes fa-2xs" @click="openBottomMenu"></v-icon>
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
                    <v-col cols="auto" md="4" sm="5">
                        <v-sheet class="ma-2 pa-2">
                            <v-row no-gutters>
                                <v-col class="d-flex flex-column"
                                    v-for="(image, index) in JSON.parse(selectProduct.images)" cols="2" md="3" sm="3">
                                    <v-hover v-slot="{ isHovering, props }" open-delay="500">
                                        <v-card width="50" class="mx-auto" v-bind="props"
                                            :color="isHovering ? 'cyan-darken-4' : undefined" elevation="0">
                                            <template :class="{ 'on-hover': isHovering }" v-bind="props">

                                            </template>
                                            <div :key="index >= 1">
                                                <v-img cover :lazy-src="`./storage/products/${image}`"
                                                    :src="`./storage/products/${image}`" @click="alterImage(index)">
                                                    <template v-slot:placeholder>
                                                        <div class="d-flex justify-center fill-height flex-column">
                                                            <v-progress-circular color="grey-lighten-4"
                                                                indeterminate></v-progress-circular>
                                                        </div>
                                                    </template>
                                                </v-img>
                                            </div>
                                        </v-card>
                                    </v-hover>


                                </v-col>

                                <v-col cols="12">
                                    <v-card class="mx-auto" :min-width="150" :max-width="1500" :height="400"
                                        elevation="0">
                                        <div v-for="(image, index) in JSON.parse(selectProduct.images)" :key="index"
                                            class="image-container">
                                            <v-img v-if="index === selectImageIndex"
                                                :lazy-src="`./storage/products/${image}`"
                                                :src="`./storage/products/${image}`" class="zoomable-image">

                                                <template v-slot:placeholder>
                                                    <div class="d-flex justify-center fill-height">
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

                    <v-col cols="auto" md="4" sm="6">
                        <p justify="start" class="text-h5">
                            {{ selectProduct.name }}
                        </p>

                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>

                        <p float="end">
                            <strong>Price:</strong> R$ {{ selectProduct.price }}
                        </p>
                        <div>
                            <p>
                                <strong>Total Price:</strong> {{ formattedTotalPrice }}
                            </p>
                        </div>

                        <p float="end" v-if="selectProduct.unity">
                            (Height x Width) {{ selectProduct.unity }}:
                            {{ selectProduct.height }} x {{ selectProduct.width }}
                        </p>

                        <p v-if="selectProduct.stock_quantity >= 1">
                            <strong>Quantity:</strong> {{ selectProduct.stock_quantity }}
                        </p>



                        <p color="red" v-else>
                            <strong> Fora de Estoque </strong>
                        </p>

                        <v-divider></v-divider>
                        <div v-if="selectProduct.availability == 1" justify="start">
                            <v-responsive class="mx-auto">
                                <v-rating v-model="rating" bg-color="orange-lighten-1" color="blue" size="x-small">
                                </v-rating>
                            </v-responsive>
                        </div>
                        <div>
                            <v-btn-group>
                                <v-btn class="mx-auto" v-for="icon in social_icons" :key="icon" icon variant="plain">
                                    <v-icon :icon="icon"></v-icon>
                                </v-btn>
                            </v-btn-group>
                        </div>

                        <div v-if="selectProduct.colors">
                            <p>Colors:</p>
                            <v-row no-gutters>
                                <v-col class="d-flex flex-column" cols="auto" md="2" sm="3"
                                    v-for="(color, index) in parsedColors" :key="index">
                                    <v-hover>
                                        <template v-slot:default="{ isHovering, props }">
                                            <div v-if="parsedQuantityColors[index] >= 1">
                                                <v-avatar @click="getColors(color)" v-bind="props" :bg-color="color"
                                                    :color="isHovering ? undefined : color" :width="60" rounded="10">
                                                    <template v-slot:append>
                                                         {{ parsedQuantityColors[index] }} 
                                                         <span>Available</span> 
                                                    </template>
                                                </v-avatar>
                                            </div>
                                            <div v-else>
                                                <v-avatar @click="outOfStock()" v-bind="props" :bg-color="color"
                                                    :color="isHovering ? undefined : color" :width="60" rounded="10">
                                                    <span>
                                                        <v-icon icon="fas fa-close" class="mr-1" size="sm"></v-icon>
                                                    </span>
                                                </v-avatar>
                                            </div>
                                        </template>
                                    </v-hover>
                                </v-col>
                            </v-row>
                        </div>

                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <div v-if="selectProduct.size != 'null'">
                            <p>Size:</p>
                            <v-row fluid>
                                <v-col cols="2" sm="2" md="2" v-for="(size, index) in parsedSizes" :key="index">
                                    <v-hover>
                                        <template v-slot:default="{ isHovering, props }">
                                            <div v-if="parsedQuantitySize[index] >= 1">
                                                <v-avatar @click="getSize(size)" v-bind="props" :width="40"
                                                    :color="isHovering ? undefined : 'grey'">
                                                    {{ size }}
                                                </v-avatar>
                                            </div>
                                            <div v-else>
                                                <v-row fluid>
                                                    <v-col cols="2" sm="2" md="2">
                                                        <v-avatar @click="outOfStock()" v-bind="props" bg-color="grey"
                                                            :color="isHovering ? undefined : 'grey'" :width="60"
                                                            rounded="10">
                                                            <span>
                                                                <v-icon icon="fas fa-close" class="mr-1"
                                                                    size="sm"></v-icon>
                                                            </span>
                                                        </v-avatar>
                                                    </v-col>
                                                </v-row>
                                            </div>
                                        </template>
                                    </v-hover>

                                </v-col>
                            </v-row>
                        </div>
                        <div v-else>
                            <p>Size:</p>
                            <v-row fluid>
                                <v-col cols="2" sm="2" md="2">
                                    <v-card @click="getSize(size)" :width="60">
                                        Unique
                                    </v-card>
                                </v-col>
                            </v-row>
                        </div>


                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>

                        <div>
                            <v-row fluid>
                                <v-col cols="auto" md="4" sm="6">
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
                                </v-col>
                            </v-row>
                        </div>

                        <div>
                            <v-btn-group>
                                <v-btn variant="flat" color="success" :loading="checkout_product"
                                    @click="checkout(selectProduct)">
                                    <v-icon icon="fa-solid fa-cart-shopping" size="large"></v-icon>
                                    Comprar
                                    <template v-slot:loader>
                                        <v-progress-circular indeterminate>Loading ...</v-progress-circular>

                                    </template>
                                </v-btn>
                                <v-btn variant="flat" color="orange-darken-4" :loading="add_cart"
                                    @click="addItem(selectProduct)">
                                    <v-icon icon="fas fa-cart-plus" size="large"></v-icon>Carrinho
                                    <template v-slot:loader>
                                        <v-progress-circular indeterminate text="teste"> Loading
                                            ...</v-progress-circular>

                                    </template>

                                </v-btn>
                            </v-btn-group>
                        </div>

                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>


                        <div>
                            <v-card class="mx-auto">
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
                </v-row>

                <v-row fluid no-gutters>
                    <v-col cols="11" md="4" sm="4">
                        <div>
                            <v-card>
                                <v-card-title>Comments</v-card-title>
                                <v-divider></v-divider>
                                <v-card-text>
                                    <div>
                                        <CommentsField :customer="this.customer" :product="selectProduct"
                                            @create-comment="newComment" />
                                    </div>
                                    <v-divider></v-divider>
                                    <div>

                                        <v-list lines="three">
                                            <v-list-item v-for="comment in comments" :key="comment.id">
                                                <v-card>
                                                    <v-card-title>
                                                        <v-toolbar color="transparent">
                                                            <v-toolbar-title>
                                                                <v-avatar color="surface-variant" v-if="comment.name">
                                                                    <template v-slot="append">
                                                                        <v-img
                                                                            :src="`./storage/avatars/${comment.name}`"
                                                                            :lazy-src="`./storage/avatars/${comment.name}`"
                                                                            cover>

                                                                        </v-img>
                                                                    </template>
                                                                </v-avatar>
                                                                {{ comment.first_name }} {{ comment.last_name }}

                                                            </v-toolbar-title>
                                                            <v-btn v-if="comment.user_id === customer.id" class="me-2"
                                                                icon size="x-small" variant="plain"
                                                                @click="remove(comment)">
                                                                <v-icon icon="fas fa-trash">
                                                                </v-icon>

                                                            </v-btn>

                                                        </v-toolbar>

                                                    </v-card-title>
                                                    <v-card-text>
                                                        {{ comment.message }}
                                                    </v-card-text>


                                                    <v-card-text align="right">
                                                        {{ comment.created_at.substr(0, 10) }}
                                                    </v-card-text>

                                                    <v-divider></v-divider>

                                                </v-card>
                                            </v-list-item>
                                        </v-list>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </div>
                    </v-col>
                </v-row>
                <v-spacer></v-spacer>
            </v-card-text>

            <div class="text-center">
                <v-snackbar v-model="snackbar" :timeout="3500" color="cyan-darken-3" vertical>

                    <div class="text-subtitle-1 pb-2">
                        {{ message }}
                    </div>
                    <template v-slot:actions>
                        <v-btn-group>
                            <v-btn size="small" variant="plain" color="white"
                                @click="this.snackbar = false">Close</v-btn>
                            <v-btn size="small" variant="plain" color="white" :to="`/login`">Login</v-btn>
                        </v-btn-group>
                    </template>

                </v-snackbar>
            </div>
        </v-card>
        <div>
            <RemoveDialog v-model="removeDialog" v-if="removeDialog" :comment="removeComment"
                @remove-comment="deleteComment" @close-dialog="removeDialog = false" />
        </div>
        <div>
            <MenuBottomSheet v-model="bottomMenu" v-if="bottomMenu" :icons="this.social_icons" />
        </div>

    </v-dialog>
</template>

<script>
import CommentsField from '../Layout/TextFields/Comments.vue';
import MenuBottomSheet from '../Layout/BottomSheet.vue';
import RemoveDialog from '../Comment/patials/Remove.vue';
import axios from 'axios';
import { EventBus } from '@/Event/EventBus';

export default {
    props: ['selectProduct', 'buyDialog', 'customer', 'likes', 'showProductSeo'],
    components: {
        CommentsField,
        RemoveDialog,
        MenuBottomSheet,
    },
    data: () => ({
        cart: [],
        deliveries: [],
        comments: [],
        colors: [],
        size: [],
        quantity: 1,
        selectImageIndex: 0,
        loading: false,
        checkoutProduct: false,
        add_cart: false,
        snackbar: false,
        message: false,
        liked: 0,
        bottomMenu: false,
        social_icons: [
            'fa-brands fa-facebook',
            'fa-brands fa-instagram',
            'fa-brands fa-whatsapp',
            'fa-brands fa-x-twitter',
            'fa-brands fa-telegram',
        ],
        removeDialog: false,
        removeComment: {},

    }),
    computed: {
        parsedColors() {
            return JSON.parse(this.selectProduct.colors);
        },
        parsedQuantityColors() {
            console.log(this.selectProduct);
            return JSON.parse(this.selectProduct.color_quantity);
        },
        parsedSizes() {
            return JSON.parse(this.selectProduct.size);
        },
        parsedQuantitySize() {
            return JSON.parse(this.selectProduct.size_quantity);
        },
        formattedTotalPrice() {
            const selectProductPrice = Number(this.selectProduct.price);
            const totalPrice = this.quantity * selectProductPrice;

            return totalPrice.toFixed(2);
        },
    },
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
                this.add_cart = false;
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
        getComments() {
            axios.get(`/api/comment`)
                .then((response) => {
                    return this.comments = response.data;
                })
                .catch((response) => {
                    return alert('Error:' + response);
                })
        },
        alterImage(index) {
            return this.selectImageIndex = index;
        },
        addItem() {
            // if (Object.keys(this.customer).length === 0) {
            //      this.snackbar = true;
            //      this.message = 'you need login to do this.';
            //      console.log(this.customer);
            //      return false;
            //  }

            const data = {
                'product': this.selectProduct,
                'quantity': this.quantity,
                'color': this.colors,
                'size': this.size,
                'total_price': this.formattedTotalPrice,
            }

            if (!data.color || data.color.length == 0 || !data.size || data.size.length == 0) {
                this.snackbar = true;
                this.message = 'Escolha as cores e tamanhos desejados';
                return false;
            }

            axios.post(`/carts/add`, data)
                .then((response) => {
                    this.cart.push(response.data)
                    return window.location.href='/';
                })
                .catch((response) => {
                    this.snackbar = true;
                    this.message = response;

                    alert('Error :' + response);
                    return false;

                });

            return true;
        },
        getColors(color) {
            return this.colors.push(color);

        },
        outOfStock() {
            alert('this color is out of stock');
        },
        getSize(size) {
            if (size.length == 0) {

                return this.size.push('unique');
            }
            else {
                return this.size.push(size);
            }
        },
        closeBuy() {
            this.$emit('update:buyDialog', false);
        },
        quantityIncrement() {
            this.quantity++;
        },
        quantityDecrement() {
            if (this.quantity >= 2) {
                this.quantity--;
            }
            else {
                alert('apenas numeros inteiros são aceitos');
            }

        },
        like() {
            // if (this.customer.length == 0) {
            //     this.snackbar = true;
            //     return this.message = 'you need login to exec this action.';
            // }

            axios.post(`products/like/${this.selectProduct.id}`)
                .then((response) => {
                    this.liked += 1;

                    return this.likes.push(response.data);
                })
                .catch((response) => {
                    return alert('Error: ' + response);
                });
        },
        dislike() {
            if (this.customer.length == 0) {
                this.snackbar = true;
                return this.message = 'you need login to exec this action.';
            }

            axios.delete(`products/dislike/${this.selectProduct.id}`)
                .then((response) => {
                    this.liked -= 1;
                    console.log(this.liked);
                    let likeIndex = this.likes.indexOf(this.likes.id);
                    return this.likes.splice(likeIndex, 1);
                    //return true;
                })
                .catch((response) => {
                    return alert('Error' + response);
                });
        },
        newComment(item) {
            return this.comments.push(item);
        },
        remove(item) {
            this.removeComment = Object.assign({}, item);
            this.removeDialog = true;
            console.log(this.removeComment);
        },
        deleteComment(item) {
            return this.comments.splice(item, 1);
        },
        openBottomMenu() {
            return this.bottomMenu = true;
        },
        async checkout() {
            try {
                const resultAdd = await this.addItem();

                if (!resultAdd) {
                    this.snackbar = true;
                    return false;
                }
                else {
                    const checkoutRedirect = this.$router.push({
                        name: 'item.buy',
                    });

                    await checkoutRedirect;
                }
            }
            catch (error) {
                this.snackbar = true;
                return;
            }

        },
        redirectToCheckout() {
            const checkoutRedirect = this.$router.push({
                name: 'item.buy',
                // query: { shippment: JSON.stringify(this.shippment), zip_code: this.zip_code }
            });
        }
    },
    mounted() {
        this.getComments();
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