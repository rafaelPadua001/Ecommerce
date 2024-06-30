<template>
    <v-container>
        <v-row no-gutters>
            <v-col>
                <AppBar />
            </v-col>
        </v-row>
        <v-row fluid>
            <v-col class="d-flex justify-center flex-column" cols="auto">
                <!-- {{ customer }}  -->
                <v-sheet class="px-2 py-2">
                    <v-row fluid>
                        <v-col class="d-flex justify-start flex-column" cols="auto">
                            <CategoriesCard
                                :category="this.category"
                                :subcategories="this.subcategories"
                                :products="this.products"
                                @update-value-filter="updateValueFilter"
                            />
                            

                        </v-col>
                       
                        <v-col v-for="product in products" :key="product.id" class="d-flex justify-center flex-column"
                            cols="auto">
                           <ProductCard 
                                :product="product"
                                :likes="this.likes"
                                @open-buy-dialog="buy(product)"
                                @like-product="like(product)"
                                @dislike-product="dislike(product)"
                           />
                            
                        </v-col>
                           <!-- Exibe mensagem se nenhum produto for encontrado -->
                           <v-col v-if="noProductFound" class="d-flex justify-center" cols="auto">
                            <v-alert type="info" class="ma-2">
                                Nenhum produto encontrado para esse intervalo de preços.
                            </v-alert>
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
import CategoriesCard from './partials/CategoriesCard.vue';
import ProductCard from './partials/ProductCard.vue';

export default {
    name: 'Subcategories',
    props: ['customer'],
    components: {
        AppBar,
        CategoriesCard,
        ProductCard,
        ProductDialog
    },
    data: () => ({
        category: [],
        category_id: null,
        subcategories: [],
        products: [],
        noProductFound: false,
        images: '',
        likes: [],
        productIndex: -1,
        selectProduct: {},
        buyDialog: false,
        value: [10, 100],
        snackbar: false,
        liked: 0,
    }),
    methods: {
        getCategories() {
            axios.get(`/categories/${this.category_id}`)
                .then((response) => {
                    return this.category = response.data;
                })
                .catch((response) => {
                    return alert('Error:' + response);
                })
        },
        getSubcategories() {
            axios.get(`/subcategories/all/${this.category_id}`)
                .then((response) => {
                    return this.subcategories = response.data;
                })
                .catch((response) => {
                    return alert('Error:' + response);
                })
        },
        getProducts() {
            axios.get(`/products/category/${this.category_id}`)
                .then((response) => {
                   this.products = response.data;
                   return this.filteredProducts = this.products; 
                })
                .catch((response) => {
                    return alert('Error: ' + response);
                })
        },
        getProductSubcategory(subcategory){
            const subcategory_id = subcategory.id;
            const item = subcategory;
            axios.get(`/products/subcategory/${subcategory_id}`)
            .then((response) => {
                if(response.data.length >= 1){
                    return this.products.push(response.data);
                }
               
                return alert('nenhum produto encontrado');
            })
            .catch((response) => {
                return alert('Error:' + response);
            })

        },
        like(product) {
            if (this.selectProduct >= 1) {
                axios.post(`http://localhost:8000/products/like/${this.selectProduct.id}`)
                    .then((response) => {
                        this.liked += 1;
                        return true;
                    })
                    .catch((response) => {
                        return;
                    });
            }
            else {
                axios.post(`http://localhost:8000/products/like/${product.id}`)
                    .then((response) => {
                      
                        return this.likes.push(response.data.original.likes);
                        
                    })
                    .catch((response) => {
                        alert(response);
                    });
            }
        },
        dislike(product) {
            // if (this.customer.length == 0) {
            //     this.snackbar = true;
            //     return this.message = 'you need login to exec this action.';
            // }
            if(this.selectProduct >= 1){
                axios.delete(`http://localhost:8000/products/dislike/${this.selectProduct.id}`)
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
            }
            else{
                axios.delete(`http://localhost:8000/products/dislike/${product.id}`)
                    .then((response) => {
                       
                    //   return this.likes = false;
                    console.log(this.liked);
                    let likeIndex = this.likes.indexOf(this.likes.id);
                    return this.likes.splice(likeIndex, 1);
                    })
                    .catch((response) => {
                        alert(response);
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
        updateValueFilter(minPrice, maxPrice){
            const filtered = this.products.filter(product => {
                
                    return product.price >= minPrice && product.price <= maxPrice;
            });

            this.products = filtered;

            if(filtered.length == 0){
                this.noProductFound = true;
                this.filteredProducts = [...this.products]; 
              //  alert('Nenhum produto encontrado para esse intervalo de preços.');
                return;
            } 
            else{
                this.noProductFound = false;
            }
        }
    },
    mounted() {
        this.category_id = this.$route.params.category_id;
        this.getProducts();
        this.getCategories();
        this.getSubcategories();
        this.getLikes();

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
</style>./partials/CategoriesCard.vue