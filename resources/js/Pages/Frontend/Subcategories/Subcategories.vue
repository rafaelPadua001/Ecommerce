<template>
    <v-container>
        <v-row no-gutters>
            <v-col>
                <AppBar />
            </v-col>
        </v-row>
        <v-row fluid>
            <v-col class="d-flex justify-center flex-column" cols="auto">
                {{ customer }}
                <v-sheet class="px-2 py-2">
                    <v-row fluid>
                        <v-col class="d-flex justify-start flex-column" cols="auto">
                            <v-card class="mx-auto">
                                <v-card-title>Categories</v-card-title>
                                <v-divider></v-divider>
                                <v-card-text>
                                    <v-row v-for="(product, index) in products" :key="index">
                                        <v-col class="d-flex flex-column" cols="auto" sm="2">
                                            <v-btn class="mr-2" size="small" variant="flat">
                                                <span>
                                                    <v-avatar size="26">
                                                        <v-img v-if="product.thumbnail"
                                                            :src="`./storage/Categories/Thumbnails/${product.thumbnail}`"
                                                            :lazy-src="`./storage/Categories/Thumbnails/${product.thumbnail}`"
                                                            :alt="`${product.name}`"></v-img>
                                                    </v-avatar>
                                                </span>
                                                {{ product.category_name }}
                                            </v-btn>

                                        </v-col>
                                        <v-divider></v-divider>
                                    </v-row>
                                </v-card-text>

                            </v-card>
                        </v-col>

                        <v-col v-for="product in products" :key="product.id" class="d-flex justify-center flex-column" cols="auto">
                            <v-sheet class="py-2 px-2">
                                <v-hover v-slot="{ isHovering, props }">
                                    <v-card class="mx-auto elevation-1">
                              
                                            
                                                <v-card class="mx-auto elevation-0" v-bind="props">
                                                    <v-toolbar class="bg-transparent">

                                                        <template v-slot:append>
                                                            <v-btn-group class="float-end">
                                                                <v-btn icon size="x-small">
                                                                    <v-icon icon="fa-regular fa-heart fa-2xs"
                                                                        v-if="Object.keys(likes).length == 0"
                                                                        class="bg-transparent" @click="like()"></v-icon>
                                                                    <v-icon icon="fa-solid fa-heart fa-2xs"
                                                                        color="red-darken-4" v-else
                                                                        @click="like()"></v-icon>
                                                                </v-btn>
                                                                <v-btn icon size="x-small">
                                                                    <v-icon
                                                                        icon="fa-solid fa-share-nodes fa-2xs"></v-icon>

                                                                </v-btn>
                                                            </v-btn-group>
                                                        </template>
                                                    </v-toolbar>

                                                    <div v-for="(image, index) in JSON.parse(product.images)"
                                                        :key="index">

                                                        <v-img v-if="index == 0" :vid-id="image" aspect-ratio="1/1"
                                                            :src="`./storage/products/${image}`"
                                                            :lazy-src="`./storage/products/${image}`" :height="300"
                                                            :width="300" cover>
                                                            <div v-if="product.discount_id"
                                                                class="d-flex justify-end text-center">
                                                                <v-chip class="ma-2" label
                                                                    :color="cardDiscountColor ?? 'orange-darken-4'"
                                                                    variant="elevated">
                                                                    - {{ product.discount_percentage * 100 }}%
                                                                </v-chip>

                                                            </div>

                                                            <div v-if="product.discount_id"
                                                                class="d-flex justify-end text-center">
                                                                <v-chip class="ma-2" label
                                                                    :color="cardDiscountColor ?? 'orange-darken-4'"
                                                                    variant="elevated">
                                                                    - {{ product.discount_percentage * 100 }}%
                                                                </v-chip>

                                                            </div>

                                                            <template>
                                                                <div
                                                                    class="d-flex align-center justify-center fill-height">
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
                                                            <v-col cols="auto">
                                                                <p>{{ product.description }}</p>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row no-gutters>
                                                            <v-btn-group>
                                                                <v-btn class="me-2" size="x-small" variant="outlined"
                                                                    color="orange" v-if="product.slug">
                                                                    {{ product.slug }}
                                                                </v-btn>
                                                                <v-btn v-if="product.discount_id" class="me-2"
                                                                    size="x-small" variant="outlined" color="green">
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
                                                                <v-btn @click="buy(product)" block>
                                                                    <v-icon icon="fas fa-eye"></v-icon>
                                                                    <v-tooltip activator="parent"
                                                                        location="end">preview</v-tooltip>
                                                                </v-btn>
                                                            </v-card-actions>
                                                        </div>
                                                    </v-expand-transition>
                                                </v-card>
                                            
                                           
                                </v-card>
                            </v-hover>
                            </v-sheet>
                        </v-col>

                        <!-- <v-col class="d-flex justify-end flex-column" cols="2" sm="2">
                            <v-card class="mx-auto">
                                <v-card-text>
                                    Outras infors necessarias aqui
                                </v-card-text>
                            </v-card></v-col> -->
                    </v-row>

                </v-sheet>
            </v-col>
        </v-row>
        <div>
            <ProductDialog v-if="buyDialog" v-model="buyDialog" :selectProduct="selectProduct" :buyDialog="buyDialog"
                :showProductSeo="this.showProductSeo" :customer="this.customer" :likes="likes"
                @close-dialog="buyDialog = false" @update:buyDialog="updateBuyDialog" />

            <v-snackbar v-model="snackbar" :timeout="3500" color="cyan-darken-3" vertical>

                <div class="text-subtitle-1 pb-2">Você deve estar logado para adicionar esse item ao carrinho
                </div>

                <template v-slot:actions>
                    <v-btn-group>
                        <v-btn size="small" variant="plain" color="white">Close</v-btn>
                        <v-btn size="small" variant="plain" color="white" :to="`/login`">Login</v-btn>
                    </v-btn-group>
                </template>

            </v-snackbar>
        </div>

    </v-container>

</template>

<script>
import AppBar from '../Layout/AppBar.vue';
import ProductDialog from '../Dialogs/ProductDialog.vue';

export default {
    props: ['customer'],
    components: {
        AppBar,
        ProductDialog
    },
    data: () => ({
        categories: [],
        category_id: null,
        subcategories: [],
        products: [],
        images: '',
        likes: false,
        productIndex: -1,
        selectProduct: {},
        buyDialog: false,
        value: [10, 100],
        snackbar: false,
    }),
    methods: {
        getProducts() {
            axios.get(`/products/category/${this.category_id}`)
                .then((response) => {

                    return this.products = response.data;
                })
                .catch((response) => {
                    return alert('Error: ' + response);
                })
        },
        like() {
            if (Object.keys(this.customer).length == 0) {
                this.snackbar = true;
            }
            if (this.selectProduct >= 1) {
                axios.post(`products/like/${this.selectProduct.id}`)
                    .then((response) => {
                        this.liked += 1;
                        return true;
                    })
                    .catch((response) => {
                        return;
                    });
            }
            else {
                axios.post(`products/like/${this.product.id}`)
                    .then((response) => {
                        this.liked += 1;
                        return true;
                    })
                    .catch((response) => {
                        return;
                    });
            }
        },
        getLikes() {
            axios.get('/likes')
                .then((response) => {

                    return this.likes = response.data;
                })
                .catch((response) => {
                    return false;
                });
        },
        buy(product) {
            this.productIndex = this.products.indexOf(product);
            this.selectProduct = Object.assign({}, product);
            this.showProductSeo = true;
            this.buyDialog = true;
        },
        updateBuyDialog(value) {
            this.buyDialog = value;

        },
    },
    mounted() {
        this.category_id = this.$route.params.category_id;
        this.getProducts();
        
    }
}

</script>

<style>
.app-bar {
    z-index: 1000;
    position: fixed;
}

.main {
    z-index: 1;
}

.v-card-menu--reveal {
    align-items: center;
    bottom: 0;
    justify-content: center;
    position: absolute;
    width: 100%;
}


.footer {
    width: 100%;
}
</style>