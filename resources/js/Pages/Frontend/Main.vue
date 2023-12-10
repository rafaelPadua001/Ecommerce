<template>
  <div>
    <v-responsive>
      <v-row>
        <v-col class="d-flex justify-center mb-6 flex-column" cols="12" md="6" sm="6">
          <AppBar class="app-bar" />
        </v-col>
      </v-row>
    </v-responsive>

  </div>

  <v-app id="inspire">
    <v-responsive>
      <v-container>
        <v-row no-gutters>
          <v-col class="d-flex justify-center flex-column" cols="auto">
            <v-main class="main">
              <v-container>
                <v-row>
                  <v-col cols="auto">
                    <v-banner>
                    <div>
                      <Banner></Banner>
                    </div>
                   
                  </v-banner>
                  </v-col>
                </v-row>
                 
                 <div cols="auto" md="4" sm="4">
                  <h4 align="start">Higlights</h4>
                  <v-divider></v-divider>
                  <v-spacer></v-spacer>
                  <v-container>
                    <v-row no-gutters>
                      <v-col cols="auto" md="2" sm="2" v-for="(product, index) in products" :key="index">
                        <v-sheet v-if="index < 6 && product.highlight">
                          <v-hover v-slot="{ isHovering, props }">
                            <v-card :max-height="300" elevation="0" v-bind="props">
                              <div class="float-right">
                                <v-btn-group>
                                  <v-btn icon size="x-small">
                                    <v-icon icon="fa-regular fa-heart fa-2xs" v-if="Object.keys(likes).length == 0" class="bg-transparent"
                                      @click="like()"></v-icon>
                                    <v-icon icon="fa-solid fa-heart fa-2xs" color="red-darken-4" v-else
                                      @click="like()"></v-icon>
                                  </v-btn>
                                  <v-btn icon size="x-small">
                                    <v-icon icon="fa-solid fa-share-nodes fa-2xs"></v-icon>
                                  </v-btn>
                                </v-btn-group>
                              </div>

                              <v-carousel :cycle="timeToCarousel" :show-arrows="false" hide-delimiters inline>
                                <v-carousel-item v-for="image in JSON.parse(product.images)" :key="image.id"
                                  :src="`./storage/products/${image}`" class="align-end" :width="150" inline
                                  :max-width="250" :max-height="210" aspect-ratio="16/9" cover>
                                  <v-chip class="bg-green-darken-4 float-right ga-2 pa-2" variant="tonal">
                                    <p>
                                      R$: {{ product.price - (product.discount_percentage * 100) }}
                                    </p>
                                  </v-chip>

                                  <!-- <p class="text-white">{{ product.description }}</p>-->
                                </v-carousel-item>

                              </v-carousel>

                              <v-expand-transition>
                                <div v-if="isHovering"
                                  class="d-flex transition-fast-in-fast-out bg-grey-darken-3 v-card-menu--reveal text-h2">
                                  <v-card-actions>
                                    <!--<v-btn class="ms-4 bg-yellow-darken-4" variant="outlined" color="yellow-darken-1"
                                      size="small" elevation="8" @click="addItem(selectProduct)">
                                      <v-icon icon="fas fa-cart-plus"></v-icon>
                                      <v-tooltip activator="parent" location="start">Adicionar ao carrinho</v-tooltip>
                                    </v-btn>
                                    -->
                                    <v-btn block @click="buy(product)">
                                      <v-icon icon="fas fa-money-bill-transfer"></v-icon>
                                      <v-tooltip activator="parent" location="end">Comprar</v-tooltip>
                                    </v-btn>
                                  </v-card-actions>
                                </div>
                              </v-expand-transition>

                            </v-card>
                          </v-hover>
                      </v-sheet>
                    </v-col>
                  </v-row>
                </v-container>
              </div>
                <div cols="auto" md="8" sm="4">
                  <h4 align="left">All Products</h4>

                  <v-spacer></v-spacer>
                  <v-spacer></v-spacer>
                  <v-divider></v-divider>

                  <v-container>
                    <v-row fluid>
                      <v-col class="d-flex justify-center flex-column" cols="auto" md="4" sm="2" v-for="product in products" :key="item">
                        <v-sheet class="ma-2 pa-2">
                          <v-hover v-slot="{ isHovering, props }">
                            <v-card class="mx-auto" :max-width="250" v-bind="props" elevation="0">

                              <v-btn-group class="float-right">
                                <v-btn icon size="x-small">
                                  <v-icon icon="fa-regular fa-heart fa-2xs" v-if="Object.keys(likes).length == 0" class="bg-transparent"
                                    @click="like()"></v-icon>
                                  <v-icon icon="fa-solid fa-heart fa-2xs" color="red-darken-4" v-else
                                    @click="like()"></v-icon>
                                </v-btn>
                                <v-btn icon size="x-small">
                                  <v-icon icon="fa-solid fa-share-nodes fa-2xs"></v-icon>

                                </v-btn>
                              </v-btn-group>
                              <div v-for="(image, index) in JSON.parse(product.images)" :key="image.id">
                                <v-img v-if="index === 0" :vid-id="image" class="align-end text-white" :width="250"
                                  :max-width="250" :max-height="200"  aspect-ratio="16/9" :src="`./storage/products/${image}`"
                                  :lazy-src="`./storage/products/${image}`" cover>

                                  <div v-if="discount_id" class="d-flex justify-end text-center">
                                    <v-chip class="ma-2" label color="orange-darken-4" variant="elevated">
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
                                <v-row>
                                  <v-col cols="12">
                                    <p class="text-h5">{{ product.name }}</p>
                                    <p>{{ product.description }}</p>
                                  </v-col>

                                </v-row>
                                <v-row>
                                  <v-btn-group>
                                    <v-btn class="ms-2" size="x-small" variant="outlined" color="orange" v-if="product.slug">
                                      {{ product.slug }}
                                    </v-btn>
                                    <v-btn v-if="product.discount_id" class="ms-2" size="x-small" variant="outlined"
                                      color="green">
                                      {{ product.discount_percentage * 100 }}% off
                                    </v-btn>
                                  </v-btn-group>
                                </v-row>

                                <v-row>
                                  <v-col align="start" cols="auto" md="4" sm="4">
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
                                            {{ product.price - (product.discount_percentage * 100) }}
                                          </p><strong>R$:</strong>
                                        </div>
                                      </div>
                                    </div>
                                  </v-col>
                                  <v-col col="auto" sm="3">
                                    <p color="red" v-if="product.stock_quantity >= 1">
                                      <strong>Qtd:</strong> {{ product.stock_quantity }}
                                    </p>
                                    <p color="red" v-if="product.stock_quantity === 0">
                                      <strong> Fora de Estoque </strong>
                                    </p>
                                  </v-col>
                                  <v-col col="auto" sm="4" align="end">
                                    <strong>Solds:</strong> 100
                                  </v-col>
                                </v-row>

                              </v-card-text>
                              <v-expand-transition>
                                <div 
                                  v-if="isHovering"
                                  class="d-flex transition-fast-in-fast-out bg-grey-darken-3 v-card-menu--reveal text-h2">
                                  <v-card-actions>
                                    <!--<v-btn class="ms-4 bg-yellow-darken-4" variant="outlined" color="yellow-darken-1"
                                      size="small" elevation="8" @click="addItem(selectProduct)">
                                      <v-icon icon="fas fa-cart-plus"></v-icon>
                                      <v-tooltip activator="parent" location="start">Adicionar ao carrinho</v-tooltip>
                                    </v-btn>
                                    -->
                                    <v-btn  @click="buy(product)" block>
                                      <v-icon icon="fas fa-money-bill-transfer"></v-icon>
                                      <v-tooltip activator="parent" location="end">Comprar</v-tooltip>
                                    </v-btn>
                                  </v-card-actions>
                                </div>
                              </v-expand-transition>
                            </v-card>
                          </v-hover>
                        </v-sheet>
                      </v-col>
                      <v-infinite-scroll ref="infinite" :height="720" :width="1800" @load="load" hide-scroll>
                        <template v-slot:empty>
                          <v-alert type="error">No more items to load</v-alert>
                        </template>
                      </v-infinite-scroll>
                    </v-row>
                  </v-container>
                  <ProductDialog 
                    v-if="buyDialog"
                    v-model="buyDialog"
                    :selectProduct="selectProduct"
                    :buyDialog="buyDialog"
                    @update:buyDialog="updateBuyDialog"
                    :customer="customer" />
                  
                </div>

              </v-container>
            </v-main>


          </v-col>
        </v-row>

        
        <div class="text-center">
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


    </v-responsive>
    <div>


<v-row no-gutters>
  <v-col cols="12">
    <FooterBar />
  </v-col>
</v-row>


</div>
  </v-app>
</template>

<script>

import axios from 'axios';
import AppBar from './Layout/AppBar.vue';
import Banner from '../../Components/Banner.vue';
import ProductDialog from './Dialogs/ProductDialog.vue';
import FooterBar from './Layout/FooterBar.vue';


export default {
  components: {
    Banner,
    AppBar,
    ProductDialog,
    FooterBar
  },
  data: () => ({
    products: [],
    customer: [],
    images: [],
    categories: [],
    discounts: [],
    timeToCarousel: 3000,
    productIndex: -1,
    selectProduct: {},
    buyDialog: false,
    colors: false,
    quantity: 1,
    rating: 0,
    postal_code: 0,
    loading: false,
    add_cart: false,
    snackbar: false,
    liked: 0,
    likes: false,
  }),
  watch: {
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
        this.buyDialog = false;
      }, 2000);
    }
  },
  methods: {
    async load({ done }) {
      //Perform Api Call
      setTimeout(() => {
        done('empty');
      }, 1000)
    },
    getCustomer() {
      axios.get('/customer')
        .then((response) => {
          return this.customer = response.data;
        })
        .catch((response) => {
          return this.customer = false;
        })
    },
    getProducts() {
      axios.get('/products/show')
        .then((response) => {
          return this.products = response.data;
        })
        .catch((response) => {
          return alert('Error :' + response);
        });
    },
    getCategories() {
      axios.get('/categories/show')
        .then((response) => {
          return this.categories = response.data;
        })
        .catch((response) => {
          return alert('Erro :' + response);
        });
    },
    /*getDiscounts() {
      axios.get('/api/coupons/all')
        .then((response) => {
          return this.discounts = response.data;
        })
        .catch((response) => {
          return alert('Error: ' + response);
        });
    },*/
    buy(product) {
      this.productIndex = this.products.indexOf(product);
      this.selectProduct = Object.assign({}, product);
      this.buyDialog = true;
    },
    updateBuyDialog(value) {
      
      this.buyDialog = value;
    },
    addItem() {
            if (Object.keys(this.customer).length == 0) {
                this.snackbar = true;
                return;
            }
            const data = {
                'product': this.selectProduct,
                'quantity': this.quantity,
                'color': this.colors
            }
            axios.post(`/carts/add`, data)
                .then((response) => {
                    this.add_cart = false;
                    return this.cart = response.data;
                })
                .catch((response) => {
                    alert('Error :' + response);
                });
        },
    like() {
      if (Object.keys(this.customer).length == 0) {
        this.snackbar = true;
      }
      if (this.selectProduct >= 1) {
        axios.post(`products/like/${this.selectProduct.id}`)
          .then((response) => {
            this.liked += 1;
            console.log(this.liked);
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
            console.log(this.liked);
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
          console.log(response);
          return this.likes = response.data;
        })
        .catch((response) => {
          alert('Error: ' + response);
        });
    },
   
  },
  mounted() {
    this.getCustomer();
    this.getCategories();
    this.getProducts();
   // this.getDiscounts();
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
</style>