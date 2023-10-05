<template>
  <div>
      <AppBar />
  </div>
  <v-app id="inspire">
   
    <v-main>
      <v-container>
        <div align='center'>
          <Banner></banner>

        </div>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>

        <div>
          <h4 align="left">Categories</h4>
          <v-row dense>
          
            <v-col cols="12" sm="4" v-for="category in categories" :key="category.id">
              <div class="d-flex justify-space-around">
                <v-hover>
                  <template v-slot:default="{ isHovering, props }">
                    <v-card v-bind="props" width="250" :color="isHovering ? 'orange-darken-2' : undefined"
                      :to="`/subcategories/all/${category.id}`">
                      <v-icon icon="fas fa-mobile-screen" cover :key="category.id">
                      </v-icon>

                      <v-card-text>
                        {{ category.name }}
                      </v-card-text>

                    </v-card>
                  </template>

                </v-hover>

              </div>
            </v-col>
            <v-col cols="12" sm="4" v-for="(icon, index) in icons" :key="index">
              <div class="d-flex justify-space-around">
                <v-hover>
                  <template v-slot:default="{ isHovering, props }">
                    <v-card v-bind="props" width="250" :color="isHovering ? 'orange-darken-2' : undefined">

                      <v-icon :icon="icon" cover :key="index">
                      </v-icon>


                      <v-card-text>

                        {{ icon }}

                      </v-card-text>
                    </v-card>
                  </template>
                </v-hover>


              </div>
            </v-col>
          </v-row>

        </div>

        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>

        <div>
          
          <h4 align="start">Higlights</h4>
          <v-divider></v-divider>
          <v-spacer></v-spacer>

          Produtos em destaque
        </div>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>

       
        <div>
          <h4 align="left">All Products</h4>

          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-divider></v-divider>

          <v-container>
            <v-row no-gutters>
              <v-col v-if="products.length == 0"><h4>No data to loading...</h4></v-col>
              <v-col v-else v-for="product in products" :key="n" cols="12" sm="4">

                <v-sheet class="ma-2 pa-2">
                  <v-hover v-slot="{ isHovering, props }">
                    <v-card class="mx-auto" max-width="250" v-bind="props">
                      <div v-for="(image, index) in JSON.parse(product.images)" :key="image.id">
                        <v-img v-if="index === 0" :vid-id="image" class="align-end text-white" :width="250"
                          max-width="250" height="200" aspect-ratio="16/9" :lazy-src="'./storage/products/' + image"
                          :src="'./storage/products/' + image" cover>

                          <template>
                            <div class="d-flex align-center justify-center fill-height">
                              <v-progress-circular color="grey-lighten-4">
                              </v-progress-circular>
                            </div>
                          </template>

                        </v-img>
                      </div>

                      <!-- <v-card-title class="text-h6"></v-card-title> -->

                      <!-- <v-card-subtitle class="pt-4">
                        {{ product.description }}
                      </v-card-subtitle> -->

                      <v-card-text>
                        <v-row>
                          <v-col cols="12">
                            {{ product.name }}, {{ product.description }}
                          </v-col>

                        </v-row>
                        <v-row>
                          <v-btn-group>
                            <v-btn class="ms-2" size="x-small" variant="outlined" color="orange">{{ product.slug
                            }}</v-btn>

                          </v-btn-group>
                        </v-row>

                        <v-row>
                          <v-col align="start">
                            R$ {{ product.price }}

                          </v-col>
                          <v-col align="end">
                            Solds : 10
                          </v-col>
                        </v-row>
                        <!-- <div>Quantidade disponivel: {{ product.stock_quantity }}</div> -->



                      </v-card-text>
                      <v-expand-transition>
                        <div v-if="isHovering"
                          class="d-flex transition-fast-in-fast-out bg-orange-darken-4 v-card--reveal text-h2">
                          <v-card-actions>
                            <v-btn class="ms-4 bg-yellow-darken-4" variant="outlined" color="yellow-darken-1" size="small"
                              elevation="8">
                              <v-icon icon="fas fa-cart-plus"></v-icon>
                              <v-tooltip activator="parent" location="start">Adicionar ao carrinho</v-tooltip>
                            </v-btn>

                            <v-btn class="ms-4 bg-green-darken-4" variant="outlined" color="green" size="small"
                              elevation="8" @click="buy(product)">
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
          <v-dialog v-model="buyDialog" :max-width="1200">

            <v-card :max-width="1200">
              <v-card-title>
                <v-toolbar class="bg-transparent">
                  Buy
                  <template v-slot:append>
                    <v-btn-group>
                      <v-btn v-bind="props" icon @click="" size="small">
                        <v-icon icon="fa-regular fa-heart fa-2xs"></v-icon>
                      </v-btn>
                      <v-btn v-bind="props" icon @click="" size="small">
                        <v-icon icon="fas fa-share-nodes fa-2xs"></v-icon>
                      </v-btn>
                      <v-btn v-bind="props" icon @click="closeBuy" size="small">
                        <v-icon icon="fas fa-close fa-2xs"></v-icon>
                      </v-btn>
                    </v-btn-group>

                  </template>
                </v-toolbar>
              </v-card-title>

              <v-divider></v-divider>
              <v-spacer></v-spacer>

              <v-card-text>
                <v-row dense>
                  <v-col col="12" sm="6">
                    <v-sheet class="ma-2 pa-2">
                      <v-row>
                        <v-col>
                          <v-card :min-width="150" :max-width="1500">
                            <div v-for="(image, index) in JSON.parse(selectProduct.images)" :key="index">
                              <v-img v-if="index === 0" :lazy-src="`./storage/products/${image}`"
                                :src="`./storage/products/${image}`" aspect-ratio="1/1">
                                <template v-slot:placeholder>
                                  <div class="d-flex align-center justify-center fill-height">
                                    <v-progress-circular color="grey-lighten-4" indeterminate></v-progress-circular>
                                  </div>
                                </template>
                              </v-img>
                            </div>
                          </v-card>

                        </v-col>

                      </v-row>
                      <v-spacer></v-spacer>
                      <v-divider></v-divider>
                      <v-row>

                        <v-col v-for="(image, index) in JSON.parse(selectProduct.images)" cols="2" sm="3">
                          <v-card width="50">
                            <div :key="index > 0">
                              <v-img cover :lazy-src="`./storage/products/${image}`" :src="`./storage/products/${image}`">
                                <template v-slot:placeholder>
                                  <div class="d-flex align-center justify-center fill-height">
                                    <v-progress-circular color="grey-lighten-4" indeterminate></v-progress-circular>
                                  </div>
                                </template>
                              </v-img>
                            </div>
                          </v-card>

                        </v-col>

                      </v-row>
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
                      R$ {{ selectProduct.price }}
                    </p>



                    <div v-if="selectProduct.availability == 1" justify="start">
                      <v-responsive class="mx-auto">
                        <v-rating v-model="rating" bg-color="orange-lighten-1" color="blue" size="x-small"></v-rating>
                      </v-responsive>

                      <!--   count availation: ({{selectProduct.stock_qua}})este 2 -->
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
                    <!-- <div justify="start">
                       <v-responsive
                        class="mx-auto"
                        width="100"
                        max-width="100"
                     >
                        
                        <v-text-field
                          v-model="postal_code"
                          label="postal code"
                          :placeholder="1"
                        >

                      </v-text-field>
                      <v-btn variant="plain" color="success" size="x-small">
                          Calcular Frete</v-btn>
                      </v-responsive>
                      
                    </div>-->
                    <div>
                      <v-btn-group>
                        <v-btn class="mx-auto" v-for="icon in social_icons" :key="icon" icon variant="text">
                          <v-icon :icon="icon"></v-icon>
                        </v-btn>
                      </v-btn-group>
                    </div>
                    <div>
                      <v-btn-group>
                        <v-btn variant="outlined" color="success" size="small" :loading="loading"
                          @click="loading = !loading">
                          <v-icon icon="fa-solid fa-cart-shopping" size="large"></v-icon>
                          Comprar
                          <template v-slot:loader>
                            <v-progress-circular indeterminate>Loading ...</v-progress-circular>

                          </template>
                        </v-btn>
                        <v-btn variant="outlined" color="warning" size="small" :loading="loading_cart"
                          @click="loading_cart = !loading_cart">
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
                      <v-card>
                        <v-card-title class="text-h5">Description:</v-card-title>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-divider></v-divider>
                        <v-card-text>
                          {{ selectProduct.description }}
                        </v-card-text>

                      </v-card>

                    </div>


                    <!-- <div>
                        {{ selectProduct.description }}
                      </div>-->

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

              <!-- <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn text="Close Dialog" @click="closeBuy"></v-btn>
              </v-card-actions>-->
            </v-card>

          </v-dialog>
        </div>

      </v-container>
    </v-main>
  </v-app>
</template>

<script>

import axios from 'axios';
import Banner from '../../Components/Banner.vue';
import AppBar from './Layout/AppBar.vue';


export default {
  components: {
    Banner,
    AppBar
  },
  data: () => ({
    products: [],
    images: [],
    categories: [],
    productIndex: -1,
    selectProduct: {},
    buyDialog: false,
    quantity: 1,
    rating: 0,
    postal_code: 0,
    loading: false,
    loading_cart: false,
    icons: [
      'fas fa-clock',
      'fas fa-suitcase-medical',
      'fas fa-baby',
      'fas fa-paw',
      'fas fa-car',
      'fas fa-motorcycle',
      'fas fa-bag-shopping',
      'fas fa-gamepad',
      'fas fa-person-dress',
      'fas fa-shirt',
      'fas fa-computer',

    ],
    social_icons: [
      'fa-brands fa-facebook',
      'fa-brands fa-instagram',
      'fa-brands fa-whatsapp',
      'fa-brands fa-x-twitter',
      'fa-brands fa-telegram',
    ]
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
    loading_cart(val) {
      if (!val) return
      alert('Estamos criando o nosso carrinho...');
      setTimeout(() => {
        this.loading_cart = false

      }, 2000);

    }
  },
  methods: {
    getProducts() {
      axios.get('/products/show')
        .then((response) => {
          this.products = response.data;
          for (let i = 0; i < this.products.images; i++) {
            alert(this.products.images[i])
          }
          //return
        })
        .catch((response) => {
          alert('Error :' + response);
        })
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
    buy(product) {
      this.productIndex = this.products.indexOf(product);
      this.selectProduct = Object.assign({}, product);
      this.buyDialog = true;
    },
    closeBuy() {
      this.buyDialog = false;

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
    checkout() {
      alert('Redirect to checkout... wait');
    }

  },
  mounted() {
    this.getProducts();
    this.getCategories();
  }
}
</script>

<style>
.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  position: absolute;
  width: 100%;
}
</style>