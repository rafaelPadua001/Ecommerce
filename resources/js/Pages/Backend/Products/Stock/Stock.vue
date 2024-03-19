<template>
  <v-container>
    <v-sheet class="px-2 py-2">
      <v-row fluid>
    <v-col class="d-flex justify-center flex-column" cols="auto">
      <Dashboard />
    </v-col>
  </v-row>
  <v-row justify="center" no-gutters>
      <v-col class="d-flex justify-center flex-column" cols="auto" sm="12">
      
        <v-data-table :headers="headers" :items="stocks" :sort-by="[{ key: 'id', order: 'asc' }]" class="elevation-0">
        <template v-slot:top>
          <v-toolbar flat class="bg-transparent">
            <v-toolbar-title>Product Stock</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" transition="dialog-top-transition" fullscreen>
              <template v-slot:activator="{ props }">
                <v-btn color="primary" dark class="mb-2" v-bind="props" disabled>
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
                    <h5>Product</h5>
                    <v-divider></v-divider>
                    <v-spacer></v-spacer>

                    <v-row>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.stock_quantity" label="Quantity"></v-text-field>
                      </v-col>
                     
                    </v-row>
                    <v-row>

                      <v-col cols="auto" md="4" sm="2">
                            <h5>Select Colors:</h5>
                            <v-color-picker
                              v-model="editedItem.product_colors"
                              class="ma-2"
                              show-swatches
                              swatches-max-height="75px"
                              width="90%"
                              v-bind:onChange="selectedColor()"
                            >
                              
                            </v-color-picker>
                     
                        </v-col>
                     
                        <v-col cols="auto" md="4" sm="2">
                         
                          <v-row v-for="(color, index) in colors" :key="index">
                            <v-col>
                              <v-chip :color="color" variant="elevated"></v-chip>
                            </v-col>
                            <v-col cols="4" sm="4">
                             
                                    <v-text-field v-model="color_qty[index]" label="Quantity" type="number"
                                      :color="color">
                                      
                                    </v-text-field>
                                   
                                  </v-col>
                                  <v-col cols="auto" sm="2">
                                   
                                    <v-btn icon density="compact" size="x-small" @click="removeSelectedColor(index)" :color="color">
                                    <v-icon icon="fas fa-close fa-2xs"></v-icon>
                                  </v-btn>
                           
                                  </v-col>
                          </v-row>
                            
                        
                        </v-col>

                    </v-row>
                    <v-row>
                      <v-col  cols="auto" md="4" sm="2">
                          <v-select
                            v-model="editedItem.product_size"
                            chips
                            label="Sizes"
                            :items="['P', 'M', 'G', 'GG', 'XG']"
                            multiple
                            variant="underlined"
                            clearable
                          ></v-select>
                          </v-col>
                          <v-col cols="auto" md="4">
                              <v-row v-for="(size, index) in editedItem.product_size" :key="index">
                                <v-col cols="auto" sm="4">
                                  <v-chip color="success" variant="elevated">{{ size }}</v-chip>

                                </v-col>
                                <v-col cols="auto">
                                  <v-text-field v-model="size_qty[index]" label="Quantity" type="number" :prefix="color"
                                    :color="color"></v-text-field>
                                </v-col>

                              </v-row>

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
                  <v-btn color="blue-darken-1" variant="text" @click="update">
                    Save
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
           
          </v-toolbar>
        </template>
        <template v-slot:item.created_at="{item}">
          {{ item.created_at.split('T')[0] }}
        </template>
        <template v-slot:item.updated_at="{item}">
          {{ item.updated_at.split('T')[0] }}
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn-group>
            <v-btn icon variant="plain" size="small" @click="editItem(item)">
              <v-icon class="me-2" icon="fa-regular fa-pen-to-square fa-2xs">
                
              </v-icon>
            </v-btn>
           
          </v-btn-group>
          
        
        </template>

        <template v-slot:item.product_colors="{ item }">
         
          <v-row v-if="item.product_colors !== 0">
            <v-col v-if="typeof item.product_colors === 'string'">
              <v-row fluid>
                  <v-col cols="auto" md="4" sm="2" v-for="(color, index) in JSON.parse(item.product_colors)" :key="index">
                     
                    <v-chip :color="color" variant="elevated">
                      <div v-if="Array.isArray(JSON.parse(item.color_qty)) && JSON.parse(item.color_qty)[index] !== undefined">{{ JSON.parse(item.color_qty)[index] }}</div>
                    </v-chip>
                 
                  <!-- <v-card  class="mx-auto" :color="color" >
                    {{ color }}
                  </v-card> -->
                </v-col>
            
              </v-row>
            </v-col>
            <v-col v-else>
              <v-row>
                <v-col  cols="12" md="2" sm="6" v-for="(color, index) in item.product_colors" :key="index">
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

        <template v-slot:item.product_size="{item}">
          <v-chip-group>
            <v-chip v-for="(size,index) in JSON.parse(item.product_size)" :key="index" size="small" class="bg-green" variant="elevated">
              {{ size }}
            </v-chip>
          </v-chip-group>
         
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
import Dashboard from '../../Auth/Dashboard.vue';


export default {
  components: { Dashboard },
  data: () => ({
    users: [],
    stocks: [],
    colors: [],
    dialog: false,
    dialogDelete: false,
    color_qty: [],
    size_qty: [],
    headers: [
      {
        title: 'Name',
        align: 'start',
        sortable: false,
        key: 'name',
      },
      { title: 'product_colors', key: 'product_colors' },
      { title: 'product_size', key: 'product_size' },
      { title: 'quantity', key: 'stock_quantity' },
     // { title: 'product_id', key: 'product_id' },
     // { title: 'user_id', key: 'user_id' },
      { title: 'created', key: 'created_at' },
      { title: 'updated', key: 'updated_at' },
      { title: 'Actions', key: 'actions', sortable: false },
    ],
    editedIndex: -1,
    editedItem: {
      name: '',
      product_colors: '',
      product_size: '',
      stock_quantity: '',
      
    },
    defaultItem: {
      name: '',
      product_colors: '',
      product_size: '',
      stock_quantity: '',
      quantity: '',
    },
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    },
  },
  watch: {
    dialog(val) {
      val || this.close()
    },
    dialogDelete(val) {
      val || this.closeDelete()
    },
  },
  created() {
    this.initialize();

  },
  methods: {
    initialize() {
      this.seo = []
    },
    getAuth() {
      axios.get('/users')
        .then((response) => {
          this.users = response.data;
          return true;
        })
        .catch((response) => {
          console.log(response);
          return false;
        });
    },
    getStocks() {
      axios.get('/stocks')
        .then((response) => {
          return this.stocks = response.data;
        })
        .catch((response) => {
          alert('Error: ' + response);
        })
    },
    removeSelectedColor(index){
     return this.colors.splice(index, 1);
    },
    editItem(item) {
    this.editedIndex = this.stocks.indexOf(item)
    this.editedItem = Object.assign({}, item)
    if(this.editedItem.product_colors.length > 1){
      this.colors = JSON.parse(this.editedItem.product_colors);
      this.color_qty = JSON.parse(this.editedItem.color_qty);
      
      this.editedItem.product_colors = '';
    }
    if(this.editedItem.size_qty.length >= 1 || this.editedItem.product_size.length >= 1){
      this.editedItem.product_size = JSON.parse(this.editedItem.product_size);
      this.size_qty = JSON.parse(this.editedItem.size_qty);
    }
    
    
    this.dialog = true
  },
  selectedColor(){
      let selected_colors = this.editedItem.product_colors;
      return this.colors.push(selected_colors);
  },
  close() {
    this.dialog = false
    this.$nextTick(() => {
      this.editedItem = Object.assign({}, this.defaultItem)
      this.editedIndex = -1
    })
  },
  update() {
    const indexStock = this.editedIndex;
    if (this.editedIndex > -1) {
      const data = {
        name: this.editedItem.name,
        stock_quantity: this.editedItem.stock_quantity,
        product_size: this.editedItem.product_size,
        size_qty: this.size_qty,
        color_qty: this.color_qty,
        product_colors: this.colors,
        user_id: this.users.id
      }

      axios.post(`/api/stocks/update/${this.editedItem.id}`, data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
      )
        .then((response) => {
          this.editedItem = response.data;
          Object.assign(this.stocks[indexStock], this.editedItem);
          return true;
        })
        .catch((response) => {
          alert('Error: ' + response);
          return false;
        });

      Object.assign(this.stocks[this.editedIndex], this.editedItem)
    } 
    this.close()
   },
  },
mounted() {
  this.getAuth();
    this.getStocks();
  }
}
</script>