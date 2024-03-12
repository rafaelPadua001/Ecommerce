<template>
  <v-row>
    <v-col class="d-flex justify-center mb-6 flex-column" cols="auto">
      <Dashboard />
    </v-col>
  </v-row>
 
  <v-row>
    <v-col>
      
    </v-col>
  </v-row>
    <v-container>
      <v-sheet>
        <v-row fluid>
    <v-col class="d-flex justify-center flex-column" >
     <v-sheet>
        <v-card class="mx-auto" :width="1200">
    <v-divider></v-divider>

    <v-card-text>
      <v-data-table :headers="headers" :items="products" :sort-by="[{ key: 'calories', order: 'asc' }]"
        class="elevation-1">
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Produtos</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" fullscreen :scrim="true">
              <template v-slot:activator="{ props }">
                <v-btn color="primary" dark class="mb-2" v-bind="props">
                  New Item
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>

                <v-divider></v-divider>

                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col>
                        <h5>Product</h5>
                      </v-col>
                    </v-row>

                    <v-divider></v-divider>
                    <v-spacer></v-spacer>

                    <v-row>
                      
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.name" label="Product name"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        {{ editedItem.category_name }}
                        <v-select v-model="editedItem.category_name" label="Category" :items="categories"
                          item-title="name" item-value="id"></v-select>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-select v-model="editedItem.subcategory_name" label="Subcategory" :items="subcategories"
                          item-title="name" item-value="id"></v-select>
                      </v-col>
                    </v-row>

                    <v-row>
                      <v-col>
                        <v-col cols="12" sm="6" md="4">
                        <v-textarea clearable v-model="editedItem.description" label="Description"
                           variant="solo-filled"></v-textarea>
                      </v-col>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col>
                        <h5>Midias</h5>
                      </v-col>
                    </v-row>

                    <v-divider></v-divider>
                    <v-spacer></v-spacer>

                    <v-row>
                      <v-col cols="12" sm="6" md="4">
                        <v-file-input v-model="editedItem.images" @change="previewImages" multiple chips></v-file-input>
                          <div v-if="previewImages">
                            <v-row v-if="images">
                              <v-col v-for="(image, index) in images" :key="index" cols="12" md="6">
                                <v-card>
                                  <v-img 
                                      v-model="images"
                                      :src="image.src" 
                                      :lazy-src="image.name"
                                      :alt="'Image ' + index"
                                      cover
                                      
                                    >
                                    <div float="right" justify="center">
                                      <v-btn icon="fas fa-close" size="12px" density="compact" @click="removeImagePreview">
                                        <v-icon icon="fas fa-close" size="10px"></v-icon>

                                      </v-btn>
                                    </div>
                                  
                                    </v-img>
                                    
                                </v-card>
                              </v-col>
                              <v-divider></v-divider>
                              <v-spacer></v-spacer>
                              <v-spacer></v-spacer>
                            </v-row>
                            <v-row v-if="images.length == 0">
                            <v-col v-for="(imageName, index) in editedItem.images" :key="index">
                              <v-img :src="`./storage/products/${imageName}`" max-width="100" max-height="100"></v-img>
                            </v-col>
                          </v-row>
                          </div>
                          <div v-else>
                            <v-row v-if="!previewImages">
                         
                              <v-col v-for="(image, index) in editedItem.images" :key="index" cols="12" md="6">
                                <v-card>
                                 
                                  <v-img 
                                      v-model="editedItem.images" 
                                      :src="'./storage/uploads/' + image"
                                      :lazy-src="image"
                                      :alt="'Image ' + index"
                                      cover
                                      
                                    >
                                   <div float="right" justify="center">
                                      <v-btn icon="fas fa-close" size="12px" density="compact" @click="removeImagePreviewEdit(index)">
                                        <v-icon icon="fas fa-close" size="10px"></v-icon>

                                      </v-btn>
                                    </div>
                                  
                                    </v-img>
                                    
                                </v-card>

                                
                              </v-col>
                              <v-divider></v-divider>
                              <v-spacer></v-spacer>
                              <v-spacer></v-spacer>
                            </v-row>
                           
                        </div>
                        
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.platform" label="Platform  (Ex:youtube)"></v-text-field>
                      </v-col>

                      <v-col cols="12" sm="6" md="4">
                        <v-text-field  
                          v-model="editedItem.video_link"
                          label="Videos"
                          hint="www.youtube.com/watch?v="
                          persistent-hint
                          @change="preview"
                        >
                          <template v-slot:append>
                            <v-btn @click="preview" density="compact" icon size="x-small">
                              <v-icon icon="fas fa-upload fa-2xs">

                              </v-icon>
                            </v-btn>
                          </template>
                        </v-text-field>
                       
                        <v-card>
                          <template v-slot:append>
                            <v-btn @click="removeVideoPreview" density="compact" icon size="x-small">
                              <v-icon icon="fas fa-close fa-2xs"></v-icon>
                            </v-btn>
                          </template>
                          <iframe id="ytplayer" type="text/html" :src="`https://www.youtube.com/embed/${previewLink}`"
                            frameborder="0" allowfullscreen autoplay="1" controls="0" fs="0" color="white"
                            v-if="editedItem.video_link" cover>
                          </iframe>
                        </v-card>

                      </v-col>

                    </v-row>
                    

                    <v-divider></v-divider>
                    <v-spacer></v-spacer>
                    <v-row>
                      <v-col>
                        <h5>Variants</h5>
                      </v-col>
                    </v-row>
                     <v-row>
                        <v-col cols="12" sm="6" md="4">
                            <h5>Select Colors:</h5>

                            <v-color-picker
                              v-model="editedItem.colors"
                              class="ma-2"
                              show-swatches
                              swatches-max-height="55px"
                              width="90%"
                              v-bind:onChange="selectedColor()"
                            >
                              
                            </v-color-picker>
                           
                          
                        </v-col>
                        
                        <v-col cols="8" sm="4" md="2">
                          <v-card v-for="(color, index) in colors" :key="index" :color="color">
                            
                            <template v-slot:append>
                                  <v-btn icon density="compact" size="small" @click="removeSelectedColor(index)">
                                    <v-icon icon="fas fa-close fa-2xs"></v-icon>
                                  </v-btn>
                            </template>
                          </v-card>
                        </v-col>
                        <v-col>
                          <v-select
                            v-model="editedItem.size"
                            chips
                            label="Sizes"
                            :items="['P', 'M', 'G', 'GG', 'XG']"
                            multiple
                            variant="underlined"
                          ></v-select>
                        </v-col>
                     </v-row>
                     
                     <v-spacer></v-spacer>
                     <v-divider></v-divider>
                     <v-row>
                      <v-col>
                        <h5>Scales</h5>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col>
                          <v-select
                            v-model="editedItem.unity"
                            chips
                            label="Unidade"
                            :items="['cm', 'mm', 'und', 'pcs']"
                            variant="underlined"
                          ></v-select>
                        </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field
                          v-model="editedItem.price"
                          label="Price"
                           prefix="R$ "
                          v-bind="config" 
                        >
                      </v-text-field>
                      
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.stock_quantity" label="Quantity"></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.weight" label="Weight"
                          suffix="Kg"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.height" label="Height" 
                          :suffix="this.editedItem.unity"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.width" label="Width" 
                          :suffix="this.editedItem.unity"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.length" label="Length" 
                          :suffix="this.editedItem.unity"></v-text-field>
                      </v-col>
                      
                    </v-row>
                    <v-row>
                      <v-col>
                        <h5>Discount</h5>
                      </v-col>
                    </v-row>

                    <v-row>
                      <v-col cols="12" sm="6" md="4">
                        <v-select
                          v-model="editedItem.discount_id"
                          :items="discounts"
                          density="compact"
                          label="Select Discount"
                          item-title="code"
                          item-value="id"
                        ></v-select>
                      </v-col>
                      <v-col>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field v-model="editedItem.sku" label="Sku"></v-text-field>
                        </v-col>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col>
                        <h5>Seo </h5>
                      </v-col>
                    </v-row>

                    <v-divider></v-divider>
                    <v-spacer></v-spacer>

                    <v-row>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.meta_name" label="Meta Name"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.meta_key" label="Meta Key"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-textarea clearable v-model="editedItem.meta_description" label="Meta Description"
                          prepend-icon="$vuetify" variant="solo-filled"></v-textarea>
                      </v-col>
                    </v-row>
                    <v-row>

                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.slug" label="Slug"></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col>
                        <h5>Settings</h5>
                      </v-col>
                    </v-row>

                    <v-divider></v-divider>
                    <v-spacer></v-spacer>

                    <v-row>
                      <v-col cols="12" sm="6" md="4">
                        <v-switch 
                          v-model="editedItem.highlights"
                          label="Highlight"
                          color="success"
                          @click="hl_turn"
                          :value="this.highlights"
                        ></v-switch>
                        
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-switch 
                          v-model="editedItem.availability"
                          label="Availability"
                          color="success"
                          @click="av_turn"
                          :value="this.availability"></v-switch>
                      </v-col>

                      <v-col cols="12" sm="6" md="4">
                        <v-switch 
                          v-model="editedItem.status"
                          label="Status"
                          color="success"
                          @click="st_turn"
                          ></v-switch>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-switch 
                          v-model="editedItem.featured"
                          label="Featured product"
                          color="success"
                          @click="ft_turn"
                        
                          ></v-switch>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-spacer></v-spacer>
                <v-divider></v-divider>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue-darken-1" variant="text" @click="close">
                    Cancel
                  </v-btn>
                  <v-btn color="blue-darken-1" variant="text" @click="save">
                    Save
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="500">
              <v-card>
                <v-card-title class="text-h5">Remove </v-card-title>
                  <v-card-text>
                    Are you sure you want to delete this item ? 
                    {{ editedItem.name }}
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancel</v-btn>
                  <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">Remove</v-btn>
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
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
              <v-img v-if="index === 0" :src="`./storage/products/${imageName}`" class="align-end text-white" :width="250"
                          max-width="90" height="90" aspect-ratio="16/9"></v-img>
            </v-col>
          </v-row>
        </template> 

        <template v-slot:item.colors="{ item }">
          <v-row v-if="item.colors !== '0'">
            <v-col v-if="typeof item.colors === 'string'">
              <v-row>
                  <v-col cols="12" md="2" sm="6" v-for="(color, index) in JSON.parse(item.colors)" :key="index">
                  <v-card  class="mx-auto" :color="color" >
                    {{ color }}
                  </v-card>
                </v-col>
              </v-row>
            </v-col>
            <v-col v-else>
              <v-row>
                <v-col  cols="12" md="2" sm="6" v-for="(color, index) in item.colors" :key="index">
                  <v-card  class="mx-auto" :color="color">
                  {{ color }}
                  </v-card>
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


        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">
            Reset
          </v-btn>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
      </v-sheet>
    </v-col>
  </v-row>
      </v-sheet>
      
    </v-container>
 
 
</template>

<script>
import axios from 'axios';
import Dashboard from '../Auth/Dashboard.vue';

export default {
  components: {
    Dashboard,
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
    platform: '',
    video_link: [],
    previewLink: '',
    colors: [],
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
    featured: false,
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
      id: '',
      name: '',
      description: '',
      category_id: 0,
      subcategory_id: 0,
      images: [],
      platform: '',
      video_link: '',
      //colors: [],
      size: [],
      price: "0.00",
      discount_id: '',
      quantity: 0,
      unity: '',
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
      status: true,
      featured: false,
      discount_id: null,
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
      featured: false,
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
          console.log(response);
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
    getProducts(){
      axios.get('/products')
        .then((response) => {
            this.products = response.data;
            this.editedItem.images = JSON.stringify(this.products.images);
         })
        .catch((response) => {
          return alert('Error :' + response);
        });
    },
    getDiscounts(){
      axios.get('/coupons/all')
      .then((response) => {
        return this.discounts = response.data;
      })
      .catch((response) => {
        return alert('Error' + response);
      });
    },
    previewImages(event) {
      const files = event.target.files;
      if (files) {
        this.images = [];
        for (let i = 0; i < files.length; i++) {
          const reader = new FileReader();
          const file = files[i];

          reader.onload = (e) => {
            this.images.push({
              src: e.target.result,
              name: file.name,
            });
          };
         reader.readAsDataURL(file);
        }
      }
    },
    removeImagePreview(index){
      return this.images.splice(index, 1);
    },
    removeImagePreviewEdit(index){
      return this.editedItem.images.splice(index, 1);
    },
    handleFiles() {
      this.$refs.images[0];
    },
    preview() {
     return this.previewLink = this.editedItem.video_link.slice(32);
    },
    removeVideoPreview(){
      return this.editedItem.video_link = "";
    },
    onPriceInput(value) {
      
    },
    selectedColor(){
      let selected_colors = this.editedItem.colors;
      this.colors.push(selected_colors);
    },
    removeSelectedColor(index){
      return this.colors.splice(index, 1);
    },
    hl_turn(){
      if(this.editedItem.highlights){
        this.highlights = 0;
        this.editedItem.highlights = this.highlights;
      }
      else{
         this.highlights = 1;
         this.editedItem.highlights = this.highlights;
       }
      
      return this.highlights;
    },
    av_turn(){
      if(this.editedItem.availability == 1){
        this.availability = 1;
        this.editedItem.availability = this.availability;
      }
      else{
        this.availability = 0;
        this.editedItem.availability = this.availability;
      }
    },
    st_turn(){
      if(this.editedItem.status){
        this.status = true;
        this.editedItem.status = this.status;
      }
      else{
        this.status = false;
        this.editedItem.status = this.status;
      }
    },
    ft_turn(){
      if(this.editedItem.featured){
        this.featured = 0;
        this.editedItem.featured = this.featured;
      }
      else{
        this.featured = 1;
        this.editedItem.featured = this.featured;
      }
    },
    editItem(item) {
      this.editedIndex = this.products.indexOf(item)
      this.editedItem = Object.assign({}, item)
     
      if (typeof this.editedItem.images === 'string') {
        this.editedItem.images = JSON.parse(this.editedItem.images);
      }
   
      if(this.editedItem.highlight == 1){
          this.highlights = 1;
          this.editedItem.highlights = this.highlights;
          
      }
      else{
         this.highlights = 0;
         this.editedItem.highlights = this.highlights;
      }
      if(this.editedItem.availability == 1){
        this.availability = 1;
        this.editedItem.availability = this.availability;
      }
      else{
        this.availability = 0;
        this.editedItem.availability = this.availability;
      }
      if(this.editedItem.status){
        this.status = true;
        this.editedItem.status = this.status;
      }
      else{
        this.status = false;
        this.editedItem.status = this.status;
      }
      if(this.editedItem.colors){
        this.colors = this.editedItem.colors;
        this.editedItem.colors = '';
      }
      if(this.editedItem.featured){
        this.featured = true;
        this.editedItem.featured = this.featured;
      }
      else{
        this.featured = false;
        this.editedItem.featured = this.featured;
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
        alert('Error:' . response);
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
          category_id: this.editedItem.category_name,
          subcategory_id: this.editedItem.subcategory_name,
          images: this.images,
          platform: this.editedItem.platform,
          video_link: this.editedItem.video_link,
          colors: this.colors,
          unity: this.editedItem.unity,
          size: this.editedItem.size,
          user_id: this.user.id,
          price: this.editedItem.price,
          quantity: this.editedItem.quantity,
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
          featured: this.featured,
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
          category_id: this.editedItem.category_name,
          subcategory_id: this.editedItem.subcategory_name,
          images: this.images,
          platform: this.editedItem.platform,
          video_link: this.editedItem.video_link,
          colors: this.colors,
          unity: this.editedItem.unity,
          size: this.editedItem.size,
          user_id: this.user.id,
          discount: this.editedItem.discount_id,
          price: this.editedItem.price,
          quantity: this.editedItem.quantity,
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
          featured: this.featured
        };
        axios.post(`/products/store`, data,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }) 
        .then((response) => {
          this.editedItem = response.data;
          return this.products.push(this.editedItem.original);
           
        })
        .catch((response) => {
          alert('Error: ' + response);
          return false;
        });
      }
      this.close();
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
