<template>
  <div>
    <v-responsive>
      <v-row>
        <v-col class="d-flex justify-center mb-6 flex-column">
          <AppBar />
        </v-col>
      </v-row>
    </v-responsive>

  </div>

  <v-app id="inspire">
    <v-responsive>
      <v-container>
        <v-row fluid>
          <v-col class="d-flex justify-center mb-6 flex-column">
            <v-main>
              <v-container>
                <div align='center'>
                  <Banner></Banner>

                </div>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <div>
                  <h4 align="left">Categories</h4>
                  <v-row no-gutters>
                    <v-col v-for="category in categories" :key="category.id">
                      <div cols="12" md="6" sm="4">
                        <v-hover>
                          <template v-slot:default="{ isHovering, props }">
                            <v-card class="mx-auto" v-bind="props" :color="isHovering ? 'grey-darken-1' : undefined"
                              :to="`/subcategories/all/${category.id}`" elevation="0">
                              <v-icon :icon="category.icon" cover :key="category.id"></v-icon>

                              <v-card-text>
                                {{ category.name }}
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

                <div cols="12" md="6" sm="4">
                  <h4 align="start">Higlights</h4>
                  <v-divider></v-divider>
                  <v-spacer></v-spacer>
                  <v-container>
                    <v-row no-gutters>
                      <v-col cols="2" md="2" sm="2" v-for="(product, index) in products" :key="index">
                        <v-sheet v-if="index < 6 && product.highlight">
                          <v-hover v-slot="{ isHovering, props }">
                            <v-card :max-width="150" :max-height="90" elevation="0" v-bind="props">
                              <v-carousel :cycle="timeToCarousel" :show-arrows="false" hide-delimiters inline>
                                <v-carousel-item v-for="image in JSON.parse(product.images)" :key="image.id"
                                  :src="`./storage/products/${image}`" cover class="align-end text-white" :width="150"
                                  inline :max-width="150" :max-height="90" aspect-ratio="16/9"
                                  transition="fade-transition">
                                  <v-expand-transition>
                                    <div v-if="isHovering"
                                      class="d-flex transition-fast-in-fast-out bg-orange-darken-4 v-card-menu--reveal text-h2">
                                      <v-card-actions>
                                        <v-btn class="ms-4 bg-yellow-darken-4" variant="outlined" color="yellow-darken-1"
                                          size="small" elevation="8" @click="addItem(selectProduct)">
                                          <v-icon icon="fas fa-cart-plus"></v-icon>
                                          <v-tooltip activator="parent" location="start">Adicionar ao carrinho</v-tooltip>
                                        </v-btn>

                                        <v-btn class="ms-4 bg-green-darken-4" variant="outlined" color="green"
                                          size="small" elevation="8" @click="buy(product)">
                                          <v-icon icon="fas fa-money-bill-transfer"></v-icon>
                                          <v-tooltip activator="parent" location="end">Comprar</v-tooltip>
                                        </v-btn>
                                      </v-card-actions>
                                    </div>
                                  </v-expand-transition>

                                  <v-card-text>


                                    <p class="text-white">{{ product.name }}</p>
                                    <p class="text-white">{{ product.description }}</p>
                                    <p class="text-white">R$: {{ product.price - (product.discount_percentage * 100) }}
                                    </p>

                                  </v-card-text>
                                </v-carousel-item>

                              </v-carousel>


                            </v-card>
                          </v-hover>
                          <div>



                          </div>
                        </v-sheet>

                      </v-col>
                    </v-row>
                  </v-container>



                </div>
                <div>
                  <h4 align="left">All Products</h4>

                  <v-spacer></v-spacer>
                  <v-spacer></v-spacer>
                  <v-divider></v-divider>

                  <v-container>
                    <v-row fluid>
                      <v-col v-for="product in products" :key="item" cols="12" md="4" sm="2">
                        <v-sheet class="ma-2 pa-2">
                          <v-hover v-slot="{ isHovering, props }">
                            <v-card class="mx-auto" :max-width="250" v-bind="props" elevation="0">

                              <v-btn-group class="float-right">
                                <v-btn icon size="x-small">
                                  <v-icon icon="fa-regular fa-heart fa-2xs" v-if="!likes" class="bg-transparent"
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
                                  max-width="250" height="200" aspect-ratio="16/9" :src="`./storage/products/${image}`"
                                  :lazy-src="`./storage/products/${image}`" cover>

                                  <div v-if="discount_id" class="d-flex justify-end text-center">
                                    <v-chip class="ma-2" label color="orange-darken-4" variant="elevated">
                                      -{{ product.discount_percentage * 100 }}%
                                    </v-chip>

                                  </div>
                                  <!--      <div v-for="discount in discounts" :key="discount.id"
                                  class="d-flex justify-end text-center">
                                  <v-chip class="ma-2" label color="orange-darken-4" variant="elevated"
                                    v-if="product.discount_id === discount.id">
                                    -{{ discount.discount_percentage * 100 }}%
                                  </v-chip>

                                </div>-->
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
                                    <p class="text-h5">{{ product.name }}</p>
                                    <p>{{ product.description }}</p>
                                  </v-col>

                                </v-row>
                                <v-row>
                                  <v-btn-group>
                                    <v-btn class="ms-2" size="x-small" variant="outlined" color="orange">
                                      {{ product.slug }}
                                    </v-btn>
                                    <v-btn v-if="product.discount_id" class="ms-2" size="x-small" variant="outlined"
                                      color="green">
                                      {{ product.discount_percentage * 100 }}% off
                                    </v-btn>
                                  </v-btn-group>
                                </v-row>

                                <v-row>
                                  <v-col align="start" cols="6" md="4" sm="4">
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
                                  <v-col col="3" sm="3">
                                    <p color="red" v-if="product.stock_quantity >= 1">
                                      <strong> {{ product.unity }}:</strong> {{ product.stock_quantity }}
                                    </p>
                                    <p color="red" v-if="product.stock_quantity === 0">
                                      <strong> Fora de Estoque </strong>
                                    </p>
                                  </v-col>
                                  <v-col col="2" sm="4" align="end">
                                    <strong>Solds:</strong> 100
                                  </v-col>
                                </v-row>

                              </v-card-text>
                              <v-expand-transition>
                                <div v-if="isHovering"
                                  class="d-flex transition-fast-in-fast-out bg-orange-darken-4 v-card-menu--reveal text-h2">
                                  <v-card-actions>
                                    <v-btn class="ms-4 bg-yellow-darken-4" variant="outlined" color="yellow-darken-1"
                                      size="small" elevation="8" @click="addItem(selectProduct)">
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
                      <v-infinite-scroll ref="infinite" :height="720" :width="1800" @load="load" hide-scroll>
                        <template v-slot:empty>
                          <v-alert type="warning">No more items to load</v-alert>
                        </template>
                      </v-infinite-scroll>
                    </v-row>
                  </v-container>
                  <v-dialog v-model="buyDialog" :max-width="1200">
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
                        <v-row no-gutters>
                          <v-col col="12" md="6" sm="2">
                            <v-sheet class="ma-2 pa-2">
                              <v-row>
                                <v-col v-for="(image, index) in JSON.parse(selectProduct.images)" cols="2" sm="3">
                                  <v-hover v-slot="{ isHovering, props }" open-delay="200">
                                    <v-card width="50" class="mx-auto" v-bind="props"
                                      :color="isHovering ? 'cyan-darken-4' : undefined">
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
                                  <v-card :min-width="150" :max-width="1500" :height="430">
                                    <div v-for="(image, index) in JSON.parse(selectProduct.images)" :key="index"
                                      class="image-container">
                                      <v-img v-if="index === selectImageIndex" :lazy-src="`./storage/products/${image}`"
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
                              R$ {{ selectProduct.price }}
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

                      <!-- <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn text="Close Dialog" @click="closeBuy"></v-btn>
              </v-card-actions>-->

                    </v-card>

                  </v-dialog>
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
    selectImageIndex: 0,
    liked: 0,
    likes: false,
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
    getDiscounts() {
      axios.get('/api/coupons/all')
        .then((response) => {
          return this.discounts = response.data;
        })
        .catch((response) => {
          return alert('Error: ' + response);
        });
    },
    buy(product) {
      this.productIndex = this.products.indexOf(product);
      this.selectProduct = Object.assign({}, product);
      this.buyDialog = true;
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
    alterImage(index) {
      if (index == 0) {
        console.log(this.selectImageIndex = index);
        alert('A imagem Já esta carregada');
      }
      return this.selectImageIndex = index;

    },
    addItem() {
      if (Object.keys(this.customer) == 0) {
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
    getColors(color) {
      this.colors = color;
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
    this.getCustomer();
    this.getCategories();
    this.getProducts();
    this.getDiscounts();
    this.getLikes();

  }
}
</script>

<style>
.v-card-menu--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  position: absolute;
  width: 100%;
}

.zoomable-image {
  transition: transform 0.3s;
}

.image-container:hover .zoomable-image {
  transform: scale(1.2);
}
</style>