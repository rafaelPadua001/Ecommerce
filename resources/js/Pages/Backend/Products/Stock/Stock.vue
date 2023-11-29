<template>
  <div>
    <Dashboard />
  </div>
  <v-card width="1500">
    <v-card-text>
      <v-data-table :headers="headers" :items="stocks" :sort-by="[{ key: 'id', order: 'asc' }]" class="elevation-1">
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Product Stock</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="900">
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

                      <v-col cols="12" sm="6" md="4">
                            <h5>Select Colors:</h5>
                            
                            <v-color-picker
                              v-model="editedItem.colors"
                              class="ma-2"
                              show-swatches
                              swatches-max-height="55px"
                              :on:change="selectedColor()"
                              width="90%"
                            >
                              
                            </v-color-picker>
                           <!-- <div>
                                <v-btn size="x-small" icon @click="selectedColor()">
                                  <v-icon icon="fas fa-save"></v-icon>
                                </v-btn>
                            </div>
                            -->
                        </v-col>
                       
                        <v-col v-if="editedItem.product_colors >= 1">
                          <div>
                            Current Colors
                            <v-card v-for="(color, index) in JSON.parse(editedItem.product_colors)" :key="index" :color="color">
                                <template v-slot:append>
                                  <v-btn icon density="compact" size="small">
                                    <v-icon icon="fas fa-close fa-2xs"></v-icon>
                                  </v-btn>
                                </template>
                          </v-card>
                            
                          </div>
                          
                        </v-col>
                        <v-col cols="8" sm="4" md="2"  v-else>
                          Selected Colors
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
                            v-model="editedItem.product_size"
                            chips
                            label="Sizes"
                            :items="['P', 'M', 'G', 'GG', 'XG']"
                            multiple
                            variant="underlined"
                            clearable
                          ></v-select>
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
                  <v-col cols="12" md="4" sm="2" v-for="(color, index) in JSON.parse(item.product_colors)" :key="index">
                  <v-card  class="mx-auto" :color="color" >
                    {{ color }}
                  </v-card>
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

        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">
            Reset
          </v-btn>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
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
      { title: 'product_id', key: 'product_id' },
      { title: 'user_id', key: 'user_id' },
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
    selectedColor(){
      let selected_colors = this.editedItem.colors;
      console.log(selected_colors);
      return this.colors.push(selected_colors);
    },
    removeSelectedColor(index){
      
      return this.colors.splice(index, 1);
    },
    editItem(item) {
    this.editedIndex = this.stocks.indexOf(item)
    this.editedItem = Object.assign({}, item)
    this.dialog = true
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
      console.log(this.users);
      const data = {
        name: this.editedItem.name,
        stock_quantity: this.editedItem.stock_quantity,
        product_size: this.editedItem.product_size,
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
          console.log(response.data);
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