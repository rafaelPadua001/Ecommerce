<template>
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
                  <template v-slot:default="{isHovering, props}">
                    <v-card
                    v-bind="props" 
                    width="250"
                    :color="isHovering ? 'orange-darken-2' : undefined"
                    :to="'/categories/' + category.id">
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
          <!--  <v-col cols="12" sm="4" v-for="(icon, index) in icons" :key="index">
              <div class="d-flex justify-space-around">
                <v-hover>
                  <template v-slot:default="{isHovering, props}">
                    <v-card
                    v-bind="props" 
                    width="250"
                    :color="isHovering ? 'orange-darken-2' : undefined"
                    >

                    <v-icon :icon="icon" cover :key="index">
                    </v-icon>


                    <v-card-text>

                      {{ icon }}

                    </v-card-text>
                    </v-card>
                  </template>
                </v-hover>
                

              </div>
            </v-col>-->
          </v-row>

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
              <v-col v-for="product in products" :key="n" cols="12" sm="4">

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
                              elevation="8">
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

      </v-container>
    </v-main>
  </v-app>
</template>

<script>

import axios from 'axios';
import Banner from '../../Components/Banner.vue';


export default {
  components: {
    Banner,

  },
  data: () => ({
    products: [],
    images: [],
    categories: [],
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

    ]
  }),
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