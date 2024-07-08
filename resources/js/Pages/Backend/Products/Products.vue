<template>
  <v-container>
    <v-sheet class="px-2 py-2">
      <v-row fluid>
    <v-col class="d-flex justify-center mb-6 flex-column" cols="auto">
      <Dashboard />
    </v-col>
  </v-row>

  <v-row justify="center" no-gutters>
    <v-col class="d-flex justify-center flex-column" cols="auto" sm="12">
       <v-data-table :headers="headers" :items="products" :sort-by="[{ key: 'calories', order: 'asc' }]"
              class="elevation-0">
              <template v-slot:top>
                <v-toolbar flat class="bg-transparent">
                  <v-toolbar-title>Produtos</v-toolbar-title>
                  <v-divider class="mx-2" inset vertical></v-divider>
                  <v-spacer></v-spacer>
                  <v-dialog v-model="dialog" fullscreen :scrim="true">
                    <template v-slot:activator="{ props }">
                      <v-btn color="primary" dark class="mb-2" v-bind="props">
                        New Item
                      </v-btn>
                    </template>
                   
                    <ProductForm 
                      :editedItem="this.editedItem"
                      :highlights="this.highlights"
                      :availability="this.availability"
                      :status="this.status"
                      :launch="this.launch"
                      :categories="this.categories"
                      :subcategories="this.subcategories"
                      :colors="this.colors"
                      :color_qty="this.color_qty"
                      :size_qty="this.size_qty"
                      @hl_turn="hl_turn"
                      @av_turn="av_turn"
                      @st_turn="st_turn"
                      @lc_turn="lc_turn"
                      @save="save"
                     
                    />

                  </v-dialog>
                  <v-dialog v-model="dialogDelete" max-width="500">
                    <ProductRemove 
                      :editedItem="this.editedItem"
                      @close-delete="closeDelete"
                      @remove-item="deleteItemConfirm"
                    />
                  </v-dialog>
                </v-toolbar>
              </template>
              <template v-slot:item.actions="{ item }">
                <v-icon size="x-small" class="me-2" @click="editItem(item)" icon="fa-regular fa-pen-to-square">

                </v-icon>
                <v-icon size="x-small" class="me-2" @click="deleteItem(item)" icon="fas fa-remove fa-2xs">

                </v-icon>
              </template>

              <template v-slot:item.images="{ item }">

                <v-row>
                  <v-col v-for="(imageName, index) in item.images" :key="index">

                    <v-img v-if="index === 0" :src="`/storage/products/${imageName}`" class="align-end text-white"
                      :width="250" max-width="90" height="90" aspect-ratio="16/9"></v-img>
                  </v-col>
                </v-row>
              </template>

              <template v-slot:item.colors="{ item }">
                <v-row v-if="item.colors !== '0'">
                  <v-col v-if="typeof item.colors === 'string'">
                    <v-row>
                      <v-col cols="12" md="2" sm="6" >
                        <v-chip-goup>
                          <v-chip v-for="(color, index) in JSON.parse(item.colors)" :key="index" :color="color" size="x-small" variant="elevated"></v-chip>
                        </v-chip-goup>
                      </v-col>
                    </v-row>
                  </v-col>
                  <v-col v-else>
                    <v-row fluid>
                      <v-col cols="1" md="1" sm="1" v-for="(color, index) in item.colors" :key="index">
                        <v-chip-goup>
                          <v-chip :color="color" size="x-small" variant="elevated"></v-chip>
                        </v-chip-goup>
                        <!-- <v-card class="mx-auto" :color="color">
                          {{ color }}
                        </v-card> -->
                      </v-col>
                    </v-row>


                  </v-col>

                </v-row>
                <v-row v-else>
                  <v-col>
                    no color
                  </v-col>
                </v-row>
              </template>

              <template v-slot:item.size="{ item }">
                <v-row>
                  <v-col cols="auto">
                    <v-chip-group>
                      <v-chip v-for="(size,index) in JSON.parse(item.size)" :key="index" class="bg-green" size="x-small">
                        {{ size }}
                      </v-chip>
                    </v-chip-group>
                  </v-col>
                </v-row>


              </template>
              <template v-slot:no-data>
                <v-btn color="primary" @click="initialize">
                  Reset
                </v-btn>
              </template>
            </v-data-table>
    </v-col>
  </v-row>
    </v-sheet>
  </v-container>
  





</template>

<script>
import axios from 'axios';
import Dashboard from '../Auth/Dashboard.vue';
import ProductForm from './partials/ProductForm.vue';
import ProductRemove from './partials/ProductRemove.vue';

export default {
  components: {
    Dashboard,
    ProductForm,
    ProductRemove,
  },
  data: () => ({
    dialog: false,
    dialogDelete: false,
    categories: [],
    subcategories: [],
    user: [],
    products: [],
    discounts: [],
    name: '',
    description: '',
    selectedCategory: false,
    selectSubcategory: false,
    images: [],
    productImg: [],
    unity: '',
    size: [],
    size_qty: [],
    platform: '',
    video_link: [],
    previewLink: '',
    colors: [],
    color_qty: [],
    price: "0.00",
    quantity: false,
    weight: false,
    height: false,
    width: false,
    length: false,
    discount_id: null,
    sku: false,
    meta_name: false,
    meta_key: false,
    meta_description: false,
    slug: false,
    highlights: false,
    Availability: false,
    status: false,
    launch: 0,
    headers: [
      { title: 'images', key: 'images' },
      {
        title: 'Name',
        align: 'start',
        sortable: false,
        key: 'name',
      },
      { title: 'video_link', key: 'video_links' },
      { title: 'colors', key: 'colors' },
      { title: 'size', key: 'size' },
      { title: 'price', key: 'price' },
      { title: 'quantity', key: 'stock_quantity' },
      { title: 'Actions', key: 'actions', sortable: false },
    ],
    swatches: [],
    desserts: [],
    editedIndex: -1,
    editedItem: {
     
    },
    defaultItem: {
      id: '',
      name: '',
      description: '',
      category_id: 0,
      subcategory_id: 0,
      images: [],
      platform: '',
      video_link: '',
      // colors: [],
      size: [],
      unity: [],
      price: "0.00",
      discount: '',
      quantity: 0,
      weight: 1.00,
      height: 1.00,
      width: 1.0,
      length: 1.0,
      sku: '',
      meta_name: '',
      meta_key: '',
      meta_description: '',
      slug: '',
      highlights: false,
      availability: false,
      launch: false,
      status: true,
    },
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    },
    config() {
      return {
        decimal: ",",
        thousands: ".",
        prefix: "R$ ",
        suffix: "",
        precision: 2,
        masked: false
      };
    },
  },
  watch: {
    dialog(val) {
      val || this.close()
    },
    dialogDelete(val) {
      val || this.closeDelete()
    },
    previewImages(newUrl, oldUrl) {
      // Executar lógica quando a imagem é alterada
      console.log(`A imagem foi alterada de ${oldUrl} para ${newUrl}`);
      return true;
    },
  },
  created() {
    this.initialize()
  },
  methods: {
    initialize() {
      this.user = [],
        this.categories = [],
        this.subcategories = [],
        this.products = [],
        this.discounts = [],
        this.colors = []
    },
    getAuth() {
      axios.get('/users')
        .then((response) => {
          this.user = response.data;
          return true;
        })
        .catch((response) => {
         
          return false;
        });
    },
    getCategories() {
      axios.get('/categories')
        .then((response) => {
          this.categories = response.data;

        })
        .catch((response) => {
          alert('Error'.$response.error);
          return false;
        });
    },
    getSubCategories() {
      axios.get('/subcategories')
        .then((response) => {
          this.subcategories = response.data;
        })
        .catch((response) => {
          alert('Error: '.response.error);
          return false;
        });
    },
    getProducts() {
      axios.get('/products')
        .then((response) => {
          this.products = response.data;
          //  this.editedItem.images = JSON.parse(this.editedItem.original.images);
          this.editedItem.images = JSON.stringify(this.products.images);
        })
        .catch((response) => {
          return alert('Error :' + response);
        });
    },
    getDiscounts() {
      axios.get('/coupons/all')
        .then((response) => {
          return this.discounts = response.data;
        })
        .catch((response) => {
          return alert('Error' + response);
        });
    },
    hl_turn() {
      if (this.editedItem.highlights) {
        this.highlights = 1;
        this.editedItem.highlights = this.highlights;
      }
      else {
        this.highlights = 0;
        this.editedItem.highlights = this.highlights;
      }

      return this.highlights;
    },
    av_turn() {
      if (this.editedItem.availability == 1) {
        this.availability = 1;
        this.editedItem.availability = this.availability;
      }
      else {
        this.availability = 0;
        this.editedItem.availability = this.availability;
      }
    },
    st_turn() {
      if (this.editedItem.status) {
        this.status = true;
        this.editedItem.status = this.status;
      }
      else {
        this.status = false;
        this.editedItem.status = this.status;
      }
    },
    lc_turn() {
      if (this.editedItem.launch) {
        this.launch = 1;
        this.editedItem.launch = this.launch;
      }
      else {
        this.launch = 0;
        this.editedItem.launch = this.launch;
      
      }
      return this.launch;
    },
    editItem(item) {
      this.editedIndex = this.products.indexOf(item)
      this.editedItem = Object.assign({}, item)

      if (typeof this.editedItem.images === 'string') {
        this.editedItem.images = JSON.parse(this.editedItem.images);
      }

      if (this.editedItem.highlight == 1) {
        this.highlights = 1;
        this.editedItem.highlights = this.highlights;
       }
      else {
        this.highlights = 0;
        this.editedItem.highlights = this.highlights;
      }
      if (this.editedItem.availability == 1) {
        this.availability = 1;
        this.editedItem.availability = this.availability;
      }
      else {
        this.availability = 0;
        this.editedItem.availability = this.availability;
      }
      if (this.editedItem.status) {
        this.status = true;
        this.editedItem.status = this.status;
      }
      else {
        this.status = false;
        this.editedItem.status = this.status;
      }
      if (this.editedItem.colors) {
        this.colors = this.editedItem.colors;
        this.color_qty = JSON.parse(this.editedItem.color_qty);
        this.editedItem.colors = '';
      }
      if(this.editedItem.size){
        this.editedItem.size = JSON.parse(this.editedItem.size);
        this.size_qty = JSON.parse(this.editedItem.size_qty);
      }
      if (this.editedItem.launch == 1) {
        this.launch = 1;
        this.editedItem.launch = this.launch;
      }
      
      this.dialog = true;
    },
    deleteItem(item) {
      this.editedIndex = this.products.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },
    deleteItemConfirm() {
      axios.delete(`/api/products/delete/${this.editedItem.id}`)
        .then((response) => {
          return true;
        })
        .catch((response) => {
          alert('Error:'.response);
          return false;
        });
      this.products.splice(this.editedIndex, 1)
      this.closeDelete()
    },
    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    closeDelete() {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    save() {
      if (this.editedIndex > -1) {
        const token = document.head.querySelector('meta[name="csrf-token"]').content;
        const indexProduct = this.editedIndex;
        const data = {
          name: this.editedItem.name,
          description: this.editedItem.description,
          category_id: this.editedItem.category_id,
          subcategory_id: this.editedItem.subcategory_id,
          images: this.editedItem.images,
          platform: this.editedItem.platform,
          video_link: this.editedItem.video_link,
          colors: this.colors,
          color_qty: this.color_qty,
          unity: this.editedItem.unity,
          size: this.editedItem.size,
          size_qty: this.size_qty,
          user_id: this.user.id,
          price: this.editedItem.price,
          quantity: this.editedItem.stock_quantity,
          weight: this.editedItem.weight,
          height: this.editedItem.height,
          width: this.editedItem.width,
          length: this.editedItem.length,
          sku: this.editedItem.sku,
          meta_name: this.editedItem.meta_name,
          meta_key: this.editedItem.meta_key,
          meta_description: this.editedItem.meta_description,
          slug: this.editedItem.slug,
          highlight: this.editedItem.highlights,
          availability: this.editedItem.availability,
          status: this.editedItem.status,
          discount_id: this.editedItem.discount_id,
          launch: this.launch,
        };
        axios.post(`/api/products/update/${this.editedItem.id}`, data, {
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'multipart/form-data'
          }
        })
          .then((response) => {
            this.close();
            return Object.assign(this.products[indexProduct], response.data);
          })
          .catch((error) => {
            alert('Error: ' + error);
          });
      }
      else {
        const data = {
          name: this.editedItem.name,
          description: this.editedItem.description,
          category_id: this.editedItem.category_id,
          subcategory_id: this.editedItem.subcategory_name,
          images: this.editedItem.images,
          platform: this.editedItem.platform,
          video_link: this.editedItem.video_link,
          colors: this.colors,
          color_qty: this.color_qty,
          unity: this.editedItem.unity,
          size: this.editedItem.size,
          size_qty: this.size_qty,
          user_id: this.user.id,
          discount: this.editedItem.discount_id,
          price: this.editedItem.price,
          quantity: this.editedItem.stock_quantity,
          weight: this.editedItem.weight,
          height: this.editedItem.height,
          width: this.editedItem.width,
          length: this.editedItem.length,
          sku: this.editedItem.sku,
          meta_name: this.editedItem.meta_name,
          meta_key: this.editedItem.meta_key,
          meta_description: this.editedItem.meta_description,
          slug: this.editedItem.slug,
          highlights: this.highlights,
          availability: this.availability,
          status: this.status,
          launch: this.launch
        };

        console.log(data);
        axios.post(`/products/store`, data,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
          .then((response) => {
            this.editedItem = response.data.original;


            if (this.editedItem.images && this.editedItem.images !== "") {
              this.editedItem.images = JSON.parse(this.editedItem.images);
            } else {
              this.editedItem.images = [];
            }

            console.log(this.editedItem.images);
            return this.products.push(this.editedItem);
          })
          .catch((error) => {
            alert('Error: ' + error);
            return false;
          });
       }
      // this.close();
    },
  },
  mounted() {
    this.getAuth();
    this.getCategories();
    this.getSubCategories();
    this.getProducts();
    this.getDiscounts();
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}

input {
  text-align: right;
}
</style>
