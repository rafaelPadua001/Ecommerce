<template>
  <v-container>
    <v-row fluid>
      <v-col class="d-flex justify-center flex-column" cols="auto">
        <AppBar class="app-bar" />
      </v-col>
    </v-row>
    <v-row no-gutters>

      <v-col class="d-flex justify-center flex-column" cols="auto">
        <v-sheet>
          <v-row>
            <v-col class="d-flex justify-center flex-column">
              <div v-if="discounts.length >= 1">
                <DiscountWindow v-model="discountWindow" :discounts="discounts" :customer="this.customer" />

              </div>

              <div v-else>

                <v-skeleton-loader type="image"></v-skeleton-loader>
              </div>
            </v-col>
          </v-row>



          <v-row fluid>

            <v-col class="d-flex justify-center flex-column">
              <v-autocomplete v-model="search" :items="searchable" item-title="name" item-value="id" lines="three"
                auto-select-first class="flex-full-width" item-props menu-icon="" placeholder="Search product name"
                chips closable-chips preppend-inner-icon="fas fa-magnifying-glass" rounded theme="light" variant="solo"
                clearable @click:append-inner="onClick" @change="closeShowList()">
                <template v-slot:item="{ item, props }">
                  <v-list density="compact" v-bind="props">
                    <v-list-item @click="productSearch(item)">
                      <template v-slot:prepend>
                        <v-list-item-avatar v-for="(image, index) in JSON.parse(item.props.images)" :key="index"
                          :size="80">
                          <v-img v-if="index === 0" :src="`./storage/products/${image}`" width="40">
                          </v-img>
                        </v-list-item-avatar>
                      </template>
                      <v-list-item-title>{{ item.title }} </v-list-item-title>
                      <v-list-item-subtitle>
                        R$ {{ item.props.price }}

                      </v-list-item-subtitle>
                      <v-list-item-subtitle>
                        {{ item.props.description }}
                      </v-list-item-subtitle>
                    </v-list-item>
                  </v-list>

                </template>


                <template v-slot:no-data>
                  <v-list-item>
                    <v-list-item-title>
                      No results matching "<strong>{{ search }}</strong>". Press <kbd>enter</kbd> to create a new one
                    </v-list-item-title>
                  </v-list-item>
                </template>
              </v-autocomplete>



            </v-col>
          </v-row>

          <v-row>

            <h4>Launch</h4>

            <v-divider></v-divider>
            <v-spacer></v-spacer>

            <v-col v-if="Object.keys(products).length >= 1" class="d-flex justify-center flex-column" cols="auto"
              v-for="(product, index) in products" :key="index">
              <v-sheet v-if="index < 12 && product.launch" class="py-2 px-2">
                <v-hover v-slot="{ isHovering, props }">
                  <v-card class="mx-auto" :max-height="200" elevation="0" v-bind="props">
                    <div class="float-right">
                      <v-btn-group>
                        <v-btn icon size="x-small">
                          <v-icon icon="fa-regular fa-heart fa-2xs" v-if="Object.keys(likes).length == 0"
                            class="bg-transparent" @click="like()"></v-icon>
                          <v-icon icon="fa-solid fa-heart fa-2xs" color="red-darken-4" v-else @click="like()"></v-icon>
                        </v-btn>
                        <v-btn icon size="x-small">
                          <v-icon icon="fa-solid fa-share-nodes fa-2xs"></v-icon>
                        </v-btn>
                      </v-btn-group>
                    </div>
                    <v-carousel :cycle="timeToCarousel" :show-arrows="false" hide-delimiters inline>
                      <v-carousel-item v-for="image in JSON.parse(product.images)" :key="image.id"
                        :src="`./storage/products/${image}`" class="align-end" inline height="100" :width="120"
                        aspect-ratio="1/1" cover>
                        <v-chip class="bg-pink-darken-1 float-right ga-2 pa-2" variant="tonal">
                          <p>
                            R$: {{ (product.price - (product.price * product.discount_percentage)).toFixed(2) }}
                          </p>
                        </v-chip>
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
                          <v-btn class="mr-2" block @click="buy(product)">
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
            <v-col v-else v-for="n in 8" class="d-flex justify-center flex-column" cols="auto" md="3" sm="2">
              <v-sheet class="py-2 px-2">
                <v-skeleton-loader type="card" :width="160"></v-skeleton-loader>
              </v-sheet>
            </v-col>


          </v-row>

          <v-row no-gutters>
            <v-col v-if="bannerImage" class="d-flex justify-center flex-column" cols="auto">
              <Banner></Banner>
            </v-col>


            <v-col v-else>
              <v-sheet class="py-4 px-4">
                <v-skeleton-loader type="image"></v-skeleton-loader>
              </v-sheet>
            </v-col>

          </v-row>

          <v-row no-gutters>
            <h4>Highlights</h4>
            <v-divider></v-divider>
            <v-spacer></v-spacer>
            <v-col v-if="Object.keys(products).length >= 1" class="d-flex justify-center flex-column" cols="auto"
              v-for="(product, index) in products" :key="index">
              <v-sheet v-if="index < 12 && product.highlight">
                <v-hover v-slot="{ isHovering, props }">
                  <v-card class="mx-auto" :max-height="200" elevation="0" v-bind="props">
                    <div class="float-right">
                      <v-btn-group>
                        <v-btn icon size="x-small">
                          <v-icon icon="fa-regular fa-heart fa-2xs" v-if="Object.keys(likes).length == 0"
                            class="bg-transparent" @click="like()"></v-icon>
                          <v-icon icon="fa-solid fa-heart fa-2xs" color="red-darken-4" v-else @click="like()"></v-icon>
                        </v-btn>
                        <v-btn icon size="x-small">
                          <v-icon icon="fa-solid fa-share-nodes fa-2xs"></v-icon>
                        </v-btn>
                      </v-btn-group>
                    </div>

                    <v-carousel :cycle="timeToCarousel" :show-arrows="false" hide-delimiters inline>
                      <v-carousel-item v-for="image in JSON.parse(product.images)" :key="image.id"
                        :src="`./storage/products/${image}`" class="align-end" inline height="100" :width="120"
                        aspect-ratio="1/1" cover>
                        <v-chip class="bg-pink-darken-1 float-right ga-2 pa-2" variant="tonal">
                          <p>
                            R$: {{ (product.price - (product.price * product.discount_percentage)).toFixed(2) }}
                          </p>
                        </v-chip>
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
                          <v-btn class="mr-2" block @click="buy(product)">
                            <v-icon icon="fas fa-money-bill-transfer"></v-icon>
                            <v-tooltip activator="parent" location="end">Comprar</v-tooltip>
                          </v-btn>
                        </v-card-actions>
                      </div>
                    </v-expand-transition>

                  </v-card>
                </v-hover>
              </v-sheet>
              <v-sheet v-else>
                <v-row>
                  <v-col v-for="n in 8" :key="index" class="d-flex justify-center flex-column" cols="auto" md="4"
                    sm="2">
                    <v-skeleton-loader type="card" :width="160"></v-skeleton-loader>
                  </v-col>
                </v-row>


              </v-sheet>
            </v-col>

          </v-row>

          <v-row justify="center">
            <h4>All Products</h4>
            <v-spacer></v-spacer>
            <v-divider></v-divider>
            <v-col v-if="Object.keys(products).length >= 1" class="d-flex justify-center flex-column" cols="6" md="3"
              sm="2" v-for="product in products" :key="item">
              <v-sheet class="py-2 px-2">
                <v-hover v-slot="{ isHovering, props }">
                  <v-card class="mx-auto elevation-0" v-bind="props">

                    <v-toolbar class="bg-transparent">

                      <template v-slot:append>
                        <v-btn-group class="float-end">
                          <v-btn icon size="x-small">
                            <v-icon icon="fa-regular fa-heart fa-2xs" v-if="Object.keys(likes).length == 0"
                              class="bg-transparent" @click="like()"></v-icon>
                            <v-icon icon="fa-solid fa-heart fa-2xs" color="red-darken-4" v-else
                              @click="like()"></v-icon>
                          </v-btn>
                          <v-btn icon size="x-small">
                            <v-icon icon="fa-solid fa-share-nodes fa-2xs"></v-icon>

                          </v-btn>
                        </v-btn-group>
                      </template>
                    </v-toolbar>

                    <div v-for="(image, index) in JSON.parse(product.images)" :key="image.id">
                      <v-img v-if="index === 0" :vid-id="image" aspect-ratio="1/1" :src="`./storage/products/${image}`"
                        :lazy-src="`./storage/products/${image}`" :height="300" :width="300" cover>

                        <div v-if="product.discount_id" class="d-flex justify-end text-center">
                          <v-chip class="ma-2" label :color="cardDiscountColor ?? 'orange-darken-4'" variant="elevated">
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
                          <v-btn class="me-2" size="x-small" variant="outlined" color="orange" v-if="product.slug">
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
                                  {{ (product.price - (product.price * product.discount_percentage)).toFixed(2) }}
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
                            <v-tooltip activator="parent" location="end">preview</v-tooltip>
                          </v-btn>
                        </v-card-actions>
                      </div>
                    </v-expand-transition>
                  </v-card>
                </v-hover>
              </v-sheet>
            </v-col>

            <v-col v-else v-for="n in 24" class="d-flex justify-center flex-column" cols="auto" md="3" sm="3">
              <v-sheet class="py-2 px-2">
                <v-skeleton-loader type="card" :width="150"></v-skeleton-loader>
              </v-sheet>
            </v-col>
            <v-infinite-scroll :color="'grey-lighten-4'" ref="infinite" :height="200" :width="1800" @load="load"
              hide-scroll>

              <template v-slot:empty>

                <v-alert class="bg-transparent"> No more items to load</v-alert>
              </template>
            </v-infinite-scroll>
          </v-row>

          <v-row>
            <v-divider></v-divider>
            <v-spacer></v-spacer>

            <v-col class="d-flex justify-center mb-6 flex-column" v-if="carouselImages">

              <Carousel :carousel="carouselImages" />

            </v-col>
          </v-row>
        </v-sheet>
      </v-col>
    </v-row>

    <div class="text-center">
      <ProductDialog v-if="buyDialog" v-model="buyDialog" :selectProduct="selectProduct" :buyDialog="buyDialog"
        :showProductSeo="this.showProductSeo" :customer="this.customer" :likes="likes" @close-dialog="buyDialog = false"
        @update:buyDialog="updateBuyDialog" />

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

    <div class="justify-center" align-center>
      <WelcomeDiscount v-model="discountDialog"
        v-if="discountDialog && this.welcomeDiscount.is_displayed && !this.welcomeDiscount.is_used"
        :coupon="this.welcomeDiscount" @close-welcome-dialog="this.discountDialog = false" />
    </div>

    <!-- <div>
      <v-row>
        <v-col>
          <v-sheet class="px-2 py-2">
              <FloatButtons></FloatButtons>
          </v-sheet>
        </v-col>
      </v-row>
    </div> -->
    <div>
      <v-row no-gutters>
        <v-col class="d-flex justify-center flex-column" cols="12">
          <FooterBar />
        </v-col>
      </v-row>
    </div>

  </v-container>
</template>

<script>

import axios from 'axios';
import AppBar from './Layout/AppBar.vue';
import Banner from '../../Components/Banner.vue';
import ProductDialog from './Dialogs/ProductDialog.vue';
import DiscountWindow from './Coupons/partials/Window.vue';
import WelcomeDiscount from './Coupons/partials/Welcome.vue';
import Carousel from './Carrousel/Carrousel.vue';
import FloatButtons from './FloatButtons/FloatButtons.vue';
import FooterBar from './Layout/FooterBar.vue';


export default {
  components: {
    Banner,
    AppBar,
    ProductDialog,
    DiscountWindow,
    WelcomeDiscount,
    Carousel,
    FloatButtons,
    FooterBar
  },
  data: () => ({
    loaded: false,
    loading: false,
    searchable: [],
    search: [],
    products: [],
    customer: [],
    images: [],
    categories: [],
    discounts: [],
    dicountView: false,
    welcomeDiscount: [],
    discountDialog: false,
    address: [],
    addressDialog: false,
    timeToCarousel: 5000,
    productIndex: -1,
    selectProduct: {},
    buyDialog: false,
    colors: false,
    quantity: 1,
    rating: 0,
    postal_code: 0,
    loading: false,
    liked: 0,
    likes: false,
    bannerImage: [],
    carouselImages: [],
    showList: true,
    showProductSeo: false,
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
    },
    closeShowList(val) {
      val || this.closeShowList();
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
          if (response.data.original && Object.keys(response.data.original).length == 0) {
            return this.openDiscountDialog();
          }
          return this.customer = response.data;
        })
        .catch((response) => {
          return false;
          //return alert('Error:' + response);
        })
    },
    getCard() {
      axios.get('/api/card')
        .then((response) => {
          return this.cardDiscountColor = JSON.parse(response.data.chip_color);
        })
        .catch((response) => {
          return false; // alert('Errror: ' + response);
        })
    },
    getProducts() {
      axios.get('/products/show')
        .then((response) => {

          this.searchable = response.data.map(item => ({
            id: item.id,
            name: item.name,
            images: item.images,
            description: item.description,
            price: item.price
          }));

          return this.products = response.data;
        })
        .catch((response) => {
          return false;
        });
    },
    getCategories() {
      axios.get('/categories/show')
        .then((response) => {
          return this.categories = response.data;
        })
        .catch((response) => {
          return false;
        });
    },
    getWelcomeDiscount() {
      axios.get(`/coupons/getWelcome`)
        .then((response) => {
          return this.welcomeDiscount = response.data;
        })
        .catch((response) => {
          return false;
        });
    },
    getDiscounts() {
      axios.get('/api/coupons/getOthers')
        .then((response) => {
          this.dicountView = true;
          return this.discounts = response.data;
        })
        .catch((response) => {
          return false;
        });
    },
    getCarousel() {
      axios.get(`/api/carrousel`)
        .then((response) => {
          // alert(response.data);
          return this.carouselImages = response.data;
        })
        .catch((response) => {
          return false;
        })
    },
    onClick() {
      this.loading = true;

      setTimeout(() => {
        this.loading = false,
          this.loaded = true,
          this.productSearch();
      }, 2000);
    },
    productSearch(item) {

      const data = { search: item.props };

      axios.post(`/api/products/search/${item.props.id}`, data)
        .then((response) => {
          const openBuy = this.buy(response.data);
          return this.search = response.data;
        })
        .catch((response) => {
          return false;
        });
    },
    closeShowList() {
      this.showList = false;
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
          return false;
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
    openDiscountDialog() {
      return this.discountDialog = true;
    }

  },
  mounted() {
    this.getCustomer();
    this.getCategories();
    this.getProducts();
    this.getWelcomeDiscount();
    this.getCard();
    this.getCarousel();
    this.getDiscounts();
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